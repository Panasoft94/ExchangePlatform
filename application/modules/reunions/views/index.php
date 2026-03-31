<?php
// ─── Helper functions ─────────────────────────────────
function format_reunion_date($date_str) {
    $months = array('','janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre');
    $days = array('dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi');
    $ts = strtotime($date_str);
    return ucfirst($days[date('w',$ts)]) . ' ' . date('d',$ts) . ' ' . $months[date('n',$ts)] . ' ' . date('Y',$ts);
}
function reunion_status_class($s) {
    $m = array('planned'=>'primary','in_progress'=>'warning','completed'=>'success','cancelled'=>'danger');
    return isset($m[$s]) ? $m[$s] : 'secondary';
}
function reunion_status_label($s) {
    $m = array('planned'=>'Planifiée','in_progress'=>'En cours','completed'=>'Terminée','cancelled'=>'Annulée');
    return isset($m[$s]) ? $m[$s] : ucfirst($s);
}
function reunion_priority_class($p) {
    $m = array('low'=>'info','normal'=>'secondary','high'=>'warning','urgent'=>'danger');
    return isset($m[$p]) ? $m[$p] : 'secondary';
}
function reunion_priority_label($p) {
    $m = array('low'=>'Basse','normal'=>'Normale','high'=>'Haute','urgent'=>'Urgente');
    return isset($m[$p]) ? $m[$p] : ucfirst($p);
}
function format_duration($min) {
    if (!$min) return '';
    if ($min < 60) return $min . ' min';
    $h = floor($min/60); $r = $min % 60;
    return $h . 'h' . ($r ? sprintf('%02d', $r) : '');
}
function reunion_card_avatar_url($user) {
    if (empty($user->photo_profil)) return '';
    $f = basename($user->photo_profil);
    if (!file_exists(FCPATH . 'assets/img/avatar/' . $f)) return '';
    return base_url('assets/img/avatar/' . rawurlencode($f));
}
function reunion_card_initials($user) {
    $p = isset($user->users_prenom) ? $user->users_prenom : '';
    $n = isset($user->users_nom) ? $user->users_nom : '';
    return mb_strtoupper(mb_substr($p,0,1).mb_substr($n,0,1));
}

$session_user = $this->session->userdata('users');
$current_filter = isset($filter) ? $filter : 'all';
$current_search = isset($search) ? $search : '';
?>

<style>
/* ── Reunions Index Styles ────────────────────── */
.reunion-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
.reunion-stat-card { background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1rem; transition: var(--transition); }
.reunion-stat-card:hover { box-shadow: var(--shadow-sm); transform: translateY(-2px); }
.reunion-stat-icon { width: 48px; height: 48px; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; font-size: 20px; }
.reunion-stat-value { font-size: 1.75rem; font-weight: 700; line-height: 1; color: var(--text-primary); }
.reunion-stat-label { font-size: 0.8rem; color: var(--text-muted); margin-top: 2px; }

.reunion-toolbar { display: flex; flex-wrap: wrap; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; }
.reunion-search-box { position: relative; flex: 1; min-width: 220px; max-width: 400px; }
.reunion-search-box input { padding-left: 2.5rem; background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius); color: var(--text-primary); height: 40px; }
.reunion-search-box input:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(26,115,232,.15); }
.reunion-search-box .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 14px; }

