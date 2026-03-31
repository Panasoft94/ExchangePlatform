<?php
$participant_ids = array();
if (!empty($participants)) {
    foreach ($participants as $p) {
        $participant_ids[] = (int) $p->user_id;
    }
}
$scheduled_value = date('Y-m-d\TH:i', strtotime($reunion->scheduled_at));
$current_location = isset($reunion->location) ? $reunion->location : '';
$current_duration = isset($reunion->duration) ? (int)$reunion->duration : 0;
$current_priority = isset($reunion->priority) ? $reunion->priority : 'normal';

function edit_avatar_url($user) {
    if (empty($user->photo_profil)) return '';
    $f = basename($user->photo_profil);
    if (!file_exists(FCPATH . 'assets/img/avatar/' . $f)) return '';
    return base_url('assets/img/avatar/' . rawurlencode($f));
}
function edit_initials($user) {
    $p = isset($user->users_prenom) ? $user->users_prenom : '';
    $n = isset($user->users_nom) ? $user->users_nom : '';
    return mb_strtoupper(mb_substr($p,0,1).mb_substr($n,0,1));
}
?>

<style>
.edit-reunion-card { background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg); }
.edit-participant-list { max-height: 320px; overflow-y: auto; }
.edit-participant-list .form-check { padding: 8px 12px; border-radius: var(--radius); transition: var(--transition); }
.edit-participant-list .form-check:hover { background: var(--bg-main); }
.edit-participant-search { position: relative; margin-bottom: 0.75rem; }
.edit-participant-search input { padding-left: 36px; background: var(--bg-main); border: 1px solid var(--border-color); border-radius: var(--radius); font-size: 0.85rem; }
.edit-participant-search .s-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 13px; }
</style>

