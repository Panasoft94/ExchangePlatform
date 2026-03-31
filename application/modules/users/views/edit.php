<?php $session = $this->session->userdata('users'); ?>

<style>
.profile-hero {
    background: linear-gradient(135deg, var(--primary) 0%, #1557b0 100%);
    border-radius: var(--radius-lg);
    padding: 2rem 2rem 4rem;
    position: relative;
    overflow: hidden;
    margin-bottom: 3.5rem;
}
.profile-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
}
.profile-hero::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -10%;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: rgba(255,255,255,0.03);
}
.profile-hero .hero-content {
    position: relative;
    z-index: 2;
}
.profile-avatar-wrapper {
    position: absolute;
    bottom: -40px;
    left: 2rem;
    z-index: 5;
}
.profile-avatar-large {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    border: 4px solid var(--bg-white);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    object-fit: cover;
    background: var(--primary-light);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary);
}
.profile-avatar-large img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}
.profile-info-bar {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    margin-top: -1px;
    padding: 1rem 2rem 1rem 8.5rem;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    min-height: 64px;
}
@media (max-width: 768px) {
    .profile-info-bar {
        padding: 3.5rem 1.5rem 1rem;
        flex-direction: column;
        align-items: flex-start;
    }
    .profile-avatar-wrapper {
        left: 1.5rem;
    }
}
.profile-stat-item {
    text-align: center;
    padding: 0 1rem;
}
.profile-stat-item + .profile-stat-item {
    border-left: 1px solid var(--border-color);
}
@media (max-width: 768px) {
    .profile-stat-item + .profile-stat-item {
        border-left: none;
    }
}
.profile-edit-card {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.profile-edit-card .card-section {
    padding: 1.5rem 2rem;
}
.profile-edit-card .card-section + .card-section {
    border-top: 1px solid var(--border-color);
}
.profile-edit-card .section-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.profile-edit-card .section-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    flex-shrink: 0;
}
.profile-edit-card .form-label {
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.375rem;
}
.profile-edit-card .form-control {
    border: 1.5px solid var(--border-color);
    border-radius: var(--radius);
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    transition: border-color 0.2s, box-shadow 0.2s;
    background: var(--bg-white);
    color: var(--text-primary);
}
.profile-edit-card .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(26,115,232,0.1);
}
.photo-upload-zone {
    border: 2px dashed var(--border-color);
    border-radius: var(--radius);
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: var(--bg-main);
}
.photo-upload-zone:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}
</style>

<?php
    $safe_photo = isset($users->photo_profil) ? basename($users->photo_profil) : '';
    $has_photo = ($safe_photo && file_exists('assets/img/avatar/' . $safe_photo));
    $initials = mb_strtoupper(mb_substr($users->users_prenom, 0, 1) . mb_substr($users->users_nom, 0, 1));
?>

<!-- Profile Hero Banner -->
<div class="profile-hero">
    <div class="hero-content">
        <a href="<?php echo site_url('home'); ?>" class="text-white text-decoration-none d-inline-flex align-items-center small mb-3" style="opacity: 0.8;">
            <i class="fas fa-arrow-left me-2"></i> Retour à l'accueil
        </a>
        <h2 class="text-white fw-bold mb-1"><?php echo htmlspecialchars($users->users_nom . ' ' . $users->users_prenom); ?></h2>
        <p class="text-white mb-0" style="opacity: 0.8; font-size: 0.9rem;">
            <i class="fas fa-at me-1"></i><?php echo htmlspecialchars($users->users_username); ?>
        </p>
    </div>
    <div class="profile-avatar-wrapper">
        <div class="profile-avatar-large">
            <?php if($has_photo): ?>
                <img src="<?php echo base_url('assets/img/avatar/' . htmlspecialchars($safe_photo)); ?>" alt="Photo de profil">
            <?php else: ?>
                <?php echo htmlspecialchars($initials); ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Profile Info Bar -->
<div class="profile-info-bar mb-4">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="badge rounded-pill" style="background: var(--primary-light); color: var(--primary); font-weight: 500; font-size: 0.8rem; padding: 6px 14px;">
            <i class="fas fa-briefcase me-1"></i><?php echo htmlspecialchars($users->users_role); ?>
        </span>
        <?php if($users->etat_online == 1): ?>
            <span class="badge rounded-pill" style="background: #e6f4ea; color: #188038; font-weight: 500; font-size: 0.8rem; padding: 6px 14px;">
                <i class="fas fa-circle me-1" style="font-size: 7px; vertical-align: middle;"></i> En ligne
            </span>
        <?php else: ?>
            <span class="badge rounded-pill" style="background: #f1f3f4; color: var(--text-secondary); font-weight: 500; font-size: 0.8rem; padding: 6px 14px;">
                <i class="fas fa-circle me-1" style="font-size: 7px; vertical-align: middle;"></i> Hors ligne
            </span>
        <?php endif; ?>
        <?php if($users->etat_compte == 1): ?>
            <span class="badge rounded-pill" style="background: #e6f4ea; color: #188038; font-weight: 500; font-size: 0.8rem; padding: 6px 14px;">
                <i class="fas fa-shield-check me-1"></i>Actif
            </span>
        <?php else: ?>
            <span class="badge rounded-pill" style="background: #fce8e6; color: #c5221f; font-weight: 500; font-size: 0.8rem; padding: 6px 14px;">
                <i class="fas fa-lock me-1"></i>Verrouillé
            </span>
        <?php endif; ?>
    </div>
    <div class="d-flex align-items-center gap-3 flex-wrap">
        <div class="profile-stat-item">
            <div class="small" style="color: var(--text-secondary);">E-mail</div>
            <div class="fw-medium" style="color: var(--text-primary); font-size: 0.875rem;">
                <?php echo htmlspecialchars($users->users_email); ?>
            </div>
        </div>
        <div class="profile-stat-item">
            <div class="small" style="color: var(--text-secondary);">Membre depuis</div>
            <div class="fw-medium" style="color: var(--text-primary); font-size: 0.875rem;">
                <?php echo isset($users->create_at) ? date('d/m/Y', strtotime($users->create_at)) : '—'; ?>
            </div>
        </div>
    </div>
