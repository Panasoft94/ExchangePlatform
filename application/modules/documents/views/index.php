<?php
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

function doc_format_size($bytes) {
    if ($bytes <= 0) return '—';
    if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' Mo';
    if ($bytes >= 1024) return round($bytes / 1024, 1) . ' Ko';
    return $bytes . ' o';
}

function is_previewable($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, array('pdf', 'png', 'jpg', 'jpeg', 'gif', 'txt', 'csv'));
}

$esc = function($v) { return htmlspecialchars($v, ENT_QUOTES, 'UTF-8'); };
$search = isset($filters['search']) ? $filters['search'] : '';
$active_tab = isset($tab) ? $tab : 'all';
$active_cat = isset($filters['category_id']) ? (int) $filters['category_id'] : 0;
$active_vis = isset($filters['visibility']) ? $filters['visibility'] : '';
?>

<style>
/* ===== Stats Cards ===== */
.doc-stats { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:12px; margin-bottom:24px; }
.doc-stat-card {
    background:var(--bg-white); border:1px solid var(--border-color); border-radius:var(--radius-lg);
    padding:16px 18px; display:flex; align-items:center; gap:14px;
}
.doc-stat-icon {
    width:42px; height:42px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:16px;
}
.doc-stat-val { font-size:20px; font-weight:700; color:var(--text-primary); line-height:1; }
.doc-stat-lbl { font-size:11px; color:var(--text-muted); margin-top:2px; }

/* ===== Search & Filters ===== */
.doc-search-bar {
    background:var(--bg-white); border:1px solid var(--border-color); border-radius:24px;
    display:flex; align-items:center; padding:4px 16px; max-width:480px; transition:var(--transition);
}
.doc-search-bar:focus-within { border-color:var(--primary); box-shadow:0 0 0 3px rgba(26,115,232,.15); }
.doc-search-bar input { border:none; outline:none; background:transparent; flex:1; padding:8px; font-size:14px; color:var(--text-primary); }
.doc-search-bar input::placeholder { color:var(--text-muted); }
.doc-search-bar .search-icon { color:var(--text-muted); font-size:14px; }

.doc-filter-pills { display:flex; flex-wrap:wrap; gap:6px; }
.doc-pill {
    display:inline-flex; align-items:center; gap:5px; padding:5px 14px; border-radius:20px;
    font-size:12px; font-weight:500; border:1px solid var(--border-color); background:var(--bg-white);
    color:var(--text-secondary); text-decoration:none; transition:var(--transition); cursor:pointer;
}
.doc-pill:hover { border-color:var(--primary); color:var(--primary); background:var(--primary-light); }
.doc-pill.active { background:var(--primary); color:#fff; border-color:var(--primary); }
.doc-pill .pill-count { font-size:10px; background:rgba(0,0,0,.08); padding:1px 6px; border-radius:10px; }
.doc-pill.active .pill-count { background:rgba(255,255,255,.25); }

/* ===== Category Sidebar ===== */
.doc-cat-list { display:flex; flex-direction:column; gap:2px; }
.doc-cat-item {
    display:flex; align-items:center; gap:10px; padding:8px 12px; border-radius:var(--radius);
    font-size:13px; color:var(--text-primary); text-decoration:none; transition:var(--transition);
}
.doc-cat-item:hover { background:var(--bg-main); }
.doc-cat-item.active { background:var(--primary-light); color:var(--primary); font-weight:600; }
.doc-cat-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }
.doc-cat-count { margin-left:auto; font-size:11px; color:var(--text-muted); }

/* ===== View Toggle ===== */
.doc-view-toggle .btn {
    border:1px solid var(--border-color); background:var(--bg-white); color:var(--text-secondary);
    padding:6px 12px; font-size:14px; transition:var(--transition);
}
.doc-view-toggle .btn.active { background:var(--primary-light); color:var(--primary); border-color:var(--primary); }

