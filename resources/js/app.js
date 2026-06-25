import './live-search.js';
import './toast-notification.js';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function renderErrors(container, errors = []) {
    if (!container) {
        return;
    }

    if (!errors.length) {
        container.innerHTML = '';
        return;
    }

    container.innerHTML = `
        <div class="rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <p class="font-semibold">Please fix the following:</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                ${errors.map((error) => `<li>${escapeHtml(error)}</li>`).join('')}
            </ul>
        </div>
    `;
}

async function fetchJson(url, options = {}) {
    const response = await fetch(url, {
        ...options,
        headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            ...(options.headers ?? {}),
        },
    });

    const contentType = response.headers.get('content-type') ?? '';
    const payload = contentType.includes('application/json')
        ? await response.json()
        : await response.text();

    if (!response.ok) {
        const error = new Error('Request failed');
        error.response = response;
        error.payload = payload;
        throw error;
    }

    return payload;
}

function formatTagPill(tag) {
    const color = tag.color || '#94a3b8';

    return `
        <span class="inline-flex items-center gap-2 rounded-md bg-slate-50 px-2 py-1 text-sm font-semibold text-slate-700" data-tag-pill="${tag.id}">
            <span class="h-2.5 w-2.5 rounded-full" style="background-color: ${escapeHtml(color)}"></span>
            ${escapeHtml(tag.name)}
            <button
                type="button"
                class="ml-1 inline-flex h-6 w-6 items-center justify-center rounded-md bg-slate-200 text-xs text-slate-700 transition hover:bg-slate-300"
                data-tag-detach="${tag.id}"
                aria-label="Detach ${escapeHtml(tag.name)}"
            >
                &times;
            </button>
        </span>
    `;
}

function formatMemberPill(member) {
    const initials = member.initials || initialsFromName(member.name);

    return `
        <span class="inline-flex items-center gap-2 rounded-md bg-teal-50 px-2 py-1 text-sm font-semibold text-teal-700" data-member-pill="${member.id}">
            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-teal-100 text-[10px] font-bold">${escapeHtml(initials)}</span>
            ${escapeHtml(member.name)}
            <button
                type="button"
                class="ml-1 inline-flex h-6 w-6 items-center justify-center rounded-md bg-teal-100 text-xs text-teal-700 transition hover:bg-teal-200"
                data-member-detach="${member.id}"
                aria-label="Remove ${escapeHtml(member.name)}"
            >
                &times;
            </button>
        </span>
    `;
}

function initialsFromName(name) {
    return String(name)
        .split(' ')
        .filter(Boolean)
        .map((part) => part[0]?.toUpperCase() ?? '')
        .slice(0, 2)
        .join('');
}

function serializeForm(form) {
    const params = new URLSearchParams(new FormData(form));

    for (const [key, value] of [...params.entries()]) {
        if (String(value).trim() === '') {
            params.delete(key);
        }
    }

    return params.toString();
}

function formatComment(comment) {
    return `
        <article class="rounded-md border border-slate-200 bg-slate-50 px-4 py-4 shadow-sm" data-comment-item>
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h3 class="font-semibold text-slate-900">${escapeHtml(comment.author_name)}</h3>
                    <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-500">${escapeHtml(comment.created_at_human ?? '')}</p>
                </div>
            </div>
            <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-700">${escapeHtml(comment.body)}</p>
        </article>
    `;
}

