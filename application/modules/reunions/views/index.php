<?php
$months_fr = array('', 'janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre');
$days_fr   = array('dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi');

function format_reunion_date($datetime, $months_fr, $days_fr) {
    $ts = strtotime($datetime);
    return array(
        'day_name'  => ucfirst($days_fr[date('w', $ts)]),
        'day'       => date('d', $ts),
        'month'     => $months_fr[date('n', $ts)],
        'month_short' => mb_strtoupper(mb_substr($months_fr[date('n', $ts)], 0, 3)),
        'year'      => date('Y', $ts),
        'time'      => date('H:i', $ts),
        'full'      => ucfirst($days_fr[date('w', $ts)]) . ' ' . date('d', $ts) . ' ' . $months_fr[date('n', $ts)] . ' ' . date('Y', $ts)
    );
}
?>

<style>
/* ===== Reunions Scoped Styles ===== */
.reunion-filter-pills { display: flex; gap: 8px; flex-wrap: wrap; }
.reunion-filter-pills a {
    padding: 6px 18px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    background: var(--bg-white);
}
.reunion-filter-pills a:hover {
    background: var(--primary-light);
    color: var(--primary);
    border-color: var(--primary);
}
.reunion-filter-pills a.active {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

.reunion-featured {
    background: linear-gradient(135deg, var(--primary) 0%, #4285f4 100%);
    border-radius: var(--radius-lg);
    color: #fff;
    padding: 1.75rem 2rem;
    position: relative;
    overflow: hidden;
}
.reunion-featured::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
}
.reunion-featured .date-badge {
    background: rgba(255,255,255,0.2);
    border-radius: var(--radius);
    padding: 10px 16px;
    text-align: center;
    min-width: 70px;
}
.reunion-featured .date-badge .day { font-size: 1.75rem; font-weight: 700; line-height: 1; }
.reunion-featured .date-badge .month { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

.reunion-card {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    transition: var(--transition);
    height: 100%;
    display: flex;
    flex-direction: column;
}
.reunion-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}
.reunion-card .card-body { flex: 1; display: flex; flex-direction: column; }
.reunion-card .card-footer-area { margin-top: auto; padding-top: 1rem; }

.reunion-card .date-badge-sm {
    background: var(--primary-light);
    color: var(--primary);
    border-radius: var(--radius);
    padding: 6px 12px;
    text-align: center;
    min-width: 52px;
    font-weight: 600;
}
.reunion-card .date-badge-sm .day { font-size: 1.1rem; font-weight: 700; line-height: 1.2; }
.reunion-card .date-badge-sm .month { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.3px; }

.participant-avatars { display: flex; align-items: center; }
.participant-avatars .avatar {
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--primary-light); color: var(--primary);
    font-size: 10px; font-weight: 600;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid var(--bg-white);
    margin-left: -6px;
}
.participant-avatars .avatar:first-child { margin-left: 0; }
.participant-avatars .avatar-more {
    background: var(--bg-main); color: var(--text-secondary);
}

.modal-participant-list { max-height: 220px; overflow-y: auto; }
.modal-participant-list .form-check { padding: 8px 12px; border-radius: var(--radius); transition: var(--transition); }
.modal-participant-list .form-check:hover { background: var(--bg-main); }

.empty-state-icon {
    width: 80px; height: 80px; border-radius: 50%;
    background: var(--primary-light);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem;
}

