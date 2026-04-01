<?php
$session = $this->session->userdata('users');
$prenom  = isset($session->users_prenom) ? htmlspecialchars($session->users_prenom) : 'Utilisateur';
$nom     = isset($session->users_nom) ? htmlspecialchars($session->users_nom) : '';
$role    = isset($session->users_role) ? htmlspecialchars($session->users_role) : '';

setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'fra');
$months = ['janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
$days   = ['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi'];
$now    = time();
$hour   = (int) date('G', $now);
$date_str = ucfirst($days[date('w', $now)]) . ' ' . date('j', $now) . ' ' . $months[date('n', $now) - 1] . ' ' . date('Y', $now);

// Contextual greeting based on time of day
if ($hour >= 5 && $hour < 12) {
    $greeting = 'Bonjour';
    $greeting_icon = '☀️';
    $greeting_sub = 'Bonne matinée et excellent début de journée.';
} elseif ($hour >= 12 && $hour < 14) {
    $greeting = 'Bon appétit';
    $greeting_icon = '🍽️';
    $greeting_sub = 'Prenez une pause bien méritée avant de continuer.';
} elseif ($hour >= 14 && $hour < 18) {
    $greeting = 'Bon après-midi';
    $greeting_icon = '⚡';
    $greeting_sub = 'Restez productif, la journée avance bien.';
} elseif ($hour >= 18 && $hour < 21) {
    $greeting = 'Bonsoir';
    $greeting_icon = '🌆';
    $greeting_sub = 'Belle fin de journée à vous.';
} else {
    $greeting = 'Bonne nuit';
    $greeting_icon = '🌙';
    $greeting_sub = 'Travail tardif ? N\'oubliez pas de vous reposer.';
}

// Summary counters for welcome banner
$reunions_today = isset($nb_reunions_today) ? (int) $nb_reunions_today : 0;
$reunions_upcoming = isset($nb_reunions_upcoming) ? (int) $nb_reunions_upcoming : 0;
?>

<style>
/* ===== Dashboard Scoped Styles ===== */

/* Welcome Banner */
.dash-welcome-banner {
    background: linear-gradient(135deg, var(--primary), #4285f4 50%, #6ea8fe);
    border-radius: var(--radius-lg);
    padding: 1.75rem 2rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin-bottom: 2rem;
}
.dash-welcome-banner::before {
    content: '';
    position: absolute;
    top: -60%;
    right: -8%;
    width: 280px;
    height: 280px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
    pointer-events: none;
}
.dash-welcome-banner::after {
    content: '';
    position: absolute;
    bottom: -40%;
    right: 10%;
    width: 180px;
    height: 180px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
    pointer-events: none;
}
.dash-welcome-banner .wb-content { position: relative; z-index: 1; }
.dash-welcome-banner .wb-greeting {
    font-size: 1.65rem;
    font-weight: 700;
    margin: 0 0 4px;
    line-height: 1.3;
}
.dash-welcome-banner .wb-subtitle {
    font-size: 0.9rem;
    opacity: 0.85;
    margin: 0 0 12px;
    font-weight: 400;
}
.dash-welcome-banner .wb-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
}
.dash-welcome-banner .wb-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.78rem;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(4px);
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 500;
}
.dash-welcome-banner .wb-meta-item i { font-size: 0.72rem; opacity: 0.9; }
@media (max-width: 576px) {
    .dash-welcome-banner { padding: 1.25rem 1.25rem; }
    .dash-welcome-banner .wb-greeting { font-size: 1.3rem; }
    .dash-welcome-banner .wb-meta { gap: 0.5rem; }
}