function initIssuePage(root) {
    const commentsUrl = root.dataset.commentsUrl;
    const commentStoreUrl = root.dataset.commentStoreUrl;
    const tagBaseUrl = root.dataset.tagBaseUrl;
    const memberBaseUrl = root.dataset.memberBaseUrl;

    const commentsList = root.querySelector('[data-comments-list]');
    const commentsMeta = root.querySelector('[data-comments-meta]');
    const commentsPagination = root.querySelector('[data-comments-pagination]');
    const commentForm = root.querySelector('[data-comment-form]');
    const commentErrors = root.querySelector('[data-comment-errors]');
    const commentStatus = root.querySelector('[data-comment-status]');

    const tagModal = document.querySelector('[data-tag-modal]');
    const tagModalOpen = document.querySelector('[data-tag-modal-open]');
    const tagModalClose = document.querySelector('[data-tag-modal-close]');
    const tagSelect = tagModal?.querySelector('[data-tag-select]');
    const attachButton = tagModal?.querySelector('[data-tag-attach]');
    const tagsList = root.querySelector('[data-tags-list]');
    const tagErrors = root.querySelector('[data-tag-errors]');
    const tagStatus = root.querySelector('[data-tag-status]');
    const tagCount = root.querySelector('[data-tags-count]');
    const tagModalErrors = tagModal?.querySelector('[data-tag-modal-errors]');

    const memberModal = document.querySelector('[data-member-modal]');
    const memberModalOpen = document.querySelector('[data-member-modal-open]');
    const memberModalClose = document.querySelector('[data-member-modal-close]');
    const memberSelect = memberModal?.querySelector('[data-member-select]');
    const memberAttachButton = memberModal?.querySelector('[data-member-attach]');
    const membersList = root.querySelector('[data-members-list]');
    const memberErrors = root.querySelector('[data-member-errors]');
    const memberStatus = root.querySelector('[data-member-status]');
    const memberCount = root.querySelector('[data-members-count]');
    const memberModalErrors = memberModal?.querySelector('[data-member-modal-errors]');

    let currentPage = 1;
    let totalComments = 0;
    let totalPages = 1;
    let statusTimeout = null;

    const showStatus = (element, message, duration = 3000) => {
        if (!element) return;
        element.textContent = message;
        if (statusTimeout) clearTimeout(statusTimeout);
        if (duration > 0) {
            statusTimeout = setTimeout(() => {
                element.textContent = '';
            }, duration);
        }
    };

    const toggleModal = (modal, show) => {
        if (!modal) return;
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };

    const updateCommentsMeta = () => {
        if (!commentsMeta) {
            return;
        }

        commentsMeta.textContent = `Page ${currentPage} of ${totalPages} - ${totalComments} total`;
    };

    const loadComments = async (page = 1) => {
        if (!commentsList) {
            return;
        }

        commentsList.innerHTML = `
            <div class="rounded-md bg-slate-50 px-4 py-4 text-sm text-slate-500">
                Loading comments...
            </div>
        `;

        try {
            const response = await fetchJson(`${commentsUrl}?page=${page}`);
            currentPage = response.meta.current_page;
            totalComments = response.meta.total;
            totalPages = response.meta.last_page;

            if (!response.data.length) {
                commentsList.innerHTML = `
                    <div class="rounded-md bg-slate-50 px-4 py-4 text-sm text-slate-500">
                        No comments yet.
                    </div>
                `;
            } else {
                commentsList.innerHTML = response.data.map(formatComment).join('');
            }

            if (commentsPagination) {
                commentsPagination.innerHTML = `
                    <div class="flex items-center gap-2">
                        <button type="button" class="rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200" data-comment-page="${response.meta.current_page - 1}" ${response.meta.prev_page_url ? '' : 'disabled'}>
                            Previous
                        </button>
                        <button type="button" class="rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200" data-comment-page="${response.meta.current_page + 1}" ${response.meta.next_page_url ? '' : 'disabled'}>
                            Next
                        </button>
                    </div>
                `;
            }

            updateCommentsMeta();
        } catch (error) {
            commentsList.innerHTML = `
                <div class="rounded-md border border-rose-200 bg-rose-50 px-4 py-4 text-sm text-rose-700">
                    Failed to load comments.
                </div>
            `;
        }
    };

    const renderTags = (tags) => {
        if (!tagsList) {
            return;
        }

        tagsList.innerHTML = tags.length
            ? tags.map((tag) => formatTagPill(tag)).join('')
            : '<span class="text-sm text-slate-500" data-tags-empty>No tags attached yet.</span>';

        if (tagCount) {
            tagCount.textContent = `${tags.length} attached`;
        }
    };

    const renderMembers = (members) => {
        if (!membersList) {
            return;
        }

        membersList.innerHTML = members.length
            ? members.map(formatMemberPill).join('')
            : '<span class="text-sm text-slate-500" data-members-empty>No members assigned yet.</span>';

        if (memberCount) {
            memberCount.textContent = `${members.length} assigned`;
        }
    };

    commentsPagination?.addEventListener('click', (event) => {
        const button = event.target.closest('[data-comment-page]');
        if (!button || button.disabled) {
            return;
        }

        const page = Number(button.dataset.commentPage);
        if (page < 1) {
            return;
        }

        loadComments(page);
    });

    commentForm?.addEventListener('submit', async (event) => {
        event.preventDefault();
        renderErrors(commentErrors);
        showStatus(commentStatus, 'Sending...', -1);

        const formData = new FormData(commentForm);

        try {
            const response = await fetchJson(commentStoreUrl, {
                method: 'POST',
                body: formData,
            });

            if (currentPage === 1) {
                if (!commentsList.querySelector('[data-comment-item]')) {
                    commentsList.innerHTML = '';
                }

                commentsList.insertAdjacentHTML('afterbegin', formatComment(response.comment));
                totalComments += 1;
                updateCommentsMeta();
            } else {
                await loadComments(1);
            }

            commentForm.reset();
            showStatus(commentStatus, 'Comment added.');
        } catch (error) {
            const errors = error.payload?.errors ? Object.values(error.payload.errors).flat() : ['Unable to add comment.'];
            renderErrors(commentErrors, errors);
            showStatus(commentStatus, '');
        }
    });

    tagModalOpen?.addEventListener('click', () => {
        toggleModal(tagModal, true);
        renderErrors(tagModalErrors);
    });

    tagModalClose?.addEventListener('click', () => {
        toggleModal(tagModal, false);
        renderErrors(tagModalErrors);
    });

    tagModal?.addEventListener('click', (event) => {
        if (event.target === tagModal) {
            toggleModal(tagModal, false);
        }
    });

    attachButton?.addEventListener('click', async () => {
        renderErrors(tagModalErrors);
        const tagId = tagSelect?.value;

        if (!tagId) {
            renderErrors(tagModalErrors, ['Choose a tag first.']);
            return;
        }

        try {
            attachButton.disabled = true;

            const response = await fetchJson(`${tagBaseUrl}/${tagId}`, {
                method: 'POST',
            });

            renderTags(response.tags);
            if (tagSelect) {
                tagSelect.value = '';
            }
            toggleModal(tagModal, false);
            showStatus(tagStatus, 'Tag attached.');
        } catch (error) {
            const errors = error.payload?.errors ? Object.values(error.payload.errors).flat() : [error.payload?.message ?? 'Unable to attach tag.'];
            renderErrors(tagModalErrors, errors);
        } finally {
            attachButton.disabled = false;
        }
    });

    tagsList?.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-tag-detach]');
        if (!button) {
            return;
        }

        const tagId = button.dataset.tagDetach;

        try {
            button.disabled = true;
            showStatus(tagStatus, 'Detaching...', -1);

            const response = await fetchJson(`${tagBaseUrl}/${tagId}`, {
                method: 'DELETE',
            });

            renderTags(response.tags);
            showStatus(tagStatus, 'Tag detached.');
        } catch (error) {
            const errors = error.payload?.errors ? Object.values(error.payload.errors).flat() : [error.payload?.message ?? 'Unable to detach tag.'];
            renderErrors(tagErrors, errors);
        } finally {
            button.disabled = false;
        }
    });

    memberModalOpen?.addEventListener('click', () => {
        toggleModal(memberModal, true);
        renderErrors(memberModalErrors);
    });

    memberModalClose?.addEventListener('click', () => {
        toggleModal(memberModal, false);
        renderErrors(memberModalErrors);
    });

    memberModal?.addEventListener('click', (event) => {
        if (event.target === memberModal) {
            toggleModal(memberModal, false);
        }
    });

    memberAttachButton?.addEventListener('click', async () => {
        renderErrors(memberModalErrors);
        const userId = memberSelect?.value;

        if (!userId) {
            renderErrors(memberModalErrors, ['Choose a user first.']);
            return;
        }

        try {
            memberAttachButton.disabled = true;

            const response = await fetchJson(`${memberBaseUrl}/${userId}`, {
                method: 'POST',
            });

            renderMembers(response.members);

            if (memberSelect) {
                memberSelect.value = '';
            }
            toggleModal(memberModal, false);
            showStatus(memberStatus, 'Member assigned.');
        } catch (error) {
            const errors = error.payload?.errors ? Object.values(error.payload.errors).flat() : [error.payload?.message ?? 'Unable to assign user.'];
            renderErrors(memberModalErrors, errors);
        } finally {
            memberAttachButton.disabled = false;
        }
    });

    membersList?.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-member-detach]');
        if (!button) {
            return;
        }

        const userId = button.dataset.memberDetach;

        try {
            button.disabled = true;
            showStatus(memberStatus, 'Removing...', -1);

            const response = await fetchJson(`${memberBaseUrl}/${userId}`, {
                method: 'DELETE',
            });

            renderMembers(response.members);
            showStatus(memberStatus, 'Member removed.');
        } catch (error) {
            const errors = error.payload?.errors ? Object.values(error.payload.errors).flat() : [error.payload?.message ?? 'Unable to remove user.'];
            renderErrors(memberErrors, errors);
        } finally {
            button.disabled = false;
        }
    });

    loadComments();
}

