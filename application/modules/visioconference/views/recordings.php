<style>
.rec-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}
.rec-page-header h2 {
    font-size: 22px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.rec-page-header h2 i { color: var(--primary); }
.rec-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: var(--radius);
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: var(--transition);
}
.rec-back-btn:hover { border-color: var(--primary); color: var(--primary); }

.rec-stats {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}
.rec-stat-card {
    flex: 1;
    min-width: 160px;
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
}
.rec-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.rec-stat-icon.red { background: #fce8e6; color: #d93025; }
.rec-stat-icon.blue { background: var(--primary-light); color: var(--primary); }
.rec-stat-icon.green { background: #e6f4ea; color: #137333; }
.rec-stat-value { font-size: 22px; font-weight: 700; color: var(--text-primary); }
.rec-stat-label { font-size: 12px; color: var(--text-muted); }

.rec-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 16px;
}
.rec-card {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: var(--transition);
}
.rec-card:hover { box-shadow: var(--shadow-sm); transform: translateY(-2px); }
.rec-card-preview {
    position: relative;
    background: #0f0f1a;
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}
.rec-card-preview video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.rec-card-preview .play-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,0,0,.35);
    opacity: 0;
    transition: opacity .2s;
}
.rec-card-preview:hover .play-overlay { opacity: 1; }
.play-overlay i { font-size: 40px; color: #fff; }
.rec-card-duration {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: rgba(0,0,0,.75);
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 4px;
    font-variant-numeric: tabular-nums;
}
.rec-card-body { padding: 14px 16px; }
.rec-card-title {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 6px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.rec-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 12px;
    color: var(--text-muted);
    margin-bottom: 12px;
}
.rec-card-meta span { display: flex; align-items: center; gap: 4px; }
.rec-card-actions {
    display: flex;
    gap: 8px;
}
.rec-card-actions .btn {
    flex: 1;
    font-size: 12px;
    padding: 6px 12px;
    border-radius: var(--radius);
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}

.rec-empty {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-muted);
}
.rec-empty i { font-size: 48px; opacity: .3; margin-bottom: 16px; }
.rec-empty p { font-size: 15px; margin: 0; }

/* Video Modal */
.rec-modal-video {
    width: 100%;
    max-height: 70vh;
    border-radius: var(--radius);
    background: #000;
}
</style>

<div class="rec-page-header">
    <h2><i class="fas fa-circle-dot"></i> <?php echo $page_title; ?></h2>
    <div>
        <?php if ($reunion): ?>
            <a href="<?php echo site_url('reunions/view/' . (int) $reunion->id); ?>" class="rec-back-btn">
                <i class="fas fa-arrow-left"></i> Retour à la réunion
            </a>
        <?php else: ?>
            <a href="<?php echo site_url('reunions'); ?>" class="rec-back-btn">
                <i class="fas fa-arrow-left"></i> Réunions
            </a>
        <?php endif; ?>
    </div>
</div>

<?php
$total_count = count($recordings);
$total_size = 0;
$total_duration = 0;
foreach ($recordings as $r) {
    $total_size += (int) $r->file_size;
    $total_duration += (int) $r->duration;
}
?>

<div class="rec-stats">
    <div class="rec-stat-card">
        <div class="rec-stat-icon red"><i class="fas fa-circle-dot"></i></div>
        <div>
            <div class="rec-stat-value"><?php echo $total_count; ?></div>
            <div class="rec-stat-label">Enregistrement<?php echo $total_count > 1 ? 's' : ''; ?></div>
        </div>
    </div>
    <div class="rec-stat-card">
        <div class="rec-stat-icon blue"><i class="fas fa-clock"></i></div>
        <div>
            <div class="rec-stat-value"><?php echo $this->Recordings_model->format_duration($total_duration); ?></div>
            <div class="rec-stat-label">Durée totale</div>
        </div>
    </div>
    <div class="rec-stat-card">
        <div class="rec-stat-icon green"><i class="fas fa-hard-drive"></i></div>
        <div>
            <div class="rec-stat-value"><?php echo $this->Recordings_model->format_file_size($total_size); ?></div>
            <div class="rec-stat-label">Espace utilisé</div>
        </div>
    </div>
</div>

<?php if (empty($recordings)): ?>
    <div class="rec-empty">
        <i class="fas fa-video-slash d-block"></i>
        <p>Aucun enregistrement disponible</p>
    </div>