.reunion-filter-pills { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.5rem; }
.reunion-filter-pill { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 999px; font-size: 0.8rem; font-weight: 500; text-decoration: none; border: 1px solid var(--border-color); color: var(--text-secondary); background: var(--bg-white); transition: var(--transition); cursor: pointer; }
.reunion-filter-pill:hover { border-color: var(--primary); color: var(--primary); }
.reunion-filter-pill.active { background: var(--primary); color: #fff; border-color: var(--primary); }
.reunion-filter-pill .pill-count { background: rgba(0,0,0,.1); padding: 1px 7px; border-radius: 999px; font-size: 0.7rem; }
.reunion-filter-pill.active .pill-count { background: rgba(255,255,255,.25); }

.reunion-next-card { background: linear-gradient(135deg, var(--primary) 0%, #4285f4 100%); border-radius: var(--radius-lg); color: #fff; padding: 1.5rem 2rem; margin-bottom: 1.5rem; position: relative; overflow: hidden; }
.reunion-next-card::before { content: ''; position: absolute; top: -30%; right: -8%; width: 200px; height: 200px; background: rgba(255,255,255,.07); border-radius: 50%; }
.reunion-next-card .next-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.8; margin-bottom: 0.5rem; }
.reunion-next-card h4 { font-weight: 600; margin-bottom: 0.5rem; }
.reunion-next-date { font-size: 0.85rem; opacity: 0.9; }

.reunion-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 1rem; }
.reunion-card { background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.25rem; transition: var(--transition); display: flex; flex-direction: column; }
.reunion-card:hover { box-shadow: var(--shadow-sm); transform: translateY(-2px); }
.reunion-card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.75rem; }
.reunion-card-title { font-size: 1rem; font-weight: 600; color: var(--text-primary); margin: 0; line-height: 1.4; }
.reunion-card-title a { color: inherit; text-decoration: none; }
.reunion-card-title a:hover { color: var(--primary); }
.reunion-card-desc { font-size: 0.8rem; color: var(--text-secondary); line-height: 1.6; margin-bottom: 0.75rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.reunion-card-meta { display: flex; flex-wrap: wrap; gap: 0.75rem; font-size: 0.78rem; color: var(--text-muted); margin-bottom: 0.75rem; }
.reunion-card-meta i { margin-right: 4px; }
.reunion-card-footer { display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 0.75rem; border-top: 1px solid var(--border-color); }
.reunion-card-avatars { display: flex; }
.reunion-card-avatars .r-avatar { width: 30px; height: 30px; border-radius: 50%; border: 2px solid var(--bg-white); margin-left: -8px; background: var(--primary-light); color: var(--primary); font-size: 10px; font-weight: 600; display: flex; align-items: center; justify-content: center; overflow: hidden; }
.reunion-card-avatars .r-avatar:first-child { margin-left: 0; }
.reunion-card-avatars .r-avatar img { width: 100%; height: 100%; object-fit: cover; }
.reunion-card-avatars .r-avatar-more { background: var(--bg-main); color: var(--text-muted); font-size: 9px; }

.reunion-list-item { background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius); padding: 1rem 1.25rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 1rem; transition: var(--transition); }
.reunion-list-item:hover { box-shadow: var(--shadow-sm); }
.reunion-list-date-block { text-align: center; min-width: 50px; }
.reunion-list-date-block .day { font-size: 1.25rem; font-weight: 700; color: var(--primary); line-height: 1; }
.reunion-list-date-block .month { font-size: 0.65rem; text-transform: uppercase; color: var(--text-muted); }

.reunion-empty-state { text-align: center; padding: 3rem 1rem; }
.reunion-empty-state i { font-size: 3rem; color: var(--border-color); margin-bottom: 1rem; }
.reunion-empty-state h5 { color: var(--text-primary); font-weight: 600; }
.reunion-empty-state p { color: var(--text-muted); font-size: 0.85rem; }

.view-toggle-btn { width: 36px; height: 36px; border: 1px solid var(--border-color); border-radius: var(--radius); background: var(--bg-white); color: var(--text-muted); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: var(--transition); }
.view-toggle-btn:hover, .view-toggle-btn.active { border-color: var(--primary); color: var(--primary); background: var(--primary-light); }

.modal-participant-search { margin-bottom: 1rem; }
.modal-participant-search input { background: var(--bg-main); border: 1px solid var(--border-color); border-radius: var(--radius); padding: 8px 12px 8px 36px; width: 100%; font-size: 0.85rem; color: var(--text-primary); }
.modal-participant-search .s-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
.modal-participant-list { max-height: 300px; overflow-y: auto; }
.modal-participant-item { padding: 8px 10px; border-radius: var(--radius); transition: var(--transition); }
.modal-participant-item:hover { background: var(--bg-main); }
</style>

