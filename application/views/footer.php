            </div> <!-- /.container-fluid #mainContent -->
        </div> <!-- /.content-area -->

        <!-- Footer -->
        <footer class="app-footer mt-auto">
            Copyright &copy; <?php echo date('Y'); ?> Plateforme d'Échange Professionnel RCA ACM — Tous droits réservés.
        </footer>
    </div> <!-- /.main-content -->

    <!-- Bootstrap 5.3.2 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="<?php echo base_url('assets/js/jquery.js'); ?>"></script>

    <!-- DataTables -->
    <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/datatables.bootstrap.js'); ?>"></script>

    <!-- Custom Scripts -->
    <script src="<?php echo base_url('assets/js/script_djim.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/functions.js'); ?>"></script>

    <script>
    (function() {
        'use strict';

        /* ===== Sidebar Active Link ===== */
        let currentUrl = window.location.href.replace(/\/+$/, '');
        const sidebarLinks = document.querySelectorAll('.sidebar-link[data-nav]');

        function setActiveLink() {
            let bestMatch = null;
            let bestLength = 0;

            sidebarLinks.forEach(function(link) {
                link.classList.remove('active');
                const href = link.href.replace(/\/+$/, '');
                if (currentUrl === href || currentUrl.indexOf(href) === 0) {
                    if (href.length > bestLength) {
                        bestLength = href.length;
                        bestMatch = link;
                    }
                }
            });

            if (bestMatch) {
                bestMatch.classList.add('active');
                const label = bestMatch.querySelector('span');
                if (label) {
                    document.getElementById('pageTitle').textContent = label.textContent;
                }
            }
        }
        setActiveLink();

        /* ===== Sidebar Toggle (Mobile) ===== */
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                if (sidebar.classList.contains('open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }
        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }

        /* ===== Progress Bar ===== */
        const progressBar = document.getElementById('progressBar');

        function showProgress() {
            progressBar.classList.remove('progress-done');
            progressBar.classList.add('ajax-loading');
        }
        function hideProgress() {
            progressBar.classList.remove('ajax-loading');
            progressBar.classList.add('progress-done');
            setTimeout(function() {
                progressBar.classList.remove('progress-done');
                progressBar.style.width = '0';
            }, 600);
        }

        /* ===== AJAX Page Loading ===== */
        window.loadPage = function(url) {
            const contentEl = document.getElementById('mainContent');
            showProgress();

            // Fade out current content
            contentEl.classList.add('fade-out');

            const xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 400) {
                    setTimeout(function() {
                        contentEl.innerHTML = xhr.responseText;
                        contentEl.classList.remove('fade-out');
                        hideProgress();

                        // Update URL without reload
                        window.history.pushState({ url: url }, '', url);
                        currentUrl = url.replace(/\/+$/, '');
                        setActiveLink();

                        // Re-init DataTables if present
                        initDataTables();
                    }, 200);
                } else {
                    // Fallback: navigate normally on error
                    window.location.href = url;
                }
            };

            xhr.onerror = function() {
                window.location.href = url;
            };

            xhr.send();
        };

        // Handle browser back/forward
        window.addEventListener('popstate', function(e) {
            if (e.state && e.state.url) {
                loadPage(e.state.url);
            }
        });

        /* ===== Keyboard Shortcut: Ctrl+K / Cmd+K ===== */
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.getElementById('globalSearch');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }
            // Close sidebar on Escape (mobile)
            if (e.key === 'Escape') {
                closeSidebar();
                const searchEl = document.getElementById('globalSearch');
                if (searchEl === document.activeElement) {
                    searchEl.blur();
                }
            }
        });

        /* ===== Flash Alert Auto-Dismiss ===== */
        setTimeout(function() {
            const alerts = document.querySelectorAll('.flash-alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-8px)';
                setTimeout(function() {
                    if (alert.parentNode) {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        bsAlert.close();
                    }
                }, 400);
            });
        }, 5000);

        /* ===== DataTables Initialization ===== */
        function initDataTables() {
            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.DataTable !== 'undefined') {
                jQuery('#myDatatable').DataTable({
                    destroy: true,
                    language: {
                        processing:     "Traitement...",
                        search:         "Rechercher :",
                        lengthMenu:     "Afficher _MENU_ entrées",
                        info:           "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
                        infoEmpty:      "Aucune entrée à afficher",
                        infoFiltered:   "(filtré de _MAX_ entrées au total)",
                        loadingRecords: "Chargement...",
                        zeroRecords:    "Aucun résultat trouvé",
                        emptyTable:     "Aucune donnée disponible",
                        paginate: {
                            first:    "Premier",
                            previous: "Précédent",
                            next:     "Suivant",
                            last:     "Dernier"
                        },
                        aria: {
                            sortAscending:  ": activer pour trier par ordre croissant",
                            sortDescending: ": activer pour trier par ordre décroissant"
                        }
                    },
                    pageLength: 25,
                    responsive: true
                });
            }
        }

        /* Init DataTables once DOM + jQuery ready */
        if (typeof jQuery !== 'undefined') {
            jQuery(document).ready(function() {
                initDataTables();
            });
        } else {
            document.addEventListener('DOMContentLoaded', initDataTables);
        }

    })();
    </script>
</body>
</html>
