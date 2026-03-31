<style>
.group-card-item {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
}
.group-card-item:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    transform: translateY(-2px);
    border-color: var(--primary);
}
.group-card-item.system-group::after {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #c5221f, #ea4335);
}
.group-card-item.custom-group::after {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), #4f9cf9);
}
.group-icon-large {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.group-action-btn {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border-color);
    background: var(--bg-white);
    color: var(--text-secondary);
    font-size: 0.8rem;
    transition: all 0.15s;
    text-decoration: none;
}
.group-action-btn:hover {
    background: var(--primary-light);
    color: var(--primary);
    border-color: var(--primary);
}
.group-action-btn.btn-delete:hover {
    background: #f8d7da;
    color: #842029;
    border-color: #dc3545;
}
</style>

<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1" style="color: var(--text-primary);">
            <i class="fas fa-layer-group me-2" style="color: var(--primary);"></i>Gestion des Groupes
        </h1>
        <p class="mb-0 small" style="color: var(--text-secondary);">Gérez les groupes d'utilisateurs et leurs permissions d'accès</p>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <a href="<?php echo site_url('users/add_group'); ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Nouveau Groupe
        </a>
        <?php if(is_allowed('user')): ?>
            <a href="<?php echo site_url('users/'); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Utilisateurs
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if(!empty($liste_groups)): ?>

<!-- Stats Bar -->
<div class="d-flex flex-wrap gap-3 mb-4">
    <?php
        $total_groups = count($liste_groups);
        $system_groups = 0;
        $custom_groups = 0;
        foreach($liste_groups as $g) {
            if($g->is_system == 1) $system_groups++;
            else $custom_groups++;
        }
    ?>
    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3" style="background: var(--bg-white); border: 1px solid var(--border-color);">
        <i class="fas fa-layer-group small" style="color: var(--primary);"></i>
        <span class="fw-semibold" style="color: var(--text-primary); font-size: 0.875rem;"><?php echo $total_groups; ?></span>
        <span class="small" style="color: var(--text-secondary);">groupes au total</span>
    </div>
    <?php if($system_groups > 0): ?>
    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3" style="background: #fce8e6; border: 1px solid #f5c6cb;">
        <i class="fas fa-lock small" style="color: #c5221f;"></i>
        <span class="fw-semibold" style="color: #c5221f; font-size: 0.875rem;"><?php echo $system_groups; ?></span>
        <span class="small" style="color: #c5221f;">système</span>
    </div>
    <?php endif; ?>
    <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3" style="background: var(--primary-light); border: 1px solid var(--primary);">
        <i class="fas fa-pen small" style="color: var(--primary);"></i>
        <span class="fw-semibold" style="color: var(--primary); font-size: 0.875rem;"><?php echo $custom_groups; ?></span>
        <span class="small" style="color: var(--primary);">personnalisés</span>
    </div>
</div>

<!-- Groups Grid -->
<div class="row g-3">
    <?php foreach($liste_groups as $l): ?>
    <div class="col-lg-4 col-md-6">
        <div class="group-card-item <?php echo $l->is_system == 1 ? 'system-group' : 'custom-group'; ?>">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="group-icon-large" style="background: <?php echo $l->is_system == 1 ? '#fce8e6' : 'var(--primary-light)'; ?>; color: <?php echo $l->is_system == 1 ? '#c5221f' : 'var(--primary)'; ?>;">
                        <i class="fas fa-<?php echo $l->is_system == 1 ? 'shield-halved' : 'layer-group'; ?>"></i>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1" style="color: var(--text-primary); font-size: 0.95rem;">
                            <?php echo htmlspecialchars($l->group_name); ?>
                        </h6>
                        <?php if($l->is_system == 1): ?>
                            <span class="badge rounded-pill" style="background: #fce8e6; color: #c5221f; font-size: 0.7rem; font-weight: 500;">
                                <i class="fas fa-lock me-1" style="font-size: 8px;"></i>Système
                            </span>
                        <?php else: ?>
                            <span class="badge rounded-pill" style="background: var(--primary-light); color: var(--primary); font-size: 0.7rem; font-weight: 500;">
                                <i class="fas fa-pen me-1" style="font-size: 8px;"></i>Personnalisé
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-1 pt-3" style="border-top: 1px solid var(--border-color);">
                <a href="<?php echo site_url('users/group_permissions/'.$l->group_id); ?>"
                   class="group-action-btn" title="Voir les permissions" style="background: var(--primary-light); color: var(--primary); border-color: var(--primary);">
                    <i class="fas fa-shield-halved"></i>
                </a>
                <?php if($l->is_system == 0): ?>
                    <a href="<?php echo site_url('users/update_group/'.$l->group_id); ?>"
                       class="group-action-btn" title="Modifier">
                        <i class="fas fa-pen"></i>
                    </a>
                    <a href="<?php echo site_url('users/delete_group/'.$l->group_id); ?>"
                       class="group-action-btn btn-delete" title="Supprimer"
                       onclick="return confirm('Supprimer ce groupe ?')">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                <?php endif; ?>
                <span class="ms-auto small" style="color: var(--text-muted);">
                    ID: <?php echo (int)$l->group_id; ?>
                </span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php else: ?>
<div class="card" style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
    <div class="card-body text-center py-5">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 80px; height: 80px; background: var(--primary-light);">
            <i class="fas fa-layer-group fa-2x" style="color: var(--primary);"></i>
        </div>
        <h5 class="fw-semibold" style="color: var(--text-primary);">Aucun groupe disponible</h5>
        <p class="mb-3" style="color: var(--text-secondary);">Commencez par créer votre premier groupe d'utilisateurs</p>
        <a href="<?php echo site_url('users/add_group'); ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Créer un groupe
        </a>
    </div>
</div>
<?php endif; ?>