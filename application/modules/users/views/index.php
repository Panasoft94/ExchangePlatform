<?php
    $s_total = isset($stats['total']) ? (int)$stats['total'] : 0;
    $s_online = isset($stats['online']) ? (int)$stats['online'] : 0;
    $s_locked = isset($stats['locked']) ? (int)$stats['locked'] : 0;
    $s_groups = isset($stats['groups']) ? (int)$stats['groups'] : 0;
?>

<style>
.users-stat-card {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.2s ease;
    cursor: default;
}
.users-stat-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    transform: translateY(-2px);
}
.users-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
}
.users-stat-card .stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1.2;
}
.users-stat-card .stat-label {
    font-size: 0.8125rem;
    color: var(--text-secondary);
    margin: 0;
}

/* Filter pills */
.user-filter-pill {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.8125rem;
    font-weight: 500;
    border: 1px solid var(--border-color);
    background: var(--bg-white);
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.user-filter-pill:hover,
.user-filter-pill.active {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}
.user-filter-pill .pill-count {
    background: rgba(255,255,255,0.2);
    padding: 1px 8px;
    border-radius: 10px;
    font-size: 0.75rem;
}
.user-filter-pill.active .pill-count {
    background: rgba(255,255,255,0.25);
}

/* User cards grid */
.user-card {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
}
.user-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    transform: translateY(-2px);
    border-color: var(--primary);
}
.user-card .user-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    font-weight: 600;
    flex-shrink: 0;
}
.user-card .user-avatar-img {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--primary-light);
    flex-shrink: 0;
}
.user-card .user-online-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid var(--bg-white);
    position: absolute;
}
.user-card .status-badge {
    font-size: 0.7rem;
    padding: 3px 10px;
    border-radius: 12px;
    font-weight: 500;
}
.user-card .action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border-color);
    background: var(--bg-white);
    color: var(--text-secondary);
    font-size: 0.8rem;
    transition: all 0.15s;
    text-decoration: none;
}
.user-card .action-btn:hover {
    background: var(--primary-light);
    color: var(--primary);
    border-color: var(--primary);
}
.user-card .action-btn.btn-lock:hover {
    background: #fff3cd;
    color: #856404;
    border-color: #ffc107;
}
.user-card .action-btn.btn-delete:hover {
    background: #f8d7da;
    color: #842029;
    border-color: #dc3545;
}

/* View toggle */
.view-toggle-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: 1px solid var(--border-color);
    background: var(--bg-white);
    color: var(--text-secondary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s;
}
.view-toggle-btn:hover,
.view-toggle-btn.active {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

/* Search input */
.users-search-input {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 8px 12px 8px 36px;
    font-size: 0.875rem;
    color: var(--text-primary);
    transition: border-color 0.2s, box-shadow 0.2s;
    width: 280px;
}
.users-search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(26,115,232,0.1);
}

/* Locked card overlay */
.user-card.locked {
    opacity: 0.75;
}
.user-card.locked::after {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 4px;
    height: 100%;
    background: #dc3545;
    border-radius: var(--radius-lg) 0 0 var(--radius-lg);
}

