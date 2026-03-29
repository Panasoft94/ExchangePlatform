<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-semibold mb-1" style="color: var(--text-primary);">
            <i class="fas fa-user-plus me-2" style="color: var(--primary);"></i>Créer un nouveau compte
        </h1>
        <p class="mb-0 small" style="color: var(--text-secondary);">Remplissez les informations pour créer un compte utilisateur</p>
    </div>
    <a href="<?php echo site_url('users'); ?>" class="btn btn-outline-secondary btn-sm mt-2 mt-md-0">
        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
    </a>
</div>

<!-- Form Card -->
<div class="card" style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
    <div class="card-body p-4">
        <?php echo form_open('', array('class' => 'needs-validation')); ?>

        <div class="row g-4">
            <!-- Username -->
            <div class="col-md-6">
                <label for="users_username" class="form-label fw-medium" style="color: var(--text-primary); font-size: 14px;">
                    Nom d'utilisateur <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text" style="background: var(--bg-main); border-color: var(--border-color);"><i class="fas fa-at" style="color: var(--text-secondary);"></i></span>
                    <input type="text" name="users_username" id="users_username"
                           class="form-control" style="border-color: var(--border-color);"
                           value="<?php echo set_value('users_username'); ?>"
                           placeholder="Entrez le nom d'utilisateur" required>
                </div>
                <div class="text-danger small mt-1"><?php echo form_error('users_username'); ?></div>
            </div>

            <!-- Email -->
            <div class="col-md-6">
                <label for="users_email" class="form-label fw-medium" style="color: var(--text-primary); font-size: 14px;">
                    Adresse e-mail <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text" style="background: var(--bg-main); border-color: var(--border-color);"><i class="fas fa-envelope" style="color: var(--text-secondary);"></i></span>
                    <input type="email" name="users_email" id="users_email"
                           class="form-control" style="border-color: var(--border-color);"
                           value="<?php echo set_value('users_email'); ?>"
                           placeholder="Entrez l'adresse e-mail" required>
                </div>
                <div class="text-danger small mt-1"><?php echo form_error('users_email'); ?></div>
            </div>

            <!-- Last Name -->
            <div class="col-md-6">
                <label for="users_nom" class="form-label fw-medium" style="color: var(--text-primary); font-size: 14px;">
                    Nom de famille <span class="text-danger">*</span>
                </label>
                <input type="text" name="users_nom" id="users_nom"
                       class="form-control" style="border-color: var(--border-color);"
                       value="<?php echo set_value('users_nom'); ?>"
                       placeholder="Entrez le nom de famille" required>
                <div class="text-danger small mt-1"><?php echo form_error('users_nom'); ?></div>
            </div>

            <!-- First Name -->
            <div class="col-md-6">
                <label for="users_prenom" class="form-label fw-medium" style="color: var(--text-primary); font-size: 14px;">
                    Prénom(s) <span class="text-danger">*</span>
                </label>
                <input type="text" name="users_prenom" id="users_prenom"
                       class="form-control" style="border-color: var(--border-color);"
                       value="<?php echo set_value('users_prenom'); ?>"
                       placeholder="Entrez le prénom" required>
                <div class="text-danger small mt-1"><?php echo form_error('users_prenom'); ?></div>
            </div>

            <!-- Role -->
            <div class="col-md-6">
                <label for="users_role" class="form-label fw-medium" style="color: var(--text-primary); font-size: 14px;">
                    Fonction ou rôle
                </label>
                <input type="text" name="users_role" id="users_role"
                       class="form-control" style="border-color: var(--border-color);"
                       value="<?php echo set_value('users_role'); ?>"
                       placeholder="Entrez le rôle ou la fonction">
                <div class="text-danger small mt-1"><?php echo form_error('users_role'); ?></div>
            </div>

            <!-- Group Selection -->
            <div class="col-12">
                <label class="form-label fw-medium" style="color: var(--text-primary); font-size: 14px;">
                    Groupes d'accès <span class="text-danger">*</span>
                </label>
                <div class="row g-3">
                    <?php foreach($liste_group as $group): ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background: var(--bg-main); border: 1px solid var(--border-color);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shield-halved me-2" style="color: var(--primary);"></i>
                                <span class="fw-medium" style="color: var(--text-primary); font-size: 14px;"><?php echo htmlspecialchars($group->group_name); ?></span>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox"
                                       id="group_<?php echo (int)$group->group_id; ?>"
                                       name="group_ids[]"
                                       value="<?php echo (int)$group->group_id; ?>">
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="d-flex justify-content-end gap-2 mt-4 pt-3" style="border-top: 1px solid var(--border-color);">
            <a href="<?php echo site_url('users'); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-times me-1"></i> Annuler
            </a>
            <?php echo form_submit('submit', 'Créer le compte', array('class' => 'btn btn-primary')); ?>
        </div>

        <?php echo form_close(); ?>
    </div>
</div>