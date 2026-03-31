<?php
function view_file_icon($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $icons = array(
        'pdf'  => array('icon' => 'fas fa-file-pdf',        'color' => '#ea4335', 'bg' => '#fce8e6'),
        'doc'  => array('icon' => 'fas fa-file-word',       'color' => '#4285f4', 'bg' => '#e8f0fe'),
        'docx' => array('icon' => 'fas fa-file-word',       'color' => '#4285f4', 'bg' => '#e8f0fe'),
        'xls'  => array('icon' => 'fas fa-file-excel',      'color' => '#34a853', 'bg' => '#e6f4ea'),
        'xlsx' => array('icon' => 'fas fa-file-excel',      'color' => '#34a853', 'bg' => '#e6f4ea'),
        'csv'  => array('icon' => 'fas fa-file-csv',        'color' => '#34a853', 'bg' => '#e6f4ea'),
        'ppt'  => array('icon' => 'fas fa-file-powerpoint', 'color' => '#fa7b17', 'bg' => '#fef7e0'),
        'pptx' => array('icon' => 'fas fa-file-powerpoint', 'color' => '#fa7b17', 'bg' => '#fef7e0'),
        'png'  => array('icon' => 'fas fa-file-image',      'color' => '#a142f4', 'bg' => '#f3e8fd'),
        'jpg'  => array('icon' => 'fas fa-file-image',      'color' => '#a142f4', 'bg' => '#f3e8fd'),
        'jpeg' => array('icon' => 'fas fa-file-image',      'color' => '#a142f4', 'bg' => '#f3e8fd'),
        'gif'  => array('icon' => 'fas fa-file-image',      'color' => '#a142f4', 'bg' => '#f3e8fd'),
        'zip'  => array('icon' => 'fas fa-file-zipper',     'color' => '#f9ab00', 'bg' => '#fef7e0'),
        'rar'  => array('icon' => 'fas fa-file-zipper',     'color' => '#f9ab00', 'bg' => '#fef7e0'),
        'txt'  => array('icon' => 'fas fa-file-lines',      'color' => '#5f6368', 'bg' => '#f1f3f4'),
    );
    return isset($icons[$ext]) ? $icons[$ext] : array('icon' => 'fas fa-file', 'color' => '#5f6368', 'bg' => '#f1f3f4');
}

function view_format_size($bytes) {
    if ($bytes <= 0) return '---';
    if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' Mo';
    if ($bytes >= 1024) return round($bytes / 1024, 1) . ' Ko';
    return $bytes . ' o';
}

function view_is_previewable($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, array('pdf', 'png', 'jpg', 'jpeg', 'gif', 'txt', 'csv'));
}

function view_is_image($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, array('png', 'jpg', 'jpeg', 'gif'));
}

$esc = function($v) { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); };
$doc = $document;
$icon = view_file_icon($doc->filename);
$ext = strtolower(pathinfo($doc->filename, PATHINFO_EXTENSION));
$uploader = trim((isset($doc->users_prenom) ? $doc->users_prenom : '') . ' ' . (isset($doc->users_nom) ? $doc->users_nom : ''));
?>

<style>
.doc-detail-card {
    background:var(--bg-white); border:1px solid var(--border-color); border-radius:var(--radius-lg);
    overflow:hidden;
}
.doc-detail-header {
    display:flex; align-items:center; gap:16px; padding:20px 24px;
    border-bottom:1px solid var(--border-color);
}
.doc-detail-icon {
    width:52px; height:52px; border-radius:var(--radius-lg); display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0;
}
.doc-detail-title { font-size:18px; font-weight:600; color:var(--text-primary); word-break:break-word; }
.doc-detail-subtitle { font-size:12px; color:var(--text-secondary); margin-top:2px; }
.doc-detail-body { padding:24px; }

