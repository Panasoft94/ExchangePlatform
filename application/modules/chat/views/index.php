<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-white"><i class="fas fa-comments me-2 text-primary"></i> Messagerie Principale</h2>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createGroupModal">
            <i class="fas fa-users"></i> Créer Chat Public
        </button>
    </div>

    <div class="row">
        <!-- Sidebar : Liste des discussions Privées ET Publiques -->
        <div class="col-md-4 mb-4">
            <div class="card bg-dark border-secondary shadow-sm h-100">
                <div class="card-header border-secondary bg-transparent p-0">
                    <ul class="nav nav-tabs nav-fill border-bottom-0" id="chatTabs" role="tablist">
                        <li class="nav-item border-bottom border-secondary" role="presentation">
                            <button class="nav-link w-100 py-3 rounded-0 text-white <?php echo ($active_type == 'private' || !$active_type) ? 'active bg-secondary bg-opacity-25' : ''; ?>" id="private-tab" data-bs-toggle="tab" data-bs-target="#private" type="button" role="tab" style="border: none;">
                                <i class="fas fa-user-friends me-1"></i> Chats Privés
                            </button>
                        </li>
                        <li class="nav-item border-bottom border-secondary" role="presentation">
                            <button class="nav-link w-100 py-3 rounded-0 text-white <?php echo ($active_type == 'group') ? 'active bg-secondary bg-opacity-25' : ''; ?>" id="group-tab" data-bs-toggle="tab" data-bs-target="#group" type="button" role="tab" style="border: none;">
                                <i class="fas fa-users me-1"></i> Chats Publics / Groupes
                            </button>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                    <div class="tab-content" id="chatTabsContent">
                        
                        <!-- TAB PRIVÉ -->
                        <div class="tab-pane fade <?php echo ($active_type == 'private' || !$active_type) ? 'show active' : ''; ?>" id="private" role="tabpanel">
                            <div class="list-group list-group-flush border-bottom-0">
                                <?php if(isset($users) && !empty($users)): ?>
                                    <?php foreach($users as $user): ?>
                                        <a href="<?php echo site_url('chat/index/private/'.$user->users_id); ?>" class="list-group-item list-group-item-action bg-dark text-white border-secondary border-start-0 border-end-0 border-top-0 <?php echo ($active_type == 'private' && $active_id == $user->users_id) ? 'active bg-primary bg-opacity-10' : ''; ?>">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="position-relative me-3">
                                                        <i class="fas fa-user-circle fa-2x text-secondary"></i>
                                                        <span class="position-absolute bottom-0 end-0 p-1 border border-dark rounded-circle <?php echo ($user->etat_online == 1) ? 'bg-success' : 'bg-secondary'; ?>"></span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-white"><?php echo htmlspecialchars($user->users_nom . ' ' . $user->users_prenom); ?></h6>
                                                        <small class="<?php echo ($active_type == 'private' && $active_id == $user->users_id) ? 'text-white' : 'text-muted'; ?>">@<?php echo htmlspecialchars($user->users_username); ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="p-3 text-muted text-center small">Aucun contact trouvé</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- TAB GROUPES / PUBLIC -->
                        <div class="tab-pane fade <?php echo ($active_type == 'group') ? 'show active' : ''; ?>" id="group" role="tabpanel">
                            <div class="list-group list-group-flush border-bottom-0">
                                <?php if(isset($groups) && !empty($groups)): ?>
                                    <?php foreach($groups as $grp): ?>
                                        <a href="<?php echo site_url('chat/index/group/'.$grp->id); ?>" class="list-group-item list-group-item-action bg-dark text-white border-secondary border-start-0 border-end-0 border-top-0 <?php echo ($active_type == 'group' && $active_id == $grp->id) ? 'active bg-primary bg-opacity-10' : ''; ?>">
                                            <div class="d-flex w-100 align-items-center">
                                                <div class="bg-primary bg-opacity-25 p-2 rounded-circle me-3 d-flex justify-content-center align-items-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-users text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 text-white"><?php echo htmlspecialchars($grp->name); ?></h6>
                                                    <small class="<?php echo ($active_type == 'group' && $active_id == $grp->id) ? 'text-white-50' : 'text-muted'; ?>">Chat Public (Groupe)</small>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="p-4 text-center">
                                        <i class="fas fa-users-slash fa-2x text-muted mb-2"></i>
                                        <p class="text-muted small">Vous n'êtes membre d'aucun chat public.</p>
                                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createGroupModal">Créer maintenant</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Fenêtre d'Affichage des Messages -->
        <div class="col-md-8 mb-4">
            <div class="card bg-dark border-secondary shadow-sm h-100">
                <?php if($active_partner || $active_group): ?>
                    
                    <!-- Header Fenêtre -->
                    <div class="card-header border-secondary bg-transparent d-flex justify-content-between align-items-center p-3">
                        <div class="d-flex align-items-center">
                            <?php if($active_type == 'private'): ?>
                                <i class="fas fa-user-circle fa-2x me-3 <?php echo ($active_partner->etat_online == 1) ? 'text-success' : 'text-secondary'; ?>"></i>
                                <div>
                                    <h5 class="card-title mb-0 text-white"><?php echo htmlspecialchars($active_partner->users_nom . ' ' . $active_partner->users_prenom); ?></h5>
                                    <?php if($active_partner->etat_online == 1): ?>
                                        <small class="text-success fw-bold"><i class="fas fa-circle font-10"></i> En ligne</small>
                                    <?php else: ?>
                                        <small class="text-muted"><i class="fas fa-circle font-10"></i> Hors ligne</small>
                                    <?php endif; ?>
                                </div>
                            <?php elseif($active_type == 'group'): ?>
                                <div class="bg-primary bg-opacity-25 p-2 rounded-circle me-3 d-flex justify-content-center align-items-center" style="width: 45px; height: 45px;">
                                    <i class="fas fa-users text-primary fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0 text-white"><?php echo htmlspecialchars($active_group->name); ?></h5>
                                    <span class="badge bg-secondary mt-1">Chat Public</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <?php if($active_type == 'group'): ?>
                                <button class="btn btn-sm btn-outline-success me-1" data-bs-toggle="modal" data-bs-target="#addGroupMembersModal"><i class="fas fa-user-plus"></i> Inviter</button>
                            <?php endif; ?>
                            <button class="btn btn-sm btn-outline-info me-1"><i class="fas fa-search"></i></button>
                            <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-ellipsis-v"></i></button>
                        </div>
                    </div>
                    
                    <!-- Corps des messages -->
                    <div class="card-body d-flex flex-column" style="height: 450px; overflow-y: auto; background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.02\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); background-color: #121212;" id="chat-messages">
                        
                        <?php if(isset($messages) && !empty($messages)): 
                            $session = $this->session->userdata('users');
                            $current_user_id = $session->users_id;
                        ?>
                            <?php foreach($messages as $msg): ?>
                                <?php if($msg->sender_id == $current_user_id): // Message personnel (Expéditeur) ?>
                                    <div class="d-flex flex-row justify-content-end mb-3 align-items-end">
                                        <div class="p-3 bg-primary rounded-3 text-white me-2 shadow-sm" style="max-width: 75%; border-bottom-right-radius: 0 !important;">
                                            <p class="small mb-0"><?php echo nl2br(htmlspecialchars($msg->content)); ?></p>
                                            <small class="text-white-50 d-block text-end mt-1" style="font-size: 0.65rem;"><?php echo date('H:i', strtotime($msg->created_at)); ?> <i class="fas fa-check-double ms-1"></i></small>
                                        </div>
                                    </div>
                                <?php else: // Message d'un tiers (Destinataire / Groupe) ?>
                                    <div class="d-flex flex-row justify-content-start mb-3 align-items-end">
                                        <?php if($active_type == 'group'): ?>
                                            <div class="me-2 text-center" style="width: 35px;">
                                                <div class="bg-secondary rounded-circle d-flex mx-auto justify-content-center align-items-center text-white small" style="width: 30px; height: 30px;">
                                                    <?php echo strtoupper(substr($msg->users_nom, 0, 1) . substr($msg->users_prenom, 0, 1)); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="p-3 bg-secondary rounded-3 text-white shadow-sm" style="max-width: 75%; border-bottom-left-radius: 0 !important; background-color: #2c2c2c !important;">
                                            <?php if($active_type == 'group'): ?>
                                                <div class="text-info fw-bold small mb-1" style="font-size: 0.75rem;"><?php echo htmlspecialchars($msg->users_nom . ' ' . $msg->users_prenom); ?></div>
                                            <?php endif; ?>
                                            <p class="small mb-0"><?php echo nl2br(htmlspecialchars($msg->content)); ?></p>
                                            <small class="text-white-50 d-block mt-1" style="font-size: 0.65rem;"><?php echo date('H:i', strtotime($msg->created_at)); ?></small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="h-100 d-flex flex-column justify-content-center align-items-center text-center mt-auto mb-auto">
                                <div class="bg-dark rounded-circle p-4 mb-3 border border-secondary shadow">
                                    <i class="fas fa-hand-wave fa-3x text-primary"></i>
                                </div>
                                <h6 class="text-white">Démarrez la conversation !</h6>
                                <p class="text-muted small">Envoyez le premier message à <?php echo ($active_type == 'private') ? 'cette personne' : 'ce groupe'; ?>.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Barre de saisie -->
                    <div class="card-footer border-secondary bg-transparent p-3">
                        <?php echo form_open('chat/send_message', array('class' => 'm-0')); ?>
                            <input type="hidden" name="type" value="<?php echo $active_type; ?>">
                            <input type="hidden" name="target_id" value="<?php echo $active_id; ?>">
                            <div class="input-group">
                                <button class="btn btn-dark border-secondary text-primary px-3" type="button" title="Joindre un fichier"><i class="fas fa-paperclip"></i></button>
                                <input type="text" name="content" class="form-control bg-dark text-white border-secondary px-3" placeholder="Écrivez votre message ici..." aria-label="Message" required autocomplete="off" autofocus>
                                <button type="submit" class="btn btn-primary px-4 fw-bold"><i class="fas fa-paper-plane me-1"></i> Envoyer</button>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                <!-- État vide (Aucune conversation sélectionnée) -->
                <?php else: ?>
                    <div class="h-100 d-flex flex-column justify-content-center align-items-center text-muted text-center p-5">
                        <div class="display-1 text-secondary mb-4 opacity-50"><i class="fab fa-whatsapp"></i></div>
                        <h4 class="text-white">Messagerie & Chat</h4>
                        <p class="small text-muted mb-4 max-w-75">Connectez-vous en direct avec vos collaborateurs via notre messagerie privée de bout-en-bout ou créez des groupes publics pour les discussions d'équipes.</p>
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createGroupModal"><i class="fas fa-plus me-1"></i> Nouveau Groupe</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Création de Groupe Public -->
<div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModalLabel" aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <?php echo form_open('chat/create_group'); ?>
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="createGroupModalLabel"><i class="fas fa-users text-primary me-2"></i> Créer un Chat Public (Groupe)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <label for="group_name" class="form-label small text-muted text-uppercase fw-bold">Nom du groupe Public</label>
                    <input type="text" class="form-control bg-transparent text-white border-secondary" id="group_name" name="group_name" placeholder="Ex: Équipe Projet Dev..." required>
                </div>
                
                <div class="mb-2">
                    <label class="form-label small text-muted text-uppercase fw-bold">Sélectionnez les membres à inviter</label>
                    <div class="border border-secondary rounded p-2 bg-transparent" style="max-height: 250px; overflow-y: auto;">
                        <?php if(isset($users) && !empty($users)): ?>
                            <?php foreach($users as $u): ?>
                                <div class="form-check custom-control custom-checkbox mb-2 p-2 rounded hover-bg-secondary cursor-pointer">
                                    <input class="form-check-input ms-1" type="checkbox" name="members[]" value="<?php echo $u->users_id; ?>" id="user_<?php echo $u->users_id; ?>">
                                    <label class="form-check-label w-100 ms-2" style="cursor: pointer;" for="user_<?php echo $u->users_id; ?>">
                                        <i class="fas fa-user-circle text-secondary me-1"></i> <?php echo htmlspecialchars($u->users_nom . ' ' . $u->users_prenom); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted small mb-0 p-2">Aucun utilisateur disponible pour créer un groupe.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i> Confirmer & Créer</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Modal Ajout de Membres au Groupe -->
