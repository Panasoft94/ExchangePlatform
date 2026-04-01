<?php
// ─── Helper functions ─────────────────────────────────
$months_fr = array('', 'janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre');
$days_fr   = array('dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi');
$ts = strtotime($reunion->scheduled_at);
$date_full = ucfirst($days_fr[date('w', $ts)]) . ' ' . date('d', $ts) . ' ' . $months_fr[date('n', $ts)] . ' ' . date('Y', $ts);
$time_str  = date('H:i', $ts);
$is_past   = $ts < time();
$is_today  = date('Y-m-d', $ts) === date('Y-m-d');
$creator_name = trim((isset($reunion->users_prenom) ? $reunion->users_prenom : '') . ' ' . (isset($reunion->users_nom) ? $reunion->users_nom : ''));
$status_str = isset($reunion->status) ? $reunion->status : ($is_past ? 'completed' : 'planned');
$priority_str = isset($reunion->priority) ? $reunion->priority : 'normal';
$session_user = $this->session->userdata('users');

function reunion_detail_avatar_url($user) {
    if (empty($user->photo_profil)) return '';
    $f = basename($user->photo_profil);
    if (!file_exists(FCPATH . 'assets/img/avatar/' . $f)) return '';
    return base_url('assets/img/avatar/' . rawurlencode($f));
}
function reunion_detail_initials($user) {
    $p = isset($user->users_prenom) ? $user->users_prenom : '';
    $n = isset($user->users_nom) ? $user->users_nom : '';
    return mb_strtoupper(mb_substr($p,0,1).mb_substr($n,0,1));
}
function view_status_class($s) {
    $m = array('planned'=>'primary','in_progress'=>'warning','completed'=>'success','cancelled'=>'danger');
    return isset($m[$s]) ? $m[$s] : 'secondary';
}
function view_status_label($s) {
    $m = array('planned'=>'Planifiée','in_progress'=>'En cours','completed'=>'Terminée','cancelled'=>'Annulée');
    return isset($m[$s]) ? $m[$s] : ucfirst($s);
}
function view_priority_label($p) {
    $m = array('low'=>'Basse','normal'=>'Normale','high'=>'Haute','urgent'=>'Urgente');
    return isset($m[$p]) ? $m[$p] : ucfirst($p);
}
function view_priority_class($p) {
    $m = array('low'=>'info','normal'=>'secondary','high'=>'warning','urgent'=>'danger');
    return isset($m[$p]) ? $m[$p] : 'secondary';
}
function view_format_duration($min) {
    if (!$min) return '';
    if ($min < 60) return $min . ' min';
    $h = floor($min/60); $r = $min % 60;
    return $h . 'h' . ($r ? sprintf('%02d',$r) : '');
}
?>

<style>
/* ── Detail Page Styles ────────────────────────── */
.reunion-detail-card { background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg); overflow: hidden; }
.reunion-detail-header { background: linear-gradient(135deg, var(--primary) 0%, #4285f4 100%); color: #fff; padding: 2rem; position: relative; }
.reunion-detail-header::before { content: ''; position: absolute; top: -40%; right: -10%; width: 250px; height: 250px; background: rgba(255,255,255,0.06); border-radius: 50%; pointer-events: none; z-index: 0; }
.reunion-detail-header > * { position: relative; z-index: 1; }

.participant-row { display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: var(--radius); transition: var(--transition); }
.participant-row:hover { background: var(--bg-main); }
.participant-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--primary-light); color: var(--primary); font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; min-width: 40px; overflow: hidden; }
.participant-avatar img { width: 100%; height: 100%; object-fit: cover; }

