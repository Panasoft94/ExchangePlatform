<?php
$participant_ids = array();
if (!empty($participants)) {
    foreach ($participants as $p) {
        $participant_ids[] = (int) $p->user_id;
    }
}
$scheduled_value = date('Y-m-d\TH:i', strtotime($reunion->scheduled_at));
?>

<style>
.edit-reunion-card {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
}
.edit-participant-list { max-height: 280px; overflow-y: auto; }
.edit-participant-list .form-check {
    padding: 8px 12px;
    border-radius: var(--radius);
    transition: var(--transition);
}
.edit-participant-list .form-check:hover { background: var(--bg-main); }
</style>

<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-semibold mb-1" style="color: var(--text-primary);">
            <i class="fas fa-pen-to-square me-2" style="color: var(--primary);"></i>Modifier la réunion
        </h1>
        <p class="mb-0 small" style="color: var(--text-secondary);">Modifiez les informations de cette réunion</p>
    </div>
    <a href="<?php echo site_url('reunions'); ?>" class="btn btn-sm mt-2 mt-md-0"
       style="color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: var(--radius);">
        <i class="fas fa-arrow-left me-1"></i> Retour aux réunions
    </a>
</div>

<!-- Edit Form -->
<div class="edit-reunion-card">
    <div class="card-body p-4">
        <?php echo form_open('reunions/update/' . (int)$reunion->id); ?>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="mb-3">
                    <label for="edit_title" class="form-label fw-medium small" style="color: var(--text-primary);">
                        Titre <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="edit_title" name="title" required
                           value="<?php echo htmlspecialchars($reunion->title); ?>"
                           style="border-color: var(--border-color); border-radius: var(--radius);">
                </div>

                <div class="mb-3">
                    <label for="edit_description" class="form-label fw-medium small" style="color: var(--text-primary);">Description</label>
                    <textarea class="form-control" id="edit_description" name="description" rows="5"
                              style="border-color: var(--border-color); border-radius: var(--radius);"><?php echo htmlspecialchars($reunion->description); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="edit_scheduled_at" class="form-label fw-medium small" style="color: var(--text-primary);">
                        Date et heure <span class="text-danger">*</span>
                    </label>
                    <input type="datetime-local" class="form-control" id="edit_scheduled_at" name="scheduled_at" required
                           value="<?php echo htmlspecialchars($scheduled_value); ?>"
                           style="border-color: var(--border-color); border-radius: var(--radius);">
                </div>
            </div>

            <div class="col-lg-5">
                <label class="form-label fw-medium small" style="color: var(--text-primary);">
                    <i class="fas fa-users me-1"></i> Participants
                </label>
                <div class="edit-participant-list border rounded p-2" style="border-color: var(--border-color) !important; border-radius: var(--radius) !important;">
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u): ?>
                        <div class="form-check d-flex align-items-center gap-2">
                            <input class="form-check-input" type="checkbox" name="participants[]"
                                   value="<?php echo (int)$u->users_id; ?>"
                                   id="edit_participant_<?php echo (int)$u->users_id; ?>"
                                   <?php echo in_array((int)$u->users_id, $participant_ids) ? 'checked' : ''; ?>>
                            <label class="form-check-label w-100 d-flex align-items-center gap-2" for="edit_participant_<?php echo (int)$u->users_id; ?>">
                                <div class="d-flex justify-content-center align-items-center rounded-circle fw-semibold"
                                     style="width: 30px; height: 30px; min-width: 30px; background: var(--primary-light); color: var(--primary); font-size: 11px;">
                                    <?php echo htmlspecialchars(mb_strtoupper(mb_substr($u->users_prenom, 0, 1) . mb_substr($u->users_nom, 0, 1))); ?>
                                </div>
                                <div>
                                    <div class="small fw-medium" style="color: var(--text-primary);"><?php echo htmlspecialchars($u->users_prenom . ' ' . $u->users_nom); ?></div>
                                    <div class="small" style="color: var(--text-muted);"><?php echo htmlspecialchars($u->users_role); ?></div>
                                </div>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="small text-center mb-0" style="color: var(--text-muted);">Aucun utilisateur disponible</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <hr style="border-color: var(--border-color);">

        <div class="d-flex justify-content-end gap-2">
            <a href="<?php echo site_url('reunions'); ?>" class="btn btn-sm"
               style="color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: var(--radius);">
                Annuler
            </a>
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-check me-1"></i> Enregistrer les modifications
            </button>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>
