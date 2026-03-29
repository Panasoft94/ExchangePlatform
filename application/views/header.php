<?php $session = $this->session->userdata('users'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exchange Pro — Plateforme d'Échange Professionnel</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6.4.2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="<?php echo base_url('assets/datatables/datatables.bootstrap.css'); ?>" rel="stylesheet">

    <!-- Base URL for JS -->
    <script>var BASE_URL = '<?php echo base_url(); ?>', SITE_URL = '<?php echo site_url(); ?>';</script>

    <style>
        /* ===== CSS Variables ===== */
        :root {
            --primary: #1a73e8;
            --primary-hover: #1557b0;
            --primary-light: #e8f0fe;
            --bg-main: #f8f9fa;
            --bg-white: #ffffff;
            --bg-sidebar: #ffffff;
            --text-primary: #202124;
            --text-secondary: #5f6368;
            --text-muted: #80868b;
            --border-color: #dadce0;
            --shadow-sm: 0 1px 2px 0 rgba(60,64,67,.3), 0 1px 3px 1px rgba(60,64,67,.15);
            --shadow-md: 0 1px 3px 0 rgba(60,64,67,.3), 0 4px 8px 3px rgba(60,64,67,.15);
            --sidebar-width: 260px;
            --topbar-height: 64px;
            --radius: 8px;
            --radius-lg: 12px;
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== Reset & Base ===== */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-main);
            color: var(--text-primary);
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== Progress Bar (YouTube-style) ===== */
        .progress-bar-top {
            position: fixed;
            top: 0;
            left: 0;
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), #4285f4, #34a853);
            z-index: 9999;
            transition: width 0.4s ease, opacity 0.3s ease;
            opacity: 0;
            pointer-events: none;
        }
        .progress-bar-top.ajax-loading {
            opacity: 1;
            animation: progress-indeterminate 1.8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }
        @keyframes progress-indeterminate {
            0%   { width: 0; left: 0; }
            50%  { width: 70%; left: 0; }
            100% { width: 100%; left: 0; }
        }
        .progress-bar-top.progress-done {
            width: 100% !important;
            opacity: 0;
            transition: width 0.2s ease, opacity 0.4s ease 0.2s;
        }

        /* ===== Sidebar ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background-color: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--border-color);
            flex-shrink: 0;
        }
        .sidebar-logo .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), #4285f4);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            flex-shrink: 0;
        }
        .sidebar-logo .logo-text {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.3px;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 8px 0;
        }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 4px; }

        .nav-section-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 16px 20px 6px;
        }

        .sidebar-separator {
            height: 1px;
            background: var(--border-color);
            margin: 8px 16px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 10px 20px;
            margin: 1px 8px;
            border-radius: var(--radius);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            border-left: 3px solid transparent;
        }
        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
            flex-shrink: 0;
        }
        .sidebar-link:hover {
            background-color: var(--primary-light);
            color: var(--primary);
        }
        .sidebar-link.active {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
            border-left-color: var(--primary);
        }

        .sidebar-footer {
            padding: 12px 16px;
            border-top: 1px solid var(--border-color);
            flex-shrink: 0;
        }
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px;
            border-radius: var(--radius);
            transition: var(--transition);
        }
        .sidebar-user:hover { background: var(--bg-main); }
        .sidebar-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--border-color);
            flex-shrink: 0;
        }
        .sidebar-user-info { flex: 1; min-width: 0; }
        .sidebar-user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar-user-role {
            font-size: 11px;
            color: var(--text-muted);
        }
        .sidebar-logout {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: none;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            flex-shrink: 0;
        }
        .sidebar-logout:hover {
            background: #fce8e6;
            color: #d93025;
        }

        /* ===== Sidebar Overlay (mobile) ===== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1039;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .sidebar-overlay.show { display: block; opacity: 1; }

        /* ===== Top Bar ===== */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            height: var(--topbar-height);
            background: var(--bg-white);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 16px;
            box-shadow: 0 1px 2px rgba(60,64,67,.1);
        }

        .topbar-hamburger {
            display: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: none;
            color: var(--text-secondary);
            font-size: 18px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            flex-shrink: 0;
        }
        .topbar-hamburger:hover { background: var(--bg-main); }

        .topbar-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .topbar-search {
            flex: 1;
            max-width: 720px;
            margin: 0 auto;
        }
        .topbar-search-input {
            width: 100%;
            height: 44px;
            background: var(--bg-main);
            border: 1px solid transparent;
            border-radius: 24px;
            padding: 0 20px 0 44px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
            transition: var(--transition);
            outline: none;
        }
        .topbar-search-input::placeholder { color: var(--text-muted); }
        .topbar-search-input:focus {
            background: var(--bg-white);
            border-color: var(--primary);
            box-shadow: 0 1px 6px rgba(26,115,232,.2);
        }
        .topbar-search-wrapper {
            position: relative;
        }
        .topbar-search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 14px;
            pointer-events: none;
        }
        .topbar-search-shortcut {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 11px;
            color: var(--text-muted);
            background: var(--bg-white);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 2px 6px;
            font-weight: 500;
            pointer-events: none;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-shrink: 0;
        }
        .topbar-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: none;
            color: var(--text-secondary);
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            position: relative;
        }
        .topbar-btn:hover { background: var(--bg-main); }
        .topbar-btn .badge-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: #ea4335;
            border-radius: 50%;
            border: 2px solid var(--bg-white);
        }

        .topbar-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
            transition: var(--transition);
        }
        .topbar-avatar:hover { border-color: var(--primary-light); }

        .dropdown-menu {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: 8px;
            min-width: 200px;
        }
        .dropdown-menu .dropdown-item {
            border-radius: var(--radius);
            padding: 10px 14px;
            font-size: 14px;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
        }
        .dropdown-menu .dropdown-item:hover {
            background: var(--primary-light);
            color: var(--primary);
        }
        .dropdown-menu .dropdown-item.text-danger:hover {
            background: #fce8e6;
            color: #d93025;
        }
        .dropdown-menu .dropdown-divider {
            margin: 4px 0;
            border-color: var(--border-color);
        }

        /* ===== Main Content ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .content-area {
            flex: 1;
            padding: 24px;
        }

        .content-area .container-fluid {
            opacity: 1;
            transition: opacity 0.25s ease;
        }
        .content-area .container-fluid.fade-out {
            opacity: 0;
        }

        /* ===== Flash Alerts ===== */
        .flash-alert {
            border: none;
            border-radius: var(--radius);
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            margin-bottom: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
            animation: slideDown 0.3s ease;
        }
        .flash-alert.alert-success {
            background: #e6f4ea;
            color: #137333;
        }
        .flash-alert.alert-info {
            background: var(--primary-light);
            color: #174ea6;
        }
        .flash-alert.alert-danger {
            background: #fce8e6;
            color: #c5221f;
        }
        .flash-alert.alert-warning {
            background: #fef7e0;
            color: #b05a00;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ===== Footer ===== */
        .app-footer {
            padding: 16px 24px;
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
            border-top: 1px solid var(--border-color);
            background: var(--bg-white);
        }

        /* ===== DataTables Overrides ===== */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 6px 12px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(26,115,232,.15);
        }
        table.dataTable thead th {
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            font-size: 13px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* ===== Cards (global override) ===== */
        .card {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
            transition: var(--transition);
        }
        .card:hover { box-shadow: var(--shadow-sm); }

        /* ===== Tables ===== */
        .table { font-size: 14px; }
        .table thead th {
            font-weight: 600;
            color: var(--text-secondary);
            border-bottom: 2px solid var(--border-color);
        }

        /* ===== Buttons ===== */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            border-radius: var(--radius);
            font-weight: 500;
            font-size: 14px;
            transition: var(--transition);
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            box-shadow: 0 1px 3px rgba(26,115,232,.3);
        }

        /* ===== Scrollbar ===== */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* ===== Responsive ===== */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: var(--shadow-md); }
            .main-content { margin-left: 0; }
            .topbar-hamburger { display: flex; }
            .topbar-search { display: none; }
        }
        @media (min-width: 992px) {
            .sidebar-overlay { display: none !important; }
        }
    </style>