.status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
.status-past { background: var(--text-muted); }
.status-upcoming { background: #34a853; }
.status-today { background: #ea4335; }
</style>

<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-semibold mb-1" style="color: var(--text-primary);">
            <i class="fas fa-calendar-alt me-2" style="color: var(--primary);"></i>Réunions
        </h1>
        <p class="mb-0 small" style="color: var(--text-secondary);">Planifiez et gérez vos réunions d'équipe</p>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createReunionModal">
            <i class="fas fa-plus me-1"></i> Planifier une réunion
        </button>
    </div>
</div>

<!-- Filter Pills -->
<div class="reunion-filter-pills mb-4">
    <a href="<?php echo site_url('reunions?filter=all'); ?>" class="<?php echo ($filter === 'all') ? 'active' : ''; ?>">
        <i class="fas fa-list me-1"></i> Toutes
    </a>
    <a href="<?php echo site_url('reunions?filter=today'); ?>" class="<?php echo ($filter === 'today') ? 'active' : ''; ?>">
        <i class="fas fa-calendar-day me-1"></i> Aujourd'hui
    </a>
    <a href="<?php echo site_url('reunions?filter=upcoming'); ?>" class="<?php echo ($filter === 'upcoming') ? 'active' : ''; ?>">
        <i class="fas fa-clock me-1"></i> À venir
    </a>
    <a href="<?php echo site_url('reunions?filter=past'); ?>" class="<?php echo ($filter === 'past') ? 'active' : ''; ?>">
        <i class="fas fa-history me-1"></i> Passées
    </a>
</div>

<!-- Next Reunion Featured Card -->
<?php if (!empty($next_reunion)): ?>
<?php $nd = format_reunion_date($next_reunion->scheduled_at, $months_fr, $days_fr); ?>
<div class="reunion-featured mb-4">
    <div class="d-flex flex-wrap align-items-center gap-4">
        <div class="date-badge">
            <div class="day"><?php echo $nd['day']; ?></div>
            <div class="month"><?php echo $nd['month_short']; ?></div>
        </div>
        <div class="flex-grow-1">
            <span class="badge bg-white bg-opacity-25 mb-2 fw-normal"><i class="fas fa-bolt me-1"></i>Prochaine Réunion</span>
            <h4 class="fw-semibold mb-1"><?php echo htmlspecialchars($next_reunion->title); ?></h4>
            <p class="mb-0 opacity-75">
                <i class="far fa-clock me-1"></i> <?php echo $nd['time']; ?> &mdash; <?php echo $nd['full']; ?>
                <?php if (!empty($next_reunion->description)): ?>
                    &nbsp;|&nbsp; <?php echo htmlspecialchars(mb_strimwidth($next_reunion->description, 0, 80, '...')); ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <?php if (!empty($next_reunion->participants)): ?>
                <span class="badge bg-white bg-opacity-25">
                    <i class="fas fa-users me-1"></i> <?php echo count($next_reunion->participants); ?> participant<?php echo count($next_reunion->participants) > 1 ? 's' : ''; ?>
                </span>
            <?php endif; ?>
            <a href="<?php echo site_url('reunions/view/' . (int)$next_reunion->id); ?>" class="btn btn-light btn-sm fw-medium">
                <i class="fas fa-eye me-1"></i> Voir détails
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Reunions Grid -->
<?php if (!empty($reunions)): ?>
<div class="row g-3">
    <?php foreach ($reunions as $r): ?>
    <?php
        $rd = format_reunion_date($r->scheduled_at, $months_fr, $days_fr);
        $is_past = strtotime($r->scheduled_at) < time();
        $is_today = date('Y-m-d', strtotime($r->scheduled_at)) === date('Y-m-d');
    ?>
    <div class="col-md-6 col-lg-4">
        <div class="reunion-card">
            <div class="card-body p-3">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="date-badge-sm">
                        <div class="day"><?php echo $rd['day']; ?></div>
                        <div class="month"><?php echo $rd['month_short']; ?></div>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <?php if ($is_today): ?>
                                <span class="status-dot status-today"></span>
                                <span class="small fw-medium" style="color: #ea4335;">Aujourd'hui</span>
                            <?php elseif ($is_past): ?>
                                <span class="status-dot status-past"></span>
                                <span class="small" style="color: var(--text-muted);">Terminée</span>
                            <?php else: ?>
                                <span class="status-dot status-upcoming"></span>
                                <span class="small fw-medium" style="color: #34a853;">À venir</span>
                            <?php endif; ?>
                            <span class="small" style="color: var(--text-muted);"><i class="far fa-clock me-1"></i><?php echo $rd['time']; ?></span>
                        </div>
                        <h6 class="fw-semibold mb-1" style="color: var(--text-primary);"><?php echo htmlspecialchars($r->title); ?></h6>
                        <?php if (!empty($r->description)): ?>
                            <p class="small mb-0" style="color: var(--text-secondary);"><?php echo htmlspecialchars(mb_strimwidth($r->description, 0, 100, '...')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-footer-area d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <?php if (!empty($r->participants)): ?>
                            <div class="participant-avatars">
                                <?php $shown = 0; foreach ($r->participants as $p): if ($shown >= 3) break; $shown++; ?>
                                    <div class="avatar" title="<?php echo htmlspecialchars($p->users_prenom . ' ' . $p->users_nom); ?>">
                                        <?php echo htmlspecialchars(mb_strtoupper(mb_substr($p->users_prenom, 0, 1) . mb_substr($p->users_nom, 0, 1))); ?>
                                    </div>
                                <?php endforeach; ?>
                                <?php if (count($r->participants) > 3): ?>
                                    <div class="avatar avatar-more">+<?php echo count($r->participants) - 3; ?></div>
                                <?php endif; ?>
                            </div>
                            <span class="small" style="color: var(--text-muted);"><?php echo count($r->participants); ?></span>
                        <?php else: ?>
                            <span class="small" style="color: var(--text-muted);"><i class="fas fa-user-slash me-1"></i>Aucun participant</span>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex gap-1">
                        <a href="<?php echo site_url('reunions/view/' . (int)$r->id); ?>" class="btn btn-sm btn-outline-primary" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="<?php echo site_url('reunions/update/' . (int)$r->id); ?>" class="btn btn-sm btn-outline-secondary" title="Modifier">
                            <i class="fas fa-pen"></i>
                        </a>
                        <a href="<?php echo site_url('reunions/delete/' . (int)$r->id); ?>" class="btn btn-sm btn-outline-danger" title="Supprimer"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réunion ?');">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php else: ?>
<!-- Empty State -->
<div class="card" style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
    <div class="card-body text-center py-5">
        <div class="empty-state-icon">
            <i class="fas fa-calendar-xmark fa-2x" style="color: var(--primary);"></i>
        </div>
        <h5 class="fw-semibold" style="color: var(--text-primary);">Aucune réunion</h5>
        <p class="mb-3" style="color: var(--text-secondary);">
            <?php if ($filter === 'today'): ?>
                Aucune réunion prévue pour aujourd'hui.
            <?php elseif ($filter === 'upcoming'): ?>
                Aucune réunion à venir pour le moment.
            <?php elseif ($filter === 'past'): ?>
                Aucune réunion passée enregistrée.
            <?php else: ?>
                Commencez par planifier votre première réunion.
            <?php endif; ?>
        </p>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createReunionModal">
            <i class="fas fa-plus me-1"></i> Planifier une réunion
        </button>
    </div>
</div>
<?php endif; ?>

<!-- Create Reunion Modal -->
<div class="modal fade" id="createReunionModal" tabindex="-1" aria-labelledby="createReunionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border: none; border-radius: var(--radius-lg); box-shadow: var(--shadow-md);">
            <?php echo form_open('reunions/create', array('id' => 'createReunionForm')); ?>
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color); padding: 1.25rem 1.5rem;">
                <h5 class="modal-title fw-semibold" id="createReunionModalLabel" style="color: var(--text-primary);">
                    <i class="fas fa-calendar-plus me-2" style="color: var(--primary);"></i>Planifier une réunion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body" style="padding: 1.5rem;">
                <div class="mb-3">
                    <label for="title" class="form-label fw-medium small" style="color: var(--text-primary);">
                        Titre <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="title" name="title" required
                           placeholder="Ex: Réunion d'équipe hebdomadaire"
                           style="border-color: var(--border-color); border-radius: var(--radius);">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label fw-medium small" style="color: var(--text-primary);">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"
                              placeholder="Décrivez l'objet de la réunion..."
                              style="border-color: var(--border-color); border-radius: var(--radius);"></textarea>
                </div>
                <div class="mb-3">
                    <label for="scheduled_at" class="form-label fw-medium small" style="color: var(--text-primary);">
                        Date et heure <span class="text-danger">*</span>
                    </label>
                    <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at" required
                           style="border-color: var(--border-color); border-radius: var(--radius);">
                </div>
                <div class="mb-0">
                    <label class="form-label fw-medium small" style="color: var(--text-primary);">
                        <i class="fas fa-users me-1"></i> Participants
                    </label>
                    <div class="modal-participant-list border rounded p-2" style="border-color: var(--border-color) !important; border-radius: var(--radius) !important;">
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $u): ?>
                            <div class="form-check d-flex align-items-center gap-2">
                                <input class="form-check-input" type="checkbox" name="participants[]"
                                       value="<?php echo (int)$u->users_id; ?>"
                                       id="participant_<?php echo (int)$u->users_id; ?>">
                                <label class="form-check-label w-100 d-flex align-items-center gap-2" for="participant_<?php echo (int)$u->users_id; ?>">
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
            <div class="modal-footer" style="border-top: 1px solid var(--border-color); padding: 1rem 1.5rem;">
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal"
                        style="color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: var(--radius);">
                    Annuler
                </button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-check me-1"></i> Créer la réunion
                </button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
