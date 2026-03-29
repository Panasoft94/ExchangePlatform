<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-semibold mb-1" style="color: var(--text-primary);">Gestion des Groupes</h4>
        <p class="text-secondary mb-0 small">Gérez les groupes d'utilisateurs et leurs permissions</p>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <a href="<?php echo site_url('users/add_group'); ?>" class="btn btn-primary btn-sm px-3">
            <i class="fas fa-plus me-1"></i> Ajouter un groupe
        </a>
        <?php if(is_allowed('user')): ?>
            <a href="<?php echo site_url('users/'); ?>" class="btn btn-outline-secondary btn-sm px-3">
                <i class="fas fa-users me-1"></i> Liste des utilisateurs
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: var(--radius-lg);">
    <?php if(!empty($liste_groups)): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="myDatatable">
                <thead>
                    <tr style="background: var(--bg-main);">
                        <th class="ps-4 py-3 text-secondary small fw-semibold" style="width: 60px;">#</th>
                        <th class="py-3 text-secondary small fw-semibold">Nom du groupe</th>
                        <th class="py-3 text-secondary small fw-semibold" style="width: 140px;">Type</th>
                        <th class="py-3 text-secondary small fw-semibold text-end pe-4" style="width: 280px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($liste_groups as $l): ?>
                        <tr>
                            <td class="ps-4 text-secondary"><?php echo $l->group_id; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 36px; height: 36px; background: var(--primary-light); color: var(--primary); flex-shrink: 0;">
                                        <i class="fas fa-layer-group small"></i>
                                    </div>
                                    <span class="fw-medium" style="color: var(--text-primary);"><?php echo htmlspecialchars($l->group_name); ?></span>
                                </div>
                            </td>
                            <td>
                                <?php if($l->is_system == 1): ?>
                                    <span class="badge rounded-pill px-3 py-1" style="background: #fce8e6; color: #c5221f; font-weight: 500;">
                                        <i class="fas fa-lock me-1 small"></i>Système
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill px-3 py-1" style="background: var(--primary-light); color: var(--primary); font-weight: 500;">
                                        <i class="fas fa-pen me-1 small"></i>Personnalisé
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <?php if($l->is_system == 0): ?>
                                        <a href="<?php echo site_url('users/update_group/'.$l->group_id); ?>" class="btn btn-sm btn-outline-primary px-2 py-1" title="Modifier">
                                            <i class="fas fa-pen-to-square small"></i>
                                        </a>
                                        <a href="<?php echo site_url('users/delete_group/'.$l->group_id); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe ?')" class="btn btn-sm btn-outline-danger px-2 py-1" title="Supprimer">
                                            <i class="fas fa-trash-can small"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo site_url('users/group_permissions/'.$l->group_id); ?>" class="btn btn-sm px-2 py-1" style="background: var(--primary-light); color: var(--primary);" title="Permissions">
                                        <i class="fas fa-shield-halved small"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 64px; height: 64px; background: var(--primary-light);">
                <i class="fas fa-folder-open fa-lg" style="color: var(--primary);"></i>
            </div>
            <h6 class="fw-semibold mb-1" style="color: var(--text-primary);">Aucun groupe disponible</h6>
            <p class="text-secondary small mb-3">Commencez par créer votre premier groupe d'utilisateurs</p>
            <a href="<?php echo site_url('users/add_group'); ?>" class="btn btn-primary btn-sm px-3">
                <i class="fas fa-plus me-1"></i> Créer un groupe
            </a>
        </div>
    <?php endif; ?>
</div>