<?php if($active_type == 'group' && isset($active_group)): ?>
<div class="modal fade" id="addGroupMembersModal" tabindex="-1" aria-labelledby="addGroupMembersModalLabel" aria-hidden="true" data-bs-theme="dark">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <?php echo form_open('chat/add_members'); ?>
            <input type="hidden" name="group_id" value="<?php echo $active_group->id; ?>">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="addGroupMembersModalLabel"><i class="fas fa-user-plus text-success me-2"></i> Inviter dans le groupe</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-3">Sélectionnez de nouveaux collaborateurs à ajouter au groupe "<strong><?php echo htmlspecialchars($active_group->name); ?></strong>".</p>
                <div class="mb-2">
                    <div class="border border-secondary rounded p-2 bg-transparent" style="max-height: 250px; overflow-y: auto;">
                        <?php if(isset($users_not_in_group) && !empty($users_not_in_group)): ?>
                            <?php foreach($users_not_in_group as $u): ?>
                                <div class="form-check custom-control custom-checkbox mb-2 p-2 rounded hover-bg-secondary cursor-pointer">
                                    <input class="form-check-input ms-1" type="checkbox" name="new_members[]" value="<?php echo $u->users_id; ?>" id="new_user_<?php echo $u->users_id; ?>">
                                    <label class="form-check-label w-100 ms-2" style="cursor: pointer;" for="new_user_<?php echo $u->users_id; ?>">
                                        <i class="fas fa-user-circle text-secondary me-1"></i> <?php echo htmlspecialchars($u->users_nom . ' ' . $u->users_prenom); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted small mb-0 p-2">Aucun nouvel utilisateur n'est disponible. Tout le monde est déjà dans ce groupe !</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-plus me-1"></i> Ajouter la sélection</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
/* Utilities */
.cursor-pointer { cursor: pointer; }
.hover-bg-secondary:hover { background-color: rgba(255,255,255,0.05); }
.max-w-75 { max-width: 75%; margin: 0 auto; }
/* Scrollbar */
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: #121212; }
::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: #007BFF; }
/* Nav Tabs customization */
.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
    background-color: transparent;
    color: #fff !important;
    border-bottom: 2px solid var(--primary-blue) !important;
}
.nav-tabs .nav-link { border-bottom: 2px solid transparent !important; transition: all 0.2s; }
.nav-tabs .nav-link:hover { border-bottom: 2px solid #555 !important; }
</style>

<script>
// Scroll automatique vers le bas de la fenêtre de chat
document.addEventListener("DOMContentLoaded", function() {
    var chatMessages = document.getElementById("chat-messages");
    if(chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
});
</script>
