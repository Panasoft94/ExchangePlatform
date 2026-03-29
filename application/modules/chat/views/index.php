<?php
    $session = $this->session->userdata('users');
    $current_user_id = $session->users_id;

    $avatar_colors = ['#1a73e8','#188038','#e37400','#c5221f','#9334e6','#e52592','#1967d2','#e8710a'];
    function get_get_chat_avatar_color($id, $colors) {
        return $colors[$id % count($colors)];
    }
    function get_get_chat_initials($nom, $prenom) {
        return strtoupper(mb_substr($nom, 0, 1) . mb_substr($prenom, 0, 1));
    }
?>

<div class="chat-wrapper">
    <!-- Left Sidebar -->
    <div class="chat-sidebar" id="chatSidebar">
        <div class="chat-sidebar-header">
            <h6 class="fw-semibold mb-3" style="color: var(--text-primary);"><i class="fas fa-comments me-2" style="color: var(--primary);"></i>Messagerie</h6>
            <div class="position-relative mb-3">
                <i class="fas fa-search position-absolute top-50 translate-middle-y ms-3 small" style="color: var(--text-secondary);"></i>
                <input type="text" id="chatSearch" class="form-control form-control-sm ps-5" placeholder="Rechercher..." style="background: var(--bg-main); border: 1px solid var(--border-color); border-radius: 20px;">
            </div>
            <ul class="nav nav-tabs chat-tabs" id="chatTabs" role="tablist">
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100 <?php echo ($active_type == 'private' || !$active_type) ? 'active' : ''; ?>" id="private-tab" data-bs-toggle="tab" data-bs-target="#privatePanel" type="button" role="tab">
                        <i class="fas fa-user me-1"></i> Privé
                    </button>
                </li>
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100 <?php echo ($active_type == 'group') ? 'active' : ''; ?>" id="group-tab" data-bs-toggle="tab" data-bs-target="#groupPanel" type="button" role="tab">
                        <i class="fas fa-users me-1"></i> Groupes
                    </button>
                </li>
            </ul>
        </div>

        <div class="chat-sidebar-body tab-content" id="chatTabsContent">
            <!-- Private contacts -->
            <div class="tab-pane fade <?php echo ($active_type == 'private' || !$active_type) ? 'show active' : ''; ?>" id="privatePanel" role="tabpanel">
                <?php if(isset($users) && !empty($users)): ?>
                    <?php foreach($users as $user): ?>
                        <?php $is_active = ($active_type == 'private' && $active_id == $user->users_id); ?>
                        <a href="<?php echo site_url('chat/index/private/'.$user->users_id); ?>" class="chat-contact-item <?php echo $is_active ? 'active' : ''; ?>" data-search="<?php echo htmlspecialchars(strtolower($user->users_nom.' '.$user->users_prenom.' '.$user->users_username)); ?>">
                            <div class="position-relative flex-shrink-0">
                                <div class="chat-avatar" style="background: <?php echo get_chat_avatar_color($user->users_id, $avatar_colors); ?>;">
                                    <?php echo get_chat_initials($user->users_nom, $user->users_prenom); ?>
                                </div>
                                <span class="chat-status-dot <?php echo ($user->etat_online == 1) ? 'online' : ''; ?>"></span>
                            </div>
                            <div class="min-width-0 flex-grow-1">
                                <div class="fw-medium small text-truncate" style="color: var(--text-primary);"><?php echo htmlspecialchars($user->users_nom.' '.$user->users_prenom); ?></div>
                                <div class="text-truncate" style="font-size: 0.75rem; color: var(--text-secondary);">@<?php echo htmlspecialchars($user->users_username); ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4 px-3">
                        <i class="fas fa-user-slash mb-2" style="font-size: 1.5rem; color: var(--text-secondary);"></i>
                        <p class="small mb-0" style="color: var(--text-secondary);">Aucun contact disponible</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Groups -->
            <div class="tab-pane fade <?php echo ($active_type == 'group') ? 'show active' : ''; ?>" id="groupPanel" role="tabpanel">
                <?php if(isset($groups) && !empty($groups)): ?>
                    <?php foreach($groups as $grp): ?>
                        <?php $is_active = ($active_type == 'group' && $active_id == $grp->id); ?>
                        <a href="<?php echo site_url('chat/index/group/'.$grp->id); ?>" class="chat-contact-item <?php echo $is_active ? 'active' : ''; ?>" data-search="<?php echo htmlspecialchars(strtolower($grp->name)); ?>">
                            <div class="flex-shrink-0">
                                <div class="chat-avatar chat-avatar-group">
                                    <i class="fas fa-users small"></i>
                                </div>
                            </div>
                            <div class="min-width-0 flex-grow-1">
                                <div class="fw-medium small text-truncate" style="color: var(--text-primary);"><?php echo htmlspecialchars($grp->name); ?></div>
                                <div class="text-truncate" style="font-size: 0.75rem; color: var(--text-secondary);">Groupe</div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4 px-3">
                        <i class="fas fa-users-slash mb-2" style="font-size: 1.5rem; color: var(--text-secondary);"></i>
                        <p class="small mb-0" style="color: var(--text-secondary);">Aucun groupe disponible</p>
                    </div>
                <?php endif; ?>
                <div class="p-3">
                    <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                        <i class="fas fa-plus me-1"></i> Créer un groupe
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile sidebar toggle -->
    <button class="btn btn-primary chat-sidebar-toggle d-md-none" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Center Panel -->
    <div class="chat-main">
        <?php if($active_partner || $active_group): ?>
            <!-- Chat Header -->
            <div class="chat-main-header">
                <div class="d-flex align-items-center">
                    <?php if($active_type == 'private'): ?>
                        <div class="position-relative me-3 flex-shrink-0">
                            <div class="chat-avatar" style="background: <?php echo get_chat_avatar_color($active_partner->users_id, $avatar_colors); ?>;">
                                <?php echo get_chat_initials($active_partner->users_nom, $active_partner->users_prenom); ?>
                            </div>
                            <span class="chat-status-dot <?php echo ($active_partner->etat_online == 1) ? 'online' : ''; ?>"></span>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold" style="color: var(--text-primary);"><?php echo htmlspecialchars($active_partner->users_nom.' '.$active_partner->users_prenom); ?></h6>
                            <?php if($active_partner->etat_online == 1): ?>
                                <span class="small" style="color: #188038;"><i class="fas fa-circle" style="font-size: 0.45rem;"></i> En ligne</span>
                            <?php else: ?>
                                <span class="small" style="color: var(--text-secondary);"><i class="fas fa-circle" style="font-size: 0.45rem;"></i> Hors ligne</span>
                            <?php endif; ?>
                        </div>
                    <?php elseif($active_type == 'group'): ?>
                        <div class="flex-shrink-0 me-3">
                            <div class="chat-avatar chat-avatar-group">
                                <i class="fas fa-users small"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-semibold" style="color: var(--text-primary);"><?php echo htmlspecialchars($active_group->name); ?></h6>
                            <span class="small" style="color: var(--text-secondary);">Groupe</span>
                        </div>
                        <button class="btn btn-sm px-3" style="background: var(--primary-light); color: var(--primary);" data-bs-toggle="modal" data-bs-target="#addGroupMembersModal">
                            <i class="fas fa-user-plus me-1"></i> Ajouter
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="chat-messages" id="chat-messages">
                <?php if(isset($messages) && !empty($messages)): ?>
                    <?php
                        $last_date = '';
                        foreach($messages as $msg):
                            $msg_date = date('d/m/Y', strtotime($msg->created_at));
                            if($msg_date !== $last_date):
                                $last_date = $msg_date;
                    ?>
                        <div class="chat-date-separator">
                            <span><?php echo ($msg_date === date('d/m/Y')) ? "Aujourd'hui" : $msg_date; ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if($msg->sender_id == $current_user_id): ?>
                        <div class="chat-bubble-row sent">
                            <div class="chat-bubble sent">
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($msg->content)); ?></p>
                                <span class="chat-bubble-time"><?php echo date('H:i', strtotime($msg->created_at)); ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="chat-bubble-row received">
                            <?php if($active_type == 'group'): ?>
                                <div class="chat-bubble-avatar" style="background: <?php echo get_chat_avatar_color($msg->sender_id, $avatar_colors); ?>;">
                                    <?php echo strtoupper(mb_substr($msg->users_nom, 0, 1) . mb_substr($msg->users_prenom, 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            <div class="chat-bubble received">
                                <?php if($active_type == 'group'): ?>
                                    <div class="chat-bubble-sender" style="color: <?php echo get_chat_avatar_color($msg->sender_id, $avatar_colors); ?>;"><?php echo htmlspecialchars($msg->users_nom.' '.$msg->users_prenom); ?></div>
                                <?php endif; ?>
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($msg->content)); ?></p>
                                <span class="chat-bubble-time"><?php echo date('H:i', strtotime($msg->created_at)); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="chat-empty-messages">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 72px; height: 72px; background: var(--primary-light);">
                            <i class="fas fa-paper-plane fa-lg" style="color: var(--primary);"></i>
                        </div>
                        <h6 class="fw-semibold mb-1" style="color: var(--text-primary);">Commencez à écrire un message</h6>
                        <p class="small mb-0" style="color: var(--text-secondary);">Envoyez le premier message à <?php echo ($active_type == 'private') ? 'cette personne' : 'ce groupe'; ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Input Area -->
            <div class="chat-input-area">
                <?php echo form_open('chat/send_message', array('class' => 'd-flex align-items-end gap-2 m-0')); ?>
                    <input type="hidden" name="type" value="<?php echo $active_type; ?>">
                    <input type="hidden" name="target_id" value="<?php echo $active_id; ?>">
                    <div class="flex-grow-1">
                        <textarea name="content" id="chatInput" class="form-control" rows="1" placeholder="Écrivez un message..." required autocomplete="off" style="border: 1px solid var(--border-color); border-radius: 24px; padding: 10px 18px; resize: none; max-height: 120px; background: var(--bg-main);"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                <?php echo form_close(); ?>
            </div>

        <?php else: ?>
            <!-- Empty state: no conversation selected -->
            <div class="chat-empty-state">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-4" style="width: 88px; height: 88px; background: var(--primary-light);">
                    <i class="fas fa-comments fa-2x" style="color: var(--primary);"></i>
                </div>
                <h5 class="fw-semibold mb-2" style="color: var(--text-primary);">Sélectionnez une conversation</h5>
                <p class="small mb-4" style="color: var(--text-secondary); max-width: 360px;">Choisissez un contact ou un groupe dans la liste pour commencer à discuter</p>
                <button class="btn btn-primary btn-sm px-4" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                    <i class="fas fa-plus me-1"></i> Nouveau Groupe
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Create Group Modal -->
<div class="modal fade" id="createGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: var(--radius-lg);">
            <?php echo form_open('chat/create_group'); ?>
            <div class="modal-header border-bottom" style="border-color: var(--border-color) !important;">
                <h6 class="modal-title fw-semibold" style="color: var(--text-primary);"><i class="fas fa-users me-2" style="color: var(--primary);"></i>Créer un groupe</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4">
                    <label for="modal_group_name" class="form-label small fw-semibold" style="color: var(--text-primary);">Nom du groupe</label>
                    <input type="text" class="form-control" id="modal_group_name" name="group_name" placeholder="Ex: Équipe Projet..." required style="border: 1px solid var(--border-color); border-radius: var(--radius);">
                </div>
                <div>
                    <label class="form-label small fw-semibold" style="color: var(--text-primary);">Membres</label>
                    <div class="border rounded-3 p-2" style="max-height: 240px; overflow-y: auto; border-color: var(--border-color) !important;">
                        <?php if(isset($users) && !empty($users)): ?>
                            <?php foreach($users as $u): ?>
                                <label class="d-flex align-items-center p-2 rounded-2 cursor-pointer modal-member-item" for="create_user_<?php echo $u->users_id; ?>" style="cursor: pointer;">
                                    <input class="form-check-input me-3 flex-shrink-0" type="checkbox" name="members[]" value="<?php echo $u->users_id; ?>" id="create_user_<?php echo $u->users_id; ?>">
                                    <div class="chat-avatar-sm me-2" style="background: <?php echo get_chat_avatar_color($u->users_id, $avatar_colors); ?>;">
                                        <?php echo get_chat_initials($u->users_nom, $u->users_prenom); ?>
                                    </div>
                                    <span class="small" style="color: var(--text-primary);"><?php echo htmlspecialchars($u->users_nom.' '.$u->users_prenom); ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="small text-center mb-0 py-2" style="color: var(--text-secondary);">Aucun utilisateur disponible</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top" style="border-color: var(--border-color) !important;">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary btn-sm px-3"><i class="fas fa-check me-1"></i> Créer</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Add Members Modal -->
<?php if($active_type == 'group' && isset($active_group)): ?>
<div class="modal fade" id="addGroupMembersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: var(--radius-lg);">
            <?php echo form_open('chat/add_members'); ?>
            <input type="hidden" name="group_id" value="<?php echo $active_group->id; ?>">
            <div class="modal-header border-bottom" style="border-color: var(--border-color) !important;">
                <h6 class="modal-title fw-semibold" style="color: var(--text-primary);"><i class="fas fa-user-plus me-2" style="color: #188038;"></i>Ajouter des membres</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="small mb-3" style="color: var(--text-secondary);">Invitez des membres dans « <strong><?php echo htmlspecialchars($active_group->name); ?></strong> »</p>
                <div class="border rounded-3 p-2" style="max-height: 240px; overflow-y: auto; border-color: var(--border-color) !important;">
                    <?php if(isset($users_not_in_group) && !empty($users_not_in_group)): ?>
                        <?php foreach($users_not_in_group as $u): ?>
                            <label class="d-flex align-items-center p-2 rounded-2 cursor-pointer modal-member-item" for="add_user_<?php echo $u->users_id; ?>" style="cursor: pointer;">
                                <input class="form-check-input me-3 flex-shrink-0" type="checkbox" name="new_members[]" value="<?php echo $u->users_id; ?>" id="add_user_<?php echo $u->users_id; ?>">
                                <div class="chat-avatar-sm me-2" style="background: <?php echo get_chat_avatar_color($u->users_id, $avatar_colors); ?>;">
                                    <?php echo get_chat_initials($u->users_nom, $u->users_prenom); ?>
                                </div>
                                <span class="small" style="color: var(--text-primary);"><?php echo htmlspecialchars($u->users_nom.' '.$u->users_prenom); ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="small text-center mb-0 py-2" style="color: var(--text-secondary);">Tous les utilisateurs sont déjà dans ce groupe</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer border-top" style="border-color: var(--border-color) !important;">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-sm px-3" style="background: #188038; color: #fff;"><i class="fas fa-plus me-1"></i> Ajouter</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
/* Chat Layout */
.chat-wrapper {
    display: flex;
    height: calc(100vh - 140px);
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    position: relative;
}

/* Sidebar */
.chat-sidebar {
    width: 300px;
    min-width: 300px;
    border-right: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    background: var(--bg-white);
}
.chat-sidebar-header {
    padding: 20px 16px 0;
    flex-shrink: 0;
}
.chat-sidebar-body {
    flex: 1;
    overflow-y: auto;
}
.chat-tabs {
    border-bottom: 1px solid var(--border-color);
}
.chat-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: var(--text-secondary);
    font-size: 0.8rem;
    font-weight: 500;
    padding: 10px 8px;
    border-radius: 0;
    transition: all 0.15s ease;
}
.chat-tabs .nav-link.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
    background: transparent;
}
.chat-tabs .nav-link:hover:not(.active) {
    color: var(--text-primary);
    border-bottom-color: var(--border-color);
}

