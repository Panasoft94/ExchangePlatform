<?php $permissions = get_all_permissions(); ?>

<?php if(!empty($group)): ?>

<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <div style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; background: <?php echo $group->is_system == 1 ? '#fce8e6' : 'var(--primary-light)'; ?>; color: <?php echo $group->is_system == 1 ? '#c5221f' : 'var(--primary)'; ?>;">
            <i class="fas fa-shield-halved"></i>
        </div>
        <div>
            <h1 class="h4 fw-bold mb-1" style="color: var(--text-primary);">
                <?php echo htmlspecialchars($group->group_name); ?>
            </h1>
            <p class="mb-0 small" style="color: var(--text-secondary);">
                <?php if($group->is_system == 1): ?>
                    <span class="badge rounded-pill" style="background: #fce8e6; color: #c5221f; font-size: 0.7rem;">
                        <i class="fas fa-lock me-1" style="font-size: 8px;"></i>Groupe système
                    </span>
                <?php else: ?>
                    <span class="badge rounded-pill" style="background: var(--primary-light); color: var(--primary); font-size: 0.7rem;">
                        <i class="fas fa-pen me-1" style="font-size: 8px;"></i>Groupe personnalisé
                    </span>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <?php if($group->is_system == 0): ?>
            <a href="<?php echo site_url('users/update_group/'.$group->group_id); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-pen me-1"></i> Modifier
            </a>
        <?php endif; ?>
        <a href="<?php echo site_url('users/group'); ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>
</div>

<!-- Permissions Summary -->
<?php
    $enabled_count = 0;
    $disabled_count = 0;
    foreach($permissions as $key => $value) {
        if(isset($group->{$key}) && $group->{$key} == 1) $enabled_count++;
        else $disabled_count++;
    }
?>
<div class="d-flex flex-wrap gap-3 mb-4">
    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3" style="background: #e6f4ea; border: 1px solid #a8dab5;">
        <i class="fas fa-check-circle small" style="color: #188038;"></i>
        <span class="fw-semibold" style="color: #188038; font-size: 0.875rem;"><?php echo $enabled_count; ?></span>
        <span class="small" style="color: #188038;">activées</span>
    </div>
    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3" style="background: #f1f3f4; border: 1px solid var(--border-color);">
        <i class="fas fa-times-circle small" style="color: var(--text-secondary);"></i>
        <span class="fw-semibold" style="color: var(--text-secondary); font-size: 0.875rem;"><?php echo $disabled_count; ?></span>
        <span class="small" style="color: var(--text-secondary);">désactivées</span>
    </div>
</div>

<!-- Permissions Grid -->
<div class="row g-3">
    <?php foreach($permissions as $key => $value): ?>
    <?php $is_enabled = (isset($group->{$key}) && $group->{$key} == 1); ?>
    <div class="col-md-6 col-lg-4">
        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background: var(--bg-white); border: 1.5px solid <?php echo $is_enabled ? 'var(--primary)' : 'var(--border-color)'; ?>; transition: all 0.2s;">
            <div style="width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.8rem;
                        background: <?php echo $is_enabled ? 'var(--primary)' : '#f1f3f4'; ?>; color: <?php echo $is_enabled ? '#fff' : 'var(--text-secondary)'; ?>;">
                <i class="fas fa-<?php echo $is_enabled ? 'check' : 'xmark'; ?>"></i>
            </div>
            <div class="flex-grow-1 min-w-0">
                <div class="small fw-medium text-truncate" style="color: var(--text-primary);"><?php echo $value; ?></div>
                <?php if($is_enabled): ?>
                    <span class="small" style="color: #188038;">Activée</span>
                <?php else: ?>
                    <span class="small" style="color: var(--text-secondary);">Désactivée</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php else: ?>

<div class="card" style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
    <div class="card-body text-center py-5">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 64px; height: 64px; background: var(--primary-light);">
            <i class="fas fa-circle-exclamation fa-lg" style="color: var(--primary);"></i>
        </div>
        <h6 class="fw-semibold mb-1" style="color: var(--text-primary);">Aucune configuration trouvée</h6>
        <p class="small mb-3" style="color: var(--text-secondary);">Le groupe demandé n'existe pas ou a été supprimé</p>
        <a href="<?php echo site_url('users/group'); ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Retour aux groupes
        </a>
    </div>
</div>

<?php endif; ?>