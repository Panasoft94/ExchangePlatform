<?php $session = $this->session->userdata('users'); ?>

<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-semibold mb-1" style="color: var(--text-primary);">
            <i class="fas fa-user-circle me-2" style="color: var(--primary);"></i>Mon Profil
        </h1>
        <p class="mb-0 small" style="color: var(--text-secondary);">Consultez et mettez à jour vos informations personnelles</p>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Profile Card -->
    <div class="col-lg-4">
        <div class="card" style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
            <div class="card-body text-center p-4">
                <!-- Avatar -->
                <div class="mb-3">
                    <?php
                        $safe_photo = basename($users->photo_profil);
                    ?>
                    <?php if($safe_photo && file_exists('assets/img/avatar/' . $safe_photo)): ?>
                        <img src="<?php echo base_url('assets/img/avatar/' . htmlspecialchars($safe_photo)); ?>"
                             class="rounded-circle" width="100" height="100"
                             style="object-fit: cover; border: 3px solid var(--primary-light);"
                             alt="Photo de profil">
                    <?php else: ?>
                        <?php $initials = mb_strtoupper(mb_substr($users->users_prenom, 0, 1) . mb_substr($users->users_nom, 0, 1)); ?>
                        <div class="d-inline-flex justify-content-center align-items-center rounded-circle mx-auto"
                             style="width: 100px; height: 100px; background: var(--primary-light); color: var(--primary); font-size: 32px; font-weight: 600;">
                            <?php echo htmlspecialchars($initials); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <h5 class="fw-semibold mb-1" style="color: var(--text-primary);">
                    <?php echo htmlspecialchars($users->users_nom . ' ' . $users->users_prenom); ?>
                </h5>
                <p class="mb-3">
                    <span class="badge rounded-pill" style="background: var(--primary-light); color: var(--primary); font-weight: 500;">
                        <?php echo htmlspecialchars($users->users_role); ?>
                    </span>
                </p>

                <hr style="border-color: var(--border-color);">

                <!-- Info List -->
                <div class="text-start">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-envelope me-3" style="color: var(--text-secondary); width: 16px; text-align: center;"></i>
                        <div>
                            <div class="small" style="color: var(--text-secondary);">E-mail</div>
                            <div class="fw-medium" style="color: var(--text-primary); font-size: 14px;"><?php echo htmlspecialchars($users->users_email); ?></div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-circle-dot me-3" style="color: var(--text-secondary); width: 16px; text-align: center;"></i>
                        <div>
                            <div class="small" style="color: var(--text-secondary);">Statut</div>
                            <div>
                                <?php if($users->etat_online == 1): ?>
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success">
                                        <i class="fas fa-circle me-1" style="font-size: 7px; vertical-align: middle;"></i> En ligne
                                    </span>
                                <?php else: ?>
                                    <span class="badge rounded-pill" style="background: #f1f3f4; color: var(--text-secondary);">
                                        <i class="fas fa-circle me-1" style="font-size: 7px; vertical-align: middle;"></i> Hors ligne
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar me-3" style="color: var(--text-secondary); width: 16px; text-align: center;"></i>
                        <div>
                            <div class="small" style="color: var(--text-secondary);">Membre depuis</div>
                            <div class="fw-medium" style="color: var(--text-primary); font-size: 14px;"><?php echo htmlspecialchars($users->create_at); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Edit Form -->
    <div class="col-lg-8">
        <div class="card" style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
            <div class="card-header py-3 px-4" style="background: var(--bg-main); border-bottom: 1px solid var(--border-color);">
                <h6 class="fw-semibold mb-0" style="color: var(--text-primary);">
                    <i class="fas fa-pen-to-square me-2" style="color: var(--primary);"></i>Modifier mes informations
                </h6>
            </div>
            <div class="card-body p-4">
                <?php echo form_open_multipart('users/edit', array('class' => 'needs-validation')); ?>

                <div class="row g-4">
                    <!-- Username -->
                    <div class="col-md-6">
                        <label for="users_username" class="form-label fw-medium" style="color: var(--text-primary); font-size: 14px;">
                            Nom d'utilisateur <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="users_username" id="users_username"
                               class="form-control" style="border-color: var(--border-color);"
                               value="<?php echo set_value('users_username') ? set_value('users_username') : htmlspecialchars($users->users_username); ?>"
                               required>
                        <div class="text-danger small mt-1"><?php echo form_error('users_username'); ?></div>
                    </div>

                    <!-- Password -->
                    <div class="col-md-6">
                        <label for="users_password" class="form-label fw-medium" style="color: var(--text-primary); font-size: 14px;">
                            Nouveau mot de passe
                        </label>
                        <input type="password" name="users_password" id="users_password"
                               class="form-control" style="border-color: var(--border-color);"
                               placeholder="Laissez vide pour ne pas changer">
                        <div class="text-danger small mt-1"><?php echo form_error('users_password'); ?></div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="users_email" class="form-label fw-medium" style="color: var(--text-primary); font-size: 14px;">
                            Adresse e-mail <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="users_email" id="users_email"
                               class="form-control" style="border-color: var(--border-color);"
                               value="<?php echo set_value('users_email') ? set_value('users_email') : htmlspecialchars($users->users_email); ?>">
                        <div class="text-danger small mt-1"><?php echo form_error('users_email'); ?></div>
                    </div>

                    <!-- Last Name -->
                    <div class="col-md-6">
                        <label for="users_nom" class="form-label fw-medium" style="color: var(--text-primary); font-size: 14px;">
                            Nom de famille <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="users_nom" id="users_nom"
                               class="form-control" style="border-color: var(--border-color);"
                               value="<?php echo set_value('users_nom') ? set_value('users_nom') : htmlspecialchars($users->users_nom); ?>">
                        <div class="text-danger small mt-1"><?php echo form_error('users_nom'); ?></div>
                    </div>

                    <!-- First Name -->
                    <div class="col-md-6">
                        <label for="users_prenom" class="form-label fw-medium" style="color: var(--text-primary); font-size: 14px;">
                            Prénom(s) <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="users_prenom" id="users_prenom"
                               class="form-control" style="border-color: var(--border-color);"
                               value="<?php echo set_value('users_prenom') ? set_value('users_prenom') : htmlspecialchars($users->users_prenom); ?>">
                        <div class="text-danger small mt-1"><?php echo form_error('users_prenom'); ?></div>
                    </div>

                    <!-- Photo Upload -->
                    <div class="col-md-6">
                        <label for="photo_profil" class="form-label fw-medium" style="color: var(--text-primary); font-size: 14px;">
                            Photo de profil
                        </label>
                        <?php echo form_upload('photo_profil', '', array('class' => 'form-control', 'style' => 'border-color: var(--border-color);', 'accept' => 'image/*', 'id' => 'photo_profil')); ?>
                        <div class="form-text" style="color: var(--text-secondary); font-size: 12px;">Formats : JPG, PNG, GIF. Taille max recommandée : 2 Mo</div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3" style="border-top: 1px solid var(--border-color);">
                    <a href="<?php echo site_url('home'); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Annuler
                    </a>
                    <?php echo form_submit('submit', 'Enregistrer les modifications', array('class' => 'btn btn-primary')); ?>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>