.rsvp-badge { font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px; }
.rsvp-badge.invited { background: #e8f0fe; color: #1a73e8; }
.rsvp-badge.accepted { background: #e6f4ea; color: #137333; }
.rsvp-badge.declined { background: #fce8e6; color: #c5221f; }

.detail-meta-item { display: flex; align-items: center; gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--border-color); }
.detail-meta-item:last-child { border-bottom: none; }
.detail-meta-icon { width: 36px; height: 36px; border-radius: var(--radius); background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 14px; min-width: 36px; }

.reunion-action-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; }

.participant-stack { display: flex; align-items: center; margin-top: 14px; }
.participant-stack-item { width: 38px; height: 38px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.85); margin-left: -10px; overflow: hidden; background: rgba(255,255,255,0.25); display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; }
.participant-stack-item:first-child { margin-left: 0; }
.participant-stack-item img { width: 100%; height: 100%; object-fit: cover; }
.participant-stack-more { background: rgba(255,255,255,0.16); }

/* Tabs */
.reunion-tabs { border-bottom: 2px solid var(--border-color); display: flex; gap: 0; margin-bottom: 0; }
.reunion-tab { padding: 12px 20px; font-size: 0.85rem; font-weight: 500; color: var(--text-muted); text-decoration: none; border-bottom: 2px solid transparent; margin-bottom: -2px; cursor: pointer; transition: var(--transition); display: flex; align-items: center; gap: 6px; }
.reunion-tab:hover { color: var(--primary); }
.reunion-tab.active { color: var(--primary); border-bottom-color: var(--primary); }
.reunion-tab .tab-count { background: var(--bg-main); padding: 1px 8px; border-radius: 999px; font-size: 0.7rem; }
.reunion-tab.active .tab-count { background: var(--primary-light); color: var(--primary); }
.tab-pane { display: none; padding: 1.5rem 0; }
.tab-pane.active { display: block; }