</div>

<!-- Edit Form -->
<div class="profile-edit-card">
    <?php echo form_open_multipart('users/edit', array('class' => 'needs-validation')); ?>

    <!-- Section 1: Personal Info -->
    <div class="card-section">
        <div class="section-title">
            <div class="section-icon" style="background: var(--primary-light); color: var(--primary);">
                <i class="fas fa-user"></i>
            </div>
            Modifier mes informations
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="users_nom" class="form-label">Nom de famille <span class="text-danger">*</span></label>
                <input type="text" name="users_nom" id="users_nom" class="form-control"
                       value="<?php echo set_value('users_nom') ? set_value('users_nom') : htmlspecialchars($users->users_nom); ?>" required>
                <div class="text-danger small mt-1"><?php echo form_error('users_nom'); ?></div>
            </div>
            <div class="col-md-6">
                <label for="users_prenom" class="form-label">Prénom(s) <span class="text-danger">*</span></label>
                <input type="text" name="users_prenom" id="users_prenom" class="form-control"
                       value="<?php echo set_value('users_prenom') ? set_value('users_prenom') : htmlspecialchars($users->users_prenom); ?>" required>
                <div class="text-danger small mt-1"><?php echo form_error('users_prenom'); ?></div>
            </div>
        </div>
    </div>

    <!-- Section 2: Account Settings -->
    <div class="card-section">
        <div class="section-title">
            <div class="section-icon" style="background: #e6f4ea; color: #188038;">
                <i class="fas fa-key"></i>
            </div>
            Compte et sécurité
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="users_username" class="form-label">Nom d'utilisateur <span class="text-danger">*</span></label>
                <input type="text" name="users_username" id="users_username" class="form-control"
                       value="<?php echo set_value('users_username') ? set_value('users_username') : htmlspecialchars($users->users_username); ?>" required>
                <div class="text-danger small mt-1"><?php echo form_error('users_username'); ?></div>
            </div>
            <div class="col-md-6">
                <label for="users_password" class="form-label">Nouveau mot de passe</label>
                <input type="password" name="users_password" id="users_password" class="form-control"
                       placeholder="Laissez vide pour ne pas changer">
                <div class="text-danger small mt-1"><?php echo form_error('users_password'); ?></div>
            </div>
            <div class="col-md-6">
                <label for="users_email" class="form-label">Adresse e-mail <span class="text-danger">*</span></label>
                <input type="email" name="users_email" id="users_email" class="form-control"
                       value="<?php echo set_value('users_email') ? set_value('users_email') : htmlspecialchars($users->users_email); ?>">
                <div class="text-danger small mt-1"><?php echo form_error('users_email'); ?></div>
            </div>
        </div>
    </div>

    <!-- Section 3: Photo -->
    <div class="card-section">
        <div class="section-title">
            <div class="section-icon" style="background: #fef7e0; color: #ea8600;">
                <i class="fas fa-camera"></i>
            </div>
            Photo de profil
        </div>
        <div class="row align-items-center g-3">
            <div class="col-md-3 text-center">
                <?php if($has_photo): ?>
                    <img src="<?php echo base_url('assets/img/avatar/' . htmlspecialchars($safe_photo)); ?>"
                         class="rounded-circle" width="80" height="80"
                         style="object-fit: cover; border: 3px solid var(--primary-light);" alt="Photo actuelle">
                <?php else: ?>
                    <div class="d-inline-flex justify-content-center align-items-center rounded-circle"
                         style="width: 80px; height: 80px; background: var(--primary-light); color: var(--primary); font-size: 1.75rem; font-weight: 600;">
                        <?php echo htmlspecialchars($initials); ?>
                    </div>
                <?php endif; ?>
                <div class="small mt-2" style="color: var(--text-secondary);">Photo actuelle</div>
            </div>
            <div class="col-md-9">
                <label class="photo-upload-zone d-block mb-0">
                    <?php echo form_upload('photo_profil', '', array('class' => 'd-none', 'accept' => 'image/*', 'id' => 'photo_profil')); ?>
                    <i class="fas fa-cloud-arrow-up mb-2" style="font-size: 1.5rem; color: var(--primary);"></i>
                    <div class="fw-medium small" style="color: var(--text-primary);">Cliquez pour choisir une photo</div>
                    <div class="small" style="color: var(--text-secondary);">JPG, PNG ou GIF • Max 2 Mo</div>
                </label>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="card-section" style="background: var(--bg-main);">
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?php echo site_url('home'); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-times me-1"></i> Annuler
            </a>
            <button type="submit" name="submit" class="btn btn-primary">
                <i class="fas fa-check me-1"></i> Enregistrer les modifications
            </button>
        </div>
    </div>

    <?php echo form_close(); ?>
</div>