<?php
    $l_utilisateur = array('' => '--- Tous les utilisateurs ---');
    foreach($utilisateurs as $u) {
        $l_utilisateur[$u->users_id] = htmlspecialchars($u->users_nom . ' ' . $u->users_prenom);
        $id = $u->users_id;
    }
    $annee_actuelle = (int)date('Y');
    $annees = array();
    for($i = $annee_actuelle - 10; $i <= $annee_actuelle; $i++) {
        $annees[$i] = $i;
    }
?>

<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-semibold mb-1" style="color: var(--text-primary);">
            <i class="fas fa-clock-rotate-left me-2" style="color: var(--primary);"></i>Journal Système
        </h1>
        <p class="mb-0 small" style="color: var(--text-secondary);">
            Historique des activités pour <?php echo htmlspecialchars($date); ?>
            <?php if(!empty($user_trace)): ?>
                — <span class="fw-medium"><?php echo htmlspecialchars($user_trace); ?></span>
            <?php endif; ?>
        </p>
    </div>
</div>

<!-- Filter Bar -->
<div class="card mb-4" style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
    <div class="card-body p-3">
        <div class="row g-3 align-items-end">
            <!-- Filter by User + Year -->
            <div class="col-lg-5">
                <?php echo form_open('', array('class' => 'd-flex flex-wrap gap-2 align-items-end', 'method' => 'get')); ?>
                    <div class="flex-grow-1" style="min-width: 140px;">
                        <label class="form-label small fw-medium mb-1" style="color: var(--text-secondary);">Utilisateur</label>
                        <?php echo form_dropdown('utilisateur', $l_utilisateur, $this->input->get('utilisateur'), array('class' => 'form-select form-select-sm', 'style' => 'border-color: var(--border-color);')); ?>
                    </div>
                    <div style="min-width: 90px;">
                        <label class="form-label small fw-medium mb-1" style="color: var(--text-secondary);">Année</label>
                        <?php echo form_dropdown('ANNEE', $annees, $this->input->get('annee') ? $this->input->get('annee') : $annee_actuelle, array('class' => 'form-select form-select-sm', 'style' => 'border-color: var(--border-color);')); ?>
                    </div>
                    <div>
                        <?php echo form_submit('submit', 'Filtrer', array('class' => 'btn btn-primary btn-sm')); ?>
                    </div>
                <?php echo form_close(); ?>
            </div>

            <!-- Filter by Date -->
            <div class="col-lg-3">
                <?php echo form_open('', array('class' => 'd-flex flex-wrap gap-2 align-items-end', 'method' => 'get')); ?>
                    <div class="flex-grow-1" style="min-width: 130px;">
                        <label class="form-label small fw-medium mb-1" style="color: var(--text-secondary);">Date précise</label>
                        <?php echo form_input('jours', $this->input->get('jours') ? $this->input->get('jours') : '', array('class' => 'form-control form-control-sm datepicker-tiret-us', 'style' => 'border-color: var(--border-color);', 'required' => true, 'placeholder' => 'AAAA-MM-JJ')); ?>
                    </div>
                    <div>
                        <?php echo form_submit('submit', 'Filtrer', array('class' => 'btn btn-primary btn-sm')); ?>
                    </div>
                <?php echo form_close(); ?>
            </div>

            <!-- Filter by Month -->
            <div class="col-lg-3">
                <?php echo form_open('', array('class' => 'd-flex flex-wrap gap-2 align-items-end', 'method' => 'get')); ?>
                    <div class="flex-grow-1" style="min-width: 120px;">
                        <label class="form-label small fw-medium mb-1" style="color: var(--text-secondary);">Mois</label>
                        <?php echo form_input('mois', $this->input->get('mois') ? $this->input->get('mois') : '', array('class' => 'form-control form-control-sm datepicker-mois-us', 'style' => 'border-color: var(--border-color);', 'required' => true, 'placeholder' => 'AAAA-MM')); ?>
                    </div>
                    <div>
                        <?php echo form_submit('submit', 'Filtrer', array('class' => 'btn btn-primary btn-sm')); ?>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Results Card -->
<div class="card" style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
    <!-- PDF Download -->
    <?php if(!empty($liste_history) && !empty($user)): ?>
    <div class="card-header d-flex justify-content-end py-3" style="background: var(--bg-main); border-bottom: 1px solid var(--border-color);">
        <?php if(is_allowed('view_history')): ?>
            <?php
                $safe_qs = http_build_query(array_intersect_key(
                    $this->input->get() ?: array(),
                    array_flip(array('utilisateur', 'ANNEE', 'jours', 'mois'))
                ));
            ?>
            <a href="<?php echo site_url('users/download_history/' . (int)$user->users_id . '/?' . $safe_qs); ?>"
               class="btn btn-outline-primary btn-sm">
                <i class="fas fa-file-pdf me-1"></i> Télécharger en PDF
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="card-body p-0">
        <?php if(!empty($liste_history)): ?>
        <?php echo form_open('users/remove'); ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" id="myDatatable">
                <thead>
                    <tr style="background: var(--bg-main);">
                        <th scope="col" class="ps-4" style="width: 180px;">Date</th>
                        <th scope="col">Activité</th>
                        <th scope="col" style="width: 180px;">Utilisateur</th>
                        <th scope="col" class="text-center pe-4" style="width: 60px;">
                            <i class="fas fa-check-square" style="color: var(--text-secondary);"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($liste_history as $l): ?>
                    <tr>
                        <td class="ps-4">
                            <span style="color: var(--text-secondary); font-size: 13px;">
                                <i class="fas fa-clock me-1"></i>
                                <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($l->history_date))); ?>
                            </span>
                        </td>
                        <td style="color: var(--text-primary);"><?php echo htmlspecialchars($l->history_action); ?></td>
                        <td>
                            <span class="badge rounded-pill" style="background: var(--primary-light); color: var(--primary); font-weight: 500;">
                                <?php echo htmlspecialchars($l->history_users); ?>
                            </span>
                        </td>
                        <td class="text-center pe-4">
                            <input type="checkbox" name="history_id[]"
                                   value="<?php echo (int)$l->history_id; ?>"
                                   class="form-check-input">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center" style="background: var(--bg-main); border-top: 1px solid var(--border-color);">
            <div>
                <?php if(!empty($pagination)): ?>
                    <?php echo $pagination; ?>
                <?php endif; ?>
            </div>
            <?php echo form_submit('submit', 'Supprimer la sélection', array(
                'class' => 'btn btn-outline-danger btn-sm',
                'onclick' => "return confirm('Êtes-vous sûr de vouloir supprimer les historiques des actions sélectionnées ?')"
            )); ?>
        </div>
        <?php echo form_close(); ?>
        <?php else: ?>
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="fas fa-folder-open" style="font-size: 48px; color: var(--border-color);"></i>
            </div>
            <h5 class="fw-semibold" style="color: var(--text-primary);">Aucune donnée</h5>
            <p class="mb-0" style="color: var(--text-secondary);">Aucun historique disponible pour les filtres sélectionnés.</p>
        </div>
        <?php endif; ?>
    </div>
</div>