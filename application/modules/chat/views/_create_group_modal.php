<?php $chat_avatar_colors = chat_view_avatar_colors(); ?>

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
                        <?php if (isset($users) && ! empty($users)): ?>
                            <?php foreach ($users as $u): ?>
                                <label class="d-flex align-items-center p-2 rounded-2 cursor-pointer modal-member-item" for="create_user_<?php echo $u->users_id; ?>" style="cursor: pointer;">
                                    <input class="form-check-input me-3 flex-shrink-0" type="checkbox" name="members[]" value="<?php echo $u->users_id; ?>" id="create_user_<?php echo $u->users_id; ?>">
                                    <div class="chat-avatar-sm me-2" style="background: <?php echo chat_view_avatar_color($u->users_id, $chat_avatar_colors); ?>;">
                                        <?php echo chat_view_initials($u->users_nom, $u->users_prenom); ?>
                                    </div>
                                    <span class="small" style="color: var(--text-primary);"><?php echo htmlspecialchars($u->users_nom . ' ' . $u->users_prenom); ?></span>
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