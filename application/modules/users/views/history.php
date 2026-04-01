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

<style>
.history-filter-card {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.history-filter-section {
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}
.history-filter-section .filter-group {
    display: flex;
    align-items: end;
    gap: 0.5rem;
    flex-wrap: wrap;
}
.history-filter-section .form-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--text-secondary);
    margin-bottom: 0.25rem;
    white-space: nowrap;
}
.history-filter-section .form-control,
.history-filter-section .form-select {
    border: 1.5px solid var(--border-color);
    border-radius: var(--radius);
    font-size: 0.8125rem;
    padding: 0.375rem 0.75rem;
    height: 36px;
}
.history-filter-section .form-control:focus,
.history-filter-section .form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(26,115,232,0.1);
}
.history-filter-divider {
    width: 1px;
    height: 40px;
    background: var(--border-color);
    flex-shrink: 0;
}
@media (max-width: 992px) {
    .history-filter-divider { display: none; }
    .history-filter-section { padding: 1rem; }
}
.history-timeline-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 1.5rem;
    transition: background 0.15s;
    border-bottom: 1px solid var(--border-color);
}
.history-timeline-item:last-child {
    border-bottom: none;
}
.history-timeline-item:hover {
    background: var(--bg-main);
}
.history-time-badge {
    min-width: 90px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex-shrink: 0;
}
.history-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--primary);
    flex-shrink: 0;
    margin-top: 6px;
}
/* Checkbox — visible in both light & dark themes */
.history-timeline-item .form-check-input {
    width: 18px;
    height: 18px;
    border: 2px solid var(--text-muted);
    border-radius: 4px;
    background-color: transparent;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    display: grid;
    place-content: center;
    transition: border-color 0.15s, background-color 0.15s;
}
.history-timeline-item .form-check-input::before {
    content: "";
    width: 10px;
    height: 10px;
    transform: scale(0);
    transition: transform 0.12s ease-in-out;
    clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
    background-color: #fff;
}
.history-timeline-item .form-check-input:hover {
    border-color: var(--primary);
}
.history-timeline-item .form-check-input:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}
.history-timeline-item .form-check-input:checked::before {
    transform: scale(1);
}
.history-timeline-item .form-check-input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(26,115,232,0.2);
}
</style>

<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1" style="color: var(--text-primary);">
            <i class="fas fa-clock-rotate-left me-2" style="color: var(--primary);"></i>Journal Système
        </h1>
        <p class="mb-0 small" style="color: var(--text-secondary);">
            Historique des activités pour <strong><?php echo htmlspecialchars($date); ?></strong>
            <?php if(!empty($user_trace)): ?>
                — <span class="fw-medium" style="color: var(--primary);"><?php echo htmlspecialchars($user_trace); ?></span>
            <?php endif; ?>
        </p>
    </div>
    <a href="<?php echo site_url('users'); ?>" class="btn btn-outline-secondary btn-sm mt-2 mt-md-0">
        <i class="fas fa-arrow-left me-1"></i> Utilisateurs
    </a>
</div>

<!-- Filters -->
<div class="history-filter-card mb-4">
    <div class="history-filter-section">
        <!-- Filter by User + Year -->
        <?php echo form_open('', array('class' => 'filter-group', 'method' => 'get')); ?>
            <div>
                <label class="form-label">Utilisateur</label>
                <?php echo form_dropdown('utilisateur', $l_utilisateur, $this->input->get('utilisateur'), array('class' => 'form-select', 'style' => 'min-width: 160px;')); ?>
            </div>
            <div>
                <label class="form-label">Année</label>
                <?php echo form_dropdown('ANNEE', $annees, $this->input->get('annee') ? $this->input->get('annee') : $annee_actuelle, array('class' => 'form-select', 'style' => 'min-width: 90px;')); ?>
            </div>
            <div>
                <?php echo form_submit('submit', 'Filtrer', array('class' => 'btn btn-primary btn-sm', 'style' => 'height: 36px;')); ?>
            </div>
        <?php echo form_close(); ?>

        <div class="history-filter-divider"></div>

        <!-- Filter by Date -->
        <?php echo form_open('', array('class' => 'filter-group', 'method' => 'get')); ?>
            <div>
                <label class="form-label">Date précise</label>
                <?php echo form_input('jours', $this->input->get('jours') ? $this->input->get('jours') : '', array('class' => 'form-control datepicker-tiret-us', 'required' => true, 'placeholder' => 'AAAA-MM-JJ', 'style' => 'min-width: 130px;')); ?>
            </div>
            <div>
                <?php echo form_submit('submit', 'Filtrer', array('class' => 'btn btn-primary btn-sm', 'style' => 'height: 36px;')); ?>
            </div>
        <?php echo form_close(); ?>

        <div class="history-filter-divider"></div>

        <!-- Filter by Month -->
        <?php echo form_open('', array('class' => 'filter-group', 'method' => 'get')); ?>
            <div>
                <label class="form-label">Mois</label>
                <?php echo form_input('mois', $this->input->get('mois') ? $this->input->get('mois') : '', array('class' => 'form-control datepicker-mois-us', 'required' => true, 'placeholder' => 'AAAA-MM', 'style' => 'min-width: 120px;')); ?>
            </div>
            <div>
                <?php echo form_submit('submit', 'Filtrer', array('class' => 'btn btn-primary btn-sm', 'style' => 'height: 36px;')); ?>
            </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Results -->
