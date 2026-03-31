<?php $chat_avatar_colors = chat_view_avatar_colors(); ?>

<?php if ($active_type == 'group' && isset($active_group) && $active_group): ?>
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
                    <?php if (isset($users_not_in_group) && ! empty($users_not_in_group)): ?>
                        <?php foreach ($users_not_in_group as $u): ?>
                            <label class="d-flex align-items-center p-2 rounded-2 cursor-pointer modal-member-item" for="add_user_<?php echo $u->users_id; ?>" style="cursor: pointer;">
                                <input class="form-check-input me-3 flex-shrink-0" type="checkbox" name="new_members[]" value="<?php echo $u->users_id; ?>" id="add_user_<?php echo $u->users_id; ?>">
                                <div class="chat-avatar-sm me-2" style="background: <?php echo chat_view_avatar_color($u->users_id, $chat_avatar_colors); ?>;">
                                    <?php echo chat_view_initials($u->users_nom, $u->users_prenom); ?>
                                </div>
                                <span class="small" style="color: var(--text-primary);"><?php echo htmlspecialchars($u->users_nom . ' ' . $u->users_prenom); ?></span>
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