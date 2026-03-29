<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-semibold mb-1" style="color: var(--text-primary);">
            <i class="fas fa-users-cog me-2" style="color: var(--primary);"></i>Gestion des Comptes
        </h1>
        <p class="mb-0 small" style="color: var(--text-secondary);">Gérez les comptes utilisateurs de la plateforme</p>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <?php if(is_allowed('group')): ?>
            <a href="<?php echo site_url('users/group'); ?>" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-layer-group me-1"></i> Groupes
            </a>
        <?php endif; ?>
        <?php if(is_allowed('add_user')): ?>
            <a href="<?php echo site_url('users/add'); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Créer Utilisateur
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if(!empty($liste_users)): ?>
<div class="card" style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
    <div class="card-body p-0">
        <?php echo form_open('users/remove_access_account', array('class' => 'm-0')); ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" id="myDatatable">
                <thead>
                    <tr style="background: var(--bg-main);">
                        <th scope="col" class="ps-4">Utilisateur</th>
                        <th scope="col">Nom d'utilisateur</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Fonction</th>
                        <th scope="col">Statut</th>
                        <th scope="col" class="text-center">État du compte</th>
                        <th scope="col" class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($liste_users as $l): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <?php
                                    $initials = mb_strtoupper(mb_substr($l->users_prenom, 0, 1) . mb_substr($l->users_nom, 0, 1));
                                ?>
                                <div class="d-flex justify-content-center align-items-center rounded-circle fw-semibold me-3"
                                     style="width: 38px; height: 38px; min-width: 38px; background: var(--primary-light); color: var(--primary); font-size: 13px;">
                                    <?php echo htmlspecialchars($initials); ?>
                                </div>
                                <div>
                                    <div class="fw-medium" style="color: var(--text-primary);"><?php echo htmlspecialchars($l->users_nom . ' ' . $l->users_prenom); ?></div>
                                </div>
                            </div>
                        </td>
                        <td style="color: var(--text-secondary);"><?php echo htmlspecialchars($l->users_username); ?></td>
                        <td>
                            <a href="mailto:<?php echo htmlspecialchars($l->users_email); ?>" class="text-decoration-none" style="color: var(--primary);">
                                <?php echo htmlspecialchars($l->users_email); ?>
                            </a>
                        </td>
                        <td>
                            <span class="badge rounded-pill" style="background: var(--primary-light); color: var(--primary); font-weight: 500;">
                                <?php echo htmlspecialchars($l->users_role); ?>
                            </span>
                        </td>
                        <td>
                            <?php if($l->etat_online == 1): ?>
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success">
                                    <i class="fas fa-circle me-1" style="font-size: 7px; vertical-align: middle;"></i> En ligne
                                </span>
                            <?php else: ?>
                                <span class="badge rounded-pill" style="background: #f1f3f4; color: var(--text-secondary);">
                                    <i class="fas fa-circle me-1" style="font-size: 7px; vertical-align: middle;"></i> Hors ligne
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="form-check form-switch d-flex justify-content-center mb-0">
                                <input class="form-check-input" type="checkbox" role="switch"
                                       id="switch_<?php echo (int)$l->users_id; ?>"
                                       name="etat_compte[]"
                                       value="<?php echo (int)$l->users_id; ?>"
                                       <?php echo $l->etat_compte == 1 ? 'checked' : ''; ?>>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-1">
                                <?php if($l->etat_compte == 1): ?>
                                    <a href="<?php echo site_url('users/verrouiller_compte_user/' . (int)$l->users_id); ?>"
                                       class="btn btn-sm btn-outline-warning" title="Verrouiller"
                                       onclick="return confirm('Verrouiller ce compte ?')">
                                        <i class="fas fa-lock"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo site_url('users/deverrouiller_compte_user/' . (int)$l->users_id); ?>"
                                       class="btn btn-sm btn-warning" title="Déverrouiller"
                                       onclick="return confirm('Déverrouiller ce compte ?')">
                                        <i class="fas fa-unlock"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if(is_allowed('update_user')): ?>
                                    <a href="<?php echo site_url('users/update/' . (int)$l->users_id); ?>"
                                       class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                <?php endif; ?>

                                <?php if(is_allowed('delete_user')): ?>
                                    <a href="<?php echo site_url('users/delete/' . (int)$l->users_id); ?>"
                                       class="btn btn-sm btn-outline-danger" title="Supprimer"
                                       onclick="return confirm('Supprimer ce compte définitivement ?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-end" style="background: var(--bg-main); border-top: 1px solid var(--border-color);">
            <button type="submit" class="btn btn-primary btn-sm"
                    onclick="return confirm('Valider les changements d\'état des comptes ?')">
                <i class="fas fa-check me-1"></i> Enregistrer les états
            </button>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<?php else: ?>
<div class="card" style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
    <div class="card-body text-center py-5">
        <div class="mb-3">
            <i class="fas fa-users" style="font-size: 48px; color: var(--border-color);"></i>
        </div>
        <h5 class="fw-semibold" style="color: var(--text-primary);">Aucun utilisateur</h5>
        <p class="mb-0" style="color: var(--text-secondary);">Aucun compte utilisateur n'est disponible pour le moment.</p>
    </div>
</div>
<?php endif; ?>