<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-white"><i class="fas fa-users-cog me-2 text-primary"></i> Gestion des Comptes Utilisateurs</h2>
        <div>
            <?php if(is_allowed('group')):?>
                <a href="<?php echo site_url('users/group'); ?>" class="btn btn-outline-primary btn-sm me-2">
                    <i class="fas fa-layer-group"></i> Groupes
                </a>
            <?php endif;?>
            <?php if(is_allowed('add_user')):?>
                <a href="<?php echo site_url('users/add'); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> Créer Utilisateur
                </a>
            <?php endif;?>
        </div>
    </div>

    <?php if(!empty($liste_users)):?>
    <div class="card bg-dark border-secondary shadow-sm mb-4">
        <div class="card-body p-0">
            <?php echo form_open('users/remove_access_account', array('class' => 'm-0'));?>
            <div class="table-responsive">
                <table class="table table-dark table-hover table-striped mb-0 align-middle" id="myDatatable">
                    <thead class="table-active">
                        <tr>
                            <th scope="col">Nom(s) & Prénom(s)</th>
                            <th scope="col">Nom d'utilisateur</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Fonction</th>
                            <th scope="col">Statut</th>
                            <th scope="col" class="text-center">État du compte</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($liste_users as $l): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 border rounded-circle border-secondary d-flex justify-content-center align-items-center bg-secondary text-white" style="width: 35px; height: 35px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-white"><?php echo htmlspecialchars($l->users_nom.' '.$l->users_prenom); ?></h6>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($l->users_username); ?></td>
                            <td><a href="mailto:<?php echo htmlspecialchars($l->users_email); ?>" class="text-info text-decoration-none"><?php echo htmlspecialchars($l->users_email); ?></a></td>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($l->users_role); ?></span></td>
                            <td>
                                <?php if($l->etat_online == 1): ?>
                                    <span class="badge bg-success"><i class="fas fa-circle ms-1 small"></i> En ligne</span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><i class="fas fa-circle ms-1 small"></i> Hors ligne</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" role="switch" id="switch_<?php echo $l->users_id;?>" name="etat_compte[]" value="<?php echo $l->users_id ?>" <?php echo $l->etat_compte == 1 ? 'checked' : '';?>>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="btn-group gap-1">
                                    <?php if($l->etat_compte == 1):?>
                                        <a href="<?php echo site_url('users/verrouiller_compte_user/'.$l->users_id); ?>" class="btn btn-sm btn-outline-warning" title="Verrouiller" onClick="return confirm('Verrouiller ce compte ?')">
                                            <i class="fas fa-lock"></i>
                                        </a>
                                    <?php else:?>
                                        <a href="<?php echo site_url('users/deverrouiller_compte_user/'.$l->users_id); ?>" class="btn btn-sm btn-warning" title="Déverrouiller" onClick="return confirm('Déverrouiller ce compte ?')">
                                            <i class="fas fa-unlock"></i>
                                        </a>
                                    <?php endif;?>

                                    <?php if(is_allowed('update_user')):?>
                                        <a href="<?php echo site_url('users/update/'.$l->users_id); ?>" class="btn btn-sm btn-outline-info" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php endif;?>

                                    <?php if(is_allowed('delete_user')):?>
                                        <a href="<?php echo site_url('users/delete/'.$l->users_id); ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onClick="return confirm('Supprimer ce compte définitivement ?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php endif;?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer border-secondary text-end bg-dark">
                <button type="submit" class="btn btn-success" onClick="return confirm('Valider les changements d\'état des comptes ?')">
                    <i class="fas fa-check me-1"></i> Enregistrer les états
                </button>
            </div>
            <?php echo form_close();?>
        </div>
    </div>
    <?php else:?>
        <div class="alert alert-info border-info bg-dark text-info shadow-sm d-flex align-items-center" role="alert">
            <i class="fas fa-info-circle fa-2x me-3"></i>
            <div>
                <h5 class="alert-heading mb-1">Aucun utilisateur</h5>
                <p class="mb-0">Aucun compte utilisateur n'est disponible pour le moment.</p>
            </div>
        </div>
    <?php endif;?>
</div>