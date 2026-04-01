<?php $session = $this->session->userdata('users'); ?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <style>#pageLoader{position:fixed;top:0;left:0;width:0;height:3px;background:#1a73e8;z-index:99999;pointer-events:none;}</style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Exchange Pro — Plateforme d'Échange Professionnel</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url('assets/img/arm.ico'); ?>">

    <!-- Google Fonts: Inter (local) -->
    <link href="<?php echo base_url('assets/css/inter.css'); ?>" rel="stylesheet">

    <!-- jQuery (must be first for DataTables) -->
    <script src="<?php echo base_url('assets/js/jquery.js'); ?>"></script>

    <!-- Bootstrap 5.3.2 CSS (local) -->
    <link href="<?php echo base_url('assets/css/bootstrap5/bootstrap.min.css'); ?>" rel="stylesheet">

    <!-- Font Awesome 6.4.2 (local) -->
    <link href="<?php echo base_url('assets/fontawesome6/css/all.min.css'); ?>" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="<?php echo base_url('assets/datatables/datatables.bootstrap.css'); ?>" rel="stylesheet">

    <!-- Base URL for JS -->
    <script>var BASE_URL = '<?php echo base_url(); ?>', SITE_URL = '<?php echo site_url(); ?>', CHAT_UNREAD_SUMMARY_URL = '<?php echo site_url('chat/unread_summary'); ?>';</script>

    <style>
        /* ===== Page Loader — Top Bar ===== */
        #pageLoader{position:fixed;top:0;left:0;width:0;height:3px;background:var(--primary,#1a73e8);z-index:99999;transition:width .3s ease;pointer-events:none;}
        #pageLoader.loading{width:85%;transition:width 8s cubic-bezier(.1,.05,.1,1);}
        #pageLoader.done{width:100%;transition:width .15s ease;opacity:0;transition:width .15s ease, opacity .3s .15s ease;}

        /* ===== Pjax Content Transition ===== */
        #mainContent{transition:opacity .18s ease,transform .18s ease;}
        #mainContent.pjax-out{opacity:0;transform:translateY(6px);}

        /* ===== CSS Variables — Light Theme ===== */
        :root, [data-theme="light"] {
            --primary: #1a73e8;
            --primary-hover: #1557b0;
            --primary-light: #e8f0fe;
            --bg-main: #f8f9fa;
            --bg-white: #ffffff;
            --bg-sidebar: #ffffff;
            --bg-card: #ffffff;
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

        /* ===== CSS Variables — Dark Theme ===== */
        [data-theme="dark"] {
            --primary: #4f9cf9;
            --primary-hover: #7bb5fb;
            --primary-light: #1e3a5f;
            --bg-main: #0f1117;
            --bg-white: #1a1d2e;
            --bg-sidebar: #141624;
            --bg-card: #1a1d2e;
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border-color: #2d3748;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.4);
            --shadow-md: 0 4px 16px rgba(0,0,0,.5);
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
            transition: background-color 0.3s ease, color 0.3s ease;
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
        .sidebar-link-badge {
            margin-left: auto;
            min-width: 22px;
            padding: 2px 7px;
            border-radius: 999px;
            background: #dc3545;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            line-height: 1.4;
            text-align: center;
        }
        .sidebar-link-badge.d-none {
            display: none !important;
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

        /* ===== Dark Mode Specific ===== */
        [data-theme="dark"] .sidebar,
        [data-theme="dark"] .topbar,
        [data-theme="dark"] .app-footer { background-color: var(--bg-sidebar); }
        [data-theme="dark"] .card,
        [data-theme="dark"] .dropdown-menu { background-color: var(--bg-card); border-color: var(--border-color); }
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background-color: var(--bg-main);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus {
            background-color: var(--bg-card);
        }
        [data-theme="dark"] .input-group-text {
            background-color: var(--bg-main);
            border-color: var(--border-color);
            color: var(--text-secondary);
        }
        [data-theme="dark"] .table > :not(caption) > * > * {
            background-color: transparent;
            color: var(--text-primary);
            border-bottom-color: var(--border-color);
        }
        [data-theme="dark"] .table-hover > tbody > tr:hover > * {
            background-color: rgba(255,255,255,0.04);
        }
        [data-theme="dark"] .modal-content {
            background-color: var(--bg-card);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        [data-theme="dark"] .modal-header,
        [data-theme="dark"] .modal-footer { border-color: var(--border-color); }
        [data-theme="dark"] .btn-close { filter: invert(1); }
        [data-theme="dark"] .topbar-search-input {
            background: var(--bg-main);
            color: var(--text-primary);
        }
        [data-theme="dark"] .badge.bg-light { background-color: var(--border-color) !important; color: var(--text-primary) !important; }

        /* ===== Dark Mode Toggle Button ===== */
        .btn-theme-toggle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: none;
            color: var(--text-secondary);
            font-size: 17px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            position: relative;
        }
        .btn-theme-toggle:hover { background: var(--bg-main); color: var(--primary); }
        [data-theme="dark"] .btn-theme-toggle .icon-sun { display: block; }
        [data-theme="dark"] .btn-theme-toggle .icon-moon { display: none; }
        [data-theme="light"] .btn-theme-toggle .icon-sun { display: none; }
        [data-theme="light"] .btn-theme-toggle .icon-moon { display: block; }

        /* ===== Global Search Overlay ===== */
        .search-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9998;
            align-items: flex-start;
            justify-content: center;
            padding-top: 80px;
        }
        .search-overlay.open { display: flex; }

        .search-panel {
            width: 100%;
            max-width: 620px;
            background: var(--bg-white);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            animation: searchPanelIn 0.2s cubic-bezier(0.4, 0, 0.2, 1) both;
        }
        @keyframes searchPanelIn {
            from { opacity: 0; transform: translateY(-16px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .search-panel-header {
            display: flex;
            align-items: center;
            padding: 0 16px;
            border-bottom: 1px solid var(--border-color);
            background: var(--bg-white);
        }
        .search-panel-header i { color: var(--text-muted); font-size: 16px; margin-right: 12px; }
        .search-panel-input {
            flex: 1;
            height: 56px;
            border: none;
            outline: none;
            background: transparent;
            font-size: 16px;
            font-family: 'Inter', sans-serif;
            color: var(--text-primary);
        }
        .search-panel-input::placeholder { color: var(--text-muted); }
        .search-panel-close {
            background: none; border: none; color: var(--text-muted);
            cursor: pointer; padding: 8px; border-radius: 6px;
            font-size: 13px; transition: var(--transition);
        }
        .search-panel-close:hover { background: var(--bg-main); color: var(--text-secondary); }

        .search-panel-results { max-height: 360px; overflow-y: auto; }
        .search-result-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 20px; cursor: pointer;
            transition: var(--transition); text-decoration: none; color: inherit;
        }
        .search-result-item:hover { background: var(--primary-light); }
        .search-result-icon {
            width: 36px; height: 36px; border-radius: var(--radius);
            background: var(--primary-light); color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; flex-shrink: 0;
        }
        .search-result-text { flex: 1; min-width: 0; }
        .search-result-title { font-size: 14px; font-weight: 500; color: var(--text-primary); }
        .search-result-sub { font-size: 12px; color: var(--text-muted); }
        .search-no-results {
            padding: 32px 20px; text-align: center;
            color: var(--text-muted); font-size: 14px;
        }
        .search-shortcuts {
            display: flex; align-items: center; justify-content: flex-end;
            gap: 16px; padding: 10px 20px;
            border-top: 1px solid var(--border-color);
            background: var(--bg-main);
        }
        .search-shortcut-item {
            display: flex; align-items: center; gap: 6px;
            font-size: 11px; color: var(--text-muted);
        }
        .search-shortcut-item kbd {
            background: var(--bg-white); border: 1px solid var(--border-color);
            border-radius: 4px; padding: 1px 5px; font-size: 10px;
            color: var(--text-secondary); font-family: inherit;
        }

        /* ===== Notifications Dropdown ===== */
        .notif-dropdown {
            width: 360px !important;
            max-width: 100vw;
            padding: 0 !important;
        }
        .notif-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 16px; border-bottom: 1px solid var(--border-color);
        }
        .notif-header h6 { margin: 0; font-size: 15px; font-weight: 600; }
        .notif-mark-all {
            font-size: 12px; color: var(--primary); cursor: pointer;
            background: none; border: none; padding: 0; font-weight: 500;
        }
        .notif-mark-all:hover { text-decoration: underline; }
        .notif-list { max-height: 320px; overflow-y: auto; }
        .notif-item {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 12px 16px; transition: var(--transition); cursor: pointer;
            border-bottom: 1px solid var(--border-color);
        }
        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background: var(--primary-light); }
        .notif-item.unread { background: rgba(26,115,232,0.04); }
        .notif-item-icon {
            width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 14px;
        }
        .notif-item-text { flex: 1; min-width: 0; }
        .notif-item-title { font-size: 13px; font-weight: 500; color: var(--text-primary); line-height: 1.4; }
        .notif-item-time { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
        .notif-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--primary); flex-shrink: 0; margin-top: 5px;
        }
        .notif-empty {
            padding: 32px 16px; text-align: center;
            color: var(--text-muted); font-size: 13px;
        }
        .notif-footer {
            padding: 10px 16px; text-align: center;
            border-top: 1px solid var(--border-color);
        }
        .notif-footer a { font-size: 13px; font-weight: 500; color: var(--primary); text-decoration: none; }
        .notif-footer a:hover { text-decoration: underline; }

        .topbar-notif-btn {
            width: 40px; height: 40px; border-radius: 50%; border: none; background: none;
            color: var(--text-secondary); font-size: 18px; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: var(--transition); position: relative;
        }
        .topbar-notif-btn:hover { background: var(--bg-main); }
        .notif-badge {
            position: absolute; top: 6px; right: 6px;
            min-width: 16px; height: 16px; border-radius: 8px;
            background: #ea4335; color: #fff; font-size: 9px; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--bg-white); padding: 0 3px;
        }
        .notif-badge.d-none { display: none !important; }

        /* ===== Responsive ===== */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: var(--shadow-md); }
            .main-content { margin-left: 0; }
            .topbar-hamburger { display: flex; }
            .topbar-search { display: none; }
            .notif-dropdown { width: 320px !important; }
        }
        @media (min-width: 992px) {
            .sidebar-overlay { display: none !important; }
        }
    </style>
