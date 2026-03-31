<?php $permissions = get_all_permissions(); ?>

<style>
.perm-card-item {
    border: 1.5px solid var(--border-color);
    border-radius: var(--radius);
    padding: 0.875rem 1rem;
    display: flex;
    align-items: center;
    transition: all 0.2s;
    cursor: pointer;
    background: var(--bg-white);
}
.perm-card-item:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}
.perm-card-item:has(input:checked) {
    border-color: var(--primary);
    background: var(--primary-light);
}
.perm-card-item .form-check-input:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}
</style>

<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1" style="color: var(--text-primary);">
            <i class="fas fa-pen-to-square me-2" style="color: var(--primary);"></i>Modifier le groupe
        </h1>
        <p class="mb-0 small" style="color: var(--text-secondary);">
            Modification de « <strong><?php echo htmlspecialchars($group->group_name); ?></strong> »
        </p>
    </div>
    <a href="<?php echo site_url('users/group'); ?>" class="btn btn-outline-secondary btn-sm mt-2 mt-md-0">
        <i class="fas fa-arrow-left me-1"></i> Retour aux groupes
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div style="background: var(--bg-white); border: 1px solid var(--border-color); border-radius: var(--radius-lg); overflow: hidden;">
            <?php echo form_open('', array('class' => 'needs-validation')); ?>

            <div style="padding: 1.5rem 2rem;">
                <div style="font-size: 0.9rem; font-weight: 600; color: var(--text-primary); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 28px; height: 28px; border-radius: 8px; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 0.75rem;">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    Informations du groupe
                </div>
                <label for="group_name" class="form-label small fw-medium" style="color: var(--text-primary);">Nom du groupe <span class="text-danger">*</span></label>
                <input type="text" id="group_name" name="group_name"
                       class="form-control" style="border: 1.5px solid var(--border-color); border-radius: var(--radius);"
                       value="<?php echo set_value('group_name') ? set_value('group_name') : htmlspecialchars($group->group_name); ?>"
                       placeholder="Saisissez le nom du groupe" required>
                <?php if(form_error('group_name')): ?>
                    <div class="text-danger small mt-1"><?php echo form_error('group_name'); ?></div>
                <?php endif; ?>
            </div>

            <div style="padding: 1.5rem 2rem; border-top: 1px solid var(--border-color);">
                <div style="font-size: 0.9rem; font-weight: 600; color: var(--text-primary); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 28px; height: 28px; border-radius: 8px; background: #fef7e0; color: #ea8600; display: flex; align-items: center; justify-content: center; font-size: 0.75rem;">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    Permissions du groupe
                </div>
                <div class="row g-3">
                    <?php foreach($permissions as $key => $value): ?>
                    <div class="col-md-6">
                        <label for="perm_<?php echo $key; ?>" class="d-block mb-0">
                            <div class="perm-card-item">
                                <div class="form-check form-switch mb-0 me-3">
                                    <input class="form-check-input" type="checkbox" role="switch"
                                           id="perm_<?php echo $key; ?>"
                                           name="group_permissions[]"
                                           value="<?php echo $key; ?>"
                                           <?php echo (isset($group->{$key}) && $group->{$key} == 1) ? 'checked' : ''; ?>
                                           style="width: 2.5em; height: 1.25em;">
                                </div>
                                <span class="small fw-medium" style="color: var(--text-primary);"><?php echo $value; ?></span>
                            </div>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div style="padding: 1.25rem 2rem; background: var(--bg-main); border-top: 1px solid var(--border-color);">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="<?php echo site_url('users/group'); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Annuler
                    </a>
                    <?php echo form_submit('submit', 'Enregistrer les modifications', array('class' => 'btn btn-primary')); ?>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>