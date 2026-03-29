<?php
/**
 * Get file extension icon class and color based on extension
 */
function get_file_icon($filename) {
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

function format_file_size($filepath) {
    $full_path = FCPATH . $filepath;
    if (!file_exists($full_path)) return '—';
    $size = filesize($full_path);
    if ($size >= 1048576) return round($size / 1048576, 1) . ' Mo';
    if ($size >= 1024) return round($size / 1024, 1) . ' Ko';
    return $size . ' o';
}

function is_previewable($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, array('pdf', 'png', 'jpg', 'jpeg', 'gif', 'txt', 'csv'));
}
?>

<style>
/* ===== Documents Scoped Styles ===== */
.doc-search-bar {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: 24px;
    display: flex;
    align-items: center;
    padding: 4px 16px;
    max-width: 480px;
    transition: var(--transition);
}
.doc-search-bar:focus-within {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(26,115,232,.15);
}
.doc-search-bar input {
    border: none;
    outline: none;
    background: transparent;
    flex: 1;
    padding: 8px;
    font-size: 14px;
    font-family: inherit;
    color: var(--text-primary);
}
.doc-search-bar input::placeholder { color: var(--text-muted); }
.doc-search-bar .search-icon { color: var(--text-muted); font-size: 14px; }
.doc-search-bar .btn-clear {
    border: none; background: none; color: var(--text-muted); cursor: pointer;
    padding: 4px; border-radius: 50%; transition: var(--transition);
}
.doc-search-bar .btn-clear:hover { background: var(--bg-main); color: var(--text-secondary); }

.doc-view-toggle .btn {
    border: 1px solid var(--border-color);
    background: var(--bg-white);
    color: var(--text-secondary);
    padding: 6px 12px;
    font-size: 14px;
    transition: var(--transition);
}
.doc-view-toggle .btn.active {
    background: var(--primary-light);
    color: var(--primary);
    border-color: var(--primary);
}
.doc-view-toggle .btn:hover:not(.active) {
    background: var(--bg-main);
}

/* List view */
.doc-table {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.doc-table table { margin-bottom: 0; }
.doc-table thead th {
    background: var(--bg-main);
    border-bottom: 1px solid var(--border-color);
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    color: var(--text-secondary);
    padding: 12px 16px;
    white-space: nowrap;
}
.doc-table tbody tr {
    transition: var(--transition);
    cursor: pointer;
}
.doc-table tbody tr:hover { background: var(--primary-light); }
.doc-table tbody td {
    padding: 12px 16px;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-color);
    font-size: 14px;
}
.doc-table tbody tr:last-child td { border-bottom: none; }

.doc-file-icon {
    width: 36px;
    height: 36px;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}

.doc-filename {
    font-weight: 500;
    color: var(--text-primary);
    word-break: break-word;
}
.doc-filename:hover { color: var(--primary); }

.doc-meta {
    font-size: 13px;
    color: var(--text-secondary);
}

.doc-actions .btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    border: none;
    background: transparent;
    color: var(--text-secondary);
    font-size: 14px;
    transition: var(--transition);
}
.doc-actions .btn:hover { background: var(--bg-main); }
.doc-actions .btn-download:hover { color: var(--primary); background: var(--primary-light); }
.doc-actions .btn-preview:hover { color: #34a853; background: #e6f4ea; }
.doc-actions .btn-delete:hover { color: #ea4335; background: #fce8e6; }

/* Grid view */
.doc-grid-item {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 20px;
    transition: var(--transition);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    height: 100%;
}
.doc-grid-item:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}
.doc-grid-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-bottom: 12px;
}
.doc-grid-name {
    font-size: 13px;
    font-weight: 500;
    color: var(--text-primary);
    word-break: break-word;
    margin-bottom: 4px;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.doc-grid-meta {
    font-size: 11px;
    color: var(--text-muted);
    margin-bottom: 12px;
}
.doc-grid-actions { margin-top: auto; display: flex; gap: 4px; }

/* Upload modal */
.upload-drop-zone {
    border: 2px dashed var(--border-color);
    border-radius: var(--radius-lg);
    padding: 40px 20px;
    text-align: center;
    transition: var(--transition);
    cursor: pointer;
    position: relative;
    background: var(--bg-main);
}
.upload-drop-zone:hover,
.upload-drop-zone.dragover {
    border-color: var(--primary);
    background: var(--primary-light);
}
.upload-drop-zone .upload-icon {
    font-size: 48px;
    color: var(--primary);
    margin-bottom: 12px;
}
.upload-drop-zone .upload-text {
    font-size: 15px;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 4px;
}
.upload-drop-zone .upload-hint {
    font-size: 13px;
    color: var(--text-muted);
}
.upload-drop-zone input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
}
.upload-file-info {
    display: none;
    background: var(--primary-light);
    border-radius: var(--radius);
    padding: 12px 16px;
    margin-top: 12px;
    font-size: 13px;
    color: var(--primary);
    font-weight: 500;
}
.upload-file-info.show { display: flex; align-items: center; gap: 8px; }

