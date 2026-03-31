<div class="chat-wrapper" id="chatApp" data-state-url="<?php echo site_url('chat/thread_state'); ?>" data-send-url="<?php echo site_url('chat/send_message'); ?>" data-page-base="<?php echo site_url('chat/index'); ?>" data-current-type="<?php echo htmlspecialchars($active_type); ?>" data-current-id="<?php echo (int) $active_id; ?>" data-poll-interval="5000">
    <div class="chat-sidebar" id="chatSidebar">
        <div id="chat-sidebar-content"><?php $this->load->view('_sidebar'); ?></div>
    </div>

    <button class="btn btn-primary chat-sidebar-toggle d-md-none" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="chat-main" id="chat-main-content"><?php $this->load->view('_main'); ?></div>
</div>

<?php $this->load->view('_create_group_modal'); ?>
<div id="chat-group-modal-container"><?php $this->load->view('_add_group_members_modal'); ?></div>

<style>
/* Chat Layout */
.chat-wrapper {
    display: flex;
    height: calc(100vh - 140px);
    background: var(--bg-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    position: relative;
}

/* Sidebar */
.chat-sidebar {
    width: 300px;
    min-width: 300px;
    border-right: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    background: var(--bg-white);
}
.chat-sidebar-header {
    padding: 20px 16px 0;
    flex-shrink: 0;
}
.chat-sidebar-body {
    flex: 1;
    overflow-y: auto;
}
.chat-tabs {
    border-bottom: 1px solid var(--border-color);
}
.chat-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: var(--text-secondary);
    font-size: 0.8rem;
    font-weight: 500;
    padding: 10px 8px;
    border-radius: 0;
    transition: all 0.15s ease;
}
.chat-tabs .nav-link.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
    background: transparent;
}
.chat-tabs .nav-link:hover:not(.active) {
    color: var(--text-primary);
    border-bottom-color: var(--border-color);
}

/* Contact items */
.chat-contact-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    text-decoration: none;
    border-left: 3px solid transparent;
    transition: all 0.12s ease;
}
.chat-contact-item:hover {
    background: var(--bg-main);
}
.chat-contact-item.active {
    background: var(--primary-light);
    border-left-color: var(--primary);
}

/* Avatars */
.chat-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.8rem;
    font-weight: 600;
    flex-shrink: 0;
}
.chat-avatar-group {
    background: var(--primary-light) !important;
    color: var(--primary) !important;
}
.chat-avatar-sm {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.65rem;
    font-weight: 600;
    flex-shrink: 0;
}
.chat-status-dot {
    position: absolute;
    bottom: 1px;
    right: 1px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #dadce0;
    border: 2px solid var(--bg-white);
}
.chat-status-dot.online {
    background: #188038;
}