</head>
<body>
    <!-- Page Loader -->
    <div id="pageLoader" class="loading"></div>

    <!-- Search Overlay -->
    <div class="search-overlay" id="searchOverlay" role="dialog" aria-modal="true" aria-label="Recherche globale">
        <div class="search-panel" id="searchPanel">
            <div class="search-panel-header">
                <i class="fas fa-search"></i>
                <input type="text" class="search-panel-input" id="searchPanelInput" placeholder="Rechercher un utilisateur, une réunion, un document..." autocomplete="off" spellcheck="false">
                <button class="search-panel-close" id="searchPanelClose" title="Fermer (Échap)"><kbd>Échap</kbd></button>
            </div>
            <div class="search-panel-results" id="searchPanelResults">
                <div class="search-no-results" id="searchPanelEmpty" style="display:none">
                    <i class="fas fa-magnifying-glass fa-2x mb-2 d-block opacity-25"></i>
                    Aucun résultat trouvé
                </div>
            </div>
            <div class="search-shortcuts">
                <div class="search-shortcut-item"><kbd>↑</kbd><kbd>↓</kbd> <span>Naviguer</span></div>
                <div class="search-shortcut-item"><kbd>Entrée</kbd> <span>Ouvrir</span></div>
                <div class="search-shortcut-item"><kbd>Échap</kbd> <span>Fermer</span></div>
            </div>
        </div>
    </div>

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
                <span class="sidebar-link-badge d-none" id="globalChatUnreadBadge">0</span>
            </a>
            <a href="<?php echo site_url('reunions'); ?>" class="sidebar-link" data-nav>
                <i class="fas fa-calendar-check"></i> <span>Réunions</span>
            </a>
            <a href="<?php echo site_url('documents'); ?>" class="sidebar-link" data-nav>
                <i class="fas fa-folder-open"></i> <span>Documents</span>
            </a>
            <a href="<?php echo site_url('visioconference/recordings'); ?>" class="sidebar-link" data-nav>
                <i class="fas fa-circle-dot"></i> <span>Enregistrements</span>
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
                <?php
                    $safe_photo_sb = isset($session->photo_profil) ? basename($session->photo_profil) : '';
                    $has_photo_sb  = $safe_photo_sb && file_exists(FCPATH . 'assets/img/avatar/' . $safe_photo_sb);
                    $sb_initials   = mb_strtoupper(
                        mb_substr(isset($session->users_prenom) ? $session->users_prenom : 'U', 0, 1) .
                        mb_substr(isset($session->users_nom)    ? $session->users_nom    : '', 0, 1)
                    );
                ?>
                <?php if ($has_photo_sb): ?>
                    <img src="<?php echo base_url('assets/img/avatar/' . htmlspecialchars($safe_photo_sb, ENT_QUOTES, 'UTF-8')); ?>"
                         alt="Avatar" class="sidebar-user-avatar">
                <?php else: ?>
                    <div class="sidebar-user-avatar d-flex align-items-center justify-content-center fw-semibold"
                         style="background: var(--primary-light); color: var(--primary); font-size: 13px; border: 2px solid var(--border-color);">
                        <?php echo htmlspecialchars($sb_initials, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
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
                    <input type="text" class="topbar-search-input" id="globalSearch" placeholder="Rechercher... (Ctrl+K)" autocomplete="off" readonly style="cursor:pointer;" onfocus="this.blur(); openSearchOverlay();">
                </div>
            </div>

            <div class="topbar-actions">
                <!-- Dark Mode Toggle -->
                <button class="btn-theme-toggle" id="themeToggle" type="button" title="Changer le thème" aria-label="Basculer le mode sombre">
                    <i class="fas fa-moon icon-moon"></i>
                    <i class="fas fa-sun icon-sun"></i>
                </button>

                <!-- Notifications -->
                <div class="dropdown">
                    <button class="topbar-notif-btn" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" title="Notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notif-badge d-none" id="notifBadge">0</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end notif-dropdown" id="notifDropdown">
                        <div class="notif-header">
                            <h6><i class="fas fa-bell me-2 text-primary"></i>Notifications</h6>
                            <button class="notif-mark-all" id="markAllRead" type="button">Tout marquer lu</button>
                        </div>
                        <div class="notif-list" id="notifList">
                            <div class="notif-empty" id="notifEmpty">
                                <i class="fas fa-bell-slash fa-2x mb-2 d-block opacity-25"></i>
                                Aucune notification
                            </div>
                        </div>
                        <div class="notif-footer">
                            <a href="<?php echo site_url('chat'); ?>">Voir tous les messages <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

                <div class="dropdown">
                    <?php
                        $safe_photo_topbar = isset($session->photo_profil) ? basename($session->photo_profil) : '';
                        $has_photo_topbar  = $safe_photo_topbar && file_exists(FCPATH . 'assets/img/avatar/' . $safe_photo_topbar);
                        $topbar_initials   = '';
                        if (!$has_photo_topbar) {
                            $topbar_initials = mb_strtoupper(
                                mb_substr(isset($session->users_prenom) ? $session->users_prenom : 'U', 0, 1) .
                                mb_substr(isset($session->users_nom)    ? $session->users_nom    : '', 0, 1)
                            );
                        }
                    ?>
                    <?php if ($has_photo_topbar): ?>
                        <img src="<?php echo base_url('assets/img/avatar/' . htmlspecialchars($safe_photo_topbar, ENT_QUOTES, 'UTF-8')); ?>"
                             alt="Avatar" class="topbar-avatar dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                    <?php else: ?>
                        <div class="topbar-avatar dropdown-toggle d-flex align-items-center justify-content-center fw-semibold"
                             style="background: var(--primary-light); color: var(--primary); font-size: 12px; border: 2px solid transparent; cursor:pointer;"
                             data-bs-toggle="dropdown" aria-expanded="false" role="button">
                            <?php echo htmlspecialchars($topbar_initials, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-2">
                            <div class="fw-semibold" style="font-size:14px">
                                <?php echo isset($session->users_prenom) ? htmlspecialchars($session->users_prenom, ENT_QUOTES, 'UTF-8') : ''; ?>
                                <?php echo isset($session->users_nom) ? htmlspecialchars($session->users_nom, ENT_QUOTES, 'UTF-8') : ''; ?>
                            </div>
                            <div style="font-size:12px;color:var(--text-muted)">
                                <?php echo isset($session->users_email) ? htmlspecialchars($session->users_email, ENT_QUOTES, 'UTF-8') : ''; ?>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo site_url('users/edit'); ?>"><i class="fas fa-gear"></i> Paramètres du compte</a></li>
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