/* Empty state */
.doc-empty {
    text-align: center;
    padding: 60px 20px;
}
.doc-empty-icon {
    font-size: 64px;
    color: var(--border-color);
    margin-bottom: 16px;
}
.doc-empty-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
}
.doc-empty-text {
    font-size: 14px;
    color: var(--text-secondary);
    margin-bottom: 20px;
}
</style>

<!-- Page Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 fw-semibold mb-1" style="color: var(--text-primary);">
            <i class="fas fa-folder-open me-2" style="color: var(--primary);"></i>Documents
        </h1>
        <p class="mb-0 small" style="color: var(--text-secondary);">Partagez et gérez vos documents d'équipe</p>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-cloud-arrow-up me-1"></i> Partager un document
        </button>
    </div>
</div>

<!-- Search + View Toggle -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <form method="get" action="<?php echo site_url('documents'); ?>" class="flex-grow-1" style="max-width: 480px;">
        <div class="doc-search-bar">
            <i class="fas fa-search search-icon"></i>
            <input type="text" name="search" placeholder="Rechercher un document..." value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off">
            <?php if ($search): ?>
                <a href="<?php echo site_url('documents'); ?>" class="btn-clear" title="Effacer"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </div>
    </form>
    <div class="doc-view-toggle btn-group" role="group">
        <button type="button" class="btn active" id="btnListView" title="Vue liste"><i class="fas fa-list"></i></button>
        <button type="button" class="btn" id="btnGridView" title="Vue grille"><i class="fas fa-grip"></i></button>
    </div>
</div>

<?php if ($search): ?>
    <div class="mb-3">
        <span class="badge rounded-pill" style="background: var(--primary-light); color: var(--primary); font-size: 13px; font-weight: 500; padding: 6px 14px;">
            <i class="fas fa-filter me-1"></i>
            Résultats pour « <?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?> »
            <a href="<?php echo site_url('documents'); ?>" style="color: var(--primary); margin-left: 6px;"><i class="fas fa-times"></i></a>
        </span>
    </div>
<?php endif; ?>