<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-semibold mb-1" style="color: var(--text-primary);">
            <i class="fas fa-pen-to-square me-2" style="color: var(--primary);"></i>Modifier la réunion
        </h1>
        <p class="mb-0 small" style="color: var(--text-secondary);">Modifiez les informations de cette réunion</p>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <a href="<?php echo site_url('reunions/view/' . (int)$reunion->id); ?>" class="btn btn-sm"
           style="color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: var(--radius);">
            <i class="fas fa-eye me-1"></i> Voir la réunion
        </a>
        <a href="<?php echo site_url('reunions'); ?>" class="btn btn-sm"
           style="color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: var(--radius);">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>
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
                    <textarea class="form-control" id="edit_description" name="description" rows="4"
                              style="border-color: var(--border-color); border-radius: var(--radius);"><?php echo htmlspecialchars($reunion->description); ?></textarea>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="edit_scheduled_at" class="form-label fw-medium small" style="color: var(--text-primary);">
                            Date et heure <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" class="form-control" id="edit_scheduled_at" name="scheduled_at" required
                               value="<?php echo htmlspecialchars($scheduled_value); ?>"
                               style="border-color: var(--border-color); border-radius: var(--radius);">
                    </div>
                    <div class="col-md-6">
                        <label for="edit_location" class="form-label fw-medium small" style="color: var(--text-primary);">
                            <i class="fas fa-map-marker-alt me-1" style="color: var(--primary);"></i> Lieu
                        </label>
                        <input type="text" class="form-control" id="edit_location" name="location"
                               value="<?php echo htmlspecialchars($current_location); ?>"
                               placeholder="Salle, lien visio..."
                               style="border-color: var(--border-color); border-radius: var(--radius);">
                    </div>
                    <div class="col-md-6">
                        <label for="edit_duration" class="form-label fw-medium small" style="color: var(--text-primary);">
                            <i class="fas fa-hourglass-half me-1" style="color: var(--primary);"></i> Durée
                        </label>
                        <select class="form-select" id="edit_duration" name="duration" style="border-color: var(--border-color); border-radius: var(--radius);">
                            <option value="" <?php echo $current_duration == 0 ? 'selected' : ''; ?>>Non définie</option>
                            <option value="15" <?php echo $current_duration == 15 ? 'selected' : ''; ?>>15 minutes</option>
                            <option value="30" <?php echo $current_duration == 30 ? 'selected' : ''; ?>>30 minutes</option>
                            <option value="45" <?php echo $current_duration == 45 ? 'selected' : ''; ?>>45 minutes</option>
                            <option value="60" <?php echo $current_duration == 60 ? 'selected' : ''; ?>>1 heure</option>
                            <option value="90" <?php echo $current_duration == 90 ? 'selected' : ''; ?>>1h30</option>
                            <option value="120" <?php echo $current_duration == 120 ? 'selected' : ''; ?>>2 heures</option>
                            <option value="180" <?php echo $current_duration == 180 ? 'selected' : ''; ?>>3 heures</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_priority" class="form-label fw-medium small" style="color: var(--text-primary);">
                            <i class="fas fa-flag me-1" style="color: var(--primary);"></i> Priorité
                        </label>
                        <select class="form-select" id="edit_priority" name="priority" style="border-color: var(--border-color); border-radius: var(--radius);">
                            <option value="low" <?php echo $current_priority === 'low' ? 'selected' : ''; ?>>Basse</option>
                            <option value="normal" <?php echo $current_priority === 'normal' ? 'selected' : ''; ?>>Normale</option>
                            <option value="high" <?php echo $current_priority === 'high' ? 'selected' : ''; ?>>Haute</option>
                            <option value="urgent" <?php echo $current_priority === 'urgent' ? 'selected' : ''; ?>>Urgente</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <label class="form-label fw-medium small" style="color: var(--text-primary);">
                    <i class="fas fa-users me-1"></i> Participants
                </label>
                <div class="edit-participant-search">
                    <i class="fas fa-search s-icon"></i>
                    <input type="text" id="editParticipantSearch" class="form-control form-control-sm" placeholder="Filtrer les utilisateurs...">
                </div>
                <div class="edit-participant-list border rounded p-2" style="border-color: var(--border-color) !important; border-radius: var(--radius) !important;">
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $u):
                            $ua = edit_avatar_url($u);
                        ?>
                        <div class="form-check d-flex align-items-center gap-2" data-name="<?php echo htmlspecialchars(strtolower($u->users_prenom.' '.$u->users_nom)); ?>">
                            <input class="form-check-input" type="checkbox" name="participants[]"
                                   value="<?php echo (int)$u->users_id; ?>"
                                   id="edit_participant_<?php echo (int)$u->users_id; ?>"
                                   <?php echo in_array((int)$u->users_id, $participant_ids) ? 'checked' : ''; ?>>
                            <label class="form-check-label w-100 d-flex align-items-center gap-2" for="edit_participant_<?php echo (int)$u->users_id; ?>">
                                <div class="d-flex justify-content-center align-items-center rounded-circle fw-semibold"
                                     style="width: 30px; height: 30px; min-width: 30px; background: var(--primary-light); color: var(--primary); font-size: 11px; overflow: hidden;">
                                    <?php if ($ua): ?><img src="<?php echo $ua; ?>" alt="" style="width:100%;height:100%;object-fit:cover;"><?php else: echo htmlspecialchars(edit_initials($u)); endif; ?>
                                </div>
                                <div>
                                    <div class="small fw-medium" style="color: var(--text-primary);"><?php echo htmlspecialchars($u->users_prenom . ' ' . $u->users_nom); ?></div>
                                    <div class="small" style="color: var(--text-muted);"><?php echo htmlspecialchars(!empty($u->users_role) ? $u->users_role : $u->users_email); ?></div>
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
            <a href="<?php echo site_url('reunions/view/' . (int)$reunion->id); ?>" class="btn btn-sm"
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

<script>
(function() {
    var searchInput = document.getElementById('editParticipantSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            var val = this.value.toLowerCase();
            document.querySelectorAll('.edit-participant-list .form-check').forEach(function(item) {
                var name = item.getAttribute('data-name') || '';
                item.style.display = name.indexOf(val) !== -1 ? '' : 'none';
            });
        });
    }
})();
</script>
