<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-white"><i class="fas fa-calendar-alt me-2 text-primary"></i> Calendrier des Réunions</h2>
        <div>
            <a href="#" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Planifier Réunion
            </a>
            <a href="#" class="btn btn-outline-info btn-sm ms-2">
                <i class="fas fa-sync-alt"></i> Actualiser
            </a>
        </div>
    </div>

    <!-- Filtres rapides -->
    <div class="card bg-dark border-secondary shadow-sm mb-4">
        <div class="card-body py-2 px-3">
            <div class="d-flex align-items-center">
                <span class="text-white me-3"><i class="fas fa-filter text-secondary"></i> Filtres:</span>
                <span class="badge bg-primary rounded-pill me-2 px-3 py-2 cursor-pointer">Toutes</span>
                <span class="badge bg-secondary rounded-pill me-2 px-3 py-2 cursor-pointer">Aujourd'hui</span>
                <span class="badge bg-secondary rounded-pill me-2 px-3 py-2 cursor-pointer">À venir</span>
                <span class="badge bg-secondary rounded-pill px-3 py-2 cursor-pointer text-muted">Passées</span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Réunion en vedette / Prochaine -->
        <div class="col-12 mb-4">
            <div class="card bg-dark border-primary shadow-sm" style="border-width: 2px;">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded px-3 py-2 text-center me-4">
                            <span class="d-block h5 mb-0 fw-bold">15</span>
                            <small class="text-uppercase fw-semibold">Avril</small>
                        </div>
                        <div>
                            <span class="badge bg-danger mb-2">Prochaine Réunion</span>
                            <h4 class="text-white mb-1">Revue de Sprint - Équipe Dev</h4>
                            <p class="mb-0 text-muted"><i class="far fa-clock me-1"></i> 10:00 - 11:30 &nbsp;|&nbsp; <i class="fas fa-video me-1"></i> Visioconférence (Lien interne)</p>
                        </div>
                    </div>
                    <div>
                        <a href="#" class="btn btn-primary"><i class="fas fa-sign-in-alt me-1"></i> Rejoindre</a>
                        <button class="btn btn-outline-secondary ms-2"><i class="fas fa-ellipsis-v"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grille des réunions -->
        <div class="col-md-4 mb-4">
            <div class="card bg-dark border-secondary shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded small fw-bold">18 Avril</div>
                        <div class="text-muted small"><i class="far fa-clock"></i> 14:00</div>
                    </div>
                    <h5 class="card-title text-white">Point d'avancement Direction</h5>
                    <p class="card-text text-muted small">Analyse des KPIs mensuels et ajustement des objectifs du trimestre en cours.</p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="avatar-group">
                             <span class="badge bg-secondary"><i class="fas fa-users"></i> 8 Invités</span>
                        </div>
                        <a href="#" class="btn btn-sm btn-outline-info">Détails</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card bg-dark border-secondary shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="text-primary bg-primary bg-opacity-10 px-2 py-1 rounded small fw-bold">22 Avril</div>
                        <div class="text-muted small"><i class="far fa-clock"></i> 09:30</div>
                    </div>
                    <h5 class="card-title text-white">Formation Nouveaux Outils</h5>
                    <p class="card-text text-muted small">Session de formation sur l'implémentation du nouveau CRM.</p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="avatar-group">
                             <span class="badge bg-secondary"><i class="fas fa-users"></i> 15 Invités</span>
                        </div>
                        <a href="#" class="btn btn-sm btn-outline-info">Détails</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-dark border-secondary shadow-sm h-100 border-dashed">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center text-muted" style="min-height: 200px;">
                    <i class="fas fa-calendar-plus fa-3x mb-3 text-secondary"></i>
                    <h5>Créer un Créneau</h5>
                    <p class="small">Planifier une nouvelle session avec vos collaborateurs.</p>
                </div>
            </div>
        </div>

    </div>
</div>
<style>
.border-dashed { border-style: dashed !important; border-color: #444 !important; cursor: pointer; transition: all 0.2s;}
.border-dashed:hover { border-color: var(--primary-blue) !important; color: var(--primary-blue) !important;}
.border-dashed:hover * { color: var(--primary-blue) !important;}
.avatar-group .badge { padding: 5px 10px; font-size: 13px;}
</style>
