<?php $permissions = get_all_permissions(); ?>

<?php if(!empty($group)): ?>

<div class="mb-4">
    <a href="<?php echo site_url('users/group'); ?>" class="text-decoration-none d-inline-flex align-items-center small" style="color: var(--primary);">
        <i class="fas fa-arrow-left me-2"></i> Retour aux groupes
    </a>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 48px; height: 48px; background: var(--primary-light);">
            <i class="fas fa-shield-halved" style="color: var(--primary);"></i>
        </div>
        <div>
            <h4 class="fw-semibold mb-0" style="color: var(--text-primary);">Permissions du groupe</h4>
            <p class="text-secondary small mb-0">
                <?php echo htmlspecialchars($group->group_name); ?>
                <?php if($group->is_system == 1): ?>
                    <span class="badge rounded-pill ms-1" style="background: #fce8e6; color: #c5221f; font-weight: 500; font-size: 0.7em;">Système</span>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <?php if($group->is_system == 0): ?>
            <a href="<?php echo site_url('users/update_group/'.$group->group_id); ?>" class="btn btn-primary btn-sm px-3">
                <i class="fas fa-pen-to-square me-1"></i> Modifier
            </a>
        <?php endif; ?>
        <a href="<?php echo site_url('users/group'); ?>" class="btn btn-outline-secondary btn-sm px-3">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: var(--radius-lg);">
    <div class="card-body p-4">
        <div class="row g-3">
            <?php foreach($permissions as $key => $value): ?>
                <?php $is_enabled = (isset($group->{$key}) && $group->{$key} == 1); ?>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3 rounded-3 border" style="border-color: <?php echo $is_enabled ? 'var(--primary)' : 'var(--border-color)'; ?> !important; background: <?php echo $is_enabled ? 'var(--primary-light)' : 'var(--bg-white)'; ?>;">
                        <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 36px; height: 36px; flex-shrink: 0; background: <?php echo $is_enabled ? 'var(--primary)' : '#f1f3f4'; ?>; color: <?php echo $is_enabled ? '#fff' : 'var(--text-secondary)'; ?>;">
                            <?php if($is_enabled): ?>
                                <i class="fas fa-check small"></i>
                            <?php else: ?>
                                <i class="fas fa-xmark small"></i>
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="small fw-medium" style="color: var(--text-primary);"><?php echo $value; ?></span>
                            <div>
                                <?php if($is_enabled): ?>
                                    <span class="small" style="color: #188038;">Activée</span>
                                <?php else: ?>
                                    <span class="small text-secondary">Désactivée</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php else: ?>

<div class="card border-0 shadow-sm" style="border-radius: var(--radius-lg);">
    <div class="text-center py-5">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 64px; height: 64px; background: var(--primary-light);">
            <i class="fas fa-circle-exclamation fa-lg" style="color: var(--primary);"></i>
        </div>
        <h6 class="fw-semibold mb-1" style="color: var(--text-primary);">Aucune configuration trouvée</h6>
        <p class="text-secondary small mb-3">Le groupe demandé n'existe pas ou a été supprimé</p>
        <a href="<?php echo site_url('users/group'); ?>" class="btn btn-primary btn-sm px-3">
            <i class="fas fa-arrow-left me-1"></i> Retour aux groupes
        </a>
    </div>
</div>

<?php endif; ?>