</head>
<body>

    <!-- Progress Bar -->
    <div class="progress-bar-top" id="progressBar"></div>

    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon"><i class="fas fa-exchange-alt"></i></div>
            <span class="logo-text">Exchange Pro</span>
        </div>

        <div class="sidebar-nav">
            <div class="nav-section-label">Principal</div>

            <a href="<?php echo base_url(); ?>" class="sidebar-link" data-nav>
                <i class="fas fa-home"></i> <span>Accueil</span>
            </a>
            <a href="<?php echo site_url('chat'); ?>" class="sidebar-link" data-nav>
                <i class="fas fa-comment-dots"></i> <span>Messagerie</span>
            </a>
            <a href="<?php echo site_url('reunions'); ?>" class="sidebar-link" data-nav>
                <i class="fas fa-calendar-check"></i> <span>Réunions</span>
            </a>
            <a href="<?php echo site_url('documents'); ?>" class="sidebar-link" data-nav>
                <i class="fas fa-folder-open"></i> <span>Documents</span>
            </a>

            <?php if(is_allowed('user') || is_allowed('group') || is_allowed('view_history')): ?>
                <div class="sidebar-separator"></div>
                <div class="nav-section-label">Administration</div>
            <?php endif; ?>

            <?php if(is_allowed('user')): ?>
                <a href="<?php echo site_url('users'); ?>" class="sidebar-link" data-nav>
                    <i class="fas fa-users"></i> <span>Comptes</span>
                </a>
            <?php endif; ?>

            <?php if(is_allowed('group')): ?>
                <a href="<?php echo site_url('users/group'); ?>" class="sidebar-link" data-nav>
                    <i class="fas fa-layer-group"></i> <span>Groupes</span>
                </a>
            <?php endif; ?>

            <?php if(is_allowed('view_history')): ?>
                <a href="<?php echo site_url('users/history'); ?>" class="sidebar-link" data-nav>
                    <i class="fas fa-clock-rotate-left"></i> <span>Historique</span>
                </a>
            <?php endif; ?>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <?php if(isset($session->photo_profil) && file_exists('assets/img/avatar/'.$session->photo_profil)): ?>
                    <img src="<?php echo base_url('assets/img/avatar/' . htmlspecialchars($session->photo_profil, ENT_QUOTES, 'UTF-8')); ?>" alt="Avatar" class="sidebar-user-avatar">
                <?php else: ?>
                    <img src="<?php echo base_url('assets/img/avatar/default.jpg'); ?>" alt="Avatar" class="sidebar-user-avatar">
                <?php endif; ?>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name"><?php echo isset($session->users_prenom) ? htmlspecialchars($session->users_prenom, ENT_QUOTES, 'UTF-8') : 'Utilisateur'; ?></div>
                    <div class="sidebar-user-role"><?php echo isset($session->users_username) ? htmlspecialchars($session->users_username, ENT_QUOTES, 'UTF-8') : ''; ?></div>
                </div>
                <a href="<?php echo site_url('users/logout'); ?>" class="sidebar-logout" title="Déconnexion">
                    <i class="fas fa-right-from-bracket"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <header class="topbar">
            <button class="topbar-hamburger" id="sidebarToggle" type="button" aria-label="Ouvrir le menu">
                <i class="fas fa-bars"></i>
            </button>

            <span class="topbar-title" id="pageTitle">Tableau de bord</span>

            <div class="topbar-search">
                <div class="topbar-search-wrapper">
                    <i class="fas fa-search topbar-search-icon"></i>
                    <input type="text" class="topbar-search-input" id="globalSearch" placeholder="Rechercher..." autocomplete="off">
                    <span class="topbar-search-shortcut">Ctrl+K</span>
                </div>
            </div>

            <div class="topbar-actions">
                <button class="topbar-btn" type="button" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="badge-dot"></span>
                </button>

                <div class="dropdown">
                    <?php if(isset($session->photo_profil) && file_exists('assets/img/avatar/'.$session->photo_profil)): ?>
                        <img src="<?php echo base_url('assets/img/avatar/' . htmlspecialchars($session->photo_profil, ENT_QUOTES, 'UTF-8')); ?>" alt="Avatar" class="topbar-avatar dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                    <?php else: ?>
                        <img src="<?php echo base_url('assets/img/avatar/default.jpg'); ?>" alt="Avatar" class="topbar-avatar dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                    <?php endif; ?>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-2">
                            <div class="fw-semibold" style="font-size:14px"><?php echo isset($session->users_prenom) ? htmlspecialchars($session->users_prenom, ENT_QUOTES, 'UTF-8') : ''; ?> <?php echo isset($session->users_nom) ? htmlspecialchars($session->users_nom, ENT_QUOTES, 'UTF-8') : ''; ?></div>
                            <div style="font-size:12px;color:var(--text-muted)"><?php echo isset($session->users_email) ? htmlspecialchars($session->users_email, ENT_QUOTES, 'UTF-8') : ''; ?></div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo site_url('users/edit'); ?>"><i class="fas fa-gear"></i> Paramètres</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?php echo site_url('users/logout'); ?>"><i class="fas fa-right-from-bracket"></i> Déconnexion</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            <div class="container-fluid" id="mainContent">
                <!-- Flash Messages -->
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert flash-alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('info')): ?>
                    <div class="alert flash-alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle"></i>
                        <?php echo $this->session->flashdata('info'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert flash-alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-circle-exclamation"></i>
                        <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('warning')): ?>
                    <div class="alert flash-alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="fas fa-triangle-exclamation"></i>
                        <?php echo $this->session->flashdata('warning'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                <?php endif; ?>