/* ===== Table ===== */
.doc-table {
    background:var(--bg-white); border:1px solid var(--border-color); border-radius:var(--radius-lg); overflow:hidden;
}
.doc-table table { margin-bottom:0; }
.doc-table thead th {
    background:var(--bg-main); border-bottom:1px solid var(--border-color);
    font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.3px;
    color:var(--text-secondary); padding:10px 14px; white-space:nowrap;
}
.doc-table tbody tr { transition:var(--transition); }
.doc-table tbody tr:hover { background:var(--primary-light); }
.doc-table tbody td { padding:10px 14px; vertical-align:middle; border-bottom:1px solid var(--border-color); font-size:13px; }
.doc-table tbody tr:last-child td { border-bottom:none; }

.doc-file-icon {
    width:36px; height:36px; border-radius:var(--radius); display:flex; align-items:center; justify-content:center;
    font-size:16px; flex-shrink:0;
}
.doc-filename { font-weight:500; color:var(--text-primary); word-break:break-word; text-decoration:none; }
.doc-filename:hover { color:var(--primary); }
.doc-meta { font-size:12px; color:var(--text-secondary); }

.doc-actions .btn {
    width:30px; height:30px; padding:0; display:inline-flex; align-items:center; justify-content:center;
    border-radius:50%; border:none; background:transparent; color:var(--text-secondary); font-size:13px; transition:var(--transition);
}
.doc-actions .btn:hover { background:var(--bg-main); }
.doc-actions .btn-download:hover { color:var(--primary); background:var(--primary-light); }
.doc-actions .btn-preview:hover { color:#34a853; background:#e6f4ea; }
.doc-actions .btn-delete:hover { color:#ea4335; background:#fce8e6; }

/* ===== Badges ===== */
.badge-public { background:#e6f4ea; color:#34a853; font-size:10px; font-weight:600; padding:3px 8px; border-radius:4px; }
.badge-private { background:#fce8e6; color:#ea4335; font-size:10px; font-weight:600; padding:3px 8px; border-radius:4px; }
.badge-cat { font-size:10px; font-weight:500; padding:3px 8px; border-radius:4px; }

/* ===== Grid ===== */
.doc-grid-item {
    background:var(--bg-white); border:1px solid var(--border-color); border-radius:var(--radius-lg);
    padding:18px; transition:var(--transition); display:flex; flex-direction:column; align-items:center;
    text-align:center; height:100%; text-decoration:none;
}
.doc-grid-item:hover { box-shadow:var(--shadow-md); transform:translateY(-2px); }
.doc-grid-icon { width:52px; height:52px; border-radius:var(--radius-lg); display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:10px; }
.doc-grid-name { font-size:12px; font-weight:500; color:var(--text-primary); word-break:break-word; margin-bottom:3px; line-height:1.3; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.doc-grid-meta { font-size:10px; color:var(--text-muted); margin-bottom:10px; }
.doc-grid-actions { margin-top:auto; display:flex; gap:4px; }

/* ===== Upload Modal ===== */
.upload-drop-zone {
    border:2px dashed var(--border-color); border-radius:var(--radius-lg); padding:32px 20px;
    text-align:center; transition:var(--transition); cursor:pointer; position:relative; background:var(--bg-main);
}
.upload-drop-zone:hover, .upload-drop-zone.dragover { border-color:var(--primary); background:var(--primary-light); }
.upload-drop-zone .upload-icon { font-size:40px; color:var(--primary); margin-bottom:10px; }
.upload-drop-zone .upload-text { font-size:14px; font-weight:500; color:var(--text-primary); margin-bottom:4px; }
.upload-drop-zone .upload-hint { font-size:12px; color:var(--text-muted); }
.upload-drop-zone input[type="file"] { position:absolute; inset:0; opacity:0; cursor:pointer; }
.upload-file-info { display:none; background:var(--primary-light); border-radius:var(--radius); padding:10px 14px; margin-top:10px; font-size:12px; color:var(--primary); font-weight:500; }
.upload-file-info.show { display:flex; align-items:center; gap:8px; }

/* ===== Empty ===== */
.doc-empty { text-align:center; padding:60px 20px; }
.doc-empty-icon { font-size:56px; color:var(--border-color); margin-bottom:14px; }
.doc-empty-title { font-size:17px; font-weight:600; color:var(--text-primary); margin-bottom:6px; }
.doc-empty-text { font-size:13px; color:var(--text-secondary); margin-bottom:18px; }

/* ===== Share User Picker ===== */
.share-user-picker { max-height:200px; overflow-y:auto; }
.share-user-item { display:flex; align-items:center; gap:8px; padding:6px 0; }
.share-user-avatar { width:28px; height:28px; border-radius:50%; background:var(--primary-light); display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; color:var(--primary); overflow:hidden; flex-shrink:0; }
.share-user-avatar img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
.share-user-name { font-size:13px; color:var(--text-primary); flex:1; }
</style>

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

<!-- Stats -->
<div class="doc-stats">
    <div class="doc-stat-card">
        <div class="doc-stat-icon" style="background:var(--primary-light);color:var(--primary);"><i class="fas fa-file-alt"></i></div>
        <div><div class="doc-stat-val"><?php echo (int) $stats['total']; ?></div><div class="doc-stat-lbl">Total documents</div></div>
    </div>
    <div class="doc-stat-card">
        <div class="doc-stat-icon" style="background:#e6f4ea;color:#34a853;"><i class="fas fa-globe"></i></div>
        <div><div class="doc-stat-val"><?php echo (int) $stats['public']; ?></div><div class="doc-stat-lbl">Documents publics</div></div>
    </div>
    <div class="doc-stat-card">
        <div class="doc-stat-icon" style="background:#fce8e6;color:#ea4335;"><i class="fas fa-lock"></i></div>
        <div><div class="doc-stat-val"><?php echo (int) $stats['private']; ?></div><div class="doc-stat-lbl">Documents privés</div></div>
    </div>
    <div class="doc-stat-card">
        <div class="doc-stat-icon" style="background:#f3e8fd;color:#a142f4;"><i class="fas fa-user"></i></div>
        <div><div class="doc-stat-val"><?php echo (int) $stats['mine']; ?></div><div class="doc-stat-lbl">Mes documents</div></div>
    </div>
    <div class="doc-stat-card">
        <div class="doc-stat-icon" style="background:#fef7e0;color:#f9ab00;"><i class="fas fa-database"></i></div>
        <div><div class="doc-stat-val"><?php echo doc_format_size($stats['size']); ?></div><div class="doc-stat-lbl">Espace utilisé</div></div>
    </div>
</div>

<!-- Header -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 fw-semibold mb-1" style="color:var(--text-primary);">
            <i class="fas fa-folder-open me-2" style="color:var(--primary);"></i>Documents
        </h1>
        <p class="mb-0 small" style="color:var(--text-secondary);">Gérez vos documents publics et privés</p>
    </div>
    <div class="d-flex gap-2 mt-2 mt-md-0">
        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#categoryModal">
            <i class="fas fa-tags me-1"></i> Catégories
        </button>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-cloud-arrow-up me-1"></i> Nouveau document
        </button>
    </div>
</div>

<!-- Search + Filters -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-3">
    <form method="get" action="<?php echo site_url('documents'); ?>" class="flex-grow-1" style="max-width:480px;">
        <?php if ($active_tab !== 'all'): ?><input type="hidden" name="tab" value="<?php echo $esc($active_tab); ?>"><?php endif; ?>
        <?php if ($active_cat > 0): ?><input type="hidden" name="category" value="<?php echo $active_cat; ?>"><?php endif; ?>
        <?php if ($active_vis): ?><input type="hidden" name="visibility" value="<?php echo $esc($active_vis); ?>"><?php endif; ?>
        <div class="doc-search-bar">
            <i class="fas fa-search search-icon"></i>
            <input type="text" name="q" placeholder="Rechercher un document..." value="<?php echo $esc($search); ?>" autocomplete="off">
            <?php if ($search): ?>
                <a href="<?php echo site_url('documents'); ?>" style="border:none;background:none;color:var(--text-muted);cursor:pointer;padding:4px;" title="Effacer"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </div>
    </form>
    <div class="doc-view-toggle btn-group" role="group">
        <button type="button" class="btn active" id="btnListView" title="Liste"><i class="fas fa-list"></i></button>
        <button type="button" class="btn" id="btnGridView" title="Grille"><i class="fas fa-grip"></i></button>
    </div>
</div>

<!-- Filter Pills -->
<div class="d-flex flex-wrap gap-3 mb-4">
    <div class="doc-filter-pills">
        <?php
        $base = site_url('documents');
        $qp = $search ? '&q=' . urlencode($search) : '';
        $cp = $active_cat > 0 ? '&category=' . $active_cat : '';
        ?>
        <a href="<?php echo $base . '?' . ltrim($qp . $cp, '&'); ?>" class="doc-pill <?php echo ($active_tab === 'all' && !$active_vis) ? 'active' : ''; ?>">
            <i class="fas fa-layer-group"></i> Tous <span class="pill-count"><?php echo (int) $stats['total']; ?></span>
        </a>
        <a href="<?php echo $base . '?visibility=public' . $qp . $cp; ?>" class="doc-pill <?php echo $active_vis === 'public' ? 'active' : ''; ?>">
            <i class="fas fa-globe"></i> Publics <span class="pill-count"><?php echo (int) $stats['public']; ?></span>
        </a>
        <a href="<?php echo $base . '?visibility=private' . $qp . $cp; ?>" class="doc-pill <?php echo $active_vis === 'private' ? 'active' : ''; ?>">
            <i class="fas fa-lock"></i> Privés <span class="pill-count"><?php echo (int) $stats['private']; ?></span>
        </a>
        <a href="<?php echo $base . '?tab=mine' . $qp . $cp; ?>" class="doc-pill <?php echo $active_tab === 'mine' ? 'active' : ''; ?>">
            <i class="fas fa-user"></i> Mes documents <span class="pill-count"><?php echo (int) $stats['mine']; ?></span>
        </a>
    </div>

    <?php if (!empty($categories)): ?>
    <div class="doc-filter-pills">
        <?php foreach ($categories as $cat): ?>
        <a href="<?php echo $base . '?category=' . (int) $cat->id . ($active_vis ? '&visibility=' . urlencode($active_vis) : '') . ($active_tab === 'mine' ? '&tab=mine' : '') . $qp; ?>" class="doc-pill <?php echo $active_cat === (int) $cat->id ? 'active' : ''; ?>" style="<?php echo $active_cat === (int) $cat->id ? 'background:' . $esc($cat->color) . ';border-color:' . $esc($cat->color) . ';' : ''; ?>">
            <span style="width:8px;height:8px;border-radius:50%;background:<?php echo $esc($cat->color); ?>;display:inline-block;"></span>
            <?php echo $esc($cat->name); ?>
        </a>
        <?php endforeach; ?>
        <?php if ($active_cat > 0): ?>
        <a href="<?php echo $base . '?' . ltrim(($active_vis ? 'visibility=' . urlencode($active_vis) : '') . ($active_tab === 'mine' ? '&tab=mine' : '') . $qp, '&'); ?>" class="doc-pill" style="color:var(--text-muted);"><i class="fas fa-times"></i></a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php if (!empty($documents)): ?>

<!-- LIST VIEW -->
<div id="listView">
    <div class="doc-table">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th style="width:44px;"></th>
                    <th>Nom</th>
                    <th>Visibilité</th>
                    <th>Catégorie</th>
                    <th>Partagé par</th>
                    <th>Date</th>
                    <th>Taille</th>
                    <th>Téléch.</th>
                    <th class="text-end" style="width:130px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($documents as $doc):
                    $icon = get_file_icon($doc->filename);
                    $is_owner = (int) $doc->uploaded_by === $current_user_id;
                ?>
                <tr>
                    <td>
                        <div class="doc-file-icon" style="background:<?php echo $icon['bg']; ?>;color:<?php echo $icon['color']; ?>;">
                            <i class="<?php echo $icon['icon']; ?>"></i>
                        </div>
                    </td>
                    <td>
                        <a href="<?php echo site_url('documents/view/' . $doc->id); ?>" class="doc-filename"><?php echo $esc($doc->filename); ?></a>
                        <?php if (!empty($doc->description)): ?>
                            <div class="doc-meta" style="margin-top:2px;"><?php echo $esc(mb_strimwidth($doc->description, 0, 60, '...')); ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($doc->visibility === 'private'): ?>
                            <span class="badge-private"><i class="fas fa-lock me-1"></i>Privé</span>
                        <?php else: ?>
                            <span class="badge-public"><i class="fas fa-globe me-1"></i>Public</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($doc->category_name)): ?>
                            <span class="badge-cat" style="background:<?php echo $esc($doc->category_color); ?>15;color:<?php echo $esc($doc->category_color); ?>;"><?php echo $esc($doc->category_name); ?></span>
                        <?php else: ?>
                            <span class="doc-meta">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="doc-meta"><?php echo $esc((isset($doc->users_prenom) ? $doc->users_prenom : '') . ' ' . (isset($doc->users_nom) ? $doc->users_nom : '')); ?></td>
                    <td class="doc-meta"><?php echo date('d/m/Y H:i', strtotime($doc->created_at)); ?></td>
                    <td class="doc-meta"><?php echo doc_format_size($doc->file_size); ?></td>
                    <td class="doc-meta text-center"><?php echo (int) $doc->download_count; ?></td>
                    <td class="text-end">
                        <div class="doc-actions d-inline-flex gap-1">
                            <?php if (is_previewable($doc->filename)): ?>
                            <a href="<?php echo site_url('documents/preview/' . $doc->id); ?>" class="btn btn-preview" title="Aperçu" target="_blank"><i class="fas fa-eye"></i></a>
                            <?php endif; ?>
                            <a href="<?php echo site_url('documents/download/' . $doc->id); ?>" class="btn btn-download" title="Télécharger"><i class="fas fa-download"></i></a>
                            <?php if ($is_owner): ?>
                            <form method="post" action="<?php echo site_url('documents/delete/' . $doc->id); ?>" style="display:inline;" onsubmit="return confirm('Supprimer ce document ?');">
                                <button type="submit" class="btn btn-delete" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- GRID VIEW -->
<div id="gridView" style="display:none;">
    <div class="row g-3">
        <?php foreach ($documents as $doc):
            $icon = get_file_icon($doc->filename);
            $is_owner = (int) $doc->uploaded_by === $current_user_id;
        ?>
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <div class="doc-grid-item">
                <div class="doc-grid-icon" style="background:<?php echo $icon['bg']; ?>;color:<?php echo $icon['color']; ?>;">
                    <i class="<?php echo $icon['icon']; ?>"></i>
                </div>
                <?php if ($doc->visibility === 'private'): ?>
                    <span class="badge-private mb-1" style="font-size:9px;"><i class="fas fa-lock me-1"></i>Privé</span>
                <?php endif; ?>
                <a href="<?php echo site_url('documents/view/' . $doc->id); ?>" class="doc-grid-name" title="<?php echo $esc($doc->filename); ?>"><?php echo $esc($doc->filename); ?></a>
                <div class="doc-grid-meta">
                    <?php echo $esc(isset($doc->users_prenom) ? $doc->users_prenom : ''); ?> · <?php echo date('d/m/Y', strtotime($doc->created_at)); ?>
                    <br><?php echo doc_format_size($doc->file_size); ?>
                    <?php if ((int) $doc->download_count > 0): ?> · <?php echo (int) $doc->download_count; ?> <i class="fas fa-download" style="font-size:9px;"></i><?php endif; ?>
                </div>
                <div class="doc-grid-actions">
                    <?php if (is_previewable($doc->filename)): ?>
                    <a href="<?php echo site_url('documents/preview/' . $doc->id); ?>" class="btn btn-sm btn-outline-secondary" title="Aperçu" target="_blank"><i class="fas fa-eye"></i></a>
                    <?php endif; ?>
                    <a href="<?php echo site_url('documents/download/' . $doc->id); ?>" class="btn btn-sm btn-outline-primary" title="Télécharger"><i class="fas fa-download"></i></a>
                    <?php if ($is_owner): ?>
                    <form method="post" action="<?php echo site_url('documents/delete/' . $doc->id); ?>" style="display:inline;" onsubmit="return confirm('Supprimer ?');">
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php else: ?>
<div class="doc-empty">
    <div class="doc-empty-icon"><i class="fas fa-folder-open"></i></div>
    <?php if ($search): ?>
        <div class="doc-empty-title">Aucun résultat</div>
        <div class="doc-empty-text">Aucun document ne correspond à « <?php echo $esc($search); ?> »</div>
        <a href="<?php echo site_url('documents'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-arrow-left me-1"></i> Tous les documents</a>
    <?php else: ?>
        <div class="doc-empty-title">Aucun document</div>
        <div class="doc-empty-text">Commencez par partager un document avec votre équipe.</div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-cloud-arrow-up me-1"></i> Nouveau document
        </button>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border:none;border-radius:var(--radius-lg);overflow:hidden;">
            <div class="modal-header" style="border-bottom:1px solid var(--border-color);padding:14px 24px;">
                <h5 class="modal-title fw-semibold" style="font-size:15px;">
                    <i class="fas fa-cloud-arrow-up me-2" style="color:var(--primary);"></i>Nouveau document
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <?php echo form_open_multipart('documents/upload', array('id' => 'uploadForm')); ?>
            <div class="modal-body" style="padding:20px 24px;">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="upload-drop-zone" id="dropZone">
                            <input type="file" name="document" id="fileInput" required>
                            <div class="upload-icon"><i class="fas fa-cloud-arrow-up"></i></div>
                            <div class="upload-text">Glissez un fichier ici</div>
                            <div class="upload-hint">PDF, DOC, XLS, PPT, TXT, CSV, ZIP, PNG, JPG — Max 10 Mo</div>
                        </div>
                        <div class="upload-file-info" id="fileInfo">
                            <i class="fas fa-file"></i>
                            <span id="fileName"></span>
                            <button type="button" class="btn-close ms-auto" id="fileClear" style="font-size:10px;"></button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-medium">Visibilité</label>
                        <select name="visibility" class="form-select form-select-sm" id="uploadVisibility">
                            <option value="public">Public — Visible par tous</option>
                            <option value="private">Privé — Accès restreint</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-medium">Catégorie</label>
                        <select name="category_id" class="form-select form-select-sm">
                            <option value="">— Aucune —</option>
                            <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo (int) $cat->id; ?>"><?php echo $esc($cat->name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-medium">Description <span class="text-muted">(optionnel)</span></label>
                        <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Décrivez le contenu du document..." maxlength="500"></textarea>
                    </div>
                    <div class="col-12" id="shareUsersSection" style="display:none;">
                        <label class="form-label small fw-medium">Partager avec</label>
                        <input type="text" class="form-control form-control-sm mb-2" id="shareSearch" placeholder="Rechercher un utilisateur...">
                        <div class="share-user-picker" id="shareUserList">
                            <?php if (!empty($all_users)): foreach ($all_users as $u):
                                if ((int) $u->users_id === $current_user_id) continue;
                                $uname = trim((isset($u->users_prenom) ? $u->users_prenom : '') . ' ' . (isset($u->users_nom) ? $u->users_nom : ''));
                                if ($uname === '') $uname = isset($u->users_username) ? $u->users_username : 'Utilisateur';
                            ?>
                            <label class="share-user-item" data-name="<?php echo $esc(strtolower($uname)); ?>">
                                <input type="checkbox" name="shared_users[]" value="<?php echo (int) $u->users_id; ?>" class="form-check-input" style="margin:0;">
                                <div class="share-user-avatar">
                                    <?php if (!empty($u->photo_profil)): ?>
                                        <img src="<?php echo base_url('assets/img/avatar/' . rawurlencode(basename($u->photo_profil))); ?>" alt="">
                                    <?php else: ?>
                                        <?php echo strtoupper(substr($uname, 0, 2)); ?>
                                    <?php endif; ?>
                                </div>
                                <span class="share-user-name"><?php echo $esc($uname); ?></span>
                            </label>
                            <?php endforeach; endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid var(--border-color);padding:10px 24px;">
                <button type="button" class="btn btn-sm" style="color:var(--text-secondary);" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-primary btn-sm" id="uploadBtn" disabled>
                    <i class="fas fa-cloud-arrow-up me-1"></i> Partager
                </button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:var(--radius-lg);overflow:hidden;">
            <div class="modal-header" style="border-bottom:1px solid var(--border-color);padding:14px 24px;">
                <h5 class="modal-title fw-semibold" style="font-size:15px;">
                    <i class="fas fa-tags me-2" style="color:var(--primary);"></i>Catégories
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:20px 24px;">
                <?php if (!empty($categories)): ?>
                <div class="doc-cat-list mb-3">
                    <?php foreach ($categories as $cat): ?>
                    <div class="d-flex align-items-center gap-2 py-2">
                        <span class="doc-cat-dot" style="background:<?php echo $esc($cat->color); ?>;"></span>
                        <span class="flex-grow-1" style="font-size:13px;color:var(--text-primary);"><?php echo $esc($cat->name); ?></span>
                        <form method="post" action="<?php echo site_url('documents/delete_category/' . (int) $cat->id); ?>" style="display:inline;" onsubmit="return confirm('Supprimer cette catégorie ?');">
                            <button type="submit" class="btn btn-sm" style="color:var(--text-muted);padding:2px 6px;"><i class="fas fa-times"></i></button>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <hr style="border-color:var(--border-color);">
                <?php echo form_open('documents/create_category'); ?>
                <div class="d-flex gap-2 align-items-end">
                    <div class="flex-grow-1">
                        <label class="form-label small fw-medium">Nouvelle catégorie</label>
                        <input type="text" name="name" class="form-control form-control-sm" required placeholder="Nom de la catégorie">
                    </div>
                    <div style="width:60px;">
                        <label class="form-label small fw-medium">Couleur</label>
                        <input type="color" name="color" class="form-control form-control-sm form-control-color" value="#1a73e8" style="height:31px;">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" style="height:31px;"><i class="fas fa-plus"></i></button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle with localStorage
    var listView = document.getElementById('listView');
    var gridView = document.getElementById('gridView');
    var btnList = document.getElementById('btnListView');
    var btnGrid = document.getElementById('btnGridView');
    var savedView = localStorage.getItem('docView');

    function showList() { if (listView) listView.style.display=''; if (gridView) gridView.style.display='none'; btnList.classList.add('active'); btnGrid.classList.remove('active'); localStorage.setItem('docView','list'); }
    function showGrid() { if (listView) listView.style.display='none'; if (gridView) gridView.style.display=''; btnGrid.classList.add('active'); btnList.classList.remove('active'); localStorage.setItem('docView','grid'); }

    if (savedView === 'grid') showGrid();
    if (btnList) btnList.addEventListener('click', showList);
    if (btnGrid) btnGrid.addEventListener('click', showGrid);

    // Upload
    var fileInput = document.getElementById('fileInput');
    var fileInfo = document.getElementById('fileInfo');
    var fileName = document.getElementById('fileName');
    var fileClear = document.getElementById('fileClear');
    var uploadBtn = document.getElementById('uploadBtn');
    var dropZone = document.getElementById('dropZone');

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) { fileName.textContent = this.files[0].name; fileInfo.classList.add('show'); uploadBtn.disabled = false; }
        });
    }
    if (fileClear) {
        fileClear.addEventListener('click', function() { fileInput.value=''; fileInfo.classList.remove('show'); uploadBtn.disabled = true; });
    }
    if (dropZone) {
        ['dragenter','dragover'].forEach(function(ev) { dropZone.addEventListener(ev, function(e) { e.preventDefault(); dropZone.classList.add('dragover'); }); });
        ['dragleave','drop'].forEach(function(ev) { dropZone.addEventListener(ev, function(e) { e.preventDefault(); dropZone.classList.remove('dragover'); }); });
        dropZone.addEventListener('drop', function(e) {
            if (e.dataTransfer.files.length > 0) { fileInput.files = e.dataTransfer.files; fileName.textContent = e.dataTransfer.files[0].name; fileInfo.classList.add('show'); uploadBtn.disabled = false; }
        });
    }

    // Visibility toggle → show/hide share users
    var visSelect = document.getElementById('uploadVisibility');
    var shareSection = document.getElementById('shareUsersSection');
    if (visSelect && shareSection) {
        visSelect.addEventListener('change', function() {
            shareSection.style.display = this.value === 'private' ? '' : 'none';
        });
    }

    // Share user search filter
    var shareSearch = document.getElementById('shareSearch');
    if (shareSearch) {
        shareSearch.addEventListener('input', function() {
            var q = this.value.toLowerCase();
            document.querySelectorAll('#shareUserList .share-user-item').forEach(function(item) {
                item.style.display = item.getAttribute('data-name').indexOf(q) !== -1 ? '' : 'none';
            });
        });
    }
});
</script>