<!-- ─── Stats Dashboard ─────────────────────────── -->
<div class="reunion-stats-grid">
    <div class="reunion-stat-card">
        <div class="reunion-stat-icon" style="background: var(--primary-light); color: var(--primary);"><i class="fas fa-calendar-alt"></i></div>
        <div><div class="reunion-stat-value"><?php echo (int)$stats['total']; ?></div><div class="reunion-stat-label">Total réunions</div></div>
    </div>
    <div class="reunion-stat-card">
        <div class="reunion-stat-icon" style="background: #fff3e0; color: #f57c00;"><i class="fas fa-calendar-day"></i></div>
        <div><div class="reunion-stat-value"><?php echo (int)$stats['today']; ?></div><div class="reunion-stat-label">Aujourd'hui</div></div>
    </div>
    <div class="reunion-stat-card">
        <div class="reunion-stat-icon" style="background: #e8f5e9; color: #2e7d32;"><i class="fas fa-calendar-check"></i></div>
        <div><div class="reunion-stat-value"><?php echo (int)$stats['upcoming']; ?></div><div class="reunion-stat-label">À venir</div></div>
    </div>
    <div class="reunion-stat-card">
        <div class="reunion-stat-icon" style="background: #fce4ec; color: #c62828;"><i class="fas fa-check-double"></i></div>
        <div><div class="reunion-stat-value"><?php echo (int)$stats['completed']; ?></div><div class="reunion-stat-label">Terminées</div></div>
    </div>
</div>

<!-- ─── Toolbar: Search + Actions ────────────────── -->
<div class="reunion-toolbar">
    <form method="get" action="<?php echo site_url('reunions'); ?>" class="reunion-search-box">
        <i class="fas fa-search search-icon"></i>
        <input type="text" name="q" class="form-control" placeholder="Rechercher une réunion..." value="<?php echo htmlspecialchars($current_search); ?>">
    </form>
    <div class="d-flex gap-2 ms-auto">
        <div class="d-flex gap-1">
            <button class="view-toggle-btn" data-view="grid" title="Vue grille"><i class="fas fa-th-large"></i></button>
            <button class="view-toggle-btn" data-view="list" title="Vue liste"><i class="fas fa-list"></i></button>
        </div>
        <button class="btn btn-primary btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createReunionModal">
            <i class="fas fa-plus"></i> <span class="d-none d-sm-inline">Nouvelle réunion</span>
        </button>
    </div>
</div>

<!-- ─── Filter Pills ─────────────────────────────── -->
<div class="reunion-filter-pills">
    <a href="<?php echo site_url('reunions'); ?>" class="reunion-filter-pill <?php echo $current_filter==='all' && empty($current_search) ? 'active' : ''; ?>">
        <i class="fas fa-layer-group"></i> Toutes <span class="pill-count"><?php echo (int)$stats['total']; ?></span>
    </a>
    <a href="<?php echo site_url('reunions?filter=today'); ?>" class="reunion-filter-pill <?php echo $current_filter==='today' ? 'active' : ''; ?>">
        <i class="fas fa-calendar-day"></i> Aujourd'hui <span class="pill-count"><?php echo (int)$stats['today']; ?></span>
    </a>
    <a href="<?php echo site_url('reunions?filter=upcoming'); ?>" class="reunion-filter-pill <?php echo $current_filter==='upcoming' ? 'active' : ''; ?>">
        <i class="fas fa-clock"></i> À venir <span class="pill-count"><?php echo (int)$stats['upcoming']; ?></span>
    </a>
    <a href="<?php echo site_url('reunions?filter=past'); ?>" class="reunion-filter-pill <?php echo $current_filter==='past' ? 'active' : ''; ?>">
        <i class="fas fa-history"></i> Passées
    </a>
    <a href="<?php echo site_url('reunions?filter=mine'); ?>" class="reunion-filter-pill <?php echo $current_filter==='mine' ? 'active' : ''; ?>">
        <i class="fas fa-user"></i> Mes réunions
    </a>
    <a href="<?php echo site_url('reunions?filter=cancelled'); ?>" class="reunion-filter-pill <?php echo $current_filter==='cancelled' ? 'active' : ''; ?>">
        <i class="fas fa-ban"></i> Annulées <span class="pill-count"><?php echo (int)$stats['cancelled']; ?></span>
    </a>
</div>

<?php if (!empty($current_search)): ?>
<div class="mb-3 d-flex align-items-center gap-2">
    <span class="small" style="color: var(--text-secondary);">Résultats pour « <strong><?php echo htmlspecialchars($current_search); ?></strong> » — <?php echo count($reunions); ?> trouvé<?php echo count($reunions)>1?'s':''; ?></span>
    <a href="<?php echo site_url('reunions'); ?>" class="btn btn-sm" style="color: var(--text-muted); border: 1px solid var(--border-color); border-radius: var(--radius); font-size: 0.75rem;"><i class="fas fa-times me-1"></i>Effacer</a>
