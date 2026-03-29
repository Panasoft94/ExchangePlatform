<?php
$months_fr = array('', 'janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre');
$days_fr   = array('dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi');
$ts = strtotime($reunion->scheduled_at);
$date_full = ucfirst($days_fr[date('w', $ts)]) . ' ' . date('d', $ts) . ' ' . $months_fr[date('n', $ts)] . ' ' . date('Y', $ts);
$time_str  = date('H:i', $ts);
$is_past   = $ts < time();
$is_today  = date('Y-m-d', $ts) === date('Y-m-d');
?>

<style>
.reunion-detail-card {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.reunion-detail-header {
    background: linear-gradient(135deg, var(--primary) 0%, #4285f4 100%);
    color: #fff;
    padding: 2rem;
    position: relative;
}
.reunion-detail-header::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -10%;
    width: 250px;
    height: 250px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
}
.reunion-detail-body { padding: 2rem; }

.participant-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-radius: var(--radius);
    transition: var(--transition);
}
.participant-row:hover { background: var(--bg-main); }
.participant-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: var(--primary-light); color: var(--primary);
    font-size: 13px; font-weight: 600;
    display: flex; align-items: center; justify-content: center;
    min-width: 40px;
}

.status-badge { font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px; }
.status-badge.invited { background: #e8f0fe; color: #1a73e8; }
.status-badge.accepted { background: #e6f4ea; color: #137333; }
.status-badge.declined { background: #fce8e6; color: #c5221f; }

.detail-meta-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-color);
}
.detail-meta-item:last-child { border-bottom: none; }
.detail-meta-icon {
    width: 36px; height: 36px; border-radius: var(--radius);
    background: var(--primary-light); color: var(--primary);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; min-width: 36px;
}
</style>

<!-- Back Button -->
<div class="mb-4">
    <a href="<?php echo site_url('reunions'); ?>" class="btn btn-sm" style="color: var(--text-secondary); border: 1px solid var(--border-color); border-radius: var(--radius);">
        <i class="fas fa-arrow-left me-1"></i> Retour aux réunions
    </a>
</div>

<!-- Detail Card -->
<div class="reunion-detail-card">
    <!-- Header -->
    <div class="reunion-detail-header">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <?php if ($is_today): ?>
                        <span class="badge bg-white bg-opacity-25"><i class="fas fa-circle text-danger me-1" style="font-size: 7px; vertical-align: middle;"></i>Aujourd'hui</span>
                    <?php elseif ($is_past): ?>
                        <span class="badge bg-white bg-opacity-25"><i class="fas fa-check me-1"></i>Terminée</span>
                    <?php else: ?>
                        <span class="badge bg-white bg-opacity-25"><i class="fas fa-clock me-1"></i>À venir</span>
                    <?php endif; ?>
                </div>
                <h3 class="fw-semibold mb-2"><?php echo htmlspecialchars($reunion->title); ?></h3>
                <p class="mb-0 opacity-75">
                    <i class="far fa-calendar me-1"></i> <?php echo htmlspecialchars($date_full); ?>
                    &nbsp;&mdash;&nbsp;
                    <i class="far fa-clock me-1"></i> <?php echo htmlspecialchars($time_str); ?>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo site_url('reunions/update/' . (int)$reunion->id); ?>" class="btn btn-light btn-sm">
                    <i class="fas fa-pen me-1"></i> Modifier
                </a>
                <a href="<?php echo site_url('reunions/delete/' . (int)$reunion->id); ?>" class="btn btn-sm"
                   style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.3);"
                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réunion ?');">
                    <i class="fas fa-trash-alt me-1"></i> Supprimer
                </a>
            </div>
        </div>
    </div>

    <!-- Body -->
    <div class="reunion-detail-body">
        <div class="row g-4">
            <!-- Left: Details -->
            <div class="col-lg-7">
                <?php if (!empty($reunion->description)): ?>
                <div class="mb-4">
                    <h6 class="fw-semibold mb-2" style="color: var(--text-primary);">
                        <i class="fas fa-align-left me-2" style="color: var(--primary);"></i>Description
                    </h6>
                    <p class="mb-0" style="color: var(--text-secondary); line-height: 1.7;"><?php echo nl2br(htmlspecialchars($reunion->description)); ?></p>
                </div>
                <?php endif; ?>

                <div class="mb-0">
                    <h6 class="fw-semibold mb-3" style="color: var(--text-primary);">
                        <i class="fas fa-info-circle me-2" style="color: var(--primary);"></i>Informations
                    </h6>
                    <div class="detail-meta-item">
                        <div class="detail-meta-icon"><i class="far fa-calendar-alt"></i></div>
                        <div>
                            <div class="small fw-medium" style="color: var(--text-primary);">Date</div>
                            <div class="small" style="color: var(--text-secondary);"><?php echo htmlspecialchars($date_full); ?></div>
                        </div>
                    </div>
                    <div class="detail-meta-item">
                        <div class="detail-meta-icon"><i class="far fa-clock"></i></div>
                        <div>
                            <div class="small fw-medium" style="color: var(--text-primary);">Heure</div>
                            <div class="small" style="color: var(--text-secondary);"><?php echo htmlspecialchars($time_str); ?></div>
                        </div>
                    </div>
                    <div class="detail-meta-item">
                        <div class="detail-meta-icon"><i class="fas fa-user"></i></div>
                        <div>
                            <div class="small fw-medium" style="color: var(--text-primary);">Créée par</div>
                            <div class="small" style="color: var(--text-secondary);">
                                <?php echo htmlspecialchars(($reunion->users_prenom ?? '') . ' ' . ($reunion->users_nom ?? '')); ?>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($reunion->created_at)): ?>
                    <div class="detail-meta-item">
                        <div class="detail-meta-icon"><i class="fas fa-history"></i></div>
                        <div>
                            <div class="small fw-medium" style="color: var(--text-primary);">Date de création</div>
                            <div class="small" style="color: var(--text-secondary);"><?php echo date('d/m/Y à H:i', strtotime($reunion->created_at)); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right: Participants -->
            <div class="col-lg-5">
                <div class="card" style="background: var(--bg-main); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
                    <div class="card-body">
                        <h6 class="fw-semibold mb-3" style="color: var(--text-primary);">
                            <i class="fas fa-users me-2" style="color: var(--primary);"></i>
                            Participants
                            <?php if (!empty($participants)): ?>
                                <span class="badge rounded-pill ms-1" style="background: var(--primary-light); color: var(--primary); font-size: 11px;">
                                    <?php echo count($participants); ?>
                                </span>
                            <?php endif; ?>
                        </h6>

                        <?php if (!empty($participants)): ?>
                            <?php foreach ($participants as $p): ?>
                            <div class="participant-row">
                                <div class="participant-avatar">
                                    <?php echo htmlspecialchars(mb_strtoupper(mb_substr($p->users_prenom, 0, 1) . mb_substr($p->users_nom, 0, 1))); ?>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small fw-medium" style="color: var(--text-primary);">
                                        <?php echo htmlspecialchars($p->users_prenom . ' ' . $p->users_nom); ?>
                                    </div>
                                    <div class="small" style="color: var(--text-muted);">
                                        <?php echo htmlspecialchars($p->users_email); ?>
                                    </div>
                                </div>
                                <span class="status-badge <?php echo htmlspecialchars($p->status); ?>">
                                    <?php
                                    switch ($p->status) {
                                        case 'accepted': echo '<i class="fas fa-check me-1"></i>Accepté'; break;
                                        case 'declined': echo '<i class="fas fa-times me-1"></i>Décliné'; break;
                                        default: echo '<i class="fas fa-envelope me-1"></i>Invité'; break;
                                    }
                                    ?>
                                </span>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-user-slash fa-2x mb-2" style="color: var(--border-color);"></i>
                                <p class="small mb-0" style="color: var(--text-muted);">Aucun participant pour cette réunion.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