function initIssueIndex() {
    const form = document.querySelector('[data-issue-filters]');
    const list = document.querySelector('[data-issues-list]');
    const pagination = document.querySelector('[data-issues-pagination]');
    const searchInput = document.querySelector('[data-issue-search]');
    const status = document.querySelector('[data-issues-status]');

    if (!form || !list || !pagination) {
        return;
    }

    let timer = null;

    const loadIssues = async (url) => {
        if (status) {
            status.textContent = 'Updating...';
        }
        list.setAttribute('aria-busy', 'true');

        try {
            const response = await fetchJson(url, {
                headers: {
                    Accept: 'application/json',
                },
            });

            list.innerHTML = response.html;
            pagination.innerHTML = response.pagination;
            history.replaceState({}, '', url);

            if (status) {
                status.textContent = `${response.total} result${response.total === 1 ? '' : 's'}`;
            }
        } finally {
            list.removeAttribute('aria-busy');
        }
    };

    const requestFromForm = () => {
        const query = serializeForm(form);
        const url = `${window.location.pathname}${query ? `?${query}` : ''}`;

        loadIssues(url).catch(() => {
            if (status) {
                status.textContent = 'Could not update';
            }
        });
    };

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        requestFromForm();
    });

    form.addEventListener('change', () => {
        requestFromForm();
    });

    searchInput?.addEventListener('input', () => {
        window.clearTimeout(timer);
        timer = window.setTimeout(requestFromForm, 300);
    });

    pagination.addEventListener('click', (event) => {
        const link = event.target.closest('a[href]');
        if (!link) {
            return;
        }

        event.preventDefault();
        loadIssues(link.href).catch(() => {
            if (status) {
                status.textContent = 'Could not update';
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-issue-page]').forEach(initIssuePage);
    initIssueIndex();
    initTrackitShell();
    initGlobalAiAssistant();
});

function initTrackitShell() {
    const html = document.documentElement;
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    const profileToggle = document.getElementById('profileToggle');
    const profileDropdown = document.getElementById('profileDropdown');
    const darkModeToggle = document.getElementById('darkModeToggle');

    const setSidebarOpen = (open) => {
        if (!sidebar || !sidebarToggle || !sidebarBackdrop) return;
        sidebar.classList.toggle('mobile-open', open);
        sidebarBackdrop.hidden = !open;
        sidebarToggle.setAttribute('aria-expanded', String(open));
    };

    sidebarToggle?.addEventListener('click', () => {
        setSidebarOpen(!sidebar?.classList.contains('mobile-open'));
    });

    sidebarBackdrop?.addEventListener('click', () => setSidebarOpen(false));

    profileToggle?.addEventListener('click', (event) => {
        event.stopPropagation();
        const open = !profileDropdown?.classList.contains('active');
        profileDropdown?.classList.toggle('active', open);
        profileToggle.setAttribute('aria-expanded', String(open));
    });

    document.addEventListener('click', (event) => {
        if (!profileDropdown || !profileToggle) return;
        if (!profileDropdown.contains(event.target) && !profileToggle.contains(event.target)) {
            profileDropdown.classList.remove('active');
            profileToggle.setAttribute('aria-expanded', 'false');
        }
    });

    const applyTheme = (theme) => {
        html.setAttribute('data-theme', theme);
        html.setAttribute('data-bs-theme', theme);
        html.style.colorScheme = theme;
        localStorage.setItem('theme', theme);

        const icon = darkModeToggle?.querySelector('i');
        if (icon) {
            icon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
        }
    };

    applyTheme(localStorage.getItem('theme') || 'light');

    darkModeToggle?.addEventListener('click', () => {
        applyTheme(html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        setSidebarOpen(false);
        profileDropdown?.classList.remove('active');
        profileToggle?.setAttribute('aria-expanded', 'false');
    });
}

function initGlobalAiAssistant() {
    const drawer = document.getElementById('globalAiDrawer');
    const backdrop = document.getElementById('globalAiBackdrop');
    const openButton = document.getElementById('globalAiToggle');
    const closeButton = document.getElementById('globalAiClose');
    const form = document.getElementById('globalAiForm');
    const input = document.getElementById('globalAiInput');
    const messages = document.getElementById('globalAiMessages');

    if (!drawer || !backdrop || !openButton || !form || !input || !messages) {
        return;
    }

    const sendUrl = drawer.dataset.sendUrl;
    let history = [];

    const setOpen = (open) => {
        drawer.classList.toggle('open', open);
        drawer.setAttribute('aria-hidden', String(!open));
        openButton.setAttribute('aria-expanded', String(open));
        backdrop.hidden = !open;

        if (open) {
            window.setTimeout(() => input.focus(), 120);
        }
    };

    const addMessage = (role, text) => {
        const wrapper = document.createElement('div');
        wrapper.className = `trackit-ai-message ${role === 'user' ? 'user' : 'assistant'}`;

        const bubble = document.createElement('div');
        bubble.className = 'trackit-ai-bubble';
        bubble.textContent = text;

        wrapper.appendChild(bubble);
        messages.appendChild(wrapper);
        messages.scrollTop = messages.scrollHeight;
        return wrapper;
    };

    const addLoading = () => addMessage('assistant', 'Thinking...');

    openButton.addEventListener('click', () => setOpen(true));
    document.getElementById('globalAiFab')?.addEventListener('click', () => setOpen(true));
    closeButton?.addEventListener('click', () => setOpen(false));
    backdrop.addEventListener('click', () => setOpen(false));

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const message = input.value.trim();
        if (!message || message.length > 2000 || !sendUrl) {
            return;
        }

        addMessage('user', message);
        input.value = '';
        input.disabled = true;
        const loading = addLoading();

        try {
            const response = await fetchJson(sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    message,
                    history,
                }),
            });

            loading.remove();

            if (response.success) {
                addMessage('assistant', response.response);
                history = [
                    ...history,
                    { role: 'user', content: message },
                    { role: 'assistant', content: response.response },
                ].slice(-10);
            } else {
                addMessage('assistant', response.error || 'The assistant is unavailable right now.');
            }
        } catch (error) {
            loading.remove();
            addMessage('assistant', error.payload?.error || 'The assistant is unavailable right now.');
        } finally {
            input.disabled = false;
            input.focus();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });
}