.doc-info-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:14px; }
.doc-info-item { display:flex; align-items:flex-start; gap:10px; }
.doc-info-icon { width:34px; height:34px; border-radius:var(--radius); background:var(--bg-main); display:flex; align-items:center; justify-content:center; color:var(--text-muted); font-size:13px; flex-shrink:0; }
.doc-info-label { font-size:11px; color:var(--text-muted); text-transform:uppercase; letter-spacing:.3px; }
.doc-info-value { font-size:13px; color:var(--text-primary); font-weight:500; }

.doc-section {
    background:var(--bg-white); border:1px solid var(--border-color); border-radius:var(--radius-lg);
    padding:20px 24px; margin-top:16px;
}
.doc-section-title {
    font-size:14px; font-weight:600; color:var(--text-primary); margin-bottom:14px;
    display:flex; align-items:center; gap:8px;
}
.doc-section-title i { color:var(--primary); font-size:14px; }

.doc-preview-frame { width:100%; border:none; border-radius:var(--radius); background:var(--bg-main); }
.doc-image-preview { max-width:100%; max-height:500px; border-radius:var(--radius); object-fit:contain; }

.doc-share-item {
    display:flex; align-items:center; gap:10px; padding:8px 0;
    border-bottom:1px solid var(--border-color);
}
.doc-share-item:last-child { border-bottom:none; }
.doc-share-avatar {
    width:32px; height:32px; border-radius:50%; background:var(--primary-light);
    display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:var(--primary);
    overflow:hidden; flex-shrink:0;
}
.doc-share-avatar img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
.doc-share-name { font-size:13px; color:var(--text-primary); flex:1; }

.doc-edit-toggle { display:none; }
.doc-edit-toggle.show { display:block; }
</style>