</div>
<?php endif; ?>

<!-- ─── Featured: Next Reunion ───────────────────── -->
<?php if (!empty($next_reunion) && $current_filter === 'all' && empty($current_search)): ?>
<div class="reunion-next-card">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
            <div class="next-label"><i class="fas fa-bolt me-1"></i> Prochaine réunion</div>
            <h4><?php echo htmlspecialchars($next_reunion->title); ?></h4>
            <div class="reunion-next-date">
                <i class="far fa-calendar me-1"></i> <?php echo format_reunion_date($next_reunion->scheduled_at); ?>
                &nbsp;—&nbsp; <i class="far fa-clock me-1"></i> <?php echo date('H:i', strtotime($next_reunion->scheduled_at)); ?>
                <?php if (!empty($next_reunion->location)): ?>
                    &nbsp;—&nbsp; <i class="fas fa-map-marker-alt me-1"></i> <?php echo htmlspecialchars($next_reunion->location); ?>
                <?php endif; ?>
                <?php if (!empty($next_reunion->duration)): ?>
                    &nbsp;—&nbsp; <i class="fas fa-hourglass-half me-1"></i> <?php echo format_duration($next_reunion->duration); ?>
                <?php endif; ?>
            </div>
            <?php if (!empty($next_reunion->participants)): ?>
            <div class="d-flex align-items-center gap-2 mt-2" style="font-size: 0.8rem; opacity: 0.85;">
                <i class="fas fa-users"></i> <?php echo count($next_reunion->participants); ?> participant<?php echo count($next_reunion->participants)>1?'s':''; ?>
            </div>
            <?php endif; ?>
        </div>
        <a href="<?php echo site_url('reunions/view/' . (int)$next_reunion->id); ?>" class="btn btn-sm" style="background: rgba(255,255,255,.18); color: #fff; border: 1px solid rgba(255,255,255,.3); border-radius: var(--radius);">
            <i class="fas fa-arrow-right me-1"></i> Voir détails
        </a>
    </div>
</div>
<?php endif; ?>

<!-- ─── Grid View ────────────────────────────────── -->
<?php if (!empty($reunions)): ?>
<div id="reunionGridView" class="reunion-grid">
    <?php foreach ($reunions as $r):
        $ts = strtotime($r->scheduled_at);
        $is_past = $ts < time();
        $is_today = date('Y-m-d', $ts) === date('Y-m-d');
        $status_str = isset($r->status) ? $r->status : ($is_past ? 'completed' : 'planned');
        $priority_str = isset($r->priority) ? $r->priority : 'normal';
    ?>
    <div class="reunion-card">
        <div class="reunion-card-header">
            <h6 class="reunion-card-title"><a href="<?php echo site_url('reunions/view/'.(int)$r->id); ?>"><?php echo htmlspecialchars($r->title); ?></a></h6>
            <div class="d-flex gap-1">
                <span class="badge bg-<?php echo reunion_status_class($status_str); ?>" style="font-size:0.68rem;"><?php echo reunion_status_label($status_str); ?></span>
                <?php if ($priority_str !== 'normal'): ?>
                <span class="badge bg-<?php echo reunion_priority_class($priority_str); ?>" style="font-size:0.68rem;"><?php echo reunion_priority_label($priority_str); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($r->description)): ?>
        <div class="reunion-card-desc"><?php echo htmlspecialchars($r->description); ?></div>
        <?php endif; ?>

        <div class="reunion-card-meta">
            <span><i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y', $ts); ?></span>
            <span><i class="far fa-clock"></i> <?php echo date('H:i', $ts); ?></span>
            <?php if (!empty($r->location)): ?>
            <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($r->location); ?></span>
            <?php endif; ?>
            <?php if (!empty($r->duration)): ?>
            <span><i class="fas fa-hourglass-half"></i> <?php echo format_duration($r->duration); ?></span>
            <?php endif; ?>
        </div>

        <div class="reunion-card-footer">
            <div class="reunion-card-avatars">
                <?php if (!empty($r->participants)):
                    $count = 0;
                    foreach ($r->participants as $rp):
                        if ($count >= 4) break; $count++;
                        $av = reunion_card_avatar_url($rp);
                ?>
                <div class="r-avatar" title="<?php echo htmlspecialchars(trim($rp->users_prenom.' '.$rp->users_nom)); ?>">
                    <?php if ($av): ?><img src="<?php echo $av; ?>" alt=""><?php else: echo htmlspecialchars(reunion_card_initials($rp)); endif; ?>
                </div>
                <?php endforeach;
                    if (count($r->participants) > 4): ?>
                    <div class="r-avatar r-avatar-more">+<?php echo count($r->participants)-4; ?></div>
                <?php endif; endif; ?>
            </div>
            <a href="<?php echo site_url('reunions/view/'.(int)$r->id); ?>" class="btn btn-sm" style="font-size:0.75rem; color: var(--primary); border: 1px solid var(--primary); border-radius: var(--radius); padding: 4px 12px;">
                Voir <i class="fas fa-arrow-right ms-1" style="font-size:0.65rem;"></i>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- ─── List View ────────────────────────────────── -->