/* Agenda */
.agenda-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px 14px; border: 1px solid var(--border-color); border-radius: var(--radius); margin-bottom: 8px; transition: var(--transition); background: var(--bg-white); }
.agenda-item:hover { box-shadow: var(--shadow-sm); }
.agenda-item.completed { opacity: 0.6; }
.agenda-item.completed .agenda-title { text-decoration: line-through; }
.agenda-check { width: 22px; height: 22px; border: 2px solid var(--border-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: var(--transition); flex-shrink: 0; margin-top: 2px; }
.agenda-check:hover { border-color: var(--primary); }
.agenda-check.done { background: var(--primary); border-color: var(--primary); color: #fff; }
.agenda-title { font-weight: 500; font-size: 0.9rem; color: var(--text-primary); }
.agenda-desc { font-size: 0.8rem; color: var(--text-muted); margin-top: 4px; }

/* Notes */
.note-card { background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius); padding: 1rem; margin-bottom: 0.75rem; }
.note-card:hover { box-shadow: var(--shadow-sm); }
.note-author { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
.note-content { color: var(--text-secondary); font-size: 0.85rem; line-height: 1.7; white-space: pre-wrap; }

/* RSVP Bar */
.rsvp-bar { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1rem; }
.rsvp-stat { display: flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 999px; font-size: 0.8rem; font-weight: 500; }

.reunion-participant-list { max-height: 260px; overflow-y: auto; }
.reunion-participant-option { padding: 8px 10px; border-radius: var(--radius); transition: var(--transition); }
.reunion-participant-option:hover { background: var(--bg-main); }

/* Status dropdown */
.status-dropdown-item { display: flex; align-items: center; gap: 8px; padding: 8px 16px; font-size: 0.85rem; }
.status-dot { width: 8px; height: 8px; border-radius: 50%; }
</style>

<!-- Back Button -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <a href="<?php echo site_url('reunions'); ?>" class="btn btn-sm" style="color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: var(--radius);">
        <i class="fas fa-arrow-left me-1"></i> Retour aux réunions
    </a>
    <?php if (!empty($my_rsvp) && $my_rsvp === 'invited'): ?>
    <div class="d-flex gap-2">
        <?php echo form_open('reunions/rsvp/' . (int)$reunion->id, 'class="d-inline"'); ?>
            <input type="hidden" name="rsvp" value="accepted">
            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check me-1"></i> Accepter l'invitation</button>
        <?php echo form_close(); ?>
        <?php echo form_open('reunions/rsvp/' . (int)$reunion->id, 'class="d-inline"'); ?>
            <input type="hidden" name="rsvp" value="declined">
            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-times me-1"></i> Décliner</button>
        <?php echo form_close(); ?>
    </div>
    <?php elseif (!empty($my_rsvp) && $my_rsvp === 'accepted'): ?>
    <span class="rsvp-badge accepted"><i class="fas fa-check-circle me-1"></i> Vous avez accepté</span>
    <?php elseif (!empty($my_rsvp) && $my_rsvp === 'declined'): ?>
    <span class="rsvp-badge declined"><i class="fas fa-times-circle me-1"></i> Vous avez décliné</span>
    <?php endif; ?>
</div>

<!-- Detail Card -->
<div class="reunion-detail-card">
    <!-- Header -->
    <div class="reunion-detail-header">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-<?php echo view_status_class($status_str); ?>"><?php echo view_status_label($status_str); ?></span>
                    <?php if ($priority_str !== 'normal'): ?>
                    <span class="badge bg-<?php echo view_priority_class($priority_str); ?>"><?php echo view_priority_label($priority_str); ?></span>
                    <?php endif; ?>
                    <?php if ($is_today): ?>
                        <span class="badge bg-white bg-opacity-25"><i class="fas fa-circle text-danger me-1" style="font-size:7px;vertical-align:middle;"></i>Aujourd'hui</span>
                    <?php endif; ?>
                </div>
                <h3 class="fw-semibold mb-2"><?php echo htmlspecialchars($reunion->title); ?></h3>
                <p class="mb-0 opacity-75">
                    <i class="far fa-calendar me-1"></i> <?php echo htmlspecialchars($date_full); ?>
                    &nbsp;&mdash;&nbsp; <i class="far fa-clock me-1"></i> <?php echo htmlspecialchars($time_str); ?>
                    <?php if (!empty($reunion->location)): ?>
                        &nbsp;&mdash;&nbsp; <i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($reunion->location); ?>
                    <?php endif; ?>
                    <?php if (!empty($reunion->duration)): ?>
                        &nbsp;&mdash;&nbsp; <i class="fas fa-hourglass-half me-1"></i> <?php echo view_format_duration($reunion->duration); ?>
                    <?php endif; ?>
                </p>
                <?php if (!empty($participants)): ?>
                <div class="participant-stack">
                    <?php $sc = 0; foreach ($participants as $sp): if ($sc >= 4) break; $sc++;
                        $sa = reunion_detail_avatar_url($sp); ?>
                    <div class="participant-stack-item" title="<?php echo htmlspecialchars(trim($sp->users_prenom . ' ' . $sp->users_nom)); ?>">
                        <?php if ($sa !== ''): ?><img src="<?php echo $sa; ?>" alt=""><?php else: echo htmlspecialchars(reunion_detail_initials($sp)); endif; ?>
                    </div>
                    <?php endforeach; if (count($participants) > 4): ?>
                    <div class="participant-stack-item participant-stack-more">+<?php echo count($participants)-4; ?></div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <?php if (!empty($is_reunion_host)): ?>
                <!-- Status Management Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-sm dropdown-toggle" data-bs-toggle="dropdown" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3);">
                        <i class="fas fa-flag me-1"></i> Statut
                    </button>
                    <ul class="dropdown-menu" style="border-radius: var(--radius); border: 1px solid var(--border-color);">
                        <?php foreach (array('planned'=>'Planifiée','in_progress'=>'En cours','completed'=>'Terminée','cancelled'=>'Annulée') as $sk => $sl): ?>
                        <li>
                            <?php echo form_open('reunions/update_status/' . (int)$reunion->id); ?>
                                <input type="hidden" name="status" value="<?php echo $sk; ?>">
                                <button type="submit" class="dropdown-item status-dropdown-item <?php echo $status_str === $sk ? 'fw-bold' : ''; ?>">
                                    <span class="status-dot bg-<?php echo view_status_class($sk); ?>"></span> <?php echo $sl; ?>
                                    <?php if ($status_str === $sk): ?><i class="fas fa-check ms-auto" style="font-size:11px;"></i><?php endif; ?>
                                </button>
                            <?php echo form_close(); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
                <?php if (!empty($can_manage_visio_settings)): ?>
                <a href="<?php echo $visio_configuration_url; ?>" class="btn btn-sm" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-sliders me-1"></i> Config RTC
                </a>
                <?php endif; ?>
                <?php if (!empty($can_join_visio)): ?>
                <button type="button" class="btn btn-sm" data-copy-text="Copier le lien" data-copied-text="Lien copié" data-copy-url="<?php echo htmlspecialchars($visio_url, ENT_QUOTES, 'UTF-8'); ?>" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3);">
                    <i class="fas fa-link me-1"></i> Copier le lien
                </button>
                <a href="<?php echo $visio_url; ?>" class="btn btn-success btn-sm reunion-action-btn">
                    <i class="fas fa-video"></i> <?php echo !empty($is_reunion_host) ? 'Démarrer' : 'Rejoindre'; ?>
                </a>
                <?php endif; ?>
                <?php if(can_access_or_owner('manage_reunions', $reunion->created_by)): ?>
                <a href="<?php echo site_url('reunions/update/' . (int)$reunion->id); ?>" class="btn btn-light btn-sm"><i class="fas fa-pen me-1"></i> Modifier</a>
                <a href="<?php echo site_url('reunions/delete/' . (int)$reunion->id); ?>" class="btn btn-sm" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3);" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réunion ?');">
                    <i class="fas fa-trash-alt me-1"></i> Supprimer
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="px-3 pt-2" style="background: var(--bg-white);">
        <div class="reunion-tabs">
            <a class="reunion-tab active" data-tab="details"><i class="fas fa-info-circle"></i> Détails</a>
            <a class="reunion-tab" data-tab="agenda"><i class="fas fa-list-ol"></i> Ordre du jour <span class="tab-count"><?php echo isset($agenda) ? count($agenda) : 0; ?></span></a>
            <a class="reunion-tab" data-tab="notes"><i class="fas fa-sticky-note"></i> Notes / PV <span class="tab-count"><?php echo isset($notes) ? count($notes) : 0; ?></span></a>
            <a class="reunion-tab" data-tab="participants"><i class="fas fa-users"></i> Participants <span class="tab-count"><?php echo count($participants); ?></span></a>
        </div>
    </div>

    <!-- Tab Contents -->
    <div style="padding: 0 2rem 2rem;">

        <!-- ─── Tab: Details ──────────────────────────── -->
        <div class="tab-pane active" id="tab-details">
            <div class="row g-4">
                <div class="col-lg-7">
                    <?php if (!empty($reunion->description)): ?>
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-2" style="color: var(--text-primary);"><i class="fas fa-align-left me-2" style="color: var(--primary);"></i>Description</h6>
                        <p class="mb-0" style="color: var(--text-secondary); line-height: 1.7;"><?php echo nl2br(htmlspecialchars($reunion->description)); ?></p>
                    </div>
                    <?php endif; ?>

                    <h6 class="fw-semibold mb-3" style="color: var(--text-primary);"><i class="fas fa-info-circle me-2" style="color: var(--primary);"></i>Informations</h6>
                    <div class="detail-meta-item">
                        <div class="detail-meta-icon"><i class="far fa-calendar-alt"></i></div>
                        <div><div class="small fw-medium" style="color: var(--text-primary);">Date</div><div class="small" style="color: var(--text-secondary);"><?php echo htmlspecialchars($date_full); ?></div></div>
                    </div>
                    <div class="detail-meta-item">
                        <div class="detail-meta-icon"><i class="far fa-clock"></i></div>
                        <div><div class="small fw-medium" style="color: var(--text-primary);">Heure</div><div class="small" style="color: var(--text-secondary);"><?php echo htmlspecialchars($time_str); ?></div></div>
                    </div>
                    <?php if (!empty($reunion->location)): ?>
                    <div class="detail-meta-item">
                        <div class="detail-meta-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div><div class="small fw-medium" style="color: var(--text-primary);">Lieu</div><div class="small" style="color: var(--text-secondary);"><?php echo htmlspecialchars($reunion->location); ?></div></div>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($reunion->duration)): ?>
                    <div class="detail-meta-item">
                        <div class="detail-meta-icon"><i class="fas fa-hourglass-half"></i></div>
                        <div><div class="small fw-medium" style="color: var(--text-primary);">Durée</div><div class="small" style="color: var(--text-secondary);"><?php echo view_format_duration($reunion->duration); ?></div></div>
                    </div>
                    <?php endif; ?>
                    <div class="detail-meta-item">
                        <div class="detail-meta-icon"><i class="fas fa-user"></i></div>
                        <div><div class="small fw-medium" style="color: var(--text-primary);">Créée par</div><div class="small" style="color: var(--text-secondary);"><?php echo htmlspecialchars($creator_name); ?></div></div>
                    </div>
                    <?php if (!empty($reunion->created_at)): ?>
                    <div class="detail-meta-item">
                        <div class="detail-meta-icon"><i class="fas fa-history"></i></div>
                        <div><div class="small fw-medium" style="color: var(--text-primary);">Date de création</div><div class="small" style="color: var(--text-secondary);"><?php echo date('d/m/Y à H:i', strtotime($reunion->created_at)); ?></div></div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- RSVP Summary -->
                <div class="col-lg-5">
                    <div class="card" style="background: var(--bg-main); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3" style="color: var(--text-primary);"><i class="fas fa-chart-pie me-2" style="color: var(--primary);"></i>Réponses RSVP</h6>
                            <div class="rsvp-bar">
                                <span class="rsvp-stat" style="background: #e6f4ea; color: #137333;"><i class="fas fa-check-circle"></i> <?php echo (int)$rsvp_accepted; ?> accepté<?php echo $rsvp_accepted > 1 ? 's' : ''; ?></span>
                                <span class="rsvp-stat" style="background: #fce8e6; color: #c5221f;"><i class="fas fa-times-circle"></i> <?php echo (int)$rsvp_declined; ?> décliné<?php echo $rsvp_declined > 1 ? 's' : ''; ?></span>
                                <span class="rsvp-stat" style="background: #e8f0fe; color: #1a73e8;"><i class="fas fa-clock"></i> <?php echo (int)$rsvp_pending; ?> en attente</span>
                            </div>
                            <?php $total_rsvp = $rsvp_accepted + $rsvp_declined + $rsvp_pending; if ($total_rsvp > 0): ?>
                            <div class="progress" style="height: 8px; border-radius: 4px; background: var(--border-color);">
                                <div class="progress-bar bg-success" style="width: <?php echo round($rsvp_accepted/$total_rsvp*100); ?>%"></div>
                                <div class="progress-bar bg-danger" style="width: <?php echo round($rsvp_declined/$total_rsvp*100); ?>%"></div>
                                <div class="progress-bar bg-primary" style="width: <?php echo round($rsvp_pending/$total_rsvp*100); ?>%"></div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ─── Tab: Agenda ───────────────────────────── -->
        <div class="tab-pane" id="tab-agenda">
            <?php if (!empty($is_reunion_host)): ?>
            <div class="mb-4 p-3" style="background: var(--bg-main); border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
                <h6 class="fw-semibold mb-3 small"><i class="fas fa-plus-circle me-1" style="color: var(--primary);"></i> Ajouter un point à l'ordre du jour</h6>
                <?php echo form_open('reunions/add_agenda/' . (int)$reunion->id); ?>
                <div class="row g-2">
                    <div class="col-md-5">
                        <input type="text" name="agenda_title" class="form-control form-control-sm" placeholder="Titre du point *" required style="border-color: var(--border-color); border-radius: var(--radius);">
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="agenda_description" class="form-control form-control-sm" placeholder="Description (optionnel)" style="border-color: var(--border-color); border-radius: var(--radius);">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-plus me-1"></i> Ajouter</button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($agenda)): ?>
                <?php foreach ($agenda as $idx => $ag): ?>
                <div class="agenda-item <?php echo $ag->completed ? 'completed' : ''; ?>">
                    <a href="<?php echo site_url('reunions/toggle_agenda/' . (int)$reunion->id . '/' . (int)$ag->id); ?>" class="agenda-check <?php echo $ag->completed ? 'done' : ''; ?>" title="<?php echo $ag->completed ? 'Marquer comme non terminé' : 'Marquer comme terminé'; ?>">
                        <?php if ($ag->completed): ?><i class="fas fa-check" style="font-size:11px;"></i><?php endif; ?>
                    </a>
                    <div class="flex-grow-1">
                        <div class="agenda-title"><?php echo htmlspecialchars($ag->title); ?></div>
                        <?php if (!empty($ag->description)): ?>
                        <div class="agenda-desc"><?php echo htmlspecialchars($ag->description); ?></div>
                        <?php endif; ?>
                    </div>
                    <span class="badge rounded-pill" style="background: var(--bg-main); color: var(--text-muted); font-size: 0.7rem;">#<?php echo $idx + 1; ?></span>
                    <?php if (!empty($is_reunion_host)): ?>
                    <a href="<?php echo site_url('reunions/delete_agenda/' . (int)$reunion->id . '/' . (int)$ag->id); ?>" class="text-danger" style="font-size: 0.8rem;" onclick="return confirm('Supprimer ce point ?');"><i class="fas fa-trash-alt"></i></a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <?php
                    $done = 0; foreach ($agenda as $a) { if ($a->completed) $done++; }
                    $total_ag = count($agenda);
                ?>
                <div class="mt-3 small" style="color: var(--text-muted);">
                    <i class="fas fa-chart-pie me-1"></i> <?php echo $done; ?>/<?php echo $total_ag; ?> terminé<?php echo $done > 1 ? 's' : ''; ?>
                    <div class="progress mt-1" style="height: 5px; border-radius: 3px;">
                        <div class="progress-bar bg-success" style="width: <?php echo $total_ag > 0 ? round($done/$total_ag*100) : 0; ?>%"></div>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-list-ol fa-2x mb-2" style="color: var(--border-color);"></i>
                    <p class="small mb-0" style="color: var(--text-muted);">Aucun point à l'ordre du jour.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- ─── Tab: Notes / PV ───────────────────────── -->
        <div class="tab-pane" id="tab-notes">
            <div class="mb-4 p-3" style="background: var(--bg-main); border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
                <h6 class="fw-semibold mb-3 small"><i class="fas fa-pen me-1" style="color: var(--primary);"></i> Ajouter une note</h6>
                <?php echo form_open('reunions/add_note/' . (int)$reunion->id); ?>
                <textarea name="note_content" class="form-control form-control-sm mb-2" rows="3" placeholder="Saisissez votre note ou procès-verbal..." required style="border-color: var(--border-color); border-radius: var(--radius);"></textarea>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-paper-plane me-1"></i> Publier la note</button>
                <?php echo form_close(); ?>
            </div>

            <?php if (!empty($notes)): ?>
                <?php foreach ($notes as $note): ?>
                <div class="note-card">
                    <div class="note-author">
                        <?php $na = reunion_detail_avatar_url($note); ?>
                        <div class="participant-avatar" style="width:34px;height:34px;min-width:34px;">
                            <?php if ($na): ?><img src="<?php echo $na; ?>" alt=""><?php else: echo htmlspecialchars(mb_strtoupper(mb_substr(isset($note->users_prenom)?$note->users_prenom:'',0,1).mb_substr(isset($note->users_nom)?$note->users_nom:'',0,1))); endif; ?>
                        </div>
                        <div class="flex-grow-1">
                            <span class="small fw-medium" style="color: var(--text-primary);"><?php echo htmlspecialchars(trim((isset($note->users_prenom)?$note->users_prenom:'').' '.(isset($note->users_nom)?$note->users_nom:''))); ?></span>
                            <span class="small" style="color: var(--text-muted);"> — <?php echo date('d/m/Y H:i', strtotime($note->created_at)); ?></span>
                        </div>
                        <?php if ($session_user && (int)$note->user_id === (int)$session_user->users_id): ?>
                        <a href="<?php echo site_url('reunions/delete_note/' . (int)$reunion->id . '/' . (int)$note->id); ?>" class="text-danger" style="font-size:0.8rem;" onclick="return confirm('Supprimer cette note ?');"><i class="fas fa-trash-alt"></i></a>
                        <?php endif; ?>
                    </div>
                    <div class="note-content"><?php echo nl2br(htmlspecialchars($note->content)); ?></div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-sticky-note fa-2x mb-2" style="color: var(--border-color);"></i>
                    <p class="small mb-0" style="color: var(--text-muted);">Aucune note pour cette réunion.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- ─── Tab: Participants ─────────────────────── -->
        <div class="tab-pane" id="tab-participants">
            <?php if (!empty($participants)): ?>
                <?php foreach ($participants as $p): ?>
                <?php $avatar_url = reunion_detail_avatar_url($p); ?>
                <div class="participant-row">
                    <div class="participant-avatar">
                        <?php if ($avatar_url !== ''): ?><img src="<?php echo $avatar_url; ?>" alt=""><?php else: echo htmlspecialchars(reunion_detail_initials($p)); endif; ?>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small fw-medium" style="color: var(--text-primary);"><?php echo htmlspecialchars($p->users_prenom . ' ' . $p->users_nom); ?></div>
                        <div class="small" style="color: var(--text-muted);"><?php echo htmlspecialchars($p->users_email); ?></div>
                    </div>
                    <span class="rsvp-badge <?php echo htmlspecialchars($p->status); ?>">
                        <?php
                        switch ($p->status) {
                            case 'accepted': echo '<i class="fas fa-check me-1"></i>Accepté'; break;
                            case 'declined': echo '<i class="fas fa-times me-1"></i>Décliné'; break;
                            default: echo '<i class="fas fa-envelope me-1"></i>Invité'; break;
                        }
                        ?>
                    </span>
                    <?php if (!empty($is_reunion_host) && (int)$p->users_id !== (int)$reunion->created_by): ?>
                    <a href="<?php echo site_url('reunions/remove_participant/' . (int)$reunion->id . '/' . (int)$p->users_id); ?>" class="btn btn-sm text-danger" style="font-size:0.75rem;" onclick="return confirm('Retirer ce participant ?');" title="Retirer"><i class="fas fa-user-minus"></i></a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-3">
                    <i class="fas fa-user-slash fa-2x mb-2" style="color: var(--border-color);"></i>
                    <p class="small mb-0" style="color: var(--text-muted);">Aucun participant pour cette réunion.</p>
                </div>
            <?php endif; ?>

            <?php if (!empty($can_manage_participants)): ?>
            <div class="mt-3 pt-3" style="border-top: 1px solid var(--border-color);">
                <button type="button" class="btn btn-primary btn-sm reunion-action-btn" data-bs-toggle="modal" data-bs-target="#addParticipantsModal">
                    <i class="fas fa-user-plus"></i> Ajouter des membres
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Participants Modal -->
<?php if (!empty($can_manage_participants)): ?>
<div class="modal fade" id="addParticipantsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border: none; border-radius: var(--radius-lg); box-shadow: var(--shadow-md);">
            <?php echo form_open('reunions/add_participants/' . (int) $reunion->id); ?>
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color); padding: 1.25rem 1.5rem;">
                <h5 class="modal-title fw-semibold" style="color: var(--text-primary);"><i class="fas fa-user-plus me-2" style="color: var(--primary);"></i>Ajouter des membres</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 1.5rem;">
                <?php if (!empty($available_users)): ?>
                    <div class="position-relative mb-3">
                        <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:13px;"></i>
                        <input type="text" id="addParticipantSearch" class="form-control form-control-sm" placeholder="Filtrer les utilisateurs..." style="padding-left:36px;border-color:var(--border-color);border-radius:var(--radius);">
                    </div>
                    <div class="reunion-participant-list border rounded p-2" style="border-color: var(--border-color) !important; border-radius: var(--radius) !important;">
                        <?php foreach ($available_users as $user): ?>
                        <?php $oa = reunion_detail_avatar_url($user); ?>
                        <div class="form-check reunion-participant-option d-flex align-items-center gap-2" data-name="<?php echo htmlspecialchars(strtolower($user->users_prenom.' '.$user->users_nom)); ?>">
                            <input class="form-check-input" type="checkbox" name="participants[]" value="<?php echo (int) $user->users_id; ?>" id="add_p_<?php echo (int) $user->users_id; ?>">
                            <label class="form-check-label w-100 d-flex align-items-center gap-2" for="add_p_<?php echo (int) $user->users_id; ?>">
                                <div class="participant-avatar" style="width:34px;height:34px;min-width:34px;">
                                    <?php if ($oa !== ''): ?><img src="<?php echo $oa; ?>" alt=""><?php else: echo htmlspecialchars(reunion_detail_initials($user)); endif; ?>
                                </div>
                                <div>
                                    <div class="small fw-medium" style="color: var(--text-primary);"><?php echo htmlspecialchars(trim($user->users_prenom . ' ' . $user->users_nom)); ?></div>
                                    <div class="small" style="color: var(--text-muted);"><?php echo htmlspecialchars(!empty($user->users_role) ? $user->users_role : $user->users_email); ?></div>
                                </div>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-2x mb-2" style="color: var(--border-color);"></i>
                        <p class="mb-0 small" style="color: var(--text-muted);">Tous les utilisateurs participent déjà.</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border-color); padding: 1rem 1.5rem;">
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: var(--radius);">Annuler</button>
                <button type="submit" class="btn btn-primary btn-sm" <?php echo empty($available_users) ? 'disabled' : ''; ?>><i class="fas fa-check me-1"></i> Ajouter</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
