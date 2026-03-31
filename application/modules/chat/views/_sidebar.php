<?php $chat_avatar_colors = chat_view_avatar_colors(); ?>

<div class="chat-sidebar-header">
    <h6 class="fw-semibold mb-3" style="color: var(--text-primary);"><i class="fas fa-comments me-2" style="color: var(--primary);"></i>Messagerie</h6>
    <div class="position-relative mb-3">
        <i class="fas fa-search position-absolute top-50 translate-middle-y ms-3 small" style="color: var(--text-secondary);"></i>
        <input type="text" id="chatSearch" class="form-control form-control-sm ps-5" placeholder="Rechercher..." style="background: var(--bg-main); border: 1px solid var(--border-color); border-radius: 20px;">
    </div>
    <ul class="nav nav-tabs chat-tabs" id="chatTabs" role="tablist">
        <li class="nav-item flex-fill" role="presentation">
            <button class="nav-link w-100 <?php echo ($active_type == 'private' || ! $active_type) ? 'active' : ''; ?>" id="private-tab" data-bs-toggle="tab" data-bs-target="#privatePanel" type="button" role="tab">
                <i class="fas fa-user me-1"></i> Privé
                <span class="badge rounded-pill ms-1 <?php echo !empty($unread_summary['private_total']) ? '' : 'd-none'; ?>" id="private-tab-unread" style="background: #dc3545; color: #fff; font-size: 0.68rem; min-width: 20px;">
                    <?php echo !empty($unread_summary['private_total']) ? (int) $unread_summary['private_total'] : 0; ?>
                </span>
            </button>
        </li>
        <li class="nav-item flex-fill" role="presentation">
            <button class="nav-link w-100 <?php echo ($active_type == 'group') ? 'active' : ''; ?>" id="group-tab" data-bs-toggle="tab" data-bs-target="#groupPanel" type="button" role="tab">
                <i class="fas fa-users me-1"></i> Groupes
                <span class="badge rounded-pill ms-1 <?php echo !empty($unread_summary['group_total']) ? '' : 'd-none'; ?>" id="group-tab-unread" style="background: #dc3545; color: #fff; font-size: 0.68rem; min-width: 20px;">
                    <?php echo !empty($unread_summary['group_total']) ? (int) $unread_summary['group_total'] : 0; ?>
                </span>
            </button>
        </li>
    </ul>
</div>

<div class="chat-sidebar-body tab-content" id="chatTabsContent">
    <div class="tab-pane fade <?php echo ($active_type == 'private' || ! $active_type) ? 'show active' : ''; ?>" id="privatePanel" role="tabpanel">
        <?php if (isset($users) && ! empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <?php $is_active = ($active_type == 'private' && $active_id == $user->users_id); ?>
                <a href="<?php echo site_url('chat/index/private/' . $user->users_id); ?>" class="chat-contact-item <?php echo $is_active ? 'active' : ''; ?>" data-chat-type="private" data-chat-id="<?php echo (int) $user->users_id; ?>" data-search="<?php echo htmlspecialchars(strtolower($user->users_nom . ' ' . $user->users_prenom . ' ' . $user->users_username)); ?>">
                    <div class="position-relative flex-shrink-0">
                        <div class="chat-avatar" style="background: <?php echo chat_view_avatar_color($user->users_id, $chat_avatar_colors); ?>;">
                            <?php echo chat_view_initials($user->users_nom, $user->users_prenom); ?>
                        </div>
                        <span class="chat-status-dot <?php echo ($user->etat_online == 1) ? 'online' : ''; ?>"></span>
                    </div>
                    <div class="min-width-0 flex-grow-1">
                        <div class="fw-medium small text-truncate" style="color: var(--text-primary);"><?php echo htmlspecialchars($user->users_nom . ' ' . $user->users_prenom); ?></div>
                        <div class="text-truncate" style="font-size: 0.75rem; color: var(--text-secondary);">@<?php echo htmlspecialchars($user->users_username); ?></div>
                    </div>
                    <?php if (! empty($user->unread_count)): ?>
                        <span class="badge rounded-pill flex-shrink-0 ms-2" style="background: #dc3545; color: #fff; min-width: 24px; font-size: 0.7rem;">
                            <?php echo ($user->unread_count > 99) ? '99+' : (int) $user->unread_count; ?>
                        </span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-4 px-3">
                <i class="fas fa-user-slash mb-2" style="font-size: 1.5rem; color: var(--text-secondary);"></i>
                <p class="small mb-0" style="color: var(--text-secondary);">Aucun contact disponible</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="tab-pane fade <?php echo ($active_type == 'group') ? 'show active' : ''; ?>" id="groupPanel" role="tabpanel">
        <?php if (isset($groups) && ! empty($groups)): ?>
            <?php foreach ($groups as $grp): ?>
                <?php $is_active = ($active_type == 'group' && $active_id == $grp->id); ?>
                <a href="<?php echo site_url('chat/index/group/' . $grp->id); ?>" class="chat-contact-item <?php echo $is_active ? 'active' : ''; ?>" data-chat-type="group" data-chat-id="<?php echo (int) $grp->id; ?>" data-search="<?php echo htmlspecialchars(strtolower($grp->name)); ?>">
                    <div class="flex-shrink-0">
                        <div class="chat-avatar chat-avatar-group">
                            <i class="fas fa-users small"></i>
                        </div>
                    </div>
                    <div class="min-width-0 flex-grow-1">
                        <div class="fw-medium small text-truncate" style="color: var(--text-primary);"><?php echo htmlspecialchars($grp->name); ?></div>
                        <div class="text-truncate" style="font-size: 0.75rem; color: var(--text-secondary);">Groupe</div>
                    </div>
                    <?php if (! empty($grp->unread_count)): ?>
                        <span class="badge rounded-pill flex-shrink-0 ms-2" style="background: #dc3545; color: #fff; min-width: 24px; font-size: 0.7rem;">
                            <?php echo ($grp->unread_count > 99) ? '99+' : (int) $grp->unread_count; ?>
                        </span>
                    <?php endif; ?>
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