<div id="reunionListView" style="display:none;">
    <?php foreach ($reunions as $r):
        $ts = strtotime($r->scheduled_at);
        $months_short = array('','jan','fév','mar','avr','mai','jun','jul','aoû','sep','oct','nov','déc');
        $status_str = isset($r->status) ? $r->status : 'planned';
        $priority_str = isset($r->priority) ? $r->priority : 'normal';
    ?>
    <div class="reunion-list-item">
        <div class="reunion-list-date-block">
            <div class="day"><?php echo date('d', $ts); ?></div>
            <div class="month"><?php echo $months_short[date('n', $ts)]; ?></div>
        </div>
        <div class="flex-grow-1">
            <a href="<?php echo site_url('reunions/view/'.(int)$r->id); ?>" class="fw-semibold small" style="color: var(--text-primary); text-decoration:none;"><?php echo htmlspecialchars($r->title); ?></a>
            <div class="d-flex flex-wrap gap-2 mt-1" style="font-size:0.75rem; color: var(--text-muted);">
                <span><i class="far fa-clock me-1"></i><?php echo date('H:i', $ts); ?></span>
                <?php if (!empty($r->location)): ?><span><i class="fas fa-map-marker-alt me-1"></i><?php echo htmlspecialchars($r->location); ?></span><?php endif; ?>
                <?php if (!empty($r->participants)): ?><span><i class="fas fa-users me-1"></i><?php echo count($r->participants); ?></span><?php endif; ?>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-<?php echo reunion_status_class($status_str); ?>" style="font-size:0.68rem;"><?php echo reunion_status_label($status_str); ?></span>
            <?php if ($priority_str !== 'normal'): ?>
            <span class="badge bg-<?php echo reunion_priority_class($priority_str); ?>" style="font-size:0.68rem;"><?php echo reunion_priority_label($priority_str); ?></span>
            <?php endif; ?>
            <a href="<?php echo site_url('reunions/view/'.(int)$r->id); ?>" class="btn btn-sm" style="font-size:0.7rem; color: var(--primary); border: 1px solid var(--primary); border-radius: var(--radius); padding: 3px 10px;">
                <i class="fas fa-eye"></i>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php else: ?>
<div class="reunion-empty-state">
    <i class="fas fa-calendar-xmark d-block"></i>
    <h5>Aucune réunion trouvée</h5>
    <p><?php echo !empty($current_search) ? 'Aucun résultat pour votre recherche.' : 'Commencez par créer votre première réunion.'; ?></p>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createReunionModal">
        <i class="fas fa-plus me-1"></i> Créer une réunion
    </button>
</div>
<?php endif; ?>

