<style>
/* ===== Visio Shell ===== */
.visio-shell { display:flex; flex-direction:column; min-height:100vh; position:relative; }

/* ===== Top Bar ===== */
.visio-topbar {
    background: linear-gradient(135deg, var(--primary), #6366f1);
    color: #fff;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    flex-shrink: 0;
    z-index: 100;
    box-shadow: 0 2px 8px rgba(0,0,0,.18);
}
.visio-topbar-title {
    font-weight: 600;
    font-size: 15px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 300px;
}
.visio-topbar-desc {
    font-size: 12px;
    opacity: .85;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 250px;
}
.visio-topbar-info { flex:1; min-width:0; }
.visio-topbar-actions { display:flex; align-items:center; gap:8px; flex-shrink:0; }
.visio-topbar .badge-pill {
    font-size: 11px;
    padding: 3px 10px;
    border-radius: 20px;
    background: rgba(255,255,255,.18);
    color: #fff;
    border: 1px solid rgba(255,255,255,.25);
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.visio-topbar .badge-pill i { font-size: 10px; }
.visio-timer {
    font-variant-numeric: tabular-nums;
    font-weight: 600;
    font-size: 13px;
    letter-spacing: .5px;
}

/* ===== Main Layout ===== */
.visio-main {
    display: flex;
    flex: 1;
    overflow: hidden;
    background: #0f0f14;
}
.visio-stage {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
    position: relative;
}
.visio-sidebar {
    width: 340px;
    flex-shrink: 0;
    background: var(--bg-white);
    border-left: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: width .3s ease;
}
.visio-sidebar.collapsed { width: 0; border-left: none; }

/* ===== Video Grid ===== */
.visio-grid {
    flex: 1;
    display: grid;
    gap: 8px;
    padding: 12px;
    overflow-y: auto;
    align-content: start;
}
.visio-grid.grid-1 { grid-template-columns: 1fr; }
.visio-grid.grid-2 { grid-template-columns: repeat(2, 1fr); }
.visio-grid.grid-3, .visio-grid.grid-4 { grid-template-columns: repeat(2, 1fr); }
.visio-grid.grid-many { grid-template-columns: repeat(3, 1fr); }

/* ===== Speaker View ===== */
.visio-grid.speaker-view {
    grid-template-columns: 1fr;
    grid-template-rows: 1fr auto;
}
.visio-grid.speaker-view .video-card.speaker-main {
    grid-column: 1 / -1;
    min-height: 0;
}
.visio-grid.speaker-view .speaker-strip {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    padding: 4px 0;
    grid-column: 1 / -1;
}
.visio-grid.speaker-view .speaker-strip .video-card {
    width: 160px;
    height: 110px;
    flex-shrink: 0;
}

/* ===== Screen Share View ===== */
.visio-grid.screen-share-view {
    grid-template-columns: 1fr 200px;
    grid-template-rows: 1fr;
}
.visio-grid.screen-share-view .video-card.screen-share-card {
    grid-row: 1 / -1;
    min-height: 0;
}
.visio-grid.screen-share-view .screen-share-strip {
    display: flex;
    flex-direction: column;
    gap: 6px;
    overflow-y: auto;
}
.visio-grid.screen-share-view .screen-share-strip .video-card {
    height: 120px;
}

/* ===== Video Card ===== */
.video-card {
    background: #1a1a24;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    aspect-ratio: 16/9;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid transparent;
    transition: border-color .3s, box-shadow .3s;
}
.video-card.is-speaking { border-color: #22c55e; box-shadow: 0 0 16px rgba(34,197,94,.35); }
.video-card.hand-raised { border-color: #f59e0b; }
.video-card.is-screen-share {
    border-color: #6366f1;
    border-style: dashed;
    aspect-ratio: auto;
}
.video-card video {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.video-card .video-placeholder {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), #6366f1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 24px;
    text-transform: uppercase;
    position: absolute;
    z-index: 2;
}
.video-card .video-placeholder img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}
.video-card .video-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 8px 10px;
    background: linear-gradient(transparent, rgba(0,0,0,.7));
    display: flex;
    align-items: center;
    gap: 6px;
    z-index: 5;
}
.video-card .video-overlay .card-name {
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.video-card .video-overlay .card-badge {
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 4px;
    color: #fff;
}
.video-card .card-top-badges {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    gap: 4px;
    z-index: 5;
}
.video-card .card-top-badges .tbadge {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    color: #fff;
}
.tbadge-muted { background: rgba(239,68,68,.85); }
.tbadge-hand { background: rgba(245,158,11,.85); animation: pulse-hand 1.5s infinite; }
.tbadge-screen { background: rgba(99,102,241,.85); }

/* ===== Audio Meter ===== */
.audio-meter {
    position: absolute;
    bottom: 38px;
    left: 8px;
    width: 4px;
    height: 40px;
    background: rgba(255,255,255,.15);
    border-radius: 3px;
    overflow: hidden;
    z-index: 5;
}
.audio-meter-fill {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #22c55e;
    border-radius: 3px;
    transition: height .15s ease;
}

/* ===== Controls Bar ===== */
.visio-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 16px;
    background: #1a1a24;
    border-top: 1px solid rgba(255,255,255,.08);
    flex-wrap: wrap;
    flex-shrink: 0;
}
.ctrl-btn {
    min-width: 44px;
    height: 44px;
    border-radius: 12px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    padding: 0 14px;
    transition: all .2s;
    color: #fff;
    background: rgba(255,255,255,.1);
}
.ctrl-btn:hover { background: rgba(255,255,255,.18); transform: translateY(-1px); }
.ctrl-btn.active { background: var(--primary); }
.ctrl-btn.danger { background: #ef4444; }
.ctrl-btn.danger:hover { background: #dc2626; }
.ctrl-btn.warn { background: rgba(245,158,11,.85); }
.ctrl-btn .ctrl-label { font-size: 12px; }
.ctrl-btn.recording { background: #ef4444; animation: recPulse 1.5s ease-in-out infinite; }
@keyframes recPulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,.5); }
    50% { box-shadow: 0 0 0 8px rgba(239,68,68,0); }
}
@media (max-width:768px) {
    .ctrl-btn .ctrl-label { display:none; }
    .ctrl-btn { padding: 0 10px; }
}

/* ===== Status Pills (compact) ===== */
.visio-status-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    background: rgba(255,255,255,.04);
    font-size: 11px;
    color: rgba(255,255,255,.5);
    flex-shrink: 0;
    border-top: 1px solid rgba(255,255,255,.06);
    flex-wrap: wrap;
}
.status-dot {
    width: 7px; height: 7px; border-radius: 50%; display:inline-block; margin-right:3px;
}
.status-dot.green { background:#22c55e; }
.status-dot.red { background:#ef4444; }
.status-dot.orange { background:#f59e0b; }
.status-dot.blue { background:#3b82f6; }

/* ===== Sidebar Tabs ===== */
.sidebar-tabs {
    display: flex;
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
}
.sidebar-tab {
    flex: 1;
    padding: 10px 8px;
    text-align: center;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-secondary);
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: all .2s;
    position: relative;
    background: none;
    border-top: none;
    border-left: none;
    border-right: none;
}
.sidebar-tab:hover { color: var(--primary); background: var(--primary-light); }
.sidebar-tab.active { color: var(--primary); border-bottom-color: var(--primary); }
.sidebar-tab .tab-badge {
    position: absolute;
    top: 4px;
    right: 8px;
    min-width: 16px;
    height: 16px;
    border-radius: 8px;
    background: #ef4444;
    color: #fff;
    font-size: 9px;
    font-weight: 700;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
}
.sidebar-panel {
    flex: 1;
    overflow-y: auto;
    display: none;
    height: 0;
}
.sidebar-panel.active { display: flex; flex-direction: column; }

/* ===== Participant List (sidebar) ===== */
.part-list { padding: 8px; display:flex; flex-direction:column; gap:4px; }
.part-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: 8px;
    transition: background .2s;
}
.part-item:hover { background: var(--bg-main); }
.part-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), #6366f1);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 700; font-size: 13px; text-transform: uppercase;
    flex-shrink: 0; overflow: hidden;
}
.part-avatar img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
.part-name {
    font-size: 13px; font-weight: 500; color: var(--text-primary);
    flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
}
.part-status-icons { display:flex; gap:4px; align-items:center; }
.part-status-icons i { font-size: 11px; }
.part-online { color:#22c55e; }
.part-offline { color:var(--text-muted); opacity:.5; }
.part-host-tag {
    font-size: 9px; background: var(--primary-light); color: var(--primary);
    padding: 1px 6px; border-radius: 4px; font-weight: 600;
}

/* ===== Chat Panel ===== */
.chat-panel { display:flex; flex-direction:column; flex:1; min-height:0; height:0; }
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    background: var(--bg-main, #f8f9fa);
}
.chat-msg {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    animation: chat-in .25s ease;
    background: var(--bg-white, #fff);
    border: 1px solid var(--border-color, #e5e7eb);
    border-radius: 10px;
    padding: 10px 12px;
    transition: background .15s;
}
.chat-msg:hover { background: var(--primary-light, #eef2ff); }
.chat-msg-avatar {
    width: 32px; height: 32px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), #6366f1);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 700; font-size: 11px; text-transform: uppercase;
    flex-shrink: 0; overflow: hidden;
    box-shadow: 0 2px 6px rgba(99,102,241,.25);
}
.chat-msg-avatar img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
.chat-msg-body { flex:1; min-width:0; }
.chat-msg-header {
    display: flex; align-items: baseline; gap: 6px; margin-bottom: 3px;
}
.chat-msg-name { font-size: 12px; font-weight: 600; color: var(--text-primary); }
.chat-msg-time { font-size: 10px; color: var(--text-muted); margin-left: auto; }
.chat-msg-text {
    font-size: 13px; color: var(--text-primary); line-height: 1.5;
    word-break: break-word;
}
.chat-system {
    text-align: center;
    font-size: 11px;
    color: var(--text-muted);
    padding: 6px 12px;
    font-style: italic;
    background: rgba(0,0,0,.03);
    border-radius: 8px;
    margin: 2px 0;
}
.chat-input-bar {
    display: flex;
    gap: 6px;
    padding: 10px 12px;
    border-top: 1px solid var(--border-color);
    flex-shrink: 0;
}
.chat-input-bar input {
    flex: 1;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 13px;
    outline: none;
    background: var(--bg-main);
    color: var(--text-primary);
}
.chat-input-bar input:focus { border-color: var(--primary); }
.chat-input-bar button {
    border: none;
    background: var(--primary);
    color: #fff;
    border-radius: 8px;
    padding: 0 14px;
    cursor: pointer;
    font-size: 14px;
}
.chat-input-bar button:hover { background: var(--primary-hover); }

/* ===== Fullscreen ===== */
.visio-shell.fullscreen-mode .visio-topbar { display:none; }
.visio-shell.fullscreen-mode { position:fixed; top:0; left:0; right:0; bottom:0; z-index:9999; }

/* ===== Warning Box ===== */
.visio-warning {
    margin: 8px 12px;
    padding: 10px 14px;
    background: rgba(245,158,11,.12);
    border: 1px solid rgba(245,158,11,.3);
    border-radius: 8px;
    color: #fbbf24;
    font-size: 12px;
    display: none;
}

/* ===== Animations ===== */
@keyframes pulse-hand {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.15); }
}
@keyframes chat-in {
    from { opacity:0; transform:translateY(6px); }
    to { opacity:1; transform:translateY(0); }
}

/* ===== Responsive ===== */
@media (max-width:992px) {
    .visio-sidebar { width:280px; }
}
@media (max-width:768px) {
    .visio-sidebar {
        position: fixed; top:0; right:0; bottom:0; z-index:200;
        width: 300px;
        transform: translateX(100%);
        transition: transform .3s ease;
    }
    .visio-sidebar.open { transform: translateX(0); }
    .visio-sidebar.collapsed { transform: translateX(100%); }
    .sidebar-overlay {
        position:fixed; top:0; left:0; right:0; bottom:0;
        background:rgba(0,0,0,.5); z-index:199; display:none;
    }
    .sidebar-overlay.show { display:block; }
    .visio-topbar-desc { display:none; }
    .visio-topbar-title { max-width:150px; }
}

/* ===== Reconnect overlay ===== */
.reconnect-overlay {
    position: absolute;
    top:0; left:0; right:0; bottom:0;
    background: rgba(0,0,0,.7);
    z-index: 50;
    display: none;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 12px;
    color: #fff;
}
.reconnect-overlay.show { display:flex; }
.reconnect-spinner {
    width:36px; height:36px; border:3px solid rgba(255,255,255,.2);
    border-top-color:#fff; border-radius:50%; animation: spin .8s linear infinite;
}
@keyframes spin { to { transform:rotate(360deg); } }
</style>

<div class="visio-shell" id="visioShell">

    <!-- Top Bar -->
    <div class="visio-topbar">
        <a href="<?php echo htmlspecialchars($back_url); ?>" class="btn btn-sm" style="background:rgba(255,255,255,.15);color:#fff;border:none;" title="Retour">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="visio-topbar-info">
            <div class="visio-topbar-title"><?php echo htmlspecialchars($reunion->title); ?></div>
            <div class="visio-topbar-desc"><?php echo htmlspecialchars(isset($reunion->description) ? $reunion->description : ''); ?></div>
        </div>
        <div class="visio-topbar-actions">
            <span class="badge-pill"><i class="fas fa-users"></i> <span id="participantCount">0</span></span>
            <span class="badge-pill"><i class="fas fa-clock"></i> <span class="visio-timer" id="meetingTimer">00:00</span></span>
            <button class="btn btn-sm" style="background:rgba(255,255,255,.15);color:#fff;border:none;" id="btnCopyLink" title="Copier le lien">
                <i class="fas fa-link"></i>
            </button>
            <?php if (!empty($can_manage_visio_settings)): ?>
            <a href="<?php echo htmlspecialchars($configuration_url); ?>" class="btn btn-sm" style="background:rgba(255,255,255,.15);color:#fff;border:none;" title="Configuration">
                <i class="fas fa-cog"></i>
            </a>
            <?php endif; ?>
            <button class="btn btn-sm d-md-none" style="background:rgba(255,255,255,.15);color:#fff;border:none;" id="btnToggleSidebar" title="Panneau lateral">
                <i class="fas fa-columns"></i>
            </button>
        </div>
    </div>

    <!-- Main -->
    <div class="visio-main">
        <!-- Stage -->
        <div class="visio-stage" id="visioStage">
            <!-- Reconnect Overlay -->
            <div class="reconnect-overlay" id="reconnectOverlay">
                <div class="reconnect-spinner"></div>
                <div style="font-size:14px;font-weight:500;">Reconnexion en cours...</div>
                <div style="font-size:12px;opacity:.6;" id="reconnectMsg">Tentative 1</div>
            </div>

            <!-- Video Grid -->
            <div class="visio-grid grid-1" id="videoGrid"></div>
            <!-- Hidden audio elements for remote peers (persisted across grid re-renders) -->
            <div id="remoteAudioContainer" style="display:none;"></div>

            <!-- Warning -->
            <div class="visio-warning" id="warningBox"></div>

            <!-- Controls -->
            <div class="visio-controls">
                <button class="ctrl-btn" id="btnMute" title="Couper/réactiver le micro">
                    <i class="fas fa-microphone"></i>
                    <span class="ctrl-label">Micro</span>
                </button>
                <button class="ctrl-btn" id="btnCamera" title="Couper/réactiver la caméra">
                    <i class="fas fa-video"></i>
                    <span class="ctrl-label">Caméra</span>
                </button>
                <button class="ctrl-btn" id="btnScreenShare" title="Partager l ecran">
                    <i class="fas fa-desktop"></i>
                    <span class="ctrl-label">Ecran</span>
                </button>
                <button class="ctrl-btn" id="btnRecord" title="Enregistrer la réunion">
                    <i class="fas fa-circle"></i>
                    <span class="ctrl-label">Enregistrer</span>
                </button>
                <button class="ctrl-btn" id="btnRaiseHand" title="Demander la parole">
                    <i class="fas fa-hand-paper"></i>
                    <span class="ctrl-label">Main</span>
                </button>
                <button class="ctrl-btn" id="btnViewToggle" title="Changer la vue">
                    <i class="fas fa-th"></i>
                    <span class="ctrl-label">Vue</span>
                </button>
                <button class="ctrl-btn" id="btnFullscreen" title="Plein ecran">
                    <i class="fas fa-expand"></i>
                    <span class="ctrl-label">Ecran</span>
                </button>
                <?php if ($is_reunion_host): ?>
                <button class="ctrl-btn warn" id="btnMuteAll" title="Couper tous les micros">
                    <i class="fas fa-volume-mute"></i>
                    <span class="ctrl-label">Muter tous</span>
                </button>
                <button class="ctrl-btn danger" id="btnEndMeeting" title="Terminer la reunion pour tous">
                    <i class="fas fa-stop-circle"></i>
                    <span class="ctrl-label">Fin</span>
                </button>
                <?php endif; ?>
                <button class="ctrl-btn danger" id="btnLeave" title="Quitter">
                    <i class="fas fa-phone-slash"></i>
                    <span class="ctrl-label">Quitter</span>
                </button>
            </div>

            <!-- Status Bar -->
            <div class="visio-status-bar">
                <span><span class="status-dot" id="dotSignal"></span> <span id="statusSignal">Déconnecté</span></span>
                <span><span class="status-dot" id="dotMic"></span> <span id="statusMic">—</span></span>
                <span><span class="status-dot" id="dotCam"></span> <span id="statusCam">—</span></span>
                <span><span class="status-dot" id="dotRec" style="display:none"></span> <span id="statusRec" style="display:none"></span></span>
                <span><span class="status-dot" id="dotRelay"></span> <span id="statusRelay"><?php echo $has_turn_server ? 'TURN actif' : 'Pas de relai'; ?></span></span>
            </div>
        </div>

        <!-- Sidebar Overlay (mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <div class="visio-sidebar" id="visioSidebar">
            <div class="sidebar-tabs">
                <button class="sidebar-tab active" data-tab="participants">
                    <i class="fas fa-users me-1"></i>Participants
                </button>
                <button class="sidebar-tab" data-tab="chat">
                    <i class="fas fa-comments me-1"></i>Chat
                    <span class="tab-badge" id="chatBadge">0</span>
                </button>
            </div>

            <!-- Participants Panel -->
            <div class="sidebar-panel active" data-panel="participants">
                <div class="part-list" id="participantList"></div>
            </div>

            <!-- Chat Panel -->
            <div class="sidebar-panel" data-panel="chat">
                <div class="chat-panel">
                    <div class="chat-messages" id="chatMessages"></div>
                    <div class="chat-input-bar">
                        <input type="text" id="chatInput" placeholder="Votre message..." maxlength="2000" autocomplete="off">
                        <button id="btnSendChat" title="Envoyer"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';

    /* ========== Config from PHP ========== */
    var meetingConfig = {
        roomId: '<?php echo addslashes($room_id); ?>',
        roomName: '<?php echo addslashes($room_name); ?>',
        roomUrl: '<?php echo addslashes($room_url); ?>',
        backUrl: '<?php echo addslashes($back_url); ?>',
        currentUserId: <?php echo (int) $current_user_id; ?>,
        displayName: '<?php echo addslashes($display_name); ?>',
        avatarUrl: '<?php echo addslashes($avatar_url); ?>',
        isHost: <?php echo $is_reunion_host ? 'true' : 'false'; ?>,
        rtcHost: '<?php echo addslashes($rtc_host); ?>',
        signalPort: <?php echo (int) $signal_port; ?>,
        iceServers: <?php echo json_encode($ice_servers); ?>,
        hasTurn: <?php echo $has_turn_server ? 'true' : 'false'; ?>,
        invitedParticipants: <?php echo json_encode(array_map(function($p) {
            return array(
                'userId' => (int) $p->users_id,
                'name' => trim($p->users_prenom . ' ' . $p->users_nom),
                'avatar' => !empty($p->photo_profil) ? base_url('assets/img/avatar/' . rawurlencode(basename($p->photo_profil))) : ''
            );
        }, $participants)); ?>,
        reunionDuration: <?php echo isset($reunion_duration) ? (int) $reunion_duration : 60; ?>,
        reunionId: <?php echo (int) $reunion->id; ?>,
        saveRecordingUrl: '<?php echo site_url('visioconference/save_recording'); ?>'
    };

    /* ========== State ========== */
    var state = {
        socket: null,
        localStream: null,
        screenStream: null,
        localClientId: null,
        hasLeft: false,
        isMuted: false,
        isCameraOff: false,
        hasCameraTrack: false,
        isHandRaised: false,
        isScreenSharing: false,
        isFullscreen: false,
        viewMode: 'grid',
        peers: {},
        participants: {},
        audioMeters: {},
        connectedUserIds: {},
        reconnectAttempts: 0,
        reconnectTimer: null,
        maxReconnectAttempts: 10,
        chatUnread: 0,
        timerStart: Date.now(),
        timerInterval: null,
        activeSpeakerId: null,
        isRecording: false,
        mediaRecorder: null,
        recordedChunks: [],
        recordingStartTime: null,
        recordingCanvas: null,
        recordingCtx: null,
        recordingAnimFrame: null,
        recordingAudioCtx: null,
        recordingAudioDest: null,
        recordingSourceNodes: {}
    };

    /* ========== DOM refs ========== */
    var shell = document.getElementById('visioShell');
    var videoGrid = document.getElementById('videoGrid');
    var remoteAudioContainer = document.getElementById('remoteAudioContainer');
    var warningBox = document.getElementById('warningBox');
    var reconnectOverlay = document.getElementById('reconnectOverlay');
    var reconnectMsg = document.getElementById('reconnectMsg');
    var participantList = document.getElementById('participantList');
    var chatMessages = document.getElementById('chatMessages');
    var chatInput = document.getElementById('chatInput');
    var chatBadge = document.getElementById('chatBadge');
    var participantCount = document.getElementById('participantCount');
    var meetingTimer = document.getElementById('meetingTimer');

    var btnMute = document.getElementById('btnMute');
    var btnCamera = document.getElementById('btnCamera');
    var btnScreenShare = document.getElementById('btnScreenShare');
    var btnRecord = document.getElementById('btnRecord');
    var btnRaiseHand = document.getElementById('btnRaiseHand');
    var btnViewToggle = document.getElementById('btnViewToggle');
    var btnFullscreen = document.getElementById('btnFullscreen');
    var btnLeave = document.getElementById('btnLeave');
    var btnCopyLink = document.getElementById('btnCopyLink');
    var btnSendChat = document.getElementById('btnSendChat');
    var btnToggleSidebar = document.getElementById('btnToggleSidebar');
    var btnMuteAll = document.getElementById('btnMuteAll');
    var btnEndMeeting = document.getElementById('btnEndMeeting');
    var sidebarOverlay = document.getElementById('sidebarOverlay');
    var sidebarEl = document.getElementById('visioSidebar');

    var dotSignal = document.getElementById('dotSignal');
    var dotMic = document.getElementById('dotMic');
    var dotCam = document.getElementById('dotCam');
    var dotRec = document.getElementById('dotRec');
    var dotRelay = document.getElementById('dotRelay');
    var statusSignal = document.getElementById('statusSignal');
    var statusMic = document.getElementById('statusMic');
    var statusCam = document.getElementById('statusCam');
    var statusRec = document.getElementById('statusRec');

    /* ========== Helpers ========== */
    function escHtml(s) {
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function initials(name) {
        var parts = (name || '?').split(/\s+/);
        if (parts.length >= 2) return (parts[0][0] + parts[1][0]).toUpperCase();
        return (name || '?').substring(0, 2).toUpperCase();
    }

    function buildSignalUrl() {
        var proto = location.protocol === 'https:' ? 'wss' : 'ws';
        return proto + '://' + meetingConfig.rtcHost + ':' + meetingConfig.signalPort;
    }

    function setStatus(dotEl, textEl, color, text) {
        if (dotEl) dotEl.className = 'status-dot ' + color;
        if (textEl) textEl.textContent = text;
    }

    function formatTime(ms) {
        var totalSec = Math.floor(ms / 1000);
        var h = Math.floor(totalSec / 3600);
        var m = Math.floor((totalSec % 3600) / 60);
        var s = totalSec % 60;
        if (h > 0) return h + ':' + (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
        return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    }

    /* ========== Timer ========== */
    state.timerInterval = setInterval(function() {
        meetingTimer.textContent = formatTime(Date.now() - state.timerStart);
    }, 1000);

    /* ========== Video Card Builder ========== */
    function buildVideoCard(id, info) {
        var card = document.createElement('div');
        card.className = 'video-card';
        card.setAttribute('data-client-id', id);

        var video = document.createElement('video');
        video.autoplay = true;
        video.playsInline = true;
        video.muted = true; // All grid videos muted — audio plays via persistent <audio> elements
        card.appendChild(video);

        var ph = document.createElement('div');
        ph.className = 'video-placeholder';
        if (info.avatarUrl) {
            var img = document.createElement('img');
            img.src = info.avatarUrl;
            img.alt = info.displayName || '';
            ph.appendChild(img);
        } else {
            ph.textContent = initials(info.displayName);
        }
        card.appendChild(ph);

        var topBadges = document.createElement('div');
        topBadges.className = 'card-top-badges';
        card.appendChild(topBadges);

        var meter = document.createElement('div');
        meter.className = 'audio-meter';
        var meterFill = document.createElement('div');
        meterFill.className = 'audio-meter-fill';
        meter.appendChild(meterFill);
        card.appendChild(meter);

        var overlay = document.createElement('div');
        overlay.className = 'video-overlay';
        var nameSpan = document.createElement('span');
        nameSpan.className = 'card-name';
        nameSpan.textContent = info.displayName || 'Participant';
        overlay.appendChild(nameSpan);
        card.appendChild(overlay);

        return card;
    }

    function applyCardState(card, p) {
        if (!card || !p) return;
        var video = card.querySelector('video');
        var ph = card.querySelector('.video-placeholder');
        var topBadges = card.querySelector('.card-top-badges');
        var meterFill = card.querySelector('.audio-meter-fill');

        var hasVideo = p.stream && p.stream.getVideoTracks().length > 0 && !p.cameraOff;
        if (video && p.stream) {
            if (video.srcObject !== p.stream) {
                video.srcObject = p.stream;
                video.play().catch(function() {});
            }
            video.style.display = hasVideo ? 'block' : 'none';
        } else if (video) {
            video.style.display = 'none';
        }
        if (ph) ph.style.display = hasVideo ? 'none' : 'flex';

        card.classList.toggle('is-speaking', !!p.speaking);
        card.classList.toggle('hand-raised', !!p.handRaised && !p.speaking);

        if (topBadges) {
            topBadges.innerHTML = '';
            if (p.muted) {
                var mb = document.createElement('span');
                mb.className = 'tbadge tbadge-muted';
                mb.innerHTML = '<i class="fas fa-microphone-slash"></i>';
                topBadges.appendChild(mb);
            }
            if (p.handRaised) {
                var hb = document.createElement('span');
                hb.className = 'tbadge tbadge-hand';
                hb.innerHTML = '<i class="fas fa-hand-paper"></i>';
                topBadges.appendChild(hb);
            }
            if (p.screenSharing) {
                var sb = document.createElement('span');
                sb.className = 'tbadge tbadge-screen';
                sb.innerHTML = '<i class="fas fa-desktop"></i>';
                topBadges.appendChild(sb);
            }
        }

        if (meterFill) {
            meterFill.style.height = (p.audioLevel || 0) + '%';
        }
    }

    /* ========== Participant Management ========== */
    function updateLocalState(render) {
        var clientId = state.localClientId || 'local';
        if (!state.participants[clientId]) {
            state.participants[clientId] = {
                clientId: clientId,
                userId: meetingConfig.currentUserId,
                displayName: meetingConfig.displayName,
                avatarUrl: meetingConfig.avatarUrl,
                isHost: meetingConfig.isHost,
                isLocal: true,
                stream: state.localStream,
                muted: state.isMuted,
                cameraOff: state.isCameraOff,
                handRaised: state.isHandRaised,
                screenSharing: state.isScreenSharing,
                speaking: false,
                audioLevel: 0,
                connected: true
            };
        } else {
            var lp = state.participants[clientId];
            lp.stream = state.localStream;
            lp.muted = state.isMuted;
            lp.cameraOff = state.isCameraOff;
            lp.handRaised = state.isHandRaised;
            lp.screenSharing = state.isScreenSharing;
            lp.connected = true;
        }
        state.connectedUserIds[meetingConfig.currentUserId] = true;
        if (render !== false) renderAll();
    }

    function syncParticipants(peers) {
        state.connectedUserIds = {};
        state.connectedUserIds[meetingConfig.currentUserId] = true;

        // Filter out self from peer list to avoid duplicate video card
        var localCid = state.localClientId || 'local';
        peers = peers.filter(function(peer) {
            return peer.clientId !== localCid && peer.userId !== meetingConfig.currentUserId;
        });

        peers.forEach(function(peer) {
            state.connectedUserIds[peer.userId] = true;
            if (!state.participants[peer.clientId]) {
                state.participants[peer.clientId] = {
                    clientId: peer.clientId,
                    userId: peer.userId,
                    displayName: peer.displayName,
                    avatarUrl: peer.avatarUrl,
                    isHost: peer.isHost,
                    isLocal: false,
                    stream: null,
                    muted: !peer.micEnabled,
                    cameraOff: !peer.cameraEnabled,
                    handRaised: peer.handRaised,
                    screenSharing: peer.screenSharing || false,
                    speaking: false,
                    audioLevel: 0,
                    connected: true
                };
            } else {
                var ep = state.participants[peer.clientId];
                ep.muted = !peer.micEnabled;
                ep.cameraOff = !peer.cameraEnabled;
                ep.handRaised = peer.handRaised;
                ep.screenSharing = peer.screenSharing || false;
                ep.connected = true;
                ep.displayName = peer.displayName;
                ep.avatarUrl = peer.avatarUrl;
                ep.isHost = peer.isHost;
            }
        });

        var knownClientIds = {};
        var localCid = state.localClientId || 'local';
        knownClientIds[localCid] = true;
        peers.forEach(function(p) { knownClientIds[p.clientId] = true; });
        Object.keys(state.participants).forEach(function(cid) {
            if (!knownClientIds[cid]) {
                delete state.participants[cid];
            }
        });

        renderAll();
    }

    function updateParticipantState(clientId, updates, render) {
        var p = state.participants[clientId];
        if (!p) return;
        Object.keys(updates).forEach(function(k) { p[k] = updates[k]; });
        if (render !== false) {
            var card = videoGrid.querySelector('[data-client-id="' + clientId + '"]');
            if (card) applyCardState(card, p);
            if (updates.speaking !== undefined) updateActiveSpeaker();
        }
    }

    function updateActiveSpeaker() {
        var speakerId = null;
        var localCid = state.localClientId || 'local';
        Object.keys(state.participants).forEach(function(cid) {
            if (cid !== localCid && state.participants[cid].speaking) {
                speakerId = cid;
            }
        });
        if (speakerId !== state.activeSpeakerId) {
            state.activeSpeakerId = speakerId;
            if (state.viewMode === 'speaker') renderVideoGrid();
        }
    }

    /* ========== Sorting ========== */
    function sortIds(ids) {
        var localCid = state.localClientId || 'local';
        return ids.sort(function(a, b) {
            if (a === localCid) return -1;
            if (b === localCid) return 1;
            var pa = state.participants[a] || {};
            var pb = state.participants[b] || {};
            if (pa.handRaised && !pb.handRaised) return -1;
            if (!pa.handRaised && pb.handRaised) return 1;
            if (pa.speaking && !pb.speaking) return -1;
            if (!pa.speaking && pb.speaking) return 1;
            if (pa.isHost && !pb.isHost) return -1;
            if (!pa.isHost && pb.isHost) return 1;
            return (pa.displayName || '').localeCompare(pb.displayName || '');
        });
    }

    /* ========== Rendering ========== */
    function getGridClass(count) {
        if (count <= 1) return 'grid-1';
        if (count <= 2) return 'grid-2';
        if (count <= 4) return 'grid-4';
        return 'grid-many';
    }

    function renderVideoGrid() {
        var ids = sortIds(Object.keys(state.participants));
        var count = ids.length;

        participantCount.textContent = count;

        videoGrid.innerHTML = '';
        videoGrid.className = 'visio-grid';

        // Check if anyone is screen sharing
        var screenSharerCid = null;
        ids.forEach(function(cid) {
            if (state.participants[cid].screenSharing) screenSharerCid = cid;
        });

        if (screenSharerCid && state.viewMode !== 'speaker') {
            // Screen share layout
            videoGrid.classList.add('screen-share-view');
            var ssCard = buildVideoCard(screenSharerCid, state.participants[screenSharerCid]);
            ssCard.classList.add('screen-share-card', 'is-screen-share');
            applyCardState(ssCard, state.participants[screenSharerCid]);
            videoGrid.appendChild(ssCard);

            var strip = document.createElement('div');
            strip.className = 'screen-share-strip';
            ids.forEach(function(cid) {
                if (cid === screenSharerCid) return;
                var c = buildVideoCard(cid, state.participants[cid]);
                applyCardState(c, state.participants[cid]);
                strip.appendChild(c);
            });
            videoGrid.appendChild(strip);

        } else if (state.viewMode === 'speaker' && count > 1) {
            videoGrid.classList.add('speaker-view');
            var mainCid = state.activeSpeakerId || ids[1] || ids[0];
            var mainCard = buildVideoCard(mainCid, state.participants[mainCid]);
            mainCard.classList.add('speaker-main');
            applyCardState(mainCard, state.participants[mainCid]);
            videoGrid.appendChild(mainCard);

            var sstrip = document.createElement('div');
            sstrip.className = 'speaker-strip';
            ids.forEach(function(cid) {
                if (cid === mainCid) return;
                var c = buildVideoCard(cid, state.participants[cid]);
                applyCardState(c, state.participants[cid]);
                sstrip.appendChild(c);
            });
            videoGrid.appendChild(sstrip);
        } else {
            videoGrid.classList.add(getGridClass(count));
            ids.forEach(function(cid) {
                var c = buildVideoCard(cid, state.participants[cid]);
                applyCardState(c, state.participants[cid]);
                videoGrid.appendChild(c);
            });
        }
    }

    function renderSidebarParticipants() {
        participantList.innerHTML = '';
        var cids = sortIds(Object.keys(state.participants));
        var connected = {};
        cids.forEach(function(cid) {
            var p = state.participants[cid];
            connected[p.userId] = p;
        });

        // Show connected participants first, then invited
        var displayedUserIds = {};

        cids.forEach(function(cid) {
            var p = state.participants[cid];
            displayedUserIds[p.userId] = true;
            var item = document.createElement('div');
            item.className = 'part-item';

            var avatar = document.createElement('div');
            avatar.className = 'part-avatar';
            if (p.avatarUrl) {
                var img = document.createElement('img');
                img.src = p.avatarUrl;
                avatar.appendChild(img);
            } else {
                avatar.textContent = initials(p.displayName);
            }
            item.appendChild(avatar);

            var nameEl = document.createElement('div');
            nameEl.className = 'part-name';
            nameEl.textContent = p.displayName || 'Participant';
            item.appendChild(nameEl);

            if (p.isHost) {
                var host = document.createElement('span');
                host.className = 'part-host-tag';
                host.textContent = 'Hôte';
                item.appendChild(host);
            }

            var icons = document.createElement('div');
            icons.className = 'part-status-icons';
            if (p.handRaised) icons.innerHTML += '<i class="fas fa-hand-paper" style="color:#f59e0b;"></i>';
            if (p.muted) icons.innerHTML += '<i class="fas fa-microphone-slash" style="color:#ef4444;"></i>';
            else icons.innerHTML += '<i class="fas fa-microphone part-online"></i>';
            if (p.screenSharing) icons.innerHTML += '<i class="fas fa-desktop" style="color:#6366f1;"></i>';
            icons.innerHTML += '<i class="fas fa-circle part-online" style="font-size:7px;"></i>';
            item.appendChild(icons);

            participantList.appendChild(item);
        });

        // Add invited but not connected
        (meetingConfig.invitedParticipants || []).forEach(function(inv) {
            if (displayedUserIds[inv.userId]) return;
            var item = document.createElement('div');
            item.className = 'part-item';

            var avatar = document.createElement('div');
            avatar.className = 'part-avatar';
            avatar.style.opacity = '.5';
            if (inv.avatar) {
                var img = document.createElement('img');
                img.src = inv.avatar;
                avatar.appendChild(img);
            } else {
                avatar.textContent = initials(inv.name);
            }
            item.appendChild(avatar);

            var nameEl = document.createElement('div');
            nameEl.className = 'part-name';
            nameEl.style.opacity = '.5';
            nameEl.textContent = inv.name || 'Invité';
            item.appendChild(nameEl);

            var icons = document.createElement('div');
            icons.className = 'part-status-icons';
            icons.innerHTML = '<i class="fas fa-circle part-offline" style="font-size:7px;"></i>';
            item.appendChild(icons);

            participantList.appendChild(item);
        });
    }

    function renderAll() {
        renderVideoGrid();
        renderSidebarParticipants();
    }

    /* ========== Audio Metering ========== */
    function stopAudioMeter(clientId) {
        var m = state.audioMeters[clientId];
        if (!m) return;
        clearInterval(m.intervalId);
        if (m.source) try { m.source.disconnect(); } catch(e) {}
        delete state.audioMeters[clientId];
    }

    function startAudioMeter(clientId, stream) {
        stopAudioMeter(clientId);
        var audioTracks = stream.getAudioTracks();
        if (!audioTracks.length) return;

        try {
            var ctx = new (window.AudioContext || window.webkitAudioContext)();
            var source = ctx.createMediaStreamSource(stream);
            var analyser = ctx.createAnalyser();
            analyser.fftSize = 256;
            source.connect(analyser);
        } catch(e) { return; }

        var data = new Uint8Array(analyser.frequencyBinCount);
        var lastLevel = 0, lastSpeaking = false;

        var intervalId = setInterval(function() {
            analyser.getByteFrequencyData(data);
            var total = 0;
            for (var i = 0; i < data.length; i++) total += data[i];
            var avg = Math.max(0, Math.min(100, Math.round(total / data.length)));
            var speaking = avg > 18;

            if (Math.abs(lastLevel - avg) >= 6) {
                updateParticipantState(clientId, { audioLevel: avg }, false);
                lastLevel = avg;
                var card = videoGrid.querySelector('[data-client-id="' + clientId + '"]');
                if (card) {
                    var fill = card.querySelector('.audio-meter-fill');
                    if (fill) fill.style.height = avg + '%';
                }
            }

            if (lastSpeaking !== speaking) {
                updateParticipantState(clientId, { speaking: speaking }, true);
                lastSpeaking = speaking;
            }
        }, 200);

        state.audioMeters[clientId] = { analyser: analyser, source: source, intervalId: intervalId };
    }

    /* ========== WebRTC ========== */
    function ensureRemoteAudio(clientId, stream) {
        // Create/update a persistent <audio> element for this remote peer
        // These live outside videoGrid so they survive grid re-renders
        if (clientId === 'local' || clientId === state.localClientId) return; // never play our own audio
        var audioTracks = stream.getAudioTracks();
        if (audioTracks.length === 0) return;

        var audioId = 'remote-audio-' + clientId;
        var audio = document.getElementById(audioId);
        if (!audio) {
            audio = document.createElement('audio');
            audio.id = audioId;
            audio.autoplay = true;
            audio.setAttribute('playsinline', '');
            remoteAudioContainer.appendChild(audio);
        }
        if (audio.srcObject !== stream) {
            audio.srcObject = stream;
            audio.play().catch(function(err) {
                console.warn('Remote audio play blocked for', clientId, err);
            });
        }
    }

    function removeRemoteAudio(clientId) {
        var audioId = 'remote-audio-' + clientId;
        var audio = document.getElementById(audioId);
        if (audio) {
            audio.srcObject = null;
            audio.remove();
        }
    }

    function attachRemoteStream(clientId, stream) {
        if (!state.participants[clientId]) return;
        state.participants[clientId].stream = stream;
        var card = videoGrid.querySelector('[data-client-id="' + clientId + '"]');
        if (card) applyCardState(card, state.participants[clientId]);
        ensureRemoteAudio(clientId, stream);
        startAudioMeter(clientId, stream);
        addRemoteAudioToRecording(clientId, stream);
    }

    function ensureRemoteStream(clientId) {
        if (!state.peers[clientId]) return null;
        if (!state.peers[clientId].remoteStream) state.peers[clientId].remoteStream = new MediaStream();
        return state.peers[clientId].remoteStream;
    }

    function attachRemoteTrack(clientId, event) {
        if (!state.participants[clientId] || !event || !event.track) return;
        if (event.streams && event.streams[0]) { attachRemoteStream(clientId, event.streams[0]); return; }
        var rs = ensureRemoteStream(clientId);
        if (!rs) return;
        var exists = false;
        rs.getTracks().forEach(function(t) { if (t.id === event.track.id) exists = true; });
        if (!exists) rs.addTrack(event.track);
        attachRemoteStream(clientId, rs);
    }

    function closePeerConnection(clientId) {
        var ps = state.peers[clientId];
        if (ps) {
            stopAudioMeter(clientId);
            removeRemoteAudio(clientId);
            if (ps.connection) {
                ps.connection.onicecandidate = null;
                ps.connection.ontrack = null;
                ps.connection.onconnectionstatechange = null;
                ps.connection.oniceconnectionstatechange = null;
                ps.connection.close();
            }
            delete state.peers[clientId];
        }
        if (state.participants[clientId]) {
            delete state.participants[clientId];
            renderAll();
        }
    }

    function sendMessage(payload) {
        if (!state.socket || state.socket.readyState !== WebSocket.OPEN) return;
        state.socket.send(JSON.stringify(payload));
    }

    function sendParticipantState() {
        sendMessage({
            type: 'participant-state',
            handRaised: !!state.isHandRaised,
            micEnabled: !state.isMuted,
            cameraEnabled: state.hasCameraTrack && !state.isCameraOff,
            screenSharing: state.isScreenSharing
        });
    }

    function createPeerConnection(clientId, peerInfo) {
        if (state.peers[clientId]) return state.peers[clientId].connection;

        var conn = new RTCPeerConnection({ iceServers: meetingConfig.iceServers || [] });
        state.peers[clientId] = { connection: conn, info: peerInfo, remoteStream: null };

        if (state.localStream) {
            state.localStream.getTracks().forEach(function(track) {
                conn.addTrack(track, state.localStream);
            });
        }

        conn.onicecandidate = function(e) {
            if (e.candidate) sendMessage({ type: 'ice-candidate', target: clientId, candidate: e.candidate });
        };

        conn.ontrack = function(e) { attachRemoteTrack(clientId, e); };

        conn.onconnectionstatechange = function() {
            if (conn.connectionState === 'connected') {
                updateParticipantState(clientId, { connected: true }, false);
            } else if (conn.connectionState === 'failed' || conn.connectionState === 'closed') {
                closePeerConnection(clientId);
            }
        };

        conn.oniceconnectionstatechange = function() {
            if (conn.iceConnectionState === 'connected' || conn.iceConnectionState === 'completed') {
                setStatus(dotRelay, null, 'green', null);
            }
        };

        return conn;
    }

    function createOffer(clientId, peerInfo) {
        var conn = createPeerConnection(clientId, peerInfo);
        conn.createOffer().then(function(offer) {
            return conn.setLocalDescription(offer).then(function() {
                sendMessage({ type: 'offer', target: clientId, sdp: offer });
            });
        }).catch(function(err) { console.error('Offer error', err); });
    }

    function handleOffer(msg) {
        var conn = createPeerConnection(msg.from, msg.peer);
        conn.setRemoteDescription(new RTCSessionDescription(msg.sdp)).then(function() {
            return conn.createAnswer();
        }).then(function(answer) {
            return conn.setLocalDescription(answer).then(function() {
                sendMessage({ type: 'answer', target: msg.from, sdp: answer });
            });
        }).catch(function(err) { console.error('Answer error', err); });
    }

    function handleAnswer(msg) {
        var ps = state.peers[msg.from];
        if (ps) ps.connection.setRemoteDescription(new RTCSessionDescription(msg.sdp)).catch(function(err) { console.error('Remote desc error', err); });
    }

    function handleIceCandidate(msg) {
        var ps = state.peers[msg.from];
        if (ps) ps.connection.addIceCandidate(new RTCIceCandidate(msg.candidate)).catch(function(err) { console.error('ICE error', err); });
    }

    /* ========== Chat ========== */
    function addChatMessage(msg) {
        var el = document.createElement('div');
        el.className = 'chat-msg';

        var av = document.createElement('div');
        av.className = 'chat-msg-avatar';
        if (msg.avatarUrl) {
            var img = document.createElement('img');
            img.src = msg.avatarUrl;
            av.appendChild(img);
        } else {
            av.textContent = initials(msg.displayName);
        }
        el.appendChild(av);

        var body = document.createElement('div');
        body.className = 'chat-msg-body';

        var header = document.createElement('div');
        header.className = 'chat-msg-header';
        var nameEl = document.createElement('span');
        nameEl.className = 'chat-msg-name';
        nameEl.textContent = msg.displayName;
        header.appendChild(nameEl);
        var timeEl = document.createElement('span');
        timeEl.className = 'chat-msg-time';
        var d = new Date(msg.timestamp);
        timeEl.textContent = (d.getHours() < 10 ? '0' : '') + d.getHours() + ':' + (d.getMinutes() < 10 ? '0' : '') + d.getMinutes();
        header.appendChild(timeEl);
        body.appendChild(header);

        var textEl = document.createElement('div');
        textEl.className = 'chat-msg-text';
        textEl.textContent = msg.text;
        body.appendChild(textEl);

        el.appendChild(body);
        chatMessages.appendChild(el);
        chatMessages.scrollTop = chatMessages.scrollHeight;

        // Update badge if chat tab not active
        var chatPanel = document.querySelector('[data-panel="chat"]');
        if (!chatPanel || !chatPanel.classList.contains('active')) {
            state.chatUnread++;
            chatBadge.textContent = state.chatUnread;
            chatBadge.style.display = 'flex';
        }
    }

    function addSystemMessage(text) {
        var el = document.createElement('div');
        el.className = 'chat-system';
        el.textContent = text;
        chatMessages.appendChild(el);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function sendChatMessage() {
        var text = chatInput.value.trim();
        if (!text) return;

        // Display locally immediately (optimistic UI)
        addChatMessage({
            clientId: state.localClientId,
            displayName: meetingConfig.displayName,
            avatarUrl: meetingConfig.avatarUrl,
            text: text,
            timestamp: Date.now()
        });

        // Send to server for broadcast to others
        sendMessage({ type: 'chat-message', text: text });
        chatInput.value = '';
    }

    /* ========== Screen Sharing ========== */
    function startScreenShare() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getDisplayMedia) {
            showWarning('Le partage d\'ecran n\'est pas supporte par ce navigateur.');
            return;
        }

        navigator.mediaDevices.getDisplayMedia({ video: true, audio: false }).then(function(screenStream) {
            state.screenStream = screenStream;
            state.isScreenSharing = true;

            var screenTrack = screenStream.getVideoTracks()[0];

            // Replace video track in all peer connections
            Object.keys(state.peers).forEach(function(cid) {
                var senders = state.peers[cid].connection.getSenders();
                senders.forEach(function(sender) {
                    if (sender.track && sender.track.kind === 'video') {
                        sender.replaceTrack(screenTrack);
                    }
                });
            });

            // Update local participant
            var localCid = state.localClientId || 'local';
            if (state.participants[localCid]) {
                state.participants[localCid].stream = screenStream;
            }

            screenTrack.onended = function() { stopScreenShare(); };

            sendMessage({ type: 'screen-share-started' });
            sendParticipantState();
            updateScreenShareButton();
            renderAll();
        }).catch(function() {
            // User cancelled
        });
    }

    function stopScreenShare() {
        if (!state.isScreenSharing) return;
        state.isScreenSharing = false;

        if (state.screenStream) {
            state.screenStream.getTracks().forEach(function(t) { t.stop(); });
            state.screenStream = null;
        }

        // Restore camera track
        var localCid = state.localClientId || 'local';
        var cameraTrack = state.localStream ? state.localStream.getVideoTracks()[0] : null;
        Object.keys(state.peers).forEach(function(cid) {
            var senders = state.peers[cid].connection.getSenders();
            senders.forEach(function(sender) {
                if (sender.track && sender.track.kind === 'video' && cameraTrack) {
                    sender.replaceTrack(cameraTrack);
                }
            });
        });

        if (state.participants[localCid]) {
            state.participants[localCid].stream = state.localStream;
        }

        sendMessage({ type: 'screen-share-stopped' });
        sendParticipantState();
        updateScreenShareButton();
        renderAll();
    }

    /* ========== Recording ========== */

    // Persistent hidden video elements for recording (not affected by grid re-renders)
    state.recVideoEls = {};

    function recEnsureVideoEl(key, stream) {
        if (!state.recVideoEls[key]) {
            var v = document.createElement('video');
            v.autoplay = true;
            v.playsInline = true;
            v.muted = true; // muted to avoid echo, audio captured separately
            v.style.position = 'fixed';
            v.style.top = '-9999px';
            v.style.left = '-9999px';
            v.style.width = '1px';
            v.style.height = '1px';
            v.style.opacity = '0.01';
            document.body.appendChild(v);
            state.recVideoEls[key] = v;
        }
        var el = state.recVideoEls[key];
        if (el.srcObject !== stream) {
            el.srcObject = stream;
            el.play().catch(function() {});
        }
        return el;
    }

    function recSyncVideoEls() {
        // Sync all participant streams to hidden video elements
        Object.keys(state.participants).forEach(function(cid) {
            var p = state.participants[cid];
            if (p.stream) {
                recEnsureVideoEl(cid, p.stream);
            }
        });
        // Remove elements for departed participants
        Object.keys(state.recVideoEls).forEach(function(key) {
            if (!state.participants[key]) {
                var el = state.recVideoEls[key];
                el.srcObject = null;
                if (el.parentNode) el.parentNode.removeChild(el);
                delete state.recVideoEls[key];
            }
        });
    }

    function recCleanupVideoEls() {
        Object.keys(state.recVideoEls).forEach(function(key) {
            var el = state.recVideoEls[key];
            el.srcObject = null;
            if (el.parentNode) el.parentNode.removeChild(el);
        });
        state.recVideoEls = {};
    }

    // roundRect polyfill for older browsers
    function drawRoundRect(ctx, x, y, w, h, r) {
        ctx.beginPath();
        ctx.moveTo(x + r, y);
        ctx.lineTo(x + w - r, y);
        ctx.arcTo(x + w, y, x + w, y + r, r);
        ctx.lineTo(x + w, y + h - r);
        ctx.arcTo(x + w, y + h, x + w - r, y + h, r);
        ctx.lineTo(x + r, y + h);
        ctx.arcTo(x, y + h, x, y + h - r, r);
        ctx.lineTo(x, y + r);
        ctx.arcTo(x, y, x + r, y, r);
        ctx.closePath();
    }

    function initRecordingCanvas() {
        if (!state.recordingCanvas) {
            state.recordingCanvas = document.createElement('canvas');
            state.recordingCanvas.width = 1280;
            state.recordingCanvas.height = 720;
            state.recordingCtx = state.recordingCanvas.getContext('2d');
        }
    }

    function drawRecordingFrame() {
        if (!state.isRecording) {
            return;
        }
        try {
            var ctx = state.recordingCtx;
            var cw = state.recordingCanvas.width;
            var ch = state.recordingCanvas.height;

            ctx.fillStyle = '#0f0f1a';
            ctx.fillRect(0, 0, cw, ch);

            // Sync hidden video elements with current participants
            recSyncVideoEls();

            var activeVideos = [];
            Object.keys(state.recVideoEls).forEach(function(key) {
                var v = state.recVideoEls[key];
                if (v.readyState >= 2 && v.videoWidth > 0) {
                    activeVideos.push(v);
                }
            });

            if (activeVideos.length === 0) {
                ctx.fillStyle = '#fff';
                ctx.font = '24px Inter, sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('Enregistrement en cours...', cw / 2, ch / 2);
            } else {
                var count = activeVideos.length;
                var cols = Math.ceil(Math.sqrt(count));
                var rows = Math.ceil(count / cols);
                var cellW = Math.floor(cw / cols);
                var cellH = Math.floor(ch / rows);
                var pad = 2;

                for (var i = 0; i < activeVideos.length; i++) {
                    var v = activeVideos[i];
                    var col = i % cols;
                    var row = Math.floor(i / cols);
                    var x = col * cellW + pad;
                    var y = row * cellH + pad;
                    var w = cellW - pad * 2;
                    var h = cellH - pad * 2;

                    var vRatio = v.videoWidth / v.videoHeight;
                    var cRatio = w / h;
                    var sx = 0, sy = 0, sw = v.videoWidth, sh = v.videoHeight;
                    if (vRatio > cRatio) {
                        sw = v.videoHeight * cRatio;
                        sx = (v.videoWidth - sw) / 2;
                    } else {
                        sh = v.videoWidth / cRatio;
                        sy = (v.videoHeight - sh) / 2;
                    }

                    ctx.save();
                    drawRoundRect(ctx, x, y, w, h, 8);
                    ctx.clip();
                    ctx.drawImage(v, sx, sy, sw, sh, x, y, w, h);
                    ctx.restore();
                }
            }

            // Recording timer overlay
            if (state.recordingStartTime) {
                var elapsed = Math.floor((Date.now() - state.recordingStartTime) / 1000);
                var mm = Math.floor(elapsed / 60);
                var ss = elapsed % 60;
                var timeStr = (mm < 10 ? '0' : '') + mm + ':' + (ss < 10 ? '0' : '') + ss;

                ctx.save();
                ctx.fillStyle = 'rgba(239,68,68,.85)';
                drawRoundRect(ctx, cw - 120, 12, 108, 28, 6);
                ctx.fill();
                ctx.fillStyle = '#fff';
                ctx.font = 'bold 13px Inter, sans-serif';
                ctx.textAlign = 'center';
                ctx.fillText('● REC ' + timeStr, cw - 66, 31);
                ctx.restore();
            }
        } catch(e) {
            console.error('[REC] drawRecordingFrame error:', e);
        }

        state.recordingAnimFrame = requestAnimationFrame(drawRecordingFrame);
    }

    function createMixedAudioDestination() {
        state.recordingAudioCtx = new (window.AudioContext || window.webkitAudioContext)();
        state.recordingAudioDest = state.recordingAudioCtx.createMediaStreamDestination();
        state.recordingSourceNodes = {};

        // Master gain to normalize mixed audio and prevent clipping
        state.recordingMasterGain = state.recordingAudioCtx.createGain();
        state.recordingMasterGain.gain.value = 0.8;
        state.recordingMasterGain.connect(state.recordingAudioDest);

        // Local audio
        if (state.localStream) {
            var audioTracks = state.localStream.getAudioTracks();
            if (audioTracks.length > 0) {
                var src = state.recordingAudioCtx.createMediaStreamSource(new MediaStream(audioTracks));
                src.connect(state.recordingMasterGain);
                state.recordingSourceNodes['local'] = src;
            }
        }

        // Remote audio
        Object.keys(state.peers).forEach(function(cid) {
            var peer = state.peers[cid];
            if (peer.remoteStream) {
                var audioTracks = peer.remoteStream.getAudioTracks();
                if (audioTracks.length > 0) {
                    var src = state.recordingAudioCtx.createMediaStreamSource(new MediaStream(audioTracks));
                    src.connect(state.recordingMasterGain);
                    state.recordingSourceNodes[cid] = src;
                }
            }
        });
    }

    function addRemoteAudioToRecording(cid, stream) {
        if (!state.isRecording || !state.recordingAudioCtx || !state.recordingAudioDest) return;
        if (state.recordingSourceNodes[cid]) return;
        var audioTracks = stream.getAudioTracks();
        if (audioTracks.length > 0) {
            var src = state.recordingAudioCtx.createMediaStreamSource(new MediaStream(audioTracks));
            src.connect(state.recordingMasterGain);
            state.recordingSourceNodes[cid] = src;
        }
    }

    function startRecording() {
        if (state.isRecording) return;
        if (!window.MediaRecorder) {
            alert('Votre navigateur ne supporte pas l\'enregistrement vidéo.');
            return;
        }

        initRecordingCanvas();
        createMixedAudioDestination();

        var canvasStream = state.recordingCanvas.captureStream(15);
        var audioTracks = state.recordingAudioDest.stream.getAudioTracks();
        var combinedStream = new MediaStream();
        canvasStream.getVideoTracks().forEach(function(t) { combinedStream.addTrack(t); });
        audioTracks.forEach(function(t) { combinedStream.addTrack(t); });

        var mimeTypes = ['video/webm;codecs=vp9,opus', 'video/webm;codecs=vp8,opus', 'video/webm'];
        var selectedMime = '';
        for (var i = 0; i < mimeTypes.length; i++) {
            if (MediaRecorder.isTypeSupported(mimeTypes[i])) {
                selectedMime = mimeTypes[i];
                break;
            }
        }
        if (!selectedMime) {
            alert('Aucun format d\'enregistrement supporté par votre navigateur.');
            return;
        }

        state.recordedChunks = [];
        state.mediaRecorder = new MediaRecorder(combinedStream, {
            mimeType: selectedMime,
            videoBitsPerSecond: 2500000,
            audioBitsPerSecond: 128000
        });

        state.mediaRecorder.ondataavailable = function(e) {
            if (e.data && e.data.size > 0) {
                state.recordedChunks.push(e.data);
            }
        };

        state.mediaRecorder.onstop = function() {
            cancelAnimationFrame(state.recordingAnimFrame);
            var duration = state.recordingStartTime ? Math.floor((Date.now() - state.recordingStartTime) / 1000) : 0;
            uploadRecording(duration);
        };

        state.mediaRecorder.start(1000);
        state.isRecording = true;
        state.recordingStartTime = Date.now();
        recSyncVideoEls(); // pre-create hidden video elements before first draw
        drawRecordingFrame();
        updateRecordButton();
    }

    function stopRecording(onUploadDone) {
        if (!state.isRecording || !state.mediaRecorder) {
            if (onUploadDone) onUploadDone();
            return;
        }
        state.isRecording = false;
        state._onRecordingUploadDone = onUploadDone || null;

        if (state.mediaRecorder.state !== 'inactive') {
            state.mediaRecorder.stop();
        }

        if (state.recordingAudioCtx) {
            Object.keys(state.recordingSourceNodes).forEach(function(key) {
                try { state.recordingSourceNodes[key].disconnect(); } catch(e) {}
            });
            state.recordingSourceNodes = {};
            state.recordingAudioCtx.close().catch(function() {});
            state.recordingAudioCtx = null;
            state.recordingAudioDest = null;
        }

        recCleanupVideoEls();
        updateRecordButton();
    }

    function uploadRecording(duration) {
        if (state.recordedChunks.length === 0) return;

        var blob = new Blob(state.recordedChunks, { type: 'video/webm' });
        state.recordedChunks = [];

        var formData = new FormData();
        formData.append('recording', blob, 'recording.webm');
        formData.append('reunion_id', meetingConfig.reunionId);
        formData.append('duration', duration);

        dotRec.style.display = 'inline-block';
        statusRec.style.display = 'inline';
        dotRec.className = 'status-dot orange';
        statusRec.textContent = 'Sauvegarde...';

        fetch(meetingConfig.saveRecordingUrl, {
            method: 'POST',
            body: formData
        })
        .then(function(r) {
            return r.text().then(function(txt) {
                try { return JSON.parse(txt); }
                catch(e) {
                    console.error('[REC] Server response not JSON:', txt.substring(0, 500));
                    return { success: false, message: 'Réponse serveur invalide' };
                }
            });
        })
        .then(function(data) {
            if (data.success) {
                dotRec.className = 'status-dot green';
                statusRec.textContent = 'Enregistré ✓';
                setTimeout(function() {
                    dotRec.style.display = 'none';
                    statusRec.style.display = 'none';
                }, 4000);
            } else {
                dotRec.className = 'status-dot red';
                statusRec.textContent = 'Erreur: ' + (data.message || 'échec');
                offerLocalDownload(blob);
            }
        })
        .catch(function() {
            dotRec.className = 'status-dot red';
            statusRec.textContent = 'Erreur réseau';
            offerLocalDownload(blob);
        })
        .finally(function() {
            if (state._onRecordingUploadDone) {
                var cb = state._onRecordingUploadDone;
                state._onRecordingUploadDone = null;
                cb();
            }
        });
    }

    function offerLocalDownload(blob) {
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'enregistrement-reunion-' + meetingConfig.reunionId + '-' + Date.now() + '.webm';
        a.style.display = 'none';
        document.body.appendChild(a);
        a.click();
        setTimeout(function() {
            document.body.removeChild(a);
            URL.revokeObjectURL(a.href);
        }, 1000);
    }

    function updateRecordButton() {
        if (state.isRecording) {
            btnRecord.innerHTML = '<i class="fas fa-stop"></i><span class="ctrl-label">Arrêter</span>';
            btnRecord.classList.add('recording');
            dotRec.style.display = 'inline-block';
            statusRec.style.display = 'inline';
            dotRec.className = 'status-dot red';
            statusRec.textContent = 'Enregistrement...';
        } else {
            btnRecord.innerHTML = '<i class="fas fa-circle"></i><span class="ctrl-label">Enregistrer</span>';
            btnRecord.classList.remove('recording');
        }
    }

    /* ========== Leave / Cleanup ========== */
    var _cleanupDone = false;
    function doLeaveCleanup(redirect) {
        if (_cleanupDone) return;
        _cleanupDone = true;
        sendMessage({ type: 'leave' });
        Object.keys(state.peers).forEach(function(cid) { closePeerConnection(cid); });
        stopAudioMeter(state.localClientId || 'local');
        if (state.screenStream) { state.screenStream.getTracks().forEach(function(t) { t.stop(); }); }
        if (state.localStream) { state.localStream.getTracks().forEach(function(t) { t.stop(); }); }
        if (state.socket) state.socket.close();
        if (state.timerInterval) clearInterval(state.timerInterval);
        if (state.reconnectTimer) clearTimeout(state.reconnectTimer);

        if (redirect !== false) window.location.href = meetingConfig.backUrl;
    }

    // Warn user if recording is active and they try to close the tab
    window.addEventListener('beforeunload', function(e) {
        if (state.isRecording) {
            e.preventDefault();
            e.returnValue = 'Un enregistrement est en cours. Voulez-vous vraiment quitter ?';
            return e.returnValue;
        }
    });

    function leaveRoom(redirect) {
        if (state.hasLeft) return;
        state.hasLeft = true;

        // If recording, wait for upload to finish before navigating
        if (state.isRecording) {
            showWarning('Sauvegarde de l\'enregistrement en cours...');
            stopRecording(function() {
                doLeaveCleanup(redirect);
            });
            // Safety timeout: navigate anyway after 30s
            setTimeout(function() { doLeaveCleanup(redirect); }, 30000);
            return;
        }

        doLeaveCleanup(redirect);
    }

    /* ========== UI Update Helpers ========== */
    function updateMuteButton() {
        if (state.isMuted) {
            btnMute.innerHTML = '<i class="fas fa-microphone-slash"></i><span class="ctrl-label">Micro</span>';
            btnMute.classList.add('active');
            setStatus(dotMic, statusMic, 'red', 'Micro coupé');
        } else {
            btnMute.innerHTML = '<i class="fas fa-microphone"></i><span class="ctrl-label">Micro</span>';
            btnMute.classList.remove('active');
            setStatus(dotMic, statusMic, 'green', 'Micro ouvert');
        }
    }

    function updateCameraButton() {
        if (!state.hasCameraTrack) {
            btnCamera.innerHTML = '<i class="fas fa-video-slash"></i><span class="ctrl-label">Caméra</span>';
            btnCamera.disabled = true;
            setStatus(dotCam, statusCam, 'red', 'Caméra indisponible');
            return;
        }
        btnCamera.disabled = false;
        if (state.isCameraOff) {
            btnCamera.innerHTML = '<i class="fas fa-video-slash"></i><span class="ctrl-label">Caméra</span>';
            btnCamera.classList.add('active');
            setStatus(dotCam, statusCam, 'red', 'Caméra coupée');
        } else {
            btnCamera.innerHTML = '<i class="fas fa-video"></i><span class="ctrl-label">Caméra</span>';
            btnCamera.classList.remove('active');
            setStatus(dotCam, statusCam, 'green', 'Caméra ouverte');
        }
    }

    function updateRaiseHandButton() {
        if (state.isHandRaised) {
            btnRaiseHand.classList.add('warn');
            btnRaiseHand.innerHTML = '<i class="fas fa-hand-paper"></i><span class="ctrl-label">Baisser</span>';
        } else {
            btnRaiseHand.classList.remove('warn');
            btnRaiseHand.innerHTML = '<i class="fas fa-hand-paper"></i><span class="ctrl-label">Main</span>';
        }
    }

    function updateScreenShareButton() {
        if (state.isScreenSharing) {
            btnScreenShare.classList.add('active');
            btnScreenShare.innerHTML = '<i class="fas fa-desktop"></i><span class="ctrl-label">Arrêter</span>';
        } else {
            btnScreenShare.classList.remove('active');
            btnScreenShare.innerHTML = '<i class="fas fa-desktop"></i><span class="ctrl-label">Ecran</span>';
        }
    }

    function updateViewButton() {
        if (state.viewMode === 'speaker') {
            btnViewToggle.innerHTML = '<i class="fas fa-th"></i><span class="ctrl-label">Grille</span>';
        } else {
            btnViewToggle.innerHTML = '<i class="fas fa-user"></i><span class="ctrl-label">Orateur</span>';
        }
    }

    function showWarning(text) {
        if (!warningBox) return;
        warningBox.textContent = text;
        warningBox.style.display = 'block';
    }

    function hideWarning() {
        if (warningBox) warningBox.style.display = 'none';
    }

    /* ========== Controls Binding ========== */
    btnMute.addEventListener('click', function() {
        if (!state.localStream) return;
        state.isMuted = !state.isMuted;
        state.localStream.getAudioTracks().forEach(function(t) { t.enabled = !state.isMuted; });
        updateMuteButton();
        updateLocalState(false);
        sendParticipantState();
    });

    btnCamera.addEventListener('click', function() {
        if (!state.localStream || !state.hasCameraTrack) return;
        state.isCameraOff = !state.isCameraOff;
        state.localStream.getVideoTracks().forEach(function(t) { t.enabled = !state.isCameraOff; });
        updateCameraButton();
        updateLocalState(true);
        sendParticipantState();
    });

    btnScreenShare.addEventListener('click', function() {
        if (state.isScreenSharing) stopScreenShare();
        else startScreenShare();
    });

    btnRecord.addEventListener('click', function() {
        if (state.isRecording) {
            if (confirm('Arrêter l\'enregistrement ?')) stopRecording();
        } else {
            startRecording();
        }
    });

    btnRaiseHand.addEventListener('click', function() {
        state.isHandRaised = !state.isHandRaised;
        updateRaiseHandButton();
        updateLocalState(true);
        sendParticipantState();
    });

    btnViewToggle.addEventListener('click', function() {
        state.viewMode = state.viewMode === 'grid' ? 'speaker' : 'grid';
        updateViewButton();
        renderVideoGrid();
    });

    btnFullscreen.addEventListener('click', function() {
        if (!document.fullscreenElement) {
            shell.requestFullscreen().then(function() {
                state.isFullscreen = true;
                shell.classList.add('fullscreen-mode');
                btnFullscreen.innerHTML = '<i class="fas fa-compress"></i><span class="ctrl-label">Quitter</span>';
            }).catch(function() {});
        } else {
            document.exitFullscreen().then(function() {
                state.isFullscreen = false;
                shell.classList.remove('fullscreen-mode');
                btnFullscreen.innerHTML = '<i class="fas fa-expand"></i><span class="ctrl-label">Ecran</span>';
            }).catch(function() {});
        }
    });

    document.addEventListener('fullscreenchange', function() {
        if (!document.fullscreenElement) {
            state.isFullscreen = false;
            shell.classList.remove('fullscreen-mode');
            btnFullscreen.innerHTML = '<i class="fas fa-expand"></i><span class="ctrl-label">Ecran</span>';
        }
    });

    btnLeave.addEventListener('click', function() {
        if (confirm('Quitter la réunion ?')) leaveRoom(true);
    });

    btnCopyLink.addEventListener('click', function() {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(meetingConfig.roomUrl).then(function() {
                btnCopyLink.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(function() { btnCopyLink.innerHTML = '<i class="fas fa-link"></i>'; }, 2000);
            });
        }
    });

    // Chat
    btnSendChat.addEventListener('click', sendChatMessage);
    chatInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') sendChatMessage();
    });

    // Sidebar tabs
    document.querySelectorAll('.sidebar-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            var target = this.getAttribute('data-tab');
            document.querySelectorAll('.sidebar-tab').forEach(function(t) { t.classList.remove('active'); });
            document.querySelectorAll('.sidebar-panel').forEach(function(p) { p.classList.remove('active'); });
            this.classList.add('active');
            document.querySelector('[data-panel="' + target + '"]').classList.add('active');
            if (target === 'chat') {
                state.chatUnread = 0;
                chatBadge.style.display = 'none';
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        });
    });

    // Mobile sidebar
    if (btnToggleSidebar) {
        btnToggleSidebar.addEventListener('click', function() {
            sidebarEl.classList.toggle('open');
            sidebarEl.classList.remove('collapsed');
            sidebarOverlay.classList.toggle('show');
        });
    }
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebarEl.classList.remove('open');
            sidebarOverlay.classList.remove('show');
        });
    }

    // Host controls
    if (btnMuteAll) {
        btnMuteAll.addEventListener('click', function() {
            if (confirm('Couper le micro de tous les participants ?')) {
                sendMessage({ type: 'host-mute-all' });
            }
        });
    }
    if (btnEndMeeting) {
        btnEndMeeting.addEventListener('click', function() {
            if (confirm('Terminer la réunion pour TOUS les participants ?')) {
                sendMessage({ type: 'host-end-meeting' });
                setTimeout(function() { leaveRoom(true); }, 500);
            }
        });
    }

    window.addEventListener('beforeunload', function() { leaveRoom(false); });

    /* ========== WebSocket with Auto-Reconnect ========== */
    function connectSignalServer() {
        if (state.hasLeft) return;
        setStatus(dotSignal, statusSignal, 'orange', 'Connexion en cours...');

        try {
            state.socket = new WebSocket(buildSignalUrl());
        } catch(e) {
            setStatus(dotSignal, statusSignal, 'red', 'Serveur indisponible');
            showWarning('Impossible de joindre le serveur local. Vérifiez qu\'il est bien lancé.');
            scheduleReconnect();
            return;
        }

        state.socket.addEventListener('open', function() {
            setStatus(dotSignal, statusSignal, 'green', 'Connecté');
            state.reconnectAttempts = 0;
            hideWarning();
            reconnectOverlay.classList.remove('show');

            sendMessage({
                type: 'join',
                roomId: meetingConfig.roomId,
                roomName: meetingConfig.roomName,
                userId: meetingConfig.currentUserId,
                displayName: meetingConfig.displayName,
                avatarUrl: meetingConfig.avatarUrl,
                isHost: meetingConfig.isHost,
                handRaised: state.isHandRaised,
                micEnabled: !state.isMuted,
                cameraEnabled: state.hasCameraTrack && !state.isCameraOff
            });
        });

        state.socket.addEventListener('message', function(event) {
            var msg;
            try { msg = JSON.parse(event.data); } catch(e) { return; }

            if (msg.type === 'joined') {
                // Clean up temp 'local' entry before switching to server-assigned clientId
                if (state.participants['local']) {
                    delete state.participants['local'];
                }
                state.localClientId = msg.clientId;
                updateLocalState(false);
                syncParticipants(msg.peers || []);
                (msg.peers || []).forEach(function(peer) { createOffer(peer.clientId, peer); });
                // Load chat history
                (msg.chatHistory || []).forEach(function(m) { addChatMessage(m); });
                return;
            }
            if (msg.type === 'participants') { syncParticipants(msg.peers || []); return; }
            if (msg.type === 'peer-left') { closePeerConnection(msg.clientId); return; }
            if (msg.type === 'offer') { handleOffer(msg); return; }
            if (msg.type === 'answer') { handleAnswer(msg); return; }
            if (msg.type === 'ice-candidate') { handleIceCandidate(msg); return; }
            if (msg.type === 'chat-message') {
                // Skip own messages (already displayed locally)
                if (msg.message && msg.message.clientId === state.localClientId) return;
                addChatMessage(msg.message);
                return;
            }
            if (msg.type === 'system-message') { addSystemMessage(msg.text); return; }
            if (msg.type === 'force-mute') {
                if (!state.isMuted && state.localStream) {
                    state.isMuted = true;
                    state.localStream.getAudioTracks().forEach(function(t) { t.enabled = false; });
                    updateMuteButton();
                    updateLocalState(false);
                    sendParticipantState();
                    addSystemMessage('Votre micro a été coupé par l\'hôte');
                }
                return;
            }
            if (msg.type === 'meeting-ended') {
                addSystemMessage(msg.text || 'La réunion a été terminée');
                setTimeout(function() { leaveRoom(true); }, 2000);
                return;
            }
        });

        state.socket.addEventListener('close', function() {
            if (!state.hasLeft) {
                setStatus(dotSignal, statusSignal, 'red', 'Déconnecté');
                Object.keys(state.peers).forEach(function(cid) { closePeerConnection(cid); });
                updateLocalState(true);
                scheduleReconnect();
            }
        });

        state.socket.addEventListener('error', function() {
            setStatus(dotSignal, statusSignal, 'red', 'Erreur de connexion');
        });
    }

    function scheduleReconnect() {
        if (state.hasLeft) return;
        if (state.reconnectAttempts >= state.maxReconnectAttempts) {
            reconnectOverlay.classList.remove('show');
            showWarning('Impossible de se reconnecter. Rechargez la page pour réessayer.');
            return;
        }

        state.reconnectAttempts++;
        var delay = Math.min(1000 * Math.pow(1.5, state.reconnectAttempts - 1), 15000);
        reconnectOverlay.classList.add('show');
        reconnectMsg.textContent = 'Tentative ' + state.reconnectAttempts + '/' + state.maxReconnectAttempts;

        state.reconnectTimer = setTimeout(function() {
            connectSignalServer();
        }, delay);
    }

    /* ========== Media Init ========== */
    function handleLocalStream(stream) {
        state.localStream = stream;
        state.hasCameraTrack = stream.getVideoTracks().length > 0;
        state.isCameraOff = !state.hasCameraTrack;

        updateMuteButton();
        updateCameraButton();
        updateRaiseHandButton();
        updateScreenShareButton();
        updateViewButton();
        updateLocalState(true);
        startAudioMeter('local', stream);
        connectSignalServer();
    }

    function startLocalMedia() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            setStatus(dotMic, statusMic, 'red', 'Non supporté');
            setStatus(dotCam, statusCam, 'red', 'Non supporté');
            showWarning('Votre navigateur ne supporte pas l\'audio/vidéo pour cette réunion.');
            return;
        }

        var audioConstraints = {
            echoCancellation: true,
            noiseSuppression: true,
            autoGainControl: true,
            sampleRate: 48000
        };

        navigator.mediaDevices.getUserMedia({ audio: audioConstraints, video: true }).then(function(stream) {
            handleLocalStream(stream);
        }).catch(function() {
            navigator.mediaDevices.getUserMedia({ audio: audioConstraints, video: false }).then(function(stream) {
                handleLocalStream(stream);
                showWarning('La réunion a démarré sans caméra.');
            }).catch(function() {
                setStatus(dotMic, statusMic, 'red', 'Accès refusé');
                setStatus(dotCam, statusCam, 'red', 'Accès refusé');
                showWarning('Autorisez le micro et la caméra pour rejoindre la réunion.');
            });
        });
    }

    /* ========== Boot ========== */
    setStatus(dotRelay, null, meetingConfig.hasTurn ? 'blue' : 'orange', null);
    startLocalMedia();
})();
</script>