/* Table view */
.users-table-view .user-row-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 600;
    flex-shrink: 0;
}
.users-table-view .user-row-avatar-img {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
</style>

<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1" style="color: var(--text-primary);">
            <i class="fas fa-users me-2" style="color: var(--primary);"></i>Gestion des Utilisateurs
        </h1>
        <p class="mb-0 small" style="color: var(--text-secondary);">Gérez les comptes, accès et profils des utilisateurs</p>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <?php if(is_allowed('view_history')): ?>
            <a href="<?php echo site_url('users/history'); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-clock-rotate-left me-1"></i> Journal
            </a>
        <?php endif; ?>
        <?php if(is_allowed('group')): ?>
            <a href="<?php echo site_url('users/group'); ?>" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-layer-group me-1"></i> Groupes
            </a>
        <?php endif; ?>
        <?php if(is_allowed('add_user')): ?>
            <a href="<?php echo site_url('users/add'); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Nouvel Utilisateur
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="users-stat-card">
            <div class="users-stat-icon" style="background: var(--primary-light); color: var(--primary);">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="stat-value" style="color: var(--text-primary);"><?php echo $s_total; ?></div>
                <p class="stat-label">Total utilisateurs</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="users-stat-card">
            <div class="users-stat-icon" style="background: #e6f4ea; color: #188038;">
                <i class="fas fa-circle-check"></i>
            </div>
            <div>
                <div class="stat-value" style="color: #188038;"><?php echo $s_online; ?></div>
                <p class="stat-label">En ligne</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="users-stat-card">
            <div class="users-stat-icon" style="background: #fce8e6; color: #c5221f;">
                <i class="fas fa-lock"></i>
            </div>
            <div>
                <div class="stat-value" style="color: #c5221f;"><?php echo $s_locked; ?></div>
                <p class="stat-label">Comptes verrouillés</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="users-stat-card">
            <div class="users-stat-icon" style="background: #fef7e0; color: #ea8600;">
                <i class="fas fa-layer-group"></i>
            </div>
            <div>
                <div class="stat-value" style="color: #ea8600;"><?php echo $s_groups; ?></div>
                <p class="stat-label">Groupes</p>
            </div>
        </div>
    </div>
</div>

<?php if(!empty($liste_users)): ?>

<!-- Toolbar: Search + Filters + View Toggle -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div class="d-flex flex-wrap align-items-center gap-2">
        <!-- Search -->
        <div class="position-relative">
            <i class="fas fa-search position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.8rem;"></i>
            <input type="text" id="usersSearchInput" class="users-search-input" placeholder="Rechercher un utilisateur...">
        </div>
        <!-- Filter Pills -->
        <button class="user-filter-pill active" data-filter="all">
            Tous <span class="pill-count"><?php echo $s_total; ?></span>
        </button>
        <button class="user-filter-pill" data-filter="online">
            <i class="fas fa-circle" style="font-size: 7px; color: #188038;"></i> En ligne <span class="pill-count"><?php echo $s_online; ?></span>
        </button>
        <?php if($s_locked > 0): ?>
        <button class="user-filter-pill" data-filter="locked">
            <i class="fas fa-lock" style="font-size: 10px;"></i> Verrouillés <span class="pill-count"><?php echo $s_locked; ?></span>
        </button>
        <?php endif; ?>
    </div>
    <div class="d-flex gap-1">
        <button class="view-toggle-btn active" id="viewGrid" title="Vue grille"><i class="fas fa-grip"></i></button>
        <button class="view-toggle-btn" id="viewTable" title="Vue tableau"><i class="fas fa-list"></i></button>
    </div>
</div>

<!-- Grid View -->
<div id="usersGridView">
    <div class="row g-3" id="usersGrid">
        <?php foreach($liste_users as $l): ?>
        <?php
            $initials = mb_strtoupper(mb_substr($l->users_prenom, 0, 1) . mb_substr($l->users_nom, 0, 1));
            $safe_photo = isset($l->photo_profil) ? basename($l->photo_profil) : '';
            $has_photo = ($safe_photo && file_exists('assets/img/avatar/' . $safe_photo));
            $is_online = ($l->etat_online == 1);
            $is_locked = ($l->etat_compte == 0);
        ?>
        <div class="col-xl-3 col-lg-4 col-md-6 user-card-wrapper"
             data-name="<?php echo htmlspecialchars(strtolower($l->users_nom . ' ' . $l->users_prenom . ' ' . $l->users_username . ' ' . $l->users_email)); ?>"
             data-online="<?php echo $is_online ? '1' : '0'; ?>"
             data-locked="<?php echo $is_locked ? '1' : '0'; ?>">
            <div class="user-card <?php echo $is_locked ? 'locked' : ''; ?>">
                <!-- Top: Avatar + Status -->
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="position-relative">
                        <?php if($has_photo): ?>
                            <img src="<?php echo base_url('assets/img/avatar/' . htmlspecialchars($safe_photo)); ?>"
                                 class="user-avatar-img" alt="Avatar">
                        <?php else: ?>
                            <div class="user-avatar" style="background: var(--primary-light); color: var(--primary);">
                                <?php echo htmlspecialchars($initials); ?>
                            </div>
                        <?php endif; ?>
                        <div class="user-online-dot" style="bottom: 2px; right: 2px; background: <?php echo $is_online ? '#188038' : '#9aa0a6'; ?>;"></div>
                    </div>
                    <?php if($is_locked): ?>
                        <span class="status-badge" style="background: #fce8e6; color: #c5221f;">
                            <i class="fas fa-lock me-1" style="font-size: 9px;"></i>Verrouillé
                        </span>
                    <?php elseif($is_online): ?>
                        <span class="status-badge" style="background: #e6f4ea; color: #188038;">
                            <i class="fas fa-circle me-1" style="font-size: 7px;"></i>En ligne
                        </span>
                    <?php else: ?>
                        <span class="status-badge" style="background: #f1f3f4; color: var(--text-secondary);">
                            <i class="fas fa-circle me-1" style="font-size: 7px;"></i>Hors ligne
                        </span>
                    <?php endif; ?>
                </div>

                <!-- User Info -->
                <div class="mb-3">
                    <h6 class="fw-semibold mb-1" style="color: var(--text-primary); font-size: 0.95rem;">
                        <?php echo htmlspecialchars($l->users_nom . ' ' . $l->users_prenom); ?>
                    </h6>
                    <div class="small mb-1" style="color: var(--text-secondary);">
                        <i class="fas fa-at me-1" style="font-size: 10px;"></i><?php echo htmlspecialchars($l->users_username); ?>
                    </div>
                    <div class="small" style="color: var(--text-secondary);">
                        <i class="fas fa-envelope me-1" style="font-size: 10px;"></i><?php echo htmlspecialchars($l->users_email); ?>
                    </div>
                </div>

                <!-- Role Badge -->
                <?php if(!empty($l->users_role)): ?>
                <div class="mb-3">
                    <span class="badge rounded-pill" style="background: var(--primary-light); color: var(--primary); font-weight: 500; font-size: 0.75rem;">
                        <?php echo htmlspecialchars($l->users_role); ?>
                    </span>
                </div>
                <?php endif; ?>

                <!-- Actions -->
                <div class="d-flex align-items-center gap-1 pt-2" style="border-top: 1px solid var(--border-color);">
                    <?php if($is_locked): ?>
                        <a href="<?php echo site_url('users/deverrouiller_compte_user/' . (int)$l->users_id); ?>"
                           class="action-btn btn-lock" title="Déverrouiller"
                           onclick="return confirm('Déverrouiller ce compte ?')">
                            <i class="fas fa-unlock"></i>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo site_url('users/verrouiller_compte_user/' . (int)$l->users_id); ?>"
                           class="action-btn btn-lock" title="Verrouiller"
                           onclick="return confirm('Verrouiller ce compte ?')">
                            <i class="fas fa-lock"></i>
                        </a>
                    <?php endif; ?>

                    <?php if(is_allowed('update_user')): ?>
                        <a href="<?php echo site_url('users/update/' . (int)$l->users_id); ?>"
                           class="action-btn" title="Modifier">
                            <i class="fas fa-pen"></i>
                        </a>
                    <?php endif; ?>

                    <?php if(is_allowed('delete_user')): ?>
                        <a href="<?php echo site_url('users/delete/' . (int)$l->users_id); ?>"
                           class="action-btn btn-delete" title="Supprimer"
                           onclick="return confirm('Supprimer ce compte définitivement ?')">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    <?php endif; ?>

                    <div class="ms-auto small" style="color: var(--text-muted);">
                        <?php if(isset($l->create_at)): ?>
                            <i class="fas fa-calendar me-1" style="font-size: 9px;"></i><?php echo date('d/m/Y', strtotime($l->create_at)); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Table View (hidden by default) -->
<div id="usersTableView" style="display: none;">
    <div class="card users-table-view" style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
        <div class="card-body p-0">
            <?php echo form_open('users/remove_access_account', array('class' => 'm-0')); ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle" id="myDatatable">
                    <thead>
                        <tr style="background: var(--bg-main);">
                            <th scope="col" class="ps-4">Utilisateur</th>
                            <th scope="col">Nom d'utilisateur</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Fonction</th>
                            <th scope="col">Statut</th>
                            <th scope="col" class="text-center">État du compte</th>
                            <th scope="col" class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($liste_users as $l): ?>
                        <?php
                            $initials_t = mb_strtoupper(mb_substr($l->users_prenom, 0, 1) . mb_substr($l->users_nom, 0, 1));
                            $safe_photo_t = isset($l->photo_profil) ? basename($l->photo_profil) : '';
                            $has_photo_t = ($safe_photo_t && file_exists('assets/img/avatar/' . $safe_photo_t));
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <?php if($has_photo_t): ?>
                                        <img src="<?php echo base_url('assets/img/avatar/' . htmlspecialchars($safe_photo_t)); ?>"
                                             class="user-row-avatar-img me-3" alt="Avatar">
                                    <?php else: ?>
                                        <div class="user-row-avatar me-3" style="background: var(--primary-light); color: var(--primary);">
                                            <?php echo htmlspecialchars($initials_t); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-medium" style="color: var(--text-primary); font-size: 0.9rem;"><?php echo htmlspecialchars($l->users_nom . ' ' . $l->users_prenom); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td style="color: var(--text-secondary); font-size: 0.875rem;"><?php echo htmlspecialchars($l->users_username); ?></td>
                            <td>
                                <a href="mailto:<?php echo htmlspecialchars($l->users_email); ?>" class="text-decoration-none" style="color: var(--primary); font-size: 0.875rem;">
                                    <?php echo htmlspecialchars($l->users_email); ?>
                                </a>
                            </td>
                            <td>
                                <span class="badge rounded-pill" style="background: var(--primary-light); color: var(--primary); font-weight: 500;">
                                    <?php echo htmlspecialchars($l->users_role); ?>
                                </span>
                            </td>
                            <td>
                                <?php if($l->etat_online == 1): ?>
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success">
                                        <i class="fas fa-circle me-1" style="font-size: 7px; vertical-align: middle;"></i> En ligne
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill" style="background: #f1f3f4; color: var(--text-secondary);">
                                        <i class="fas fa-circle me-1" style="font-size: 7px; vertical-align: middle;"></i> Hors ligne
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                           id="switch_<?php echo (int)$l->users_id; ?>"
                                           name="etat_compte[]"
                                           value="<?php echo (int)$l->users_id; ?>"
                                           <?php echo $l->etat_compte == 1 ? 'checked' : ''; ?>>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-1">
                                    <?php if($l->etat_compte == 1): ?>
                                        <a href="<?php echo site_url('users/verrouiller_compte_user/' . (int)$l->users_id); ?>"
                                           class="btn btn-sm btn-outline-warning" title="Verrouiller"
                                           onclick="return confirm('Verrouiller ce compte ?')" style="font-size: 0.75rem;">
                                            <i class="fas fa-lock"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo site_url('users/deverrouiller_compte_user/' . (int)$l->users_id); ?>"
                                           class="btn btn-sm btn-warning" title="Déverrouiller"
                                           onclick="return confirm('Déverrouiller ce compte ?')" style="font-size: 0.75rem;">
                                            <i class="fas fa-unlock"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if(is_allowed('update_user')): ?>
                                        <a href="<?php echo site_url('users/update/' . (int)$l->users_id); ?>"
                                           class="btn btn-sm btn-outline-primary" title="Modifier" style="font-size: 0.75rem;">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    <?php endif; ?>

                                    <?php if(is_allowed('delete_user')): ?>
                                        <a href="<?php echo site_url('users/delete/' . (int)$l->users_id); ?>"
                                           class="btn btn-sm btn-outline-danger" title="Supprimer"
                                           onclick="return confirm('Supprimer ce compte définitivement ?')" style="font-size: 0.75rem;">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-end" style="background: var(--bg-main); border-top: 1px solid var(--border-color);">
                <button type="submit" class="btn btn-primary btn-sm"
                        onclick="return confirm('Valider les changements d\'état des comptes ?')">
                    <i class="fas fa-check me-1"></i> Enregistrer les états
                </button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<?php else: ?>
<div class="card" style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
    <div class="card-body text-center py-5">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 80px; height: 80px; background: var(--primary-light);">
            <i class="fas fa-users fa-2x" style="color: var(--primary);"></i>
        </div>
        <h5 class="fw-semibold" style="color: var(--text-primary);">Aucun utilisateur</h5>
        <p class="mb-3" style="color: var(--text-secondary);">Aucun compte utilisateur n'est disponible pour le moment.</p>
        <?php if(is_allowed('add_user')): ?>
            <a href="<?php echo site_url('users/add'); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Créer le premier utilisateur
            </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle
    var gridView = document.getElementById('usersGridView');
    var tableView = document.getElementById('usersTableView');
    var btnGrid = document.getElementById('viewGrid');
    var btnTable = document.getElementById('viewTable');

    if(btnGrid && btnTable) {
        btnGrid.addEventListener('click', function() {
            gridView.style.display = '';
            tableView.style.display = 'none';
            btnGrid.classList.add('active');
            btnTable.classList.remove('active');
        });
        btnTable.addEventListener('click', function() {
            gridView.style.display = 'none';
            tableView.style.display = '';
            btnTable.classList.add('active');
            btnGrid.classList.remove('active');
            // Init DataTable on first show
            if(typeof jQuery !== 'undefined' && jQuery.fn.DataTable && !jQuery.fn.DataTable.isDataTable('#myDatatable')) {
                jQuery('#myDatatable').DataTable({
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' },
                    pageLength: 25,
                    order: [[0, 'asc']]
                });
            }
        });
    }

    // Search
    var searchInput = document.getElementById('usersSearchInput');
    if(searchInput) {
        searchInput.addEventListener('input', function() {
            var query = this.value.toLowerCase().trim();
            var cards = document.querySelectorAll('.user-card-wrapper');
            cards.forEach(function(card) {
                var name = card.getAttribute('data-name') || '';
                card.style.display = name.indexOf(query) !== -1 ? '' : 'none';
            });
        });
    }

    // Filter pills
    var pills = document.querySelectorAll('.user-filter-pill');
    pills.forEach(function(pill) {
        pill.addEventListener('click', function() {
            pills.forEach(function(p) { p.classList.remove('active'); });
            this.classList.add('active');
            var filter = this.getAttribute('data-filter');
            var cards = document.querySelectorAll('.user-card-wrapper');
            cards.forEach(function(card) {
                if(filter === 'all') {
                    card.style.display = '';
                } else if(filter === 'online') {
                    card.style.display = card.getAttribute('data-online') === '1' ? '' : 'none';
                } else if(filter === 'locked') {
                    card.style.display = card.getAttribute('data-locked') === '1' ? '' : 'none';
                }
            });
        });
    });
});
</script>