<div class="card" style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg);">
    <!-- Header with PDF download -->
    <?php if(!empty($liste_history)): ?>
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center py-3 px-4" style="background: var(--bg-main); border-bottom: 1px solid var(--border-color);">
        <div class="small fw-medium" style="color: var(--text-secondary);">
            <i class="fas fa-list me-1"></i>
            <?php echo count($liste_history); ?> entrée<?php echo count($liste_history) > 1 ? 's' : ''; ?> trouvée<?php echo count($liste_history) > 1 ? 's' : ''; ?>
        </div>
        <div class="d-flex gap-2">
            <?php if(!empty($user) && is_allowed('view_history')): ?>
                <?php
                    $safe_qs = http_build_query(array_intersect_key(
                        $this->input->get() ? $this->input->get() : array(),
                        array_flip(array('utilisateur', 'ANNEE', 'jours', 'mois'))
                    ));
                ?>
                <a href="<?php echo site_url('users/download_history/' . (int)$user->users_id . '/?' . $safe_qs); ?>"
                   class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-file-pdf me-1"></i> Télécharger PDF
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="card-body p-0">
        <?php if(!empty($liste_history)): ?>
        <?php echo form_open('users/remove'); ?>

        <!-- Timeline List -->
        <div>
            <?php foreach($liste_history as $l): ?>
            <div class="history-timeline-item">
                <div class="history-time-badge">
                    <div class="small fw-semibold" style="color: var(--text-primary);">
                        <?php echo date('H:i', strtotime($l->history_date)); ?>
                    </div>
                    <div style="font-size: 0.7rem; color: var(--text-muted);">
                        <?php echo date('d/m/Y', strtotime($l->history_date)); ?>
                    </div>
                </div>
                <div class="history-dot"></div>
                <div class="flex-grow-1">
                    <div style="color: var(--text-primary); font-size: 0.875rem;">
                        <?php echo htmlspecialchars($l->history_action); ?>
                    </div>
                    <div class="mt-1">
                        <span class="badge rounded-pill" style="background: var(--primary-light); color: var(--primary); font-weight: 500; font-size: 0.7rem;">
                            <i class="fas fa-user me-1" style="font-size: 9px;"></i><?php echo htmlspecialchars($l->history_users); ?>
                        </span>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <input type="checkbox" name="history_id[]"
                           value="<?php echo (int)$l->history_id; ?>"
                           class="form-check-input" style="cursor: pointer;">
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-2" style="background: var(--bg-main); border-top: 1px solid var(--border-color); padding: 1rem 1.5rem;">
            <div>
                <?php if(!empty($pagination)): ?>
                    <?php echo $pagination; ?>
                <?php endif; ?>
            </div>
            <?php echo form_submit('submit', 'Supprimer la sélection', array(
                'class' => 'btn btn-outline-danger btn-sm',
                'onclick' => "return confirm('Supprimer les historiques sélectionnés ?')"
            )); ?>
        </div>
        <?php echo form_close(); ?>
        <?php else: ?>
        <div class="text-center py-5">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 64px; height: 64px; background: var(--primary-light);">
                <i class="fas fa-folder-open fa-lg" style="color: var(--primary);"></i>
            </div>
            <h6 class="fw-semibold" style="color: var(--text-primary);">Aucune donnée</h6>
            <p class="mb-0 small" style="color: var(--text-secondary);">Aucun historique disponible pour les filtres sélectionnés.</p>
        </div>
        <?php endif; ?>
    </div>
</div>