(function() {
    // Tab switching
    var tabs = document.querySelectorAll('.reunion-tab');
    var panes = document.querySelectorAll('.tab-pane');
    tabs.forEach(function(tab) {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            var target = this.getAttribute('data-tab');
            tabs.forEach(function(t) { t.classList.remove('active'); });
            panes.forEach(function(p) { p.classList.remove('active'); });
            this.classList.add('active');
            var pane = document.getElementById('tab-' + target);
            if (pane) pane.classList.add('active');
            // Update URL hash
            history.replaceState(null, null, '#' + target);
        });
    });

    // Activate tab from URL hash
    var hash = window.location.hash.replace('#', '');
    if (hash) {
        var tabEl = document.querySelector('.reunion-tab[data-tab="' + hash + '"]');
        if (tabEl) tabEl.click();
    }

    // Copy link
    document.querySelectorAll('[data-copy-url]').forEach(function(button) {
        button.addEventListener('click', function() {
            var text = this.getAttribute('data-copy-url');
            var defaultL = this.getAttribute('data-copy-text') || 'Copier';
            var copiedL = this.getAttribute('data-copied-text') || 'Copié';
            var btn = this;
            var p = navigator.clipboard ? navigator.clipboard.writeText(text) : new Promise(function(r) { var i=document.createElement('input');i.value=text;document.body.appendChild(i);i.select();document.execCommand('copy');document.body.removeChild(i);r(); });
            p.then(function() {
                btn.innerHTML = '<i class="fas fa-check me-1"></i> ' + copiedL;
                setTimeout(function() { btn.innerHTML = '<i class="fas fa-link me-1"></i> ' + defaultL; }, 2200);
            });
        });
    });

    // Participant search in modal
    var searchInp = document.getElementById('addParticipantSearch');
    if (searchInp) {
        searchInp.addEventListener('input', function() {
            var v = this.value.toLowerCase();
            document.querySelectorAll('#addParticipantsModal .reunion-participant-option').forEach(function(item) {
                item.style.display = (item.getAttribute('data-name') || '').indexOf(v) !== -1 ? '' : 'none';
            });
        });
    }
})();
</script>