/* Stat Cards */
.dash-stat-card {
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: var(--transition);
    text-decoration: none;
    color: inherit;
    height: 100%;
}
.dash-stat-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
    color: inherit;
    text-decoration: none;
}
.dash-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}
.dash-stat-icon.blue   { background: #e8f0fe; color: #1a73e8; }
.dash-stat-icon.green  { background: #e6f4ea; color: #1e8e3e; }
.dash-stat-icon.orange { background: #fef7e0; color: #e37400; }
.dash-stat-icon.purple { background: #f3e8fd; color: #8430ce; }
.dash-stat-info { flex: 1; min-width: 0; }
.dash-stat-count {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.2;
}
.dash-stat-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
    font-weight: 500;
}

/* Quick Actions */
.dash-action-card {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.5rem 1rem;
    text-align: center;
    transition: var(--transition);
    text-decoration: none;
    color: var(--text-primary);
    display: block;
    height: 100%;
}
.dash-action-card:hover {
    border-color: var(--primary);
    background: var(--primary-light);
    color: var(--primary);
    text-decoration: none;
}
.dash-action-card i {
    font-size: 1.5rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
    display: block;
}
.dash-action-card span {
    font-size: 0.85rem;
    font-weight: 600;
}

/* Dashboard Card */
.dash-card {
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.dash-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.dash-card-header h5 {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-primary);
}
.dash-card-body { padding: 0; }
.dash-card-footer {
    padding: 0.75rem 1.25rem;
    border-top: 1px solid var(--border-color);
    text-align: center;
}
.dash-card-footer a {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--primary);
    text-decoration: none;
}
.dash-card-footer a:hover { text-decoration: underline; }

/* Users Table */
.dash-table {
    width: 100%;
    margin: 0;
    font-size: 0.85rem;
}
.dash-table thead th {
    background: var(--bg-main);
    color: var(--text-secondary);
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    padding: 0.75rem 1.25rem;
    border: none;
    border-bottom: 1px solid var(--border-color);
}
.dash-table tbody td {
    padding: 0.75rem 1.25rem;
    vertical-align: middle;
    border: none;
    border-bottom: 1px solid #f1f3f4;
    color: var(--text-primary);
}
.dash-table tbody tr:last-child td { border-bottom: none; }
.dash-table tbody tr:hover { background: #f8f9fa; }

/* Avatar Initials */
.dash-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}

/* Role Badge */
.dash-role-badge {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 20px;
    display: inline-block;
}

/* Activity Feed */
.dash-activity-item {
    display: flex;
    gap: 0.75rem;
    padding: 0.85rem 1.25rem;
    border-bottom: 1px solid #f1f3f4;
}
.dash-activity-item:last-child { border-bottom: none; }
.dash-activity-dot {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    flex-shrink: 0;
}
.dash-activity-content { flex: 1; min-width: 0; }
.dash-activity-text {
    font-size: 0.8rem;
    color: var(--text-primary);
    line-height: 1.4;
}
.dash-activity-time {
    font-size: 0.7rem;
    color: var(--text-muted);
    margin-top: 2px;
}
</style>

<!-- ===== Welcome Banner ===== -->
<div class="dash-welcome-banner">
    <div class="wb-content">
        <div class="wb-greeting"><?php echo $greeting; ?>, <?php echo $prenom; ?> <span aria-hidden="true"><?php echo $greeting_icon; ?></span></div>
        <p class="wb-subtitle"><?php echo $greeting_sub; ?> Bienvenue sur votre espace de collaboration MFPRA.</p>
        <div class="wb-meta">
            <span class="wb-meta-item">
                <i class="fa-regular fa-calendar"></i> <?php echo $date_str; ?>
            </span>
            <?php if ($role): ?>
            <span class="wb-meta-item">
                <i class="fa-solid fa-shield-halved"></i> <?php echo $role; ?>
            </span>
            <?php endif; ?>
            <?php if ($reunions_today > 0): ?>
            <span class="wb-meta-item">
                <i class="fa-solid fa-video"></i> <?php echo $reunions_today; ?> réunion<?php echo $reunions_today > 1 ? 's' : ''; ?> aujourd'hui
            </span>
            <?php endif; ?>
            <?php if ($reunions_upcoming > 0): ?>
            <span class="wb-meta-item">
                <i class="fa-regular fa-calendar-check"></i> <?php echo $reunions_upcoming; ?> à venir
            </span>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ===== Statistics Cards ===== -->
<div class="row g-3 mb-4">
    <?php if (is_allowed('user')): ?>
    <div class="col-6 col-lg-3">
        <a href="<?php echo site_url('users'); ?>" class="dash-stat-card">
            <div class="dash-stat-icon blue"><i class="fa-solid fa-users"></i></div>
            <div class="dash-stat-info">
                <div class="dash-stat-count"><?php echo (int) $nb_users; ?></div>
                <div class="dash-stat-label">Utilisateurs</div>
            </div>
        </a>
    </div>
    <?php endif; ?>

    <div class="col-6 col-lg-3">
        <a href="<?php echo site_url('chat'); ?>" class="dash-stat-card">
            <div class="dash-stat-icon green"><i class="fa-solid fa-comments"></i></div>
            <div class="dash-stat-info">
                <div class="dash-stat-count"><i class="fa-solid fa-arrow-right" style="font-size: 0.9rem;"></i></div>
                <div class="dash-stat-label">Messagerie</div>
            </div>
        </a>
    </div>

    <div class="col-6 col-lg-3">
        <a href="<?php echo site_url('reunions'); ?>" class="dash-stat-card">
            <div class="dash-stat-icon orange"><i class="fa-solid fa-calendar-check"></i></div>
            <div class="dash-stat-info">
                <div class="dash-stat-count"><?php echo (int) $nb_reunions_upcoming; ?></div>
                <div class="dash-stat-label">Réunions à venir</div>
            </div>
        </a>
    </div>

    <div class="col-6 col-lg-3">
        <a href="<?php echo site_url('documents'); ?>" class="dash-stat-card">
            <div class="dash-stat-icon purple"><i class="fa-solid fa-folder-open"></i></div>
            <div class="dash-stat-info">
                <div class="dash-stat-count"><?php echo (int) $nb_documents; ?></div>
                <div class="dash-stat-label">Documents partagés</div>
            </div>
        </a>
    </div>
</div>

<!-- ===== Next Reunion Banner ===== -->
<?php if (!empty($next_reunion)): ?>
<div class="mb-4" style="background: linear-gradient(135deg, var(--primary), #4285f4); border-radius: var(--radius-lg); padding: 1.25rem 1.5rem; color: #fff; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -40%; right: -10%; width: 200px; height: 200px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
    <div class="d-flex flex-wrap align-items-center gap-3 position-relative" style="z-index: 1;">
        <div style="background: rgba(255,255,255,0.2); border-radius: var(--radius); padding: 8px 14px; text-align: center; min-width: 60px;">
            <div style="font-size: 1.5rem; font-weight: 700; line-height: 1;"><?php echo date('d', strtotime($next_reunion->scheduled_at)); ?></div>
            <div style="font-size: 0.7rem; font-weight: 600; text-transform: uppercase;"><?php echo $months[date('n', strtotime($next_reunion->scheduled_at)) - 1]; ?></div>
        </div>
        <div class="flex-grow-1">
            <span class="badge bg-white bg-opacity-25 mb-1 fw-normal" style="font-size: 0.7rem;"><i class="fas fa-bolt me-1"></i>Prochaine Réunion</span>
            <h6 class="fw-semibold mb-0"><?php echo htmlspecialchars($next_reunion->title); ?></h6>
            <small class="opacity-75"><i class="far fa-clock me-1"></i><?php echo date('H:i', strtotime($next_reunion->scheduled_at)); ?></small>
        </div>
        <a href="<?php echo site_url('reunions/view/' . (int)$next_reunion->id); ?>" class="btn btn-light btn-sm fw-medium">
            <i class="fas fa-eye me-1"></i> Voir
        </a>
    </div>
</div>
<?php endif; ?>

<!-- ===== Quick Actions ===== -->
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <a href="<?php echo site_url('chat'); ?>" class="dash-action-card">
            <i class="fa-solid fa-paper-plane"></i>
            <span>Nouveau message</span>
        </a>
    </div>
    <div class="col-sm-4">
        <a href="<?php echo site_url('reunions'); ?>" class="dash-action-card">
            <i class="fa-solid fa-video"></i>
            <span>Planifier réunion</span>
        </a>
    </div>
    <div class="col-sm-4">
        <a href="<?php echo site_url('documents'); ?>" class="dash-action-card">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <span>Partager document</span>
        </a>
    </div>
</div>

<!-- ===== Bottom Row: Users Table + Activity Feed ===== -->
<div class="row g-3">
    <?php if (is_allowed('user')): ?>
    <!-- Recent Users Table -->
    <div class="col-lg-8">
        <div class="dash-card">
            <div class="dash-card-header">
                <h5><i class="fa-solid fa-user-clock me-2 text-muted"></i>Derniers utilisateurs créés</h5>
                <span class="badge rounded-pill" style="background: var(--primary-light); color: var(--primary); font-size: 0.7rem;"><?php echo (int) $nb_users; ?> au total</span>
            </div>
            <div class="dash-card-body">
                <?php if (!empty($users)): ?>
                <div class="table-responsive">
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>E-mail</th>
                                <th>Rôle</th>
                                <th>Date de création</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $avatar_colors = ['#1a73e8','#1e8e3e','#e37400','#8430ce','#d93025'];
                            $i = 0;
                            foreach ($users as $u):
                                $nom    = htmlspecialchars($u->users_nom);
                                $prenom_u = htmlspecialchars($u->users_prenom);
                                $email  = htmlspecialchars($u->users_email);
                                $role   = htmlspecialchars($u->users_role);
                                $date   = htmlspecialchars($u->create_at);
                                $initials = mb_strtoupper(mb_substr($prenom_u, 0, 1) . mb_substr($nom, 0, 1));
                                $color  = $avatar_colors[$i % count($avatar_colors)];
                                $i++;
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="dash-avatar" style="background: <?php echo $color; ?>;"><?php echo $initials; ?></div>
                                        <div>
                                            <div style="font-weight: 600; line-height: 1.2;"><?php echo $prenom_u . ' ' . $nom; ?></div>
                                            <div style="font-size: 0.7rem; color: var(--text-muted);">@<?php echo htmlspecialchars($u->users_username); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo $email; ?></td>
                                <td>
                                    <?php
                                    $role_bg    = '#e8f0fe';
                                    $role_color = '#1a73e8';
                                    if (stripos($role, 'admin') !== false) {
                                        $role_bg = '#fce8e6'; $role_color = '#d93025';
                                    } elseif (stripos($role, 'moder') !== false || stripos($role, 'manager') !== false) {
                                        $role_bg = '#fef7e0'; $role_color = '#e37400';
                                    }
                                    ?>
                                    <span class="dash-role-badge" style="background: <?php echo $role_bg; ?>; color: <?php echo $role_color; ?>;">
                                        <?php echo $role; ?>
                                    </span>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.8rem;"><?php echo $date; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="p-4 text-center" style="color: var(--text-muted);">
                    <i class="fa-solid fa-inbox fa-2x mb-2 d-block" style="opacity: 0.4;"></i>
                    Aucun utilisateur pour le moment
                </div>
                <?php endif; ?>
            </div>
            <div class="dash-card-footer">
                <a href="<?php echo site_url('users'); ?>">Voir tous les comptes <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Activity Feed -->
    <div class="<?php echo is_allowed('user') ? 'col-lg-4' : 'col-lg-6 mx-auto'; ?>">
        <div class="dash-card">
            <div class="dash-card-header">
                <h5><i class="fa-solid fa-clock-rotate-left me-2 text-muted"></i>Activité récente</h5>
            </div>
            <div class="dash-card-body">
                <?php if (!empty($recent_activity)): ?>
                    <?php
                    $activity_icons = array(
                        'Connexion'    => array('icon' => 'fa-solid fa-right-to-bracket', 'bg' => '#e6f4ea', 'color' => '#1e8e3e'),
                        'Déconnexion'  => array('icon' => 'fa-solid fa-right-from-bracket', 'bg' => '#fce8e6', 'color' => '#d93025'),
                        'Création'     => array('icon' => 'fa-solid fa-plus',   'bg' => '#e8f0fe', 'color' => '#1a73e8'),
                        'Modification' => array('icon' => 'fa-solid fa-pen',    'bg' => '#fef7e0', 'color' => '#e37400'),
                        'Suppression'  => array('icon' => 'fa-solid fa-trash',  'bg' => '#fce8e6', 'color' => '#d93025'),
                    );
                    foreach ($recent_activity as $act):
                        $act_text = htmlspecialchars($act->history_action);
                        $act_user = htmlspecialchars($act->history_users);
                        $act_date = isset($act->history_date) ? $act->history_date : '';
                        $icon_data = array('icon' => 'fa-solid fa-circle-info', 'bg' => '#f1f3f4', 'color' => '#5f6368');
                        foreach ($activity_icons as $key => $val) {
                            if (mb_stripos($act_text, $key) !== false) { $icon_data = $val; break; }
                        }
                        // Time ago
                        $time_display = '';
                        if ($act_date) {
                            $diff = time() - strtotime($act_date);
                            if ($diff < 60) $time_display = 'À l\'instant';
                            elseif ($diff < 3600) $time_display = 'Il y a ' . floor($diff/60) . ' min';
                            elseif ($diff < 86400) $time_display = 'Il y a ' . floor($diff/3600) . 'h';
                            else $time_display = date('d/m/Y H:i', strtotime($act_date));
                        }
                    ?>
                    <div class="dash-activity-item">
                        <div class="dash-activity-dot" style="background: <?php echo $icon_data['bg']; ?>; color: <?php echo $icon_data['color']; ?>;">
                            <i class="<?php echo $icon_data['icon']; ?>"></i>
                        </div>
                        <div class="dash-activity-content">
                            <div class="dash-activity-text"><?php echo $act_text; ?></div>
                            <div class="dash-activity-time">
                                <?php if ($act_user): ?><strong><?php echo $act_user; ?></strong> &mdash; <?php endif; ?>
                                <?php echo $time_display; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-4 text-center" style="color: var(--text-muted);">
                        <i class="fa-solid fa-clock fa-2x mb-2 d-block" style="opacity: 0.3;"></i>
                        Aucune activité récente
                    </div>
                <?php endif; ?>
            </div>
            <?php if (is_allowed('view_history')): ?>
            <div class="dash-card-footer">
                <a href="<?php echo site_url('users/history'); ?>">Voir tout l'historique <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