/* Main area */
.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
    background: var(--bg-main);
}
.chat-main-header {
    padding: 16px 24px;
    background: var(--bg-white);
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
}
.chat-group-members-dropdown {
    flex-shrink: 0;
}
.chat-group-add-btn {
    height: 46px;
    min-width: 132px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    white-space: nowrap;
}
.chat-group-members-trigger {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 8px 12px;
    border: 1px solid var(--border-color);
    border-radius: 999px;
    background: var(--bg-white);
    color: var(--text-primary);
    box-shadow: none;
}
.chat-group-members-trigger:hover,
.chat-group-members-trigger:focus,
.chat-group-members-trigger:active {
    border-color: var(--primary);
    background: var(--primary-light);
    color: var(--text-primary);
}
.chat-group-members-stack {
    display: inline-flex;
    align-items: center;
    padding-right: 2px;
}
.chat-group-member-dot,
.chat-group-member-avatar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #fff;
    font-weight: 600;
    flex-shrink: 0;
}
.chat-group-member-dot {
    width: 30px;
    height: 30px;
    font-size: 0.68rem;
    border: 2px solid var(--bg-white);
    margin-left: -8px;
}
.chat-group-member-dot:first-child {
    margin-left: 0;
}
.chat-group-members-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    line-height: 1.1;
}
.chat-group-members-label {
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--text-primary);
}
.chat-group-members-count {
    font-size: 0.7rem;
    color: var(--text-secondary);
}
.chat-group-members-menu {
    width: 290px;
    padding: 0;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-md);
}
.chat-group-members-menu-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 14px;
    background: var(--bg-main);
    border-bottom: 1px solid var(--border-color);
    font-size: 0.78rem;
    font-weight: 600;
    color: var(--text-primary);
}
.chat-group-members-menu-body {
    max-height: 280px;
    overflow-y: auto;
    padding: 8px;
}
.chat-group-member-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px;
    border-radius: var(--radius);
}
.chat-group-member-item:hover {
    background: var(--bg-main);
}
.chat-group-member-avatar {
    width: 34px;
    height: 34px;
    font-size: 0.72rem;
}
.chat-group-member-text {
    min-width: 0;
    flex: 1;
}
.chat-group-member-name {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.chat-group-member-subtitle {
    font-size: 0.72rem;
    color: var(--text-secondary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.chat-group-member-online {
    color: #188038;
    font-weight: 600;
    margin-left: 8px;
}

/* Messages */
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
    display: flex;
    flex-direction: column;
}
.chat-date-separator {
    text-align: center;
    margin: 16px 0;
}
.chat-date-separator span {
    background: var(--bg-white);
    padding: 4px 16px;
    border-radius: 12px;
    font-size: 0.7rem;
    color: var(--text-secondary);
    font-weight: 500;
    box-shadow: var(--shadow-sm);
}
.chat-bubble-row {
    display: flex;
    margin-bottom: 8px;
    align-items: flex-end;
    gap: 8px;
}
.chat-bubble-row.sent {
    justify-content: flex-end;
}
.chat-bubble-row.received {
    justify-content: flex-start;
}
.chat-bubble-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.6rem;
    font-weight: 600;
    flex-shrink: 0;
}
.chat-bubble {
    max-width: 65%;
    padding: 10px 16px;
    font-size: 0.875rem;
    line-height: 1.5;
    word-wrap: break-word;
}
.chat-bubble.sent {
    background: var(--primary);
    color: #fff;
    border-radius: 18px 18px 4px 18px;
}
.chat-bubble.received {
    background: var(--bg-white);
    color: var(--text-primary);
    border-radius: 18px 18px 18px 4px;
    border: 1px solid var(--border-color);
}
.chat-bubble-sender {
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 2px;
}
.chat-bubble-time {
    display: block;
    font-size: 0.65rem;
    margin-top: 4px;
    opacity: 0.7;
    text-align: right;
}
.chat-bubble.sent .chat-bubble-time {
    color: rgba(255,255,255,0.7);
}
.chat-bubble.received .chat-bubble-time {
    color: var(--text-secondary);
}
.chat-attachments-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 8px;
}
.chat-attachment-chip {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: 12px;
    text-decoration: none;
    transition: var(--transition);
}
.chat-attachment-chip.sent {
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
}
.chat-attachment-chip.received {
    background: var(--bg-main);
    color: var(--text-primary);
}
.chat-attachment-chip:hover {
    transform: translateY(-1px);
}
.chat-attachment-icon {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.9);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
}
.chat-attachment-chip.received .chat-attachment-icon {
    background: var(--bg-white);
}
.chat-attachment-body {
    display: flex;
    flex-direction: column;
    min-width: 0;
    flex: 1;
}
.chat-attachment-action {
    flex-shrink: 0;
    font-size: 0.82rem;
    opacity: 0.9;
}
.chat-attachment-name,
.chat-attachment-meta {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.chat-attachment-name {
    font-size: 0.8rem;
    font-weight: 600;
}
.chat-attachment-meta {
    font-size: 0.68rem;
    opacity: 0.8;
}

/* Empty states */
.chat-empty-messages,
.chat-empty-state {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 40px;
}

/* Input area */
.chat-input-area {
    padding: 16px 24px;
    background: var(--bg-white);
    border-top: 1px solid var(--border-color);
    flex-shrink: 0;
}
.chat-attach-btn {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 1px solid var(--border-color);
    background: var(--bg-white);
    color: var(--primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: var(--transition);
}
.chat-attach-btn:hover {
    background: var(--primary-light);
    border-color: var(--primary);
    color: var(--primary);
}
.chat-upload-preview {
    display: none;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 10px;
}
.chat-upload-preview.show {
    display: flex;
}
.chat-upload-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 10px;
    border-radius: 999px;
    background: var(--bg-main);
    border: 1px solid var(--border-color);
    font-size: 0.74rem;
    color: var(--text-primary);
}
.chat-upload-chip-name {
    max-width: 220px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.chat-upload-chip-remove {
    border: none;
    background: transparent;
    color: var(--text-secondary);
    padding: 0;
    line-height: 1;
}
.chat-upload-chip-remove:hover {
    color: #d93025;
}

/* Modal items */
.modal-member-item:hover {
    background: var(--bg-main);
}
.form-check-input:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}

/* Scrollbar styling */
.chat-sidebar-body::-webkit-scrollbar,
.chat-messages::-webkit-scrollbar {
    width: 5px;
}
.chat-sidebar-body::-webkit-scrollbar-track,
.chat-messages::-webkit-scrollbar-track {
    background: transparent;
}
.chat-sidebar-body::-webkit-scrollbar-thumb,
.chat-messages::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 3px;
}