<?php else: ?>
    <div class="rec-grid">
        <?php foreach ($recordings as $rec): ?>
            <?php
                $file_url = base_url('assets/uploads/recordings/' . rawurlencode($rec->filename));
                $download_url = site_url('visioconference/download_recording/' . (int) $rec->id);
                $can_delete = ($is_admin || (int) $rec->user_id === $current_user_id);
                $reunion_title = isset($rec->reunion_title) ? $rec->reunion_title : (isset($reunion->title) ? $reunion->title : 'Réunion');
            ?>
            <div class="rec-card" id="recCard-<?php echo (int) $rec->id; ?>">
                <div class="rec-card-preview" onclick="openRecordingModal('<?php echo addslashes($file_url); ?>', '<?php echo addslashes(htmlspecialchars($rec->original_name, ENT_QUOTES, 'UTF-8')); ?>')">
                    <video src="<?php echo htmlspecialchars($file_url, ENT_QUOTES, 'UTF-8'); ?>" preload="metadata" muted></video>
                    <div class="play-overlay"><i class="fas fa-play-circle"></i></div>
                    <div class="rec-card-duration"><?php echo $this->Recordings_model->format_duration((int) $rec->duration); ?></div>
                </div>
                <div class="rec-card-body">
                    <div class="rec-card-title" title="<?php echo htmlspecialchars($rec->original_name, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($rec->original_name, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <div class="rec-card-meta">
                        <?php if (!$reunion && isset($rec->reunion_title)): ?>
                            <span><i class="fas fa-calendar-check"></i> <?php echo htmlspecialchars($rec->reunion_title, ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                        <span><i class="fas fa-user"></i> <?php echo htmlspecialchars(isset($rec->recorder_name) ? $rec->recorder_name : 'Inconnu', ENT_QUOTES, 'UTF-8'); ?></span>
                        <span><i class="fas fa-clock"></i> <?php echo date('d/m/Y H:i', strtotime($rec->created_at)); ?></span>
                        <span><i class="fas fa-hard-drive"></i> <?php echo $this->Recordings_model->format_file_size((int) $rec->file_size); ?></span>
                    </div>
                    <div class="rec-card-actions">
                        <a href="<?php echo htmlspecialchars($download_url, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-download"></i> Télécharger
                        </a>
                        <button class="btn btn-outline-secondary btn-sm" onclick="openRecordingModal('<?php echo addslashes($file_url); ?>', '<?php echo addslashes(htmlspecialchars($rec->original_name, ENT_QUOTES, 'UTF-8')); ?>')">
                            <i class="fas fa-play"></i> Lire
                        </button>
                        <?php if ($can_delete): ?>
                            <button class="btn btn-outline-danger btn-sm" onclick="deleteRecording(<?php echo (int) $rec->id; ?>)" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Video Player Modal -->
<div class="modal fade" id="recordingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg-card); border: 1px solid var(--border-color);">
            <div class="modal-header" style="border-color: var(--border-color);">
                <h5 class="modal-title" id="recordingModalTitle" style="font-size: 15px; font-weight: 600;">
                    <i class="fas fa-play-circle text-primary me-2"></i> Lecture
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body p-0">
                <video id="modalVideo" class="rec-modal-video" controls autoplay></video>
            </div>
        </div>
    </div>
</div>

<script>
function openRecordingModal(url, title) {
    var modal = new bootstrap.Modal(document.getElementById('recordingModal'));
    document.getElementById('recordingModalTitle').innerHTML = '<i class="fas fa-play-circle text-primary me-2"></i> ' + title;
    var video = document.getElementById('modalVideo');
    video.src = url;
    modal.show();
}

document.getElementById('recordingModal').addEventListener('hidden.bs.modal', function() {
    var video = document.getElementById('modalVideo');
    video.pause();
    video.src = '';
});

function deleteRecording(id) {
    if (!confirm('Supprimer cet enregistrement ? Cette action est irréversible.')) return;
    fetch(SITE_URL + 'visioconference/delete_recording/' + id, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            var card = document.getElementById('recCard-' + id);
            if (card) {
                card.style.transition = 'opacity .3s, transform .3s';
                card.style.opacity = '0';
                card.style.transform = 'scale(.95)';
                setTimeout(function() { card.remove(); }, 300);
            }
        } else {
            alert(data.message || 'Erreur lors de la suppression');
        }
    })
    .catch(function() { alert('Erreur réseau'); });
}
</script>
