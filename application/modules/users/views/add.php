<style>
.add-user-card {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.add-user-card .card-section {
    padding: 1.5rem 2rem;
}
.add-user-card .card-section + .card-section {
    border-top: 1px solid var(--border-color);
}
.add-user-card .section-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.add-user-card .section-title .section-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    flex-shrink: 0;
}
.add-user-card .form-label {
    font-size: 0.8125rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.375rem;
}
.add-user-card .form-control,
.add-user-card .form-select {
    border: 1.5px solid var(--border-color);
    border-radius: var(--radius);
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    transition: border-color 0.2s, box-shadow 0.2s;
    background: var(--bg-white);
    color: var(--text-primary);
}
.add-user-card .form-control:focus,
.add-user-card .form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(26,115,232,0.1);
}
.add-user-card .input-group-text {
    background: var(--bg-main);
    border: 1.5px solid var(--border-color);
    color: var(--text-secondary);
    font-size: 0.85rem;
}
.group-selection-card {
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
.group-selection-card:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}
.group-selection-card:has(input:checked) {
    border-color: var(--primary);
    background: var(--primary-light);
}
.group-selection-card .group-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: var(--primary-light);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    flex-shrink: 0;
}
.add-user-card .form-check-input:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}
.add-user-default-pass {
    background: #fef7e0;
    border: 1px solid #fdd835;
    border-radius: var(--radius);
    padding: 0.75rem 1rem;
    font-size: 0.8125rem;
    color: #856404;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
</style>

<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1" style="color: var(--text-primary);">
            <i class="fas fa-user-plus me-2" style="color: var(--primary);"></i>Nouvel Utilisateur
        </h1>
        <p class="mb-0 small" style="color: var(--text-secondary);">Créez un nouveau compte utilisateur sur la plateforme</p>
    </div>
    <a href="<?php echo site_url('users'); ?>" class="btn btn-outline-secondary btn-sm mt-2 mt-md-0">
        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="add-user-card">
            <?php echo form_open('', array('class' => 'needs-validation')); ?>

            <!-- Section 1: Identité -->
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
                        <input type="text" name="users_nom" id="users_nom"
                               class="form-control"
                               value="<?php echo set_value('users_nom'); ?>"
                               placeholder="Entrez le nom" required>
                        <div class="text-danger small mt-1"><?php echo form_error('users_nom'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <label for="users_prenom" class="form-label">Prénom(s) <span class="text-danger">*</span></label>
                        <input type="text" name="users_prenom" id="users_prenom"
                               class="form-control"
                               value="<?php echo set_value('users_prenom'); ?>"
                               placeholder="Entrez le prénom" required>
                        <div class="text-danger small mt-1"><?php echo form_error('users_prenom'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <label for="users_role" class="form-label">Fonction ou rôle</label>
                        <input type="text" name="users_role" id="users_role"
                               class="form-control"
                               value="<?php echo set_value('users_role'); ?>"
                               placeholder="Ex: Chef de service, Agent...">
                        <div class="text-danger small mt-1"><?php echo form_error('users_role'); ?></div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Accès -->
            <div class="card-section">
                <div class="section-title">
                    <div class="section-icon" style="background: #e6f4ea; color: #188038;">
                        <i class="fas fa-key"></i>
                    </div>
                    Informations de connexion
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="users_username" class="form-label">Nom d'utilisateur <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                            <input type="text" name="users_username" id="users_username"
                                   class="form-control"
                                   value="<?php echo set_value('users_username'); ?>"
                                   placeholder="Nom d'utilisateur unique" required>
                        </div>
                        <div class="text-danger small mt-1"><?php echo form_error('users_username'); ?></div>
                    </div>
                    <div class="col-md-6">
                        <label for="users_email" class="form-label">Adresse e-mail <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="users_email" id="users_email"
                                   class="form-control"
                                   value="<?php echo set_value('users_email'); ?>"
                                   placeholder="adresse@email.com" required>
                        </div>
                        <div class="text-danger small mt-1"><?php echo form_error('users_email'); ?></div>
                    </div>
                    <div class="col-12">
                        <div class="add-user-default-pass">
                            <i class="fas fa-info-circle"></i>
                            <span>Le mot de passe par défaut sera attribué automatiquement. L'utilisateur pourra le modifier depuis son profil.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Groupes -->
            <div class="card-section">
                <div class="section-title">
                    <div class="section-icon" style="background: #fef7e0; color: #ea8600;">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    Groupes d'accès <span class="text-danger">*</span>
                </div>
                <div class="row g-3">
                    <?php foreach($liste_group as $group): ?>
                    <div class="col-md-6">
                        <label for="group_<?php echo (int)$group->group_id; ?>" class="d-block mb-0">
                            <div class="group-selection-card">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="group-icon">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <span class="fw-medium" style="color: var(--text-primary); font-size: 0.875rem;"><?php echo htmlspecialchars($group->group_name); ?></span>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox"
                                           id="group_<?php echo (int)$group->group_id; ?>"
                                           name="group_ids[]"
                                           value="<?php echo (int)$group->group_id; ?>">
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
                        <i class="fas fa-user-plus me-1"></i> Créer le compte
                    </button>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>