/* Mobile toggle */
.chat-sidebar-toggle {
    position: absolute;
    top: 12px;
    left: 12px;
    z-index: 10;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}

/* Responsive */
@media (max-width: 767.98px) {
    .chat-sidebar {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 20;
        transform: translateX(-100%);
        transition: transform 0.25s ease;
        box-shadow: var(--shadow-md);
    }
    .chat-sidebar.show {
        transform: translateX(0);
    }
    .chat-wrapper {
        height: calc(100vh - 120px);
    }
    .chat-group-members-menu {
        width: 260px;
    }
}
@media (min-width: 768px) {
    .chat-sidebar-toggle {
        display: none !important;
    }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var chatApp = document.getElementById("chatApp");
    if (!chatApp) {
        return;
    }

    var chatSidebar = document.getElementById("chatSidebar");
    var sidebarToggle = document.getElementById("sidebarToggle");
    var sidebarContent = document.getElementById("chat-sidebar-content");
    var mainContent = document.getElementById("chat-main-content");
    var groupModalContainer = document.getElementById("chat-group-modal-container");
    var pollInterval = parseInt(chatApp.getAttribute("data-poll-interval"), 10) || 5000;
    var requestInFlight = false;
    var pollTimer = null;
    var messagesObserver = null;
    var sidebarSearchValue = "";
    var selectedChatFiles = [];
    var sidebarTabState = chatApp.getAttribute("data-current-type") || "private";
    var currentState = {
        type: chatApp.getAttribute("data-current-type") || "private",
        id: chatApp.getAttribute("data-current-id") || ""
    };

    function getAttachmentIconClass(fileName) {
        var extension = (fileName.split('.').pop() || '').toLowerCase();
        var map = {
            pdf: 'fas fa-file-pdf',
            doc: 'fas fa-file-word',
            docx: 'fas fa-file-word',
            xls: 'fas fa-file-excel',
            xlsx: 'fas fa-file-excel',
            ppt: 'fas fa-file-powerpoint',
            pptx: 'fas fa-file-powerpoint',
            png: 'fas fa-file-image',
            jpg: 'fas fa-file-image',
            jpeg: 'fas fa-file-image',
            gif: 'fas fa-file-image',
            txt: 'fas fa-file-lines',
            csv: 'fas fa-file-csv',
            zip: 'fas fa-file-zipper',
            rar: 'fas fa-file-zipper'
        };

        return map[extension] || 'fas fa-file';
    }

    function syncChatFileInput() {
        var chatFilesInput = document.getElementById("chatFiles");
        if (!chatFilesInput || typeof DataTransfer === "undefined") {
            return;
        }

        var dataTransfer = new DataTransfer();
        selectedChatFiles.forEach(function(file) {
            dataTransfer.items.add(file);
        });
        chatFilesInput.files = dataTransfer.files;
    }

    function renderSelectedFiles() {
        var preview = document.getElementById("chatUploadPreview");
        if (!preview) {
            return;
        }

        if (!selectedChatFiles.length) {
            preview.innerHTML = "";
            preview.classList.remove("show");
            return;
        }

        preview.classList.add("show");
        preview.innerHTML = selectedChatFiles.map(function(file, index) {
            return '<span class="chat-upload-chip">'
                + '<i class="' + getAttachmentIconClass(file.name) + '"></i>'
                + '<span class="chat-upload-chip-name">' + file.name.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</span>'
                + '<button type="button" class="chat-upload-chip-remove" data-chat-file-index="' + index + '"><i class="fas fa-times"></i></button>'
                + '</span>';
        }).join("");

        Array.prototype.forEach.call(preview.querySelectorAll(".chat-upload-chip-remove"), function(button) {
            button.addEventListener("click", function() {
                var fileIndex = parseInt(this.getAttribute("data-chat-file-index"), 10);
                if (!isNaN(fileIndex)) {
                    selectedChatFiles.splice(fileIndex, 1);
                    syncChatFileInput();
                    renderSelectedFiles();
                }
            });
        });
    }

    function getStateUrl(type, id) {
        var base = chatApp.getAttribute("data-state-url");
        if (!id) {
            return base;
        }
        return base + "/" + encodeURIComponent(type) + "/" + encodeURIComponent(id);
    }

    function focusChatInput(moveCaretToEnd) {
        var chatInput = document.getElementById("chatInput");
        if (!chatInput) {
            return;
        }

        window.requestAnimationFrame(function() {
            chatInput.focus();
            if (moveCaretToEnd && typeof chatInput.setSelectionRange === "function") {
                var valueLength = chatInput.value.length;
                chatInput.setSelectionRange(valueLength, valueLength);
            }
        });
    }

    function scrollMessagesToBottom(force) {
        var chatMessages = document.getElementById("chat-messages");
        if (!chatMessages) {
            return;
        }

        function moveToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
            if (chatMessages.lastElementChild && typeof chatMessages.lastElementChild.scrollIntoView === "function") {
                chatMessages.lastElementChild.scrollIntoView({ block: "end" });
            }
        }

        var nearBottom = (chatMessages.scrollHeight - chatMessages.scrollTop - chatMessages.clientHeight) < 120;
        if (force || nearBottom) {
            moveToBottom();
            window.requestAnimationFrame(function() {
                moveToBottom();
                window.setTimeout(moveToBottom, 80);
            });
        }
    }

    function observeMessagesContainer() {
        var chatMessages = document.getElementById("chat-messages");

        if (messagesObserver) {
            messagesObserver.disconnect();
            messagesObserver = null;
        }

        if (!chatMessages || typeof MutationObserver === "undefined") {
            return;
        }

        messagesObserver = new MutationObserver(function(mutations) {
            var hasNewNodes = mutations.some(function(mutation) {
                return mutation.addedNodes && mutation.addedNodes.length > 0;
            });

            if (hasNewNodes) {
                scrollMessagesToBottom(true);
            }
        });

        messagesObserver.observe(chatMessages, { childList: true, subtree: true });
    }

    function bindTextarea() {
        var chatInput = document.getElementById("chatInput");
        if (!chatInput || chatInput.getAttribute("data-chat-bound") === "1") {
            return;
        }

        chatInput.setAttribute("data-chat-bound", "1");
        chatInput.addEventListener("pointerdown", function() {
            var input = this;
            window.setTimeout(function() {
                input.focus();
            }, 0);
        });
        chatInput.addEventListener("input", function() {
            this.style.height = "auto";
            this.style.height = Math.min(this.scrollHeight, 120) + "px";
        });
        chatInput.addEventListener("keydown", function(e) {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                if (this.value.trim() || selectedChatFiles.length) {
                    this.closest("form").dispatchEvent(new Event("submit", { cancelable: true, bubbles: true }));
                }
            }
        });
    }

    function bindAttachmentInput() {
        var chatFilesInput = document.getElementById("chatFiles");
        if (!chatFilesInput || chatFilesInput.getAttribute("data-chat-bound") === "1") {
            renderSelectedFiles();
            return;
        }

        chatFilesInput.setAttribute("data-chat-bound", "1");
        chatFilesInput.addEventListener("change", function() {
            var incomingFiles = Array.prototype.slice.call(this.files || []);
            if (incomingFiles.length) {
                selectedChatFiles = selectedChatFiles.concat(incomingFiles);
                syncChatFileInput();
                renderSelectedFiles();
            }
        });

        renderSelectedFiles();
    }

    function bindSearch() {
        var searchInput = document.getElementById("chatSearch");
        if (!searchInput || searchInput.getAttribute("data-chat-bound") === "1") {
            if (searchInput && sidebarSearchValue) {
                searchInput.value = sidebarSearchValue;
            }
            return;
        }

        searchInput.setAttribute("data-chat-bound", "1");
        if (sidebarSearchValue) {
            searchInput.value = sidebarSearchValue;
        }
        searchInput.addEventListener("input", function() {
            var query = this.value.toLowerCase();
            sidebarSearchValue = this.value;
            var items = sidebarContent ? sidebarContent.querySelectorAll(".chat-contact-item") : [];
            Array.prototype.forEach.call(items, function(item) {
                var text = item.getAttribute("data-search") || "";
                item.style.display = text.indexOf(query) !== -1 ? "" : "none";
            });
        });
        searchInput.dispatchEvent(new Event("input"));
    }

    function bindSidebarToggle() {
        if (!sidebarToggle || !chatSidebar || sidebarToggle.getAttribute("data-chat-bound") === "1") {
            return;
        }

        sidebarToggle.setAttribute("data-chat-bound", "1");
        sidebarToggle.addEventListener("click", function() {
            chatSidebar.classList.toggle("show");
        });
    }

    function activateSidebarTab(tabName) {
        var normalizedTab = (tabName === "group") ? "group" : "private";
        var targetButton = document.getElementById(normalizedTab + "-tab");

        if (!targetButton) {
            return;
        }

        if (typeof bootstrap !== "undefined" && bootstrap.Tab) {
            bootstrap.Tab.getOrCreateInstance(targetButton).show();
            window.setTimeout(function() {
                targetButton.blur();
            }, 0);
            return;
        }

        var privateButton = document.getElementById("private-tab");
        var groupButton = document.getElementById("group-tab");
        var privatePanel = document.getElementById("privatePanel");
        var groupPanel = document.getElementById("groupPanel");

        if (privateButton && groupButton && privatePanel && groupPanel) {
            var showPrivate = normalizedTab === "private";
            privateButton.classList.toggle("active", showPrivate);
            groupButton.classList.toggle("active", !showPrivate);
            privatePanel.classList.toggle("show", showPrivate);
            privatePanel.classList.toggle("active", showPrivate);
            groupPanel.classList.toggle("show", !showPrivate);
            groupPanel.classList.toggle("active", !showPrivate);
        }
    }

    function bindSidebarTabs() {
        var tabButtons = sidebarContent ? sidebarContent.querySelectorAll('[data-bs-toggle="tab"]') : [];

        Array.prototype.forEach.call(tabButtons, function(button) {
            if (button.getAttribute("data-chat-bound") === "1") {
                return;
            }

            button.setAttribute("data-chat-bound", "1");
            button.addEventListener("shown.bs.tab", function() {
                sidebarTabState = this.id === "group-tab" ? "group" : "private";
            });
            button.addEventListener("click", function() {
                sidebarTabState = this.id === "group-tab" ? "group" : "private";
            });
        });

        activateSidebarTab(sidebarTabState);
    }

    function applyState(payload, options) {
        var shouldScroll = options && options.scrollToBottom;
        var shouldFocusInput = options && options.focusInput;
        var currentSidebarHtml = sidebarContent ? sidebarContent.innerHTML : "";
        var currentMainHtml = mainContent ? mainContent.innerHTML : "";
        var currentGroupModalHtml = groupModalContainer ? groupModalContainer.innerHTML : "";
        var sidebarChanged = typeof payload.sidebar_html === "string" && payload.sidebar_html !== currentSidebarHtml;
        var mainChanged = typeof payload.main_html === "string" && payload.main_html !== currentMainHtml;
        var groupModalChanged = typeof payload.group_modal_html === "string" && payload.group_modal_html !== currentGroupModalHtml;

        if (sidebarChanged && sidebarContent) {
            sidebarContent.innerHTML = payload.sidebar_html;
        }
        if (mainChanged && mainContent) {
            mainContent.innerHTML = payload.main_html;
        }
        if (groupModalChanged && groupModalContainer) {
            groupModalContainer.innerHTML = payload.group_modal_html;
        }

        currentState.type = payload.active_type || currentState.type;
        currentState.id = payload.active_id ? String(payload.active_id) : "";
        chatApp.setAttribute("data-current-type", currentState.type);
        chatApp.setAttribute("data-current-id", currentState.id);

        if (mainChanged) {
            selectedChatFiles = [];
        }

        if (payload.unread_summary && typeof window.updateChatUnreadCounts === "function") {
            window.updateChatUnreadCounts(payload.unread_summary);
        }

        if (sidebarChanged || mainChanged || groupModalChanged) {
            bindDynamicUi();
        }
        scrollMessagesToBottom(shouldScroll || payload.message_sent === true || mainChanged);
        if (shouldFocusInput || payload.message_sent === true) {
            focusChatInput(true);
        }
    }

    function fetchState(type, id, options) {
        if (requestInFlight) {
            return Promise.resolve();
        }

        requestInFlight = true;
        return fetch(getStateUrl(type, id), {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            },
            credentials: "same-origin"
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error("Erreur de chargement de la conversation");
            }
            return response.json();
        })
        .then(function(payload) {
            applyState(payload, options || {});
            if (!(options && options.skipHistory) && payload.page_url) {
                window.history.pushState({ type: currentState.type, id: currentState.id }, "", payload.page_url);
            }
        })
        .catch(function() {
            if (!(options && options.silent)) {
                window.location.href = chatApp.getAttribute("data-page-base") + "/" + encodeURIComponent(type) + "/" + encodeURIComponent(id);
            }
        })
        .then(function() {
            requestInFlight = false;
        });
    }

    function submitMessage(form) {
        var formData = new FormData(form);
        var content = formData.get("content");
        var hasText = content && content.replace(/^\s+|\s+$/g, "");
        var hasFiles = selectedChatFiles.length > 0;
        if (!hasText && !hasFiles) {
            return;
        }

        fetch(chatApp.getAttribute("data-send-url"), {
            method: "POST",
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            },
            credentials: "same-origin",
            body: formData
        })
        .then(function(response) {
            if (!response.ok) {
                return response.json().then(function(payload) {
                    throw new Error(payload && payload.message ? payload.message : "Erreur d'envoi");
                });
            }
            return response.json();
        })
        .then(function(payload) {
            selectedChatFiles = [];
            applyState(payload, { scrollToBottom: true });
        })
        .catch(function(error) {
            if (error && error.message) {
                window.alert(error.message);
                return;
            }
            form.submit();
        });
    }

    function bindConversationLinks() {
        var links = sidebarContent ? sidebarContent.querySelectorAll(".chat-contact-item") : [];
        Array.prototype.forEach.call(links, function(link) {
            if (link.getAttribute("data-chat-bound") === "1") {
                return;
            }

            link.setAttribute("data-chat-bound", "1");
            link.addEventListener("click", function(event) {
                var type = this.getAttribute("data-chat-type");
                var id = this.getAttribute("data-chat-id");

                if (!type || !id || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                    return;
                }

                event.preventDefault();
                sidebarTabState = type === "group" ? "group" : "private";
                fetchState(type, id, { scrollToBottom: true, focusInput: true });

                if (window.innerWidth < 768 && chatSidebar) {
                    chatSidebar.classList.remove("show");
                }
            });
        });
    }

    function bindMessageForm() {
        var form = document.getElementById("chat-message-form");
        if (!form || form.getAttribute("data-chat-bound") === "1") {
            return;
        }

        form.setAttribute("data-chat-bound", "1");
        form.addEventListener("submit", function(event) {
            event.preventDefault();
            submitMessage(form);
        });
    }

    function bindDynamicUi() {
        bindSearch();
        bindSidebarToggle();
        bindSidebarTabs();
        bindConversationLinks();
        bindTextarea();
        bindAttachmentInput();
        bindMessageForm();
        observeMessagesContainer();
    }

    function startPolling() {
        if (pollTimer) {
            window.clearInterval(pollTimer);
        }

        pollTimer = window.setInterval(function() {
            var chatInput = document.getElementById("chatInput");
            var inputIsFocused = !!(chatInput && document.activeElement === chatInput);
            var hasDraft = (chatInput && chatInput.value && chatInput.value.replace(/^\s+|\s+$/g, "") !== "") || selectedChatFiles.length > 0 || inputIsFocused;

            if (!document.hidden && currentState.id && !hasDraft && !requestInFlight) {
                fetchState(currentState.type, currentState.id, { skipHistory: true, silent: true });
            }
        }, pollInterval);
    }

    window.refreshChatThreadState = function() {
        return fetchState(currentState.type, currentState.id, { skipHistory: true, silent: true, scrollToBottom: false });
    };

    window.getChatRealtimeState = function() {
        return {
            hasActiveThread: !!currentState.id
        };
    };

    window.addEventListener("popstate", function(event) {
        if (event.state && event.state.type && event.state.id) {
            fetchState(event.state.type, event.state.id, { skipHistory: true, scrollToBottom: false });
        }
    });

    bindDynamicUi();
    scrollMessagesToBottom(true);
    focusChatInput(false);
    startPolling();
});
</script>
