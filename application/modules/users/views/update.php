<?php
    $safe_photo = isset($users->photo_profil) ? basename($users->photo_profil) : '';
    $has_photo = ($safe_photo && file_exists('assets/img/avatar/' . $safe_photo));
    $initials = mb_strtoupper(mb_substr($users->users_prenom, 0, 1) . mb_substr($users->users_nom, 0, 1));
?>

<style>
.update-user-sidebar {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.update-user-sidebar .sidebar-header {
    background: linear-gradient(135deg, var(--primary) 0%, #1557b0 100%);
    padding: 2rem 1.5rem;
    text-align: center;
    position: relative;
}
.update-user-sidebar .sidebar-header::before {
    content: '';
    position: absolute;
    top: -30%;
    right: -20%;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
}
.update-user-sidebar .sidebar-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.3);
    object-fit: cover;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem;
    font-size: 1.5rem;
    font-weight: 700;
    color: #fff;
    background: rgba(255,255,255,0.15);
    position: relative;
    z-index: 2;
}
.update-user-sidebar .sidebar-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}
.update-user-sidebar .sidebar-info {
    padding: 1.25rem 1.5rem;
}
.update-user-sidebar .info-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem 0;
}
.update-user-sidebar .info-item + .info-item {
    border-top: 1px solid var(--border-color);
}
.update-user-sidebar .info-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 0.75rem;
}
.update-form-card {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.update-form-card .card-section {
    padding: 1.5rem 2rem;
}
.update-form-card .card-section + .card-section {
    border-top: 1px solid var(--border-color);
}
.update-form-card .section-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.update-form-card .section-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    flex-shrink: 0;
}
.update-form-card .form-label {
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.375rem;
}
.update-form-card .form-control {
    border: 1.5px solid var(--border-color);
    border-radius: var(--radius);
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    transition: border-color 0.2s, box-shadow 0.2s;
    background: var(--bg-white);
    color: var(--text-primary);
}
.update-form-card .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(26,115,232,0.1);
}
.update-form-card .input-group-text {
    background: var(--bg-main);
    border: 1.5px solid var(--border-color);
    color: var(--text-secondary);
    font-size: 0.85rem;
}
.update-group-card {
    background: var(--bg-main);
    border: 1.5px solid var(--border-color);
    border-radius: var(--radius);
    padding: 0.875rem 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.2s;
    cursor: pointer;
}
.update-group-card:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}
.update-group-card:has(input:checked) {
    border-color: var(--primary);
    background: var(--primary-light);
}
.update-group-card .form-check-input:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}
</style>

<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1" style="color: var(--text-primary);">
            <i class="fas fa-user-pen me-2" style="color: var(--primary);"></i>Modifier l'utilisateur
        </h1>
        <p class="mb-0 small" style="color: var(--text-secondary);">
            Modification du compte de <strong><?php echo htmlspecialchars($users->users_nom . ' ' . $users->users_prenom); ?></strong>
        </p>
    </div>
    <a href="<?php echo site_url('users'); ?>" class="btn btn-outline-secondary btn-sm mt-2 mt-md-0">
        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
    </a>
</div>

