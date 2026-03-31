<?php $permissions = get_all_permissions(); ?>

<div class="mb-4">
    <a href="<?php echo site_url('users/group'); ?>" class="text-decoration-none d-inline-flex align-items-center small" style="color: var(--primary);">
        <i class="fas fa-arrow-left me-2"></i> Retour aux groupes
    </a>
</div>

<div class="d-flex align-items-center mb-4">
    <div class="d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 48px; height: 48px; background: var(--primary-light);">
        <i class="fas fa-layer-group" style="color: var(--primary);"></i>
    </div>
    <div>
        <h4 class="fw-semibold mb-0" style="color: var(--text-primary);">Créer un groupe</h4>
        <p class="text-secondary small mb-0">Définissez le nom et les permissions du nouveau groupe</p>
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: var(--radius-lg);">
    <div class="card-body p-4">
        <?php echo form_open('', array('class' => 'needs-validation')); ?>

            <div class="mb-4">
                <label for="group_name" class="form-label small fw-semibold" style="color: var(--text-primary);">Nom du groupe</label>
                <input type="text" id="group_name" name="group_name" class="form-control py-2 px-3"
                       value="<?php echo set_value('group_name'); ?>"
                       placeholder="Saisissez le nom du groupe"
                       required
                       style="border: 1px solid var(--border-color); border-radius: var(--radius);">
                <?php if(form_error('group_name')): ?>
                    <div class="text-danger small mt-1"><?php echo form_error('group_name'); ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold mb-3" style="color: var(--text-primary);">
                    <i class="fas fa-shield-halved me-1" style="color: var(--primary);"></i> Permissions du groupe
                </label>
                <div class="row g-3">
                    <?php foreach($permissions as $key => $value): ?>
                        <div class="col-md-6">
                            <label for="perm_<?php echo $key; ?>" class="d-flex align-items-center p-3 rounded-3 border cursor-pointer permission-card" style="border-color: var(--border-color); transition: all 0.15s ease; cursor: pointer;">
                                <div class="form-check form-switch mb-0 me-3">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                           id="perm_<?php echo $key; ?>"
                                           name="group_permissions[]"
                                           value="<?php echo $key; ?>"
                                           style="width: 2.5em; height: 1.25em;">
                                </div>
                                <span class="small fw-medium" style="color: var(--text-primary);"><?php echo $value; ?></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <hr class="my-4" style="border-color: var(--border-color);">

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo site_url('users/group'); ?>" class="btn btn-outline-secondary px-4">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
                <?php echo form_submit('submit', 'Créer le groupe', array('class' => 'btn btn-primary px-4')); ?>
            </div>

        <?php echo form_close(); ?>
    </div>
</div>

<style>
.permission-card:hover {
    background: var(--primary-light) !important;
    border-color: var(--primary) !important;
}
.permission-card:has(input:checked) {
    background: var(--primary-light);
    border-color: var(--primary) !important;
}
.form-check-input:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}
</style>