/* Contact items */
.chat-contact-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    text-decoration: none;
    border-left: 3px solid transparent;
    transition: all 0.12s ease;
}
.chat-contact-item:hover {
    background: var(--bg-main);
}
.chat-contact-item.active {
    background: var(--primary-light);
    border-left-color: var(--primary);
}

/* Avatars */
.chat-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.8rem;
    font-weight: 600;
    flex-shrink: 0;
}
.chat-avatar-group {
    background: var(--primary-light) !important;
    color: var(--primary) !important;
}
.chat-avatar-sm {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.65rem;
    font-weight: 600;
    flex-shrink: 0;
}
.chat-status-dot {
    position: absolute;
    bottom: 1px;
    right: 1px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #dadce0;
    border: 2px solid var(--bg-white);
}
.chat-status-dot.online {
    background: #188038;
}

/* Main area */
.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
    background: var(--bg-main);
}
.chat-main-header {
    padding: 16px 24px;
    background: var(--bg-white);
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
}

/* Messages */
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
    display: flex;
    flex-direction: column;
}
.chat-date-separator {
    text-align: center;
    margin: 16px 0;
}
.chat-date-separator span {
    background: var(--bg-white);
    padding: 4px 16px;
    border-radius: 12px;
    font-size: 0.7rem;
    color: var(--text-secondary);
    font-weight: 500;
    box-shadow: var(--shadow-sm);
}
.chat-bubble-row {
    display: flex;
    margin-bottom: 8px;
    align-items: flex-end;
    gap: 8px;
}
.chat-bubble-row.sent {
    justify-content: flex-end;
}
.chat-bubble-row.received {
    justify-content: flex-start;
}
.chat-bubble-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.6rem;
    font-weight: 600;
    flex-shrink: 0;
}
.chat-bubble {
    max-width: 65%;
    padding: 10px 16px;
    font-size: 0.875rem;
    line-height: 1.5;
    word-wrap: break-word;
}
.chat-bubble.sent {
    background: var(--primary);
    color: #fff;
    border-radius: 18px 18px 4px 18px;
}
.chat-bubble.received {
    background: var(--bg-white);
    color: var(--text-primary);
    border-radius: 18px 18px 18px 4px;
    border: 1px solid var(--border-color);
}
.chat-bubble-sender {
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 2px;
}
.chat-bubble-time {
    display: block;
    font-size: 0.65rem;
    margin-top: 4px;
    opacity: 0.7;
    text-align: right;
}
.chat-bubble.sent .chat-bubble-time {
    color: rgba(255,255,255,0.7);
}
.chat-bubble.received .chat-bubble-time {
    color: var(--text-secondary);
}