<!-- ─── Create Reunion Modal ─────────────────────── -->
<div class="modal fade" id="createReunionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border:none; border-radius: var(--radius-lg); box-shadow: var(--shadow-md);">
            <?php echo form_open('reunions/create'); ?>
            <div class="modal-header" style="border-bottom:1px solid var(--border-color); padding:1.25rem 1.5rem;">
                <h5 class="modal-title fw-semibold" style="color: var(--text-primary);"><i class="fas fa-calendar-plus me-2" style="color: var(--primary);"></i>Nouvelle réunion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1.5rem;">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-medium small">Titre <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required placeholder="Ex: Réunion de suivi projet" style="border-color: var(--border-color); border-radius: var(--radius);">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium small">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Ordre du jour, objectifs..." style="border-color: var(--border-color); border-radius: var(--radius);"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium small">Date et heure <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="scheduled_at" class="form-control" required style="border-color: var(--border-color); border-radius: var(--radius);">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium small">Lieu</label>
                        <input type="text" name="location" class="form-control" placeholder="Salle, lien visio..." style="border-color: var(--border-color); border-radius: var(--radius);">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium small">Durée</label>
                        <select name="duration" class="form-select" style="border-color: var(--border-color); border-radius: var(--radius);">
                            <option value="">Non définie</option>
                            <option value="15">15 minutes</option>
                            <option value="30">30 minutes</option>
                            <option value="45">45 minutes</option>
                            <option value="60" selected>1 heure</option>
                            <option value="90">1h30</option>
                            <option value="120">2 heures</option>
                            <option value="180">3 heures</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium small">Priorité</label>
                        <select name="priority" class="form-select" style="border-color: var(--border-color); border-radius: var(--radius);">
                            <option value="low">Basse</option>
                            <option value="normal" selected>Normale</option>
                            <option value="high">Haute</option>
                            <option value="urgent">Urgente</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-medium small"><i class="fas fa-users me-1"></i> Participants</label>
                        <div class="modal-participant-search position-relative">
                            <i class="fas fa-search s-icon"></i>
                            <input type="text" id="createParticipantSearch" placeholder="Filtrer les utilisateurs...">
                        </div>
                        <div class="modal-participant-list border rounded p-2" style="border-color: var(--border-color) !important; border-radius: var(--radius) !important;">
                            <?php if (!empty($users)): foreach ($users as $u): ?>
                            <div class="form-check modal-participant-item d-flex align-items-center gap-2" data-name="<?php echo htmlspecialchars(strtolower($u->users_prenom.' '.$u->users_nom)); ?>">
                                <input class="form-check-input" type="checkbox" name="participants[]" value="<?php echo (int)$u->users_id; ?>" id="create_p_<?php echo (int)$u->users_id; ?>">
                                <label class="form-check-label w-100 d-flex align-items-center gap-2" for="create_p_<?php echo (int)$u->users_id; ?>">
                                    <div class="d-flex justify-content-center align-items-center rounded-circle fw-semibold" style="width:30px;height:30px;min-width:30px;background:var(--primary-light);color:var(--primary);font-size:11px;">
                                        <?php echo htmlspecialchars(mb_strtoupper(mb_substr($u->users_prenom,0,1).mb_substr($u->users_nom,0,1))); ?>
                                    </div>
                                    <div>
                                        <div class="small fw-medium" style="color: var(--text-primary);"><?php echo htmlspecialchars($u->users_prenom.' '.$u->users_nom); ?></div>
                                        <div class="small" style="color: var(--text-muted);"><?php echo htmlspecialchars(!empty($u->users_role)?$u->users_role:$u->users_email); ?></div>
                                    </div>
                                </label>
                            </div>
                            <?php endforeach; endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid var(--border-color); padding:1rem 1.5rem;">
                <button type="button" class="btn btn-sm" data-bs-dismiss="modal" style="color: var(--text-secondary); border:1px solid var(--border-color); border-radius: var(--radius);">Annuler</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-check me-1"></i> Créer la réunion</button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
(function() {
    // View toggle
    var savedView = localStorage.getItem('reunion_view') || 'grid';
    var gridEl = document.getElementById('reunionGridView');
    var listEl = document.getElementById('reunionListView');
    var toggleBtns = document.querySelectorAll('.view-toggle-btn');

    function setView(v) {
        if (gridEl) gridEl.style.display = v === 'grid' ? '' : 'none';
        if (listEl) listEl.style.display = v === 'list' ? '' : 'none';
        toggleBtns.forEach(function(b) { b.classList.toggle('active', b.getAttribute('data-view') === v); });
        localStorage.setItem('reunion_view', v);
    }
    setView(savedView);
    toggleBtns.forEach(function(b) { b.addEventListener('click', function() { setView(this.getAttribute('data-view')); }); });

    // Participant search filter in create modal
    var searchInput = document.getElementById('createParticipantSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            var val = this.value.toLowerCase();
            document.querySelectorAll('.modal-participant-item').forEach(function(item) {
                var name = item.getAttribute('data-name') || '';
                item.style.display = name.indexOf(val) !== -1 ? '' : 'none';
            });
        });
    }
})();
</script>