<!-- Breadcrumb & back -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <nav>
        <ol class="breadcrumb mb-0" style="font-size:13px;">
            <li class="breadcrumb-item"><a href="<?php echo site_url('documents'); ?>" style="color:var(--primary);text-decoration:none;"><i class="fas fa-folder-open me-1"></i>Documents</a></li>
            <li class="breadcrumb-item active" style="color:var(--text-secondary);"><?php echo $esc(mb_strimwidth($doc->filename, 0, 40, '...')); ?></li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <?php if (view_is_previewable($doc->filename)): ?>
        <a href="<?php echo site_url('documents/preview/' . $doc->id); ?>" class="btn btn-sm btn-outline-success" target="_blank"><i class="fas fa-eye me-1"></i> Aperçu</a>
        <?php endif; ?>
        <a href="<?php echo site_url('documents/download/' . $doc->id); ?>" class="btn btn-sm btn-primary"><i class="fas fa-download me-1"></i> Télécharger</a>
        <?php if ($is_owner): ?>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleEdit()"><i class="fas fa-edit me-1"></i> Modifier</button>
        <form method="post" action="<?php echo site_url('documents/delete/' . $doc->id); ?>" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">
            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash-alt me-1"></i> Supprimer</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success alert-dismissible fade show py-2 px-3 mb-3" role="alert" style="font-size:13px;border-radius:var(--radius);">
    <i class="fas fa-check-circle me-1"></i><?php echo $this->session->flashdata('success'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" style="padding:8px 12px;"></button>
</div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show py-2 px-3 mb-3" role="alert" style="font-size:13px;border-radius:var(--radius);">
    <i class="fas fa-exclamation-circle me-1"></i><?php echo $this->session->flashdata('error'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" style="padding:8px 12px;"></button>
</div>
<?php endif; ?>

<div class="row g-3">
    <!-- Main Column -->
    <div class="col-lg-8">
        <!-- Document Card -->
        <div class="doc-detail-card">
            <div class="doc-detail-header">
                <div class="doc-detail-icon" style="background:<?php echo $icon['bg']; ?>;color:<?php echo $icon['color']; ?>;">
                    <i class="<?php echo $icon['icon']; ?>"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="doc-detail-title"><?php echo $esc($doc->filename); ?></div>
                    <div class="doc-detail-subtitle">
                        Partagé par <strong><?php echo $esc($uploader); ?></strong> le <?php echo date('d/m/Y à H:i', strtotime($doc->created_at)); ?>
                    </div>
                </div>
                <?php if ($doc->visibility === 'private'): ?>
                    <span style="background:#fce8e6;color:#ea4335;font-size:11px;font-weight:600;padding:4px 10px;border-radius:6px;"><i class="fas fa-lock me-1"></i>Privé</span>
                <?php else: ?>
                    <span style="background:#e6f4ea;color:#34a853;font-size:11px;font-weight:600;padding:4px 10px;border-radius:6px;"><i class="fas fa-globe me-1"></i>Public</span>
                <?php endif; ?>
            </div>
            <div class="doc-detail-body">
                <?php if (!empty($doc->description)): ?>
                <div style="margin-bottom:18px;">
                    <p style="font-size:13px;color:var(--text-primary);line-height:1.6;margin:0;"><?php echo nl2br($esc($doc->description)); ?></p>
                </div>
                <?php endif; ?>

                <div class="doc-info-grid">
                    <div class="doc-info-item">
                        <div class="doc-info-icon"><i class="fas fa-file"></i></div>
                        <div><div class="doc-info-label">Type</div><div class="doc-info-value"><?php echo strtoupper($esc($ext)); ?></div></div>
                    </div>
                    <div class="doc-info-item">
                        <div class="doc-info-icon"><i class="fas fa-weight-hanging"></i></div>
                        <div><div class="doc-info-label">Taille</div><div class="doc-info-value"><?php echo view_format_size($doc->file_size); ?></div></div>
                    </div>
                    <div class="doc-info-item">
                        <div class="doc-info-icon"><i class="fas fa-download"></i></div>
                        <div><div class="doc-info-label">Téléchargements</div><div class="doc-info-value"><?php echo (int) $doc->download_count; ?></div></div>
                    </div>
                    <?php if (!empty($doc->category_name)): ?>
                    <div class="doc-info-item">
                        <div class="doc-info-icon"><i class="fas fa-tag"></i></div>
                        <div><div class="doc-info-label">Catégorie</div><div class="doc-info-value"><span style="background:<?php echo $esc($doc->category_color); ?>15;color:<?php echo $esc($doc->category_color); ?>;padding:2px 8px;border-radius:4px;font-size:12px;"><?php echo $esc($doc->category_name); ?></span></div></div>
                    </div>
                    <?php endif; ?>
                    <div class="doc-info-item">
                        <div class="doc-info-icon"><i class="fas fa-calendar"></i></div>
                        <div><div class="doc-info-label">Créé le</div><div class="doc-info-value"><?php echo date('d/m/Y H:i', strtotime($doc->created_at)); ?></div></div>
                    </div>
                    <?php if (!empty($doc->updated_at) && $doc->updated_at !== $doc->created_at): ?>
                    <div class="doc-info-item">
                        <div class="doc-info-icon"><i class="fas fa-clock-rotate-left"></i></div>
                        <div><div class="doc-info-label">Modifié le</div><div class="doc-info-value"><?php echo date('d/m/Y H:i', strtotime($doc->updated_at)); ?></div></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <?php if (view_is_previewable($doc->filename)): ?>
        <div class="doc-section">
            <div class="doc-section-title"><i class="fas fa-eye"></i> Aperçu</div>
            <?php if (view_is_image($doc->filename)): ?>
                <img src="<?php echo site_url('documents/preview/' . $doc->id); ?>" alt="<?php echo $esc($doc->filename); ?>" class="doc-image-preview">
            <?php elseif ($ext === 'pdf'): ?>
                <iframe src="<?php echo site_url('documents/preview/' . $doc->id); ?>" class="doc-preview-frame" style="height:600px;"></iframe>
            <?php else: ?>
                <iframe src="<?php echo site_url('documents/preview/' . $doc->id); ?>" class="doc-preview-frame" style="height:400px;"></iframe>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Edit Form (Owner Only) -->
        <?php if ($is_owner): ?>
        <div class="doc-section doc-edit-toggle" id="editSection">
            <div class="doc-section-title"><i class="fas fa-edit"></i> Modifier le document</div>
            <?php echo form_open('documents/update/' . (int) $doc->id); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-medium">Visibilité</label>
                    <select name="visibility" class="form-select form-select-sm">
                        <option value="public" <?php echo $doc->visibility === 'public' ? 'selected' : ''; ?>>Public — Visible par tous</option>
                        <option value="private" <?php echo $doc->visibility === 'private' ? 'selected' : ''; ?>>Privé — Accès restreint</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-medium">Catégorie</label>
                    <select name="category_id" class="form-select form-select-sm">
                        <option value="">— Aucune —</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo (int) $cat->id; ?>" <?php echo (int) $doc->category_id === (int) $cat->id ? 'selected' : ''; ?>><?php echo $esc($cat->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-medium">Description</label>
                    <textarea name="description" class="form-control form-control-sm" rows="3" maxlength="500"><?php echo $esc(isset($doc->description) ? $doc->description : ''); ?></textarea>
                </div>
                <div class="col-12 text-end">
                    <button type="button" class="btn btn-sm" style="color:var(--text-secondary);" onclick="toggleEdit()">Annuler</button>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i> Enregistrer</button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Share Management (Owner + private docs) -->
        <?php if ($is_owner): ?>
        <div class="doc-section" style="margin-top:0;">
            <div class="doc-section-title">
                <i class="fas fa-user-group"></i> Partage
                <?php if (!empty($shares)): ?>
                <span style="background:var(--primary-light);color:var(--primary);font-size:10px;padding:2px 8px;border-radius:10px;margin-left:auto;"><?php echo count($shares); ?></span>
                <?php endif; ?>
            </div>

            <?php if (!empty($shares)): ?>
            <div class="mb-3">
                <?php foreach ($shares as $share):
                    $sname = trim((isset($share->users_prenom) ? $share->users_prenom : '') . ' ' . (isset($share->users_nom) ? $share->users_nom : ''));
                    if ($sname === '') $sname = 'Utilisateur #' . $share->shared_with;
                ?>
                <div class="doc-share-item">
                    <div class="doc-share-avatar">
                        <?php if (!empty($share->photo_profil)): ?>
                            <img src="<?php echo base_url('assets/img/avatar/' . rawurlencode(basename($share->photo_profil))); ?>" alt="">
                        <?php else: ?>
                            <?php echo strtoupper(substr($sname, 0, 2)); ?>
                        <?php endif; ?>
                    </div>
                    <span class="doc-share-name"><?php echo $esc($sname); ?></span>
                    <span style="font-size:11px;color:var(--text-muted);"><?php echo date('d/m/Y', strtotime($share->created_at)); ?></span>
                    <a href="<?php echo site_url('documents/remove_share/' . (int) $doc->id . '/' . (int) $share->shared_with); ?>" class="btn btn-sm" style="color:var(--text-muted);padding:2px 6px;" title="Retirer" onclick="return confirm('Retirer cet accès ?');"><i class="fas fa-times"></i></a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p style="font-size:12px;color:var(--text-muted);margin-bottom:14px;">
                <?php echo $doc->visibility === 'private' ? 'Ce document n\'est partagé avec personne.' : 'Ce document est public — tous les utilisateurs y ont accès. Vous pouvez aussi partager manuellement.'; ?>
            </p>
            <?php endif; ?>

            <!-- Add share form -->
            <?php echo form_open('documents/add_share/' . (int) $doc->id); ?>
            <div class="d-flex gap-2">
                <select name="shared_users[]" class="form-select form-select-sm flex-grow-1" id="addShareSelect">
                    <option value="">— Ajouter un utilisateur —</option>
                    <?php
                    $shared_ids = array();
                    if (!empty($shares)) { foreach ($shares as $s) $shared_ids[] = (int) $s->shared_with; }
                    if (!empty($all_users)): foreach ($all_users as $u):
                        $uid = (int) $u->users_id;
                        if ($uid === $current_user_id || in_array($uid, $shared_ids)) continue;
                        $uname = trim((isset($u->users_prenom) ? $u->users_prenom : '') . ' ' . (isset($u->users_nom) ? $u->users_nom : ''));
                        if ($uname === '') $uname = isset($u->users_username) ? $u->users_username : 'Utilisateur';
                    ?>
                    <option value="<?php echo $uid; ?>"><?php echo $esc($uname); ?></option>
                    <?php endforeach; endif; ?>
                </select>
                <button type="submit" class="btn btn-primary btn-sm" style="white-space:nowrap;"><i class="fas fa-plus me-1"></i> Ajouter</button>
            </div>
            <?php echo form_close(); ?>
        </div>
        <?php endif; ?>

        <!-- Quick Info Sidebar -->
        <div class="doc-section">
            <div class="doc-section-title"><i class="fas fa-circle-info"></i> Informations</div>
            <table style="width:100%;font-size:12px;">
                <tr>
                    <td style="padding:5px 0;color:var(--text-muted);width:40%;">Propriétaire</td>
                    <td style="padding:5px 0;color:var(--text-primary);font-weight:500;"><?php echo $esc($uploader); ?></td>
                </tr>
                <tr>
                    <td style="padding:5px 0;color:var(--text-muted);">Visibilité</td>
                    <td style="padding:5px 0;">
                        <?php if ($doc->visibility === 'private'): ?>
                            <span style="background:#fce8e6;color:#ea4335;font-size:10px;font-weight:600;padding:2px 7px;border-radius:4px;"><i class="fas fa-lock me-1"></i>Privé</span>
                        <?php else: ?>
                            <span style="background:#e6f4ea;color:#34a853;font-size:10px;font-weight:600;padding:2px 7px;border-radius:4px;"><i class="fas fa-globe me-1"></i>Public</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding:5px 0;color:var(--text-muted);">Type</td>
                    <td style="padding:5px 0;color:var(--text-primary);font-weight:500;">.<?php echo $esc($ext); ?></td>
                </tr>
                <tr>
                    <td style="padding:5px 0;color:var(--text-muted);">Taille</td>
                    <td style="padding:5px 0;color:var(--text-primary);font-weight:500;"><?php echo view_format_size($doc->file_size); ?></td>
                </tr>
                <tr>
                    <td style="padding:5px 0;color:var(--text-muted);">Téléchargements</td>
                    <td style="padding:5px 0;color:var(--text-primary);font-weight:500;"><?php echo (int) $doc->download_count; ?></td>
                </tr>
                <?php if (!empty($doc->category_name)): ?>
                <tr>
                    <td style="padding:5px 0;color:var(--text-muted);">Catégorie</td>
                    <td style="padding:5px 0;"><span style="background:<?php echo $esc($doc->category_color); ?>15;color:<?php echo $esc($doc->category_color); ?>;padding:2px 7px;border-radius:4px;font-size:11px;"><?php echo $esc($doc->category_name); ?></span></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>

        <!-- Related actions -->
        <div class="doc-section">
            <div class="doc-section-title"><i class="fas fa-bolt"></i> Actions rapides</div>
            <div class="d-grid gap-2">
                <a href="<?php echo site_url('documents/download/' . $doc->id); ?>" class="btn btn-sm btn-outline-primary text-start">
                    <i class="fas fa-download me-2"></i> Télécharger le fichier
                </a>
                <?php if (view_is_previewable($doc->filename)): ?>
                <a href="<?php echo site_url('documents/preview/' . $doc->id); ?>" class="btn btn-sm btn-outline-success text-start" target="_blank">
                    <i class="fas fa-eye me-2"></i> Ouvrir l'aperçu
                </a>
                <?php endif; ?>
                <a href="<?php echo site_url('documents'); ?>" class="btn btn-sm btn-outline-secondary text-start">
                    <i class="fas fa-arrow-left me-2"></i> Retour aux documents
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEdit() {
    var section = document.getElementById('editSection');
    if (section) section.classList.toggle('show');
}
</script>
