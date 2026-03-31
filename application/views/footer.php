            </div> <!-- /.container-fluid #mainContent -->
        </div> <!-- /.content-area -->

        <!-- Footer -->
        <footer class="app-footer mt-auto">
            Copyright &copy; <?php echo date('Y'); ?> Exchange Pro — Plateforme d'Échange Professionnel RCA — Tous droits réservés.
        </footer>
    </div> <!-- /.main-content -->

    <!-- Bootstrap 5.3.2 JS Bundle (jQuery already loaded in header) -->
    <script src="<?php echo base_url('assets/js/bootstrap5/bootstrap.bundle.min.js'); ?>"></script>

    <!-- DataTables -->
    <script src="<?php echo base_url('assets/datatables/jquery.dataTables.js'); ?>"></script>
    <script src="<?php echo base_url('assets/datatables/datatables.bootstrap.js'); ?>"></script>

    <script>
    (function() {
        'use strict';

        /* ===== Page Loader ===== */
        var loader = document.getElementById('pageLoader');
        if (loader) {
            loader.classList.remove('loading');
            loader.classList.add('done');
            setTimeout(function(){ loader.style.display='none'; }, 500);
        }
        document.addEventListener('click', function(e) {
            var a = e.target.closest('a[href]');
            if (!a) return;
            var href = a.getAttribute('href');
            if (!href || href.charAt(0)==='#' || href.indexOf('javascript:')===0 || a.target==='_blank' || a.hasAttribute('data-no-loader')) return;
            var l = document.getElementById('pageLoader');
            if (l) { l.style.display=''; l.style.opacity='1'; l.style.width='0'; void l.offsetWidth; l.className='loading'; }
        });
        window.addEventListener('submit', function() {
            var l = document.getElementById('pageLoader');
            if (l) { l.style.display=''; l.style.opacity='1'; l.style.width='0'; void l.offsetWidth; l.className='loading'; }
        });

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
                if (label) document.getElementById('pageTitle').textContent = label.textContent;
            }
        }
        setActiveLink();

        /* ===== Sidebar Toggle (Mobile) ===== */
        var sidebar = document.getElementById('sidebar');
        var sidebarOverlay = document.getElementById('sidebarOverlay');
        var sidebarToggleBtn = document.getElementById('sidebarToggle');

        function openSidebar() {
            sidebar.classList.add('open');
            sidebarOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = '';
        }
        if (sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', function() {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);

        /* ===== Progress Bar ===== */
        var progressBar = document.getElementById('progressBar');
        function showProgress() {
            progressBar.classList.remove('progress-done');
            progressBar.classList.add('ajax-loading');
        }
        function hideProgress() {
            progressBar.classList.remove('ajax-loading');
            progressBar.classList.add('progress-done');
            setTimeout(function() { progressBar.classList.remove('progress-done'); progressBar.style.width = '0'; }, 600);
        }

        /* ===== Flash Alert Auto-Dismiss ===== */
        setTimeout(function() {
            document.querySelectorAll('.flash-alert').forEach(function(el) {
                el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                el.style.opacity = '0';
                el.style.transform = 'translateY(-8px)';
                setTimeout(function() {
                    if (el.parentNode) {
                        try { bootstrap.Alert.getOrCreateInstance(el).close(); } catch(e) { el.remove(); }
                    }
                }, 400);
            });
        }, 5000);

        /* ===== DataTables Initialization ===== */
        window.initDataTables = function() {
            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.DataTable !== 'undefined') {
                jQuery('.ep-datatable, #myDatatable').each(function() {
                    if (jQuery.fn.DataTable.isDataTable(this)) return;
                    jQuery(this).DataTable({
                        language: {
                            processing: "Traitement...", search: "Rechercher :",
                            lengthMenu: "Afficher _MENU_ entrées",
                            info: "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
                            infoEmpty: "Aucune entrée à afficher",
                            infoFiltered: "(filtré de _MAX_ entrées au total)",
                            loadingRecords: "Chargement...", zeroRecords: "Aucun résultat trouvé",
                            emptyTable: "Aucune donnée disponible",
                            paginate: { first: "Premier", previous: "Précédent", next: "Suivant", last: "Dernier" },
                            aria: { sortAscending: ": activer pour trier par ordre croissant", sortDescending: ": activer pour trier par ordre décroissant" }
                        },
                        pageLength: 25,
                        responsive: true,
                        dom: '<"row align-items-center mb-3"<"col-sm-6"l><"col-sm-6"f>>rtip'
                    });
                });
            }
        };
        jQuery(document).ready(window.initDataTables);

        /* ===== Dark Mode Toggle ===== */
        (function initTheme() {
            var saved = localStorage.getItem('ep-theme');
            var theme = saved || 'light';
            document.documentElement.setAttribute('data-theme', theme);

            var toggleBtn = document.getElementById('themeToggle');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    var current = document.documentElement.getAttribute('data-theme') || 'light';
                    var next = current === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-theme', next);
                    localStorage.setItem('ep-theme', next);
                });
            }
        })();

        /* ===== Global Search Overlay ===== */
        (function initSearch() {
            var searchOverlay = document.getElementById('searchOverlay');
            var searchInput = document.getElementById('searchPanelInput');
            var searchResults = document.getElementById('searchPanelResults');
            var searchEmpty = document.getElementById('searchPanelEmpty');
            var searchClose = document.getElementById('searchPanelClose');
            var searchTimer = null;
            var selectedIndex = -1;

            window.openSearchOverlay = function() {
                if (!searchOverlay) return;
                searchOverlay.classList.add('open');
                document.body.style.overflow = 'hidden';
                setTimeout(function() { if (searchInput) searchInput.focus(); }, 100);
            };

            function closeSearchOverlay() {
                if (!searchOverlay) return;
                searchOverlay.classList.remove('open');
                document.body.style.overflow = '';
                if (searchInput) searchInput.value = '';
                if (searchResults) searchResults.innerHTML = '';
                if (searchEmpty) searchEmpty.style.display = 'none';
                selectedIndex = -1;
            }

            if (searchOverlay) {
                searchOverlay.addEventListener('click', function(e) {
                    if (e.target === searchOverlay) closeSearchOverlay();
                });
            }
            if (searchClose) searchClose.addEventListener('click', closeSearchOverlay);

            /* Keyboard shortcuts */
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    openSearchOverlay();
                }
                if (e.key === 'Escape') {
                    if (searchOverlay && searchOverlay.classList.contains('open')) {
                        closeSearchOverlay();
                    } else {
                        closeSidebar();
                    }
                }
                /* Arrow nav in search results */
                if (searchOverlay && searchOverlay.classList.contains('open')) {
                    var items = searchResults ? searchResults.querySelectorAll('.search-result-item') : [];
                    if (e.key === 'ArrowDown') { e.preventDefault(); selectedIndex = Math.min(selectedIndex + 1, items.length - 1); highlightResult(items); }
                    if (e.key === 'ArrowUp')   { e.preventDefault(); selectedIndex = Math.max(selectedIndex - 1, 0); highlightResult(items); }
                    if (e.key === 'Enter' && selectedIndex >= 0 && items[selectedIndex]) {
                        e.preventDefault();
                        window.location.href = items[selectedIndex].getAttribute('href');
                    }
                }
            });

            function highlightResult(items) {
                items.forEach(function(el, i) {
                    el.style.background = i === selectedIndex ? 'var(--primary-light)' : '';
                });
                if (items[selectedIndex]) items[selectedIndex].scrollIntoView({ block: 'nearest' });
            }

            /* Search with debounce */
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    var q = this.value.trim();
                    selectedIndex = -1;
                    if (q.length < 2) {
                        if (searchResults) searchResults.innerHTML = '';
                        if (searchEmpty) searchEmpty.style.display = 'none';
                        return;
                    }
                    searchTimer = setTimeout(function() { performSearch(q); }, 300);
                });
            }

            function performSearch(query) {
                fetch(SITE_URL + 'home/search?q=' + encodeURIComponent(query), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    credentials: 'same-origin'
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (!searchResults) return;
                    searchResults.innerHTML = '';
                    if (!data.results || data.results.length === 0) {
                        if (searchEmpty) searchEmpty.style.display = 'block';
                        return;
                    }
                    if (searchEmpty) searchEmpty.style.display = 'none';
                    data.results.forEach(function(item) {
                        var el = document.createElement('a');
                        el.className = 'search-result-item';
                        el.href = item.url;
                        el.innerHTML =
                            '<div class="search-result-icon" style="background:' + (item.bg || 'var(--primary-light)') + ';color:' + (item.color || 'var(--primary)') + '"><i class="' + escHtml(item.icon || 'fas fa-link') + '"></i></div>' +
                            '<div class="search-result-text"><div class="search-result-title">' + escHtml(item.title) + '</div>' +
                            '<div class="search-result-sub">' + escHtml(item.subtitle || '') + '</div></div>';
                        searchResults.appendChild(el);
                    });
                })
                .catch(function() {
                    if (searchEmpty) searchEmpty.style.display = 'block';
                });
            }

            function escHtml(str) {
                var div = document.createElement('div');
                div.appendChild(document.createTextNode(str || ''));
                return div.innerHTML;
            }
        })();

        /* ===== Chat Unread Counters ===== */
        var chatUnreadPollTimer = null;

        window.updateChatUnreadCounts = function(summary) {
            if (!summary) return;
            var total = parseInt(summary.total, 10) || 0;
            var privateTotal = parseInt(summary.private_total, 10) || 0;
            var groupTotal = parseInt(summary.group_total, 10) || 0;

            function applyBadge(el, count) {
                if (!el) return;
                if (count > 0) { el.textContent = count > 99 ? '99+' : count; el.classList.remove('d-none'); }
                else { el.textContent = '0'; el.classList.add('d-none'); }
            }
            applyBadge(document.getElementById('globalChatUnreadBadge'), total);
            applyBadge(document.getElementById('private-tab-unread'), privateTotal);
            applyBadge(document.getElementById('group-tab-unread'), groupTotal);

            /* Also update notification badge */
            var notifBadge = document.getElementById('notifBadge');
            if (notifBadge) {
                if (total > 0) { notifBadge.textContent = total > 99 ? '99+' : total; notifBadge.classList.remove('d-none'); }
                else { notifBadge.classList.add('d-none'); }
            }
        };

        function fetchChatUnreadCounts() {
            if (typeof CHAT_UNREAD_SUMMARY_URL === 'undefined' || !CHAT_UNREAD_SUMMARY_URL) return;
            fetch(CHAT_UNREAD_SUMMARY_URL, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            })
            .then(function(r) { if (!r.ok) throw new Error('fail'); return r.json(); })
            .then(function(payload) {
                if (payload && payload.unread_summary) {
                    window.updateChatUnreadCounts(payload.unread_summary);
                    if (document.getElementById('chatApp') && typeof window.getChatRealtimeState === 'function' && typeof window.refreshChatThreadState === 'function') {
                        var chatState = window.getChatRealtimeState();
                        if (chatState && !chatState.hasActiveThread) window.refreshChatThreadState();
                    }
                }
            })
            .catch(function() {});
        }

        fetchChatUnreadCounts();
        chatUnreadPollTimer = setInterval(function() { if (!document.hidden) fetchChatUnreadCounts(); }, 5000);

        /* ===== Mark All Notifications Read ===== */
        var markAllBtn = document.getElementById('markAllRead');
        if (markAllBtn) {
            markAllBtn.addEventListener('click', function() {
                document.querySelectorAll('.notif-item.unread').forEach(function(el) { el.classList.remove('unread'); });
                var badge = document.getElementById('notifBadge');
                if (badge) badge.classList.add('d-none');
            });
        }

    })();
    </script>
</body>
</html>