/* Empty states */
.chat-empty-messages,
.chat-empty-state {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 40px;
}

/* Input area */
.chat-input-area {
    padding: 16px 24px;
    background: var(--bg-white);
    border-top: 1px solid var(--border-color);
    flex-shrink: 0;
}

/* Modal items */
.modal-member-item:hover {
    background: var(--bg-main);
}
.form-check-input:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}

/* Scrollbar styling */
.chat-sidebar-body::-webkit-scrollbar,
.chat-messages::-webkit-scrollbar {
    width: 5px;
}
.chat-sidebar-body::-webkit-scrollbar-track,
.chat-messages::-webkit-scrollbar-track {
    background: transparent;
}
.chat-sidebar-body::-webkit-scrollbar-thumb,
.chat-messages::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 3px;
}

/* Mobile toggle */
.chat-sidebar-toggle {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 10;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

/* Responsive */
@media (max-width: 767.98px) {
    .chat-sidebar {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 20;
        transform: translateX(-100%);
        transition: transform 0.25s ease;
        box-shadow: var(--shadow-md);
    }
    .chat-sidebar.show {
        transform: translateX(0);
    }
    .chat-wrapper {
        height: calc(100vh - 120px);
    }
}
@media (min-width: 768px) {
    .chat-sidebar-toggle {
        display: none !important;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Auto-scroll messages to bottom
    var chatMessages = document.getElementById("chat-messages");
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Auto-resize textarea
    var chatInput = document.getElementById("chatInput");
    if (chatInput) {
        chatInput.addEventListener("input", function() {
            this.style.height = "auto";
            this.style.height = Math.min(this.scrollHeight, 120) + "px";
        });
        // Submit on Enter (Shift+Enter for new line)
        chatInput.addEventListener("keydown", function(e) {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                if (this.value.trim()) {
                    this.closest("form").submit();
                }
            }
        });
    }

    // Sidebar search
    var searchInput = document.getElementById("chatSearch");
    if (searchInput) {
        searchInput.addEventListener("input", function() {
            var query = this.value.toLowerCase();
            document.querySelectorAll(".chat-contact-item").forEach(function(item) {
                var text = item.getAttribute("data-search") || "";
                item.style.display = text.indexOf(query) !== -1 ? "" : "none";
            });
        });
    }

    // Mobile sidebar toggle
    var sidebarToggle = document.getElementById("sidebarToggle");
    var chatSidebar = document.getElementById("chatSidebar");
    if (sidebarToggle && chatSidebar) {
        sidebarToggle.addEventListener("click", function() {
            chatSidebar.classList.toggle("show");
        });
        // Close sidebar when clicking a contact on mobile
        chatSidebar.querySelectorAll(".chat-contact-item").forEach(function(item) {
            item.addEventListener("click", function() {
                if (window.innerWidth < 768) {
                    chatSidebar.classList.remove("show");
                }
            });
        });
    }
});
</script>
