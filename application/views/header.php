<?php
	$session = $this->session->userdata('users');
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Plateforme d'Échange Professionnel</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles for Dark Theme -->
    <style>
        :root {
            --bs-body-bg: #121212;
            --bs-body-color: #e0e0e0;
            --primary-blue: #007BFF;
            --sidebar-bg: #1e1e1e;
            --sidebar-hover: #2c2c2c;
        }
        body {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            display: flex;
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            border-right: 1px solid #333;
            transition: all 0.3s;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1000;
        }
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #333;
        }
        .sidebar-header h4 {
            margin: 0;
            font-weight: 600;
            color: var(--primary-blue);
        }
        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
            overflow-y: auto;
        }
        .nav-links li {
            border-bottom: 1px solid #2a2a2a;
        }
        .nav-links li a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #b0b0b0;
            text-decoration: none;
            transition: background 0.3s, color 0.3s;
            font-size: 15px;
        }
        .nav-links li a i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
            font-size: 18px;
        }
        .nav-links li a:hover, .nav-links li a.active {
            background-color: var(--sidebar-hover);
            color: var(--primary-blue);
        }
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        /* Main Content */
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            margin-left: 250px;
            width: calc(100% - 250px);
        }
        .topbar {
            background-color: var(--sidebar-bg);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #333;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .content-area {
            padding: 20px;
            flex-grow: 1;
        }
        /* User Profile in Sidebar */
        .user-profile {
            padding: 15px;
            display: flex;
            align-items: center;
            border-top: 1px solid #333;
            margin-top: auto;
            background-color: #1a1a1a;
        }
        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 15px;
            border: 2px solid var(--primary-blue);
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-network-wired"></i> Exchange Platform</h4>
        </div>
        <ul class="nav-links">
            <li><a href="<?php echo base_url();?>"><i class="fas fa-home"></i> Accueil</a></li>
            <li><a href="<?php echo site_url('users/edit');?>"><i class="fas fa-cog"></i> Paramètres</a></li>
            <li><a href="<?php echo site_url('users');?>"><i class="fas fa-users"></i> Comptes</a></li>
            <li><a href="<?php echo site_url('chat');?>"><i class="fas fa-comments"></i> Messagerie & Chat</a></li>
            <li><a href="<?php echo site_url('reunions');?>"><i class="fas fa-calendar-alt"></i> Réunion</a></li>
            <li><a href="<?php echo site_url('visioconference');?>"><i class="fas fa-video"></i> Visioconférence</a></li>
            <li><a href="<?php echo site_url('documents');?>"><i class="fas fa-folder-open"></i> Documents</a></li>
        </ul>
        <div class="user-profile">
            <?php if(isset($session->photo_profil) && file_exists('assets/img/avatar/'.$session->photo_profil)):?>									
                <img src="<?php echo base_url('assets/img/avatar/'.$session->photo_profil);?>" alt="User"> 
            <?php else:?>												
                <img src="<?php echo base_url('assets/img/avatar/default.jpg');?>" alt="User"> 
            <?php endif;?>
            <div>
                <small class="d-block text-muted">Connecté en tant que</small>
                <strong><?php echo isset($session->users_username) ? strtoupper($session->users_username) : 'UTILISATEUR';?></strong>
                <a href="<?php echo site_url('users/logout');?>" class="text-danger ms-2" title="Déconnexion"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h5 class="mb-0"><i class="fas fa-bars me-2 text-primary"></i> Tableau de bord</h5>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle"></i> Mon Profil
                </button>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?php echo site_url('users/edit');?>"><i class="fas fa-cog me-2"></i>Paramètres</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?php echo site_url('users/logout');?>"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
                </ul>
            </div>
        </div>

        <div class="content-area">
            <div class="container-fluid">
                <!-- Flash Messages -->
                <?php
                if($this->session->flashdata('success')){
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'><i class='fas fa-check-circle me-2'></i>".$this->session->flashdata('success')."<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                }
                if($this->session->flashdata('info')){
                    echo "<div class='alert alert-info alert-dismissible fade show' role='alert'><i class='fas fa-info-circle me-2'></i>".$this->session->flashdata('info')."<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                }
                if($this->session->flashdata('error')){
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'><i class='fas fa-exclamation-circle me-2'></i>".$this->session->flashdata('error')."<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                }
                if($this->session->flashdata('warning')){
                    echo "<div class='alert alert-warning alert-dismissible fade show' role='alert'><i class='fas fa-exclamation-triangle me-2'></i>".$this->session->flashdata('warning')."<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
                }
                ?>