<div class="row g-4">
    <!-- Left Sidebar: User Info -->
    <div class="col-lg-4">
        <div class="update-user-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-avatar">
                    <?php if($has_photo): ?>
                        <img src="<?php echo base_url('assets/img/avatar/' . htmlspecialchars($safe_photo)); ?>" alt="Avatar">
                    <?php else: ?>
                        <?php echo htmlspecialchars($initials); ?>
                    <?php endif; ?>
                </div>
                <h6 class="text-white fw-semibold mb-1"><?php echo htmlspecialchars($users->users_nom . ' ' . $users->users_prenom); ?></h6>
                <span class="badge rounded-pill" style="background: rgba(255,255,255,0.2); color: #fff; font-size: 0.75rem;">
                    <?php echo htmlspecialchars($users->users_role); ?>
                </span>
            </div>
            <div class="sidebar-info">
                <div class="info-item">
                    <div class="info-icon" style="background: var(--primary-light); color: var(--primary);">
                        <i class="fas fa-at"></i>
                    </div>
                    <div>
                        <div class="small" style="color: var(--text-secondary);">Nom d'utilisateur</div>
                        <div class="fw-medium" style="color: var(--text-primary); font-size: 0.875rem;"><?php echo htmlspecialchars($users->users_username); ?></div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon" style="background: #e6f4ea; color: #188038;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <div class="small" style="color: var(--text-secondary);">E-mail</div>
                        <div class="fw-medium" style="color: var(--text-primary); font-size: 0.875rem;"><?php echo htmlspecialchars($users->users_email); ?></div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon" style="background: #fef7e0; color: #ea8600;">
                        <i class="fas fa-circle-dot"></i>
                    </div>
                    <div>
                        <div class="small" style="color: var(--text-secondary);">Statut</div>
                        <div>
                            <?php if($users->etat_online == 1): ?>
                                <span class="badge rounded-pill" style="background: #e6f4ea; color: #188038; font-size: 0.75rem;">En ligne</span>
                            <?php else: ?>
                                <span class="badge rounded-pill" style="background: #f1f3f4; color: var(--text-secondary); font-size: 0.75rem;">Hors ligne</span>
                            <?php endif; ?>
                            <?php if($users->etat_compte == 1): ?>
                                <span class="badge rounded-pill" style="background: #e6f4ea; color: #188038; font-size: 0.75rem;">Actif</span>
                            <?php else: ?>
                                <span class="badge rounded-pill" style="background: #fce8e6; color: #c5221f; font-size: 0.75rem;">Verrouillé</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon" style="background: #fce8f4; color: #9c27b0;">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div>
                        <div class="small" style="color: var(--text-secondary);">Membre depuis</div>
                        <div class="fw-medium" style="color: var(--text-primary); font-size: 0.875rem;">
                            <?php echo isset($users->create_at) ? date('d/m/Y', strtotime($users->create_at)) : '—'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Edit Form -->
    <div class="col-lg-8">
        <div class="update-form-card">
            <?php echo form_open('', array('class' => 'needs-validation')); ?>

            <!-- Section 1: Personal Info -->
            <div class="card-section">
                <div class="section-title">
                    <div class="section-icon" style="background: var(--primary-light); color: var(--primary);">
                        <i class="fas fa-user"></i>
                    </div>
                    Informations personnelles
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
                    <div class="col-md-6">
                        <label for="users_role" class="form-label">Fonction ou rôle</label>
                        <input type="text" name="users_role" id="users_role" class="form-control"
                               value="<?php echo set_value('users_role') ? set_value('users_role') : htmlspecialchars($users->users_role); ?>">
                        <div class="text-danger small mt-1"><?php echo form_error('users_role'); ?></div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Account -->
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
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                            <input type="text" name="users_username" id="users_username" class="form-control"
                                   value="<?php echo set_value('users_username') ? set_value('users_username') : htmlspecialchars($users->users_username); ?>" required>
                        </div>
                        <div class="text-danger small mt-1"><?php echo form_error('users_username'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <label for="users_password" class="form-label">Nouveau mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="users_password" id="users_password" class="form-control"
                                   value="<?php echo set_value('users_password'); ?>"
                                   placeholder="Laissez vide pour ne pas changer">
                        </div>
                        <div class="text-danger small mt-1"><?php echo form_error('users_password'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <label for="users_email" class="form-label">Adresse e-mail <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="users_email" id="users_email" class="form-control"
                                   value="<?php echo set_value('users_email') ? set_value('users_email') : htmlspecialchars($users->users_email); ?>" required>
                        </div>
                        <div class="text-danger small mt-1"><?php echo form_error('users_email'); ?></div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Groups -->
            <div class="card-section">
                <div class="section-title">
                    <div class="section-icon" style="background: #fef7e0; color: #ea8600;">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    Groupes d'accès
                </div>
                <div class="row g-3">
                    <?php foreach($liste_group as $group): ?>
                    <div class="col-md-6">
                        <label for="group_<?php echo (int)$group->group_id; ?>" class="d-block mb-0">
                            <div class="update-group-card">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; flex-shrink: 0;">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <span class="fw-medium" style="color: var(--text-primary); font-size: 0.875rem;"><?php echo htmlspecialchars($group->group_name); ?></span>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox"
                                           id="group_<?php echo (int)$group->group_id; ?>"
                                           name="group_ids[]"
                                           value="<?php echo (int)$group->group_id; ?>"
                                           <?php echo in_array($group->group_id, $liste_users_group) ? 'checked' : ''; ?>>
                                </div>
                            </div>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Actions -->
            <div class="card-section" style="background: var(--bg-main);">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="<?php echo site_url('users'); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Annuler
                    </a>
                    <button type="submit" name="submit" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i> Enregistrer les modifications
                    </button>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>