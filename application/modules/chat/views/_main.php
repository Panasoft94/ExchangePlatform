<?php
$session = $this->session->userdata('users');
$current_user_id = $session->users_id;
$chat_avatar_colors = chat_view_avatar_colors();
?>

<?php if ($active_partner || $active_group): ?>
    <div class="chat-main-header">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <?php if ($active_type == 'private'): ?>
                <div class="position-relative me-3 flex-shrink-0">
                    <div class="chat-avatar" style="background: <?php echo chat_view_avatar_color($active_partner->users_id, $chat_avatar_colors); ?>;">
                        <?php echo chat_view_initials($active_partner->users_nom, $active_partner->users_prenom); ?>
                    </div>
                    <span class="chat-status-dot <?php echo ($active_partner->etat_online == 1) ? 'online' : ''; ?>"></span>
                </div>
                <div>
                    <h6 class="mb-0 fw-semibold" style="color: var(--text-primary);"><?php echo htmlspecialchars($active_partner->users_nom . ' ' . $active_partner->users_prenom); ?></h6>
                    <?php if ($active_partner->etat_online == 1): ?>
                        <span class="small" style="color: #188038;"><i class="fas fa-circle" style="font-size: 0.45rem;"></i> En ligne</span>
                    <?php else: ?>
                        <span class="small" style="color: var(--text-secondary);"><i class="fas fa-circle" style="font-size: 0.45rem;"></i> Hors ligne</span>
                    <?php endif; ?>
                </div>
            <?php elseif ($active_type == 'group'): ?>
                <?php $group_members_count = isset($group_members) ? count($group_members) : 0; ?>
                <div class="flex-shrink-0">
                    <div class="chat-avatar chat-avatar-group">
                        <i class="fas fa-users small"></i>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold" style="color: var(--text-primary);"><?php echo htmlspecialchars($active_group->name); ?></h6>
                    <span class="small" style="color: var(--text-secondary);">
                        Groupe<?php if ($group_members_count > 0): ?> · <?php echo (int) $group_members_count; ?> membre<?php echo ($group_members_count > 1) ? 's' : ''; ?><?php endif; ?>
                    </span>
                </div>
                <?php if (!empty($group_members)): ?>
                    <div class="dropdown chat-group-members-dropdown">
                        <button class="btn chat-group-members-trigger dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="chat-group-members-stack">
                                <?php foreach (array_slice($group_members, 0, 4) as $member): ?>
                                    <span class="chat-group-member-dot" style="background: <?php echo chat_view_avatar_color($member->users_id, $chat_avatar_colors); ?>;">
                                        <?php echo chat_view_initials($member->users_nom, $member->users_prenom); ?>
                                    </span>
                                <?php endforeach; ?>
                            </span>
                            <span class="chat-group-members-meta">
                                <span class="chat-group-members-label">Membres</span>
                                <span class="chat-group-members-count"><?php echo (int) $group_members_count; ?></span>
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end chat-group-members-menu">
                            <div class="chat-group-members-menu-header">
                                <span>Participants</span>
                                <span><?php echo (int) $group_members_count; ?></span>
                            </div>
                            <div class="chat-group-members-menu-body">
                                <?php foreach ($group_members as $member): ?>
                                    <div class="chat-group-member-item">
                                        <span class="chat-group-member-avatar" style="background: <?php echo chat_view_avatar_color($member->users_id, $chat_avatar_colors); ?>;">
                                            <?php echo chat_view_initials($member->users_nom, $member->users_prenom); ?>
                                        </span>
                                        <div class="chat-group-member-text">
                                            <div class="chat-group-member-name"><?php echo htmlspecialchars($member->users_nom . ' ' . $member->users_prenom); ?></div>
                                            <div class="chat-group-member-subtitle">
                                                @<?php echo htmlspecialchars($member->users_username); ?>
                                                <?php if ((int) $member->etat_online === 1): ?>
                                                    <span class="chat-group-member-online">En ligne</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <button class="btn btn-sm px-3 chat-group-add-btn" style="background: var(--primary-light); color: var(--primary);" data-bs-toggle="modal" data-bs-target="#addGroupMembersModal">
                    <i class="fas fa-user-plus me-1"></i> Ajouter
                </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="chat-messages" id="chat-messages">
        <?php if (isset($messages) && ! empty($messages)): ?>
            <?php
            $last_date = '';
            foreach ($messages as $msg):
                $msg_date = date('d/m/Y', strtotime($msg->created_at));
                if ($msg_date !== $last_date):
                    $last_date = $msg_date;
            ?>
                <div class="chat-date-separator">
                    <span><?php echo ($msg_date === date('d/m/Y')) ? "Aujourd'hui" : $msg_date; ?></span>
                </div>
                <?php endif; ?>

                <?php if ($msg->sender_id == $current_user_id): ?>
                    <div class="chat-bubble-row sent">
                        <div class="chat-bubble sent">
                            <?php if (!empty($msg->attachments)): ?>
                                <div class="chat-attachments-list">
                                    <?php foreach ($msg->attachments as $attachment): ?>
                                        <?php $attachment_meta = chat_view_attachment_meta($attachment->original_name); ?>
                                        <a href="<?php echo site_url('chat/download_attachment/' . (int) $attachment->id); ?>" class="chat-attachment-chip sent" download title="Télécharger <?php echo htmlspecialchars($attachment->original_name); ?>">
                                            <span class="chat-attachment-icon" style="color: <?php echo $attachment_meta['color']; ?>;"><i class="<?php echo $attachment_meta['icon']; ?>"></i></span>
                                            <span class="chat-attachment-body">
                                                <span class="chat-attachment-name"><?php echo htmlspecialchars($attachment->original_name); ?></span>
                                                <span class="chat-attachment-meta"><?php echo chat_view_format_bytes($attachment->file_size); ?></span>
                                            </span>
                                            <span class="chat-attachment-action"><i class="fas fa-download"></i></span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($msg->content !== ''): ?>
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($msg->content)); ?></p>
                            <?php endif; ?>
                            <span class="chat-bubble-time"><?php echo date('H:i', strtotime($msg->created_at)); ?></span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="chat-bubble-row received">
                        <?php if ($active_type == 'group'): ?>
                            <div class="chat-bubble-avatar" style="background: <?php echo chat_view_avatar_color($msg->sender_id, $chat_avatar_colors); ?>;">
                                <?php echo chat_view_initials($msg->users_nom, $msg->users_prenom); ?>
                            </div>
                        <?php endif; ?>
                        <div class="chat-bubble received">
                            <?php if ($active_type == 'group'): ?>
                                <div class="chat-bubble-sender" style="color: <?php echo chat_view_avatar_color($msg->sender_id, $chat_avatar_colors); ?>;"><?php echo htmlspecialchars($msg->users_nom . ' ' . $msg->users_prenom); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($msg->attachments)): ?>
                                <div class="chat-attachments-list">
                                    <?php foreach ($msg->attachments as $attachment): ?>
                                        <?php $attachment_meta = chat_view_attachment_meta($attachment->original_name); ?>
                                        <a href="<?php echo site_url('chat/download_attachment/' . (int) $attachment->id); ?>" class="chat-attachment-chip received" download title="Télécharger <?php echo htmlspecialchars($attachment->original_name); ?>">
                                            <span class="chat-attachment-icon" style="color: <?php echo $attachment_meta['color']; ?>;"><i class="<?php echo $attachment_meta['icon']; ?>"></i></span>
                                            <span class="chat-attachment-body">
                                                <span class="chat-attachment-name"><?php echo htmlspecialchars($attachment->original_name); ?></span>
                                                <span class="chat-attachment-meta"><?php echo chat_view_format_bytes($attachment->file_size); ?></span>
                                            </span>
                                            <span class="chat-attachment-action"><i class="fas fa-download"></i></span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($msg->content !== ''): ?>
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($msg->content)); ?></p>
                            <?php endif; ?>
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

    <div class="chat-input-area">
        <?php echo form_open_multipart('chat/send_message', array('class' => 'm-0', 'id' => 'chat-message-form')); ?>
            <input type="hidden" name="type" value="<?php echo $active_type; ?>">
            <input type="hidden" name="target_id" value="<?php echo $active_id; ?>">
            <div class="chat-upload-preview" id="chatUploadPreview"></div>
            <div class="d-flex align-items-end gap-2">
                <div class="flex-shrink-0">
                    <label for="chatFiles" class="btn chat-attach-btn mb-0" title="Ajouter un document">
                        <i class="fas fa-plus"></i>
                    </label>
                    <input type="file" name="chat_files[]" id="chatFiles" class="d-none" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.png,.jpg,.jpeg,.gif,.txt,.csv,.zip,.rar" multiple>
                </div>
                <div class="flex-grow-1">
                    <textarea name="content" id="chatInput" class="form-control" rows="1" placeholder="Écrivez un message..." autocomplete="off" style="border: 1px solid var(--border-color); border-radius: 24px; padding: 10px 18px; resize: none; max-height: 120px; background: var(--bg-main);"></textarea>
                </div>
                <button type="submit" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px;">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        <?php echo form_close(); ?>
    </div>
<?php else: ?>
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