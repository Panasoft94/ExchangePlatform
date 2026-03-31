<?php
$turn_username = 'reunion';
$turn_password = 'reunion123';
if (!empty($turn_config['credentials']) && is_array($turn_config['credentials'])) {
    foreach ($turn_config['credentials'] as $username => $password) {
        $turn_username = $username;
        $turn_password = $password;
        break;
    }
}
?>

<style>
.rtc-config-shell { display: flex; flex-direction: column; gap: 1.5rem; }
.rtc-config-card {
    background: var(--bg-white);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
}
.rtc-config-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.rtc-config-body {
    padding: 1.5rem;
}
.rtc-config-help {
    background: var(--bg-main);
    border: 1px solid var(--border-color);
    border-radius: var(--radius);
    padding: 14px 16px;
    color: var(--text-secondary);
    font-size: 13px;
    line-height: 1.6;
}
.rtc-config-preview {
    background: #0f172a;
    color: #dbe7ff;
    border-radius: var(--radius-lg);
    padding: 16px;
    font-size: 12px;
    line-height: 1.6;
    white-space: pre-wrap;
    font-family: 'SFMono-Regular', Consolas, monospace;
}
.rtc-feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 12px;
}
.rtc-feature-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 14px;
    background: var(--bg-main);
    border-radius: var(--radius);
    border: 1px solid var(--border-color);
}
.rtc-feature-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}
.rtc-feature-icon.blue { background: var(--primary-light); color: var(--primary); }
.rtc-feature-icon.green { background: #dcfce7; color: #16a34a; }
.rtc-feature-icon.purple { background: #ede9fe; color: #7c3aed; }
.rtc-feature-icon.orange { background: #fff7ed; color: #ea580c; }
.rtc-feature-text strong { display: block; font-size: 13px; color: var(--text-primary); margin-bottom: 2px; }
.rtc-feature-text span { font-size: 11px; color: var(--text-muted); line-height: 1.4; }
</style>

<div class="rtc-config-shell">
    <div>
        <h1 class="h4 fw-semibold mb-1" style="color: var(--text-primary);">
            <i class="fas fa-sliders me-2" style="color: var(--primary);"></i>Configuration RTC locale
        </h1>
        <p class="mb-0 small" style="color: var(--text-secondary);">Gérez les ports et identifiants du salon audio/vidéo local, du serveur de signalisation et du TURN interne.</p>
    </div>

    <!-- Features overview -->
    <div class="rtc-config-card">
        <div class="rtc-config-header">
            <div>
                <div class="fw-semibold" style="color: var(--text-primary);">Fonctionnalités du salon</div>
                <div class="small" style="color: var(--text-secondary);">Fonctions disponibles dans la visioconférence.</div>
            </div>
        </div>
        <div class="rtc-config-body">
            <div class="rtc-feature-grid">
                <div class="rtc-feature-item">
                    <div class="rtc-feature-icon blue"><i class="fas fa-video"></i></div>
                    <div class="rtc-feature-text">
                        <strong>Audio &amp; Vidéo</strong>
                        <span>Conférence WebRTC pair à pair avec micro et caméra</span>
                    </div>
                </div>
                <div class="rtc-feature-item">
                    <div class="rtc-feature-icon purple"><i class="fas fa-desktop"></i></div>
                    <div class="rtc-feature-text">
                        <strong>Partage d'écran</strong>
                        <span>Partagez votre écran avec les participants</span>
                    </div>
                </div>
                <div class="rtc-feature-item">
                    <div class="rtc-feature-icon green"><i class="fas fa-comments"></i></div>
                    <div class="rtc-feature-text">
                        <strong>Chat en direct</strong>
                        <span>Messagerie texte pendant la réunion</span>
                    </div>
                </div>
                <div class="rtc-feature-item">
                    <div class="rtc-feature-icon orange"><i class="fas fa-hand-paper"></i></div>
                    <div class="rtc-feature-text">
                        <strong>Demande de parole</strong>
                        <span>Levez la main pour signaler votre intervention</span>
                    </div>
                </div>
                <div class="rtc-feature-item">
                    <div class="rtc-feature-icon blue"><i class="fas fa-th"></i></div>
                    <div class="rtc-feature-text">
                        <strong>Vues multiples</strong>
                        <span>Mode grille et mode intervenant actif</span>
                    </div>
                </div>
                <div class="rtc-feature-item">
                    <div class="rtc-feature-icon purple"><i class="fas fa-crown"></i></div>
                    <div class="rtc-feature-text">
                        <strong>Contrôles hôte</strong>
                        <span>Muter tous, terminer la réunion pour tous</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Config form -->
    <div class="rtc-config-card">
        <div class="rtc-config-header">
            <div>
                <div class="fw-semibold" style="color: var(--text-primary);">Paramètres du salon local</div>
                <div class="small" style="color: var(--text-secondary);">Les changements s'appliquent au prochain redémarrage des services Node locaux.</div>
            </div>
            <a href="<?php echo site_url('reunions'); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>
        <div class="rtc-config-body">
            <?php echo form_open('visioconference/save_configuration'); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium small" style="color: var(--text-primary);">Hôte réseau RTC</label>
                    <input type="text" name="rtc_host" class="form-control" value="<?php echo htmlspecialchars($app_config['rtcHost']); ?>" placeholder="Ex: 192.168.1.10 ou intranet.local">
                    <div class="form-text">Adresse IP ou hostname accessible par les postes du réseau.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium small" style="color: var(--text-primary);">Port signalisation WebSocket</label>
                    <input type="number" name="signal_port" class="form-control" value="<?php echo (int) $app_config['signalPort']; ?>" min="1" max="65535">
                    <div class="form-text">Port du serveur Node.js de signalisation WebSocket.</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium small" style="color: var(--text-primary);">Port TURN/STUN</label>
                    <input type="number" name="turn_port" class="form-control" value="<?php echo (int) $app_config['turnPort']; ?>" min="1" max="65535">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium small" style="color: var(--text-primary);">Utilisateur TURN</label>
                    <input type="text" name="turn_username" class="form-control" value="<?php echo htmlspecialchars($turn_username); ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-medium small" style="color: var(--text-primary);">Mot de passe TURN</label>
                    <input type="password" name="turn_password" class="form-control" value="<?php echo htmlspecialchars($turn_password); ?>">
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="turnEnabled" name="turn_enabled" value="1" <?php echo !empty($app_config['turnEnabled']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="turnEnabled">Activer le serveur TURN local (recommandé pour les réseaux complexes)</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save me-1"></i> Enregistrer la configuration
                        </button>
                        <a href="<?php echo base_url('start-local-reunion.bat'); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-play me-1"></i> Démarrer les services locaux
                        </a>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="rtc-config-help">
                <strong><i class="fas fa-info-circle me-1"></i>Effet des changements</strong><br>
                Le port WebSocket contrôle la signalisation locale. Le port TURN/STUN sert à améliorer les connexions WebRTC entre postes du réseau. Après modification, relancez le script local pour recharger les services Node.<br><br>
                <strong><i class="fas fa-shield-alt me-1"></i>Sécurité</strong><br>
                Les identifiants TURN sont stockés dans <code>realtime/turn-config.json</code>. Changez les valeurs par défaut en production.
            </div>
        </div>
        <div class="col-lg-6">
            <div class="rtc-config-preview">ICE actif:
<?php echo htmlspecialchars(json_encode($ice_servers, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); ?></div>
        </div>
    </div>
</div>