<?php if (!empty($documents)): ?>

    <!-- LIST VIEW -->
    <div id="listView">
        <div class="doc-table">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;"></th>
                        <th>Nom du fichier</th>
                        <th>Partagé par</th>
                        <th>Date</th>
                        <th>Taille</th>
                        <th style="width: 120px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($documents as $doc):
                        $icon = get_file_icon($doc->filename);
                    ?>
                    <tr>
                        <td>
                            <div class="doc-file-icon" style="background: <?php echo $icon['bg']; ?>; color: <?php echo $icon['color']; ?>;">
                                <i class="<?php echo $icon['icon']; ?>"></i>
                            </div>
                        </td>
                        <td>
                            <span class="doc-filename"><?php echo htmlspecialchars($doc->filename, ENT_QUOTES, 'UTF-8'); ?></span>
                        </td>
                        <td class="doc-meta">
                            <?php echo htmlspecialchars(($doc->users_prenom ?? '') . ' ' . ($doc->users_nom ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td class="doc-meta">
                            <?php echo date('d/m/Y H:i', strtotime($doc->created_at)); ?>
                        </td>
                        <td class="doc-meta">
                            <?php echo format_file_size($doc->filepath); ?>
                        </td>
                        <td class="text-end">
                            <div class="doc-actions d-inline-flex gap-1">
                                <?php if (is_previewable($doc->filename)): ?>
                                    <a href="<?php echo site_url('documents/preview/' . $doc->id); ?>" class="btn btn-preview" title="Aperçu" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo site_url('documents/download/' . $doc->id); ?>" class="btn btn-download" title="Télécharger">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="<?php echo site_url('documents/delete/' . $doc->id); ?>" class="btn btn-delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- GRID VIEW (hidden by default) -->
    <div id="gridView" style="display: none;">
        <div class="row g-3">
            <?php foreach ($documents as $doc):
                $icon = get_file_icon($doc->filename);
            ?>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="doc-grid-item">
                    <div class="doc-grid-icon" style="background: <?php echo $icon['bg']; ?>; color: <?php echo $icon['color']; ?>;">
                        <i class="<?php echo $icon['icon']; ?>"></i>
                    </div>
                    <div class="doc-grid-name" title="<?php echo htmlspecialchars($doc->filename, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($doc->filename, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <div class="doc-grid-meta">
                        <?php echo htmlspecialchars(($doc->users_prenom ?? ''), ENT_QUOTES, 'UTF-8'); ?> · <?php echo date('d/m/Y', strtotime($doc->created_at)); ?>
                        <br><?php echo format_file_size($doc->filepath); ?>
                    </div>
                    <div class="doc-grid-actions">
                        <?php if (is_previewable($doc->filename)): ?>
                            <a href="<?php echo site_url('documents/preview/' . $doc->id); ?>" class="btn btn-sm btn-outline-secondary" title="Aperçu" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo site_url('documents/download/' . $doc->id); ?>" class="btn btn-sm btn-outline-primary" title="Télécharger">
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="<?php echo site_url('documents/delete/' . $doc->id); ?>" class="btn btn-sm btn-outline-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

<?php else: ?>

    <!-- Empty State -->
    <div class="doc-empty">
        <div class="doc-empty-icon"><i class="fas fa-folder-open"></i></div>
        <?php if ($search): ?>
            <div class="doc-empty-title">Aucun résultat</div>
            <div class="doc-empty-text">Aucun document ne correspond à « <?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?> »</div>
            <a href="<?php echo site_url('documents'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-arrow-left me-1"></i> Voir tous les documents</a>
        <?php else: ?>
            <div class="doc-empty-title">Aucun document partagé</div>
            <div class="doc-empty-text">Commencez par partager un document avec votre équipe.</div>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="fas fa-cloud-arrow-up me-1"></i> Partager un document
            </button>
        <?php endif; ?>
    </div>

<?php endif; ?>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: var(--radius-lg); overflow: hidden;">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color); padding: 16px 24px;">
                <h5 class="modal-title fw-semibold" id="uploadModalLabel" style="font-size: 16px;">
                    <i class="fas fa-cloud-arrow-up me-2" style="color: var(--primary);"></i>Partager un document
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <?php echo form_open_multipart('documents/upload', array('id' => 'uploadForm')); ?>
            <div class="modal-body" style="padding: 24px;">
                <div class="upload-drop-zone" id="dropZone">
                    <input type="file" name="document" id="fileInput" required>
                    <div class="upload-icon"><i class="fas fa-cloud-arrow-up"></i></div>
                    <div class="upload-text">Glissez-déposez un fichier ici</div>
                    <div class="upload-hint">ou cliquez pour sélectionner un fichier</div>
                    <div class="upload-hint mt-2" style="font-size: 11px;">
                        PDF, DOC, XLS, PPT, TXT, CSV, ZIP, RAR, PNG, JPG, GIF — Max 10 Mo
                    </div>
                </div>
                <div class="upload-file-info" id="fileInfo">
                    <i class="fas fa-file"></i>
                    <span id="fileName"></span>
                    <button type="button" class="btn-clear ms-auto" id="fileClear"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border-color); padding: 12px 24px;">
                <button type="button" class="btn btn-sm" style="color: var(--text-secondary);" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary btn-sm" id="uploadBtn" disabled>
                    <i class="fas fa-cloud-arrow-up me-1"></i> Partager
                </button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle
    var listView = document.getElementById('listView');
    var gridView = document.getElementById('gridView');
    var btnList = document.getElementById('btnListView');
    var btnGrid = document.getElementById('btnGridView');

    if (btnList && btnGrid) {
        btnList.addEventListener('click', function() {
            listView.style.display = '';
            gridView.style.display = 'none';
            btnList.classList.add('active');
            btnGrid.classList.remove('active');
        });
        btnGrid.addEventListener('click', function() {
            listView.style.display = 'none';
            gridView.style.display = '';
            btnGrid.classList.add('active');
            btnList.classList.remove('active');
        });
    }

    // Upload zone interactions
    var dropZone = document.getElementById('dropZone');
    var fileInput = document.getElementById('fileInput');
    var fileInfo = document.getElementById('fileInfo');
    var fileName = document.getElementById('fileName');
    var fileClear = document.getElementById('fileClear');
    var uploadBtn = document.getElementById('uploadBtn');

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
                fileInfo.classList.add('show');
                uploadBtn.disabled = false;
            }
        });
    }

    if (fileClear) {
        fileClear.addEventListener('click', function() {
            fileInput.value = '';
            fileInfo.classList.remove('show');
            uploadBtn.disabled = true;
        });
    }

    if (dropZone) {
        ['dragenter', 'dragover'].forEach(function(evt) {
            dropZone.addEventListener(evt, function(e) {
                e.preventDefault();
                dropZone.classList.add('dragover');
            });
        });
        ['dragleave', 'drop'].forEach(function(evt) {
            dropZone.addEventListener(evt, function(e) {
                e.preventDefault();
                dropZone.classList.remove('dragover');
            });
        });
        dropZone.addEventListener('drop', function(e) {
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                fileName.textContent = e.dataTransfer.files[0].name;
                fileInfo.classList.add('show');
                uploadBtn.disabled = false;
            }
        });
    }
});
</script>
