// Debounce utility function
function debounce(func, delay = 300) {
    let timeoutId = null;
    return function (...args) {
        if (timeoutId) {
            clearTimeout(timeoutId);
        }
        timeoutId = setTimeout(() => {
            func(...args);
        }, delay);
    };
}

// Format text for display
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
    };
    return String(text).replace(/[&<>"']/g, (m) => map[m]);
}

// Get initials from name
function getInitials(name) {
    return name
        .split(' ')
        .filter(Boolean)
        .map((part) => part[0].toUpperCase())
        .slice(0, 2)
        .join('');
}

// Highlight search term in text
function highlightText(text, query) {
    if (!query) return escapeHtml(text);

    const regex = new RegExp(`(${query})`, 'gi');
    const highlighted = escapeHtml(text).replace(
        regex,
        '<strong class="text-amber-600 bg-amber-50">$1</strong>'
    );
    return highlighted;
}

// Get CSRF token from meta tag
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

// Perform search
async function performSearch(searchTerm) {
    const searchInput = document.querySelector('[data-issue-search-input]');
    const resultsContainer = document.querySelector('[data-search-results]');
    const loadingSpinner = document.querySelector('[data-search-loading]');
    const noResultsMessage = document.querySelector('[data-search-no-results]');
    const resultsList = document.querySelector('[data-search-results-list]');

    if (!searchInput || !resultsContainer) {
        return;
    }

    const query = searchTerm.trim();

    // Show loading state
    if (loadingSpinner) {
        loadingSpinner.classList.remove('hidden');
        loadingSpinner.classList.add('flex');
    }
    if (resultsList) {
        resultsList.innerHTML = '';
    }
    if (noResultsMessage) {
        noResultsMessage.classList.add('hidden');
    }

    // Clear results if query is empty
    if (!query) {
        resultsContainer.classList.add('hidden');
        if (loadingSpinner) {
            loadingSpinner.classList.add('hidden');
            loadingSpinner.classList.remove('flex');
        }
        return;
    }

    try {
        const response = await fetch(`/api/issues/search?q=${encodeURIComponent(query)}&limit=8`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
        });

        const result = await response.json();

        // Hide loading spinner
        if (loadingSpinner) {
            loadingSpinner.classList.add('hidden');
            loadingSpinner.classList.remove('flex');
        }

        if (!response.ok) {
            throw new Error(result.message || 'Search failed');
        }

        // Show results container
        resultsContainer.classList.remove('hidden');

        // Display results
        if (result.data && result.data.length > 0) {
            if (noResultsMessage) {
                noResultsMessage.classList.add('hidden');
            }

            if (resultsList) {
                resultsList.innerHTML = result.data
                    .map((issue) => renderSearchResult(issue, query))
                    .join('');

                // Add click handlers to results
                document.querySelectorAll('[data-search-result]').forEach((item) => {
                    item.addEventListener('click', () => {
                        const url = item.dataset.searchResultUrl;
                        if (url) {
                            window.location.href = url;
                        }
                    });
                });
            }

            // Update total count
            const totalText = document.querySelector('[data-search-total]');
            if (totalText && result.total > result.count) {
                totalText.textContent = `Showing ${result.count} of ${result.total} results`;
            }
        } else {
            // Show no results message
            if (resultsList) {
                resultsList.innerHTML = '';
            }
            if (noResultsMessage) {
                noResultsMessage.classList.remove('hidden');
            }
        }
    } catch (error) {
        console.error('Search error:', error);

        // Hide loading spinner
        if (loadingSpinner) {
            loadingSpinner.classList.add('hidden');
            loadingSpinner.classList.remove('flex');
        }

        // Show error message
        if (resultsList) {
            resultsList.innerHTML = `
                <div class="px-4 py-8 text-center text-sm text-red-600">
                    <p>An error occurred while searching. Please try again.</p>
                </div>
            `;
        }
    }
}

// Render individual search result
function renderSearchResult(issue, query) {
    const statusColorMap = {
        open: 'bg-blue-100 text-blue-700',
        in_progress: 'bg-amber-100 text-amber-700',
        closed: 'bg-green-100 text-green-700',
    };

    const priorityColorMap = {
        low: 'bg-slate-100 text-slate-700',
        medium: 'bg-orange-100 text-orange-700',
        high: 'bg-red-100 text-red-700',
    };

    const statusColor = statusColorMap[issue.status] || 'bg-slate-100 text-slate-700';
    const priorityColor = priorityColorMap[issue.priority] || 'bg-slate-100 text-slate-700';

    return `
        <div class="border-b border-slate-200 p-4 hover:bg-slate-50 cursor-pointer transition"
             data-search-result
             data-search-result-url="${escapeHtml(issue.url)}">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-semibold text-slate-900">
                        ${highlightText(issue.title, query)}
                    </h3>
                    <p class="mt-1 text-xs text-slate-500">
                        in <span class="font-medium">${escapeHtml(issue.project.name)}</span>
                    </p>
                    ${
                        issue.description
                            ? `<p class="mt-2 text-xs text-slate-600 line-clamp-2">
                        ${highlightText(issue.description, query)}
                    </p>`
                            : ''
                    }
                </div>
                <div class="flex flex-shrink-0 items-center gap-2">
                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold ${statusColor}">
                        ${escapeHtml(issue.status.replace('_', ' '))}
                    </span>
                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold ${priorityColor}">
                        ${escapeHtml(issue.priority)}
                    </span>
                </div>
            </div>

            ${
                issue.members.length > 0
                    ? `
                <div class="mt-3 flex items-center gap-2">
                    <div class="flex -space-x-2">
                        ${issue.members
                            .slice(0, 3)
                            .map(
                                (member) =>
                                    `<div class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-300 text-[10px] font-bold text-white ring-2 ring-white"
                                        title="${escapeHtml(member.name)}">
                                        ${escapeHtml(member.initials)}
                                    </div>`
                            )
                            .join('')}
                        ${
                            issue.members.length > 3
                                ? `<div class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-400 text-[10px] font-bold text-white ring-2 ring-white">
                                    +${issue.members.length - 3}
                                </div>`
                                : ''
                        }
                    </div>
                    <span class="text-xs text-slate-600">${issue.comments_count} comments</span>
                </div>
            `
                    : ''
            }

            ${
                issue.tags.length > 0
                    ? `
                <div class="mt-3 flex flex-wrap gap-1">
                    ${issue.tags
                        .slice(0, 3)
                        .map(
                            (tag) =>
                                `<span class="inline-flex items-center gap-1 rounded-md px-2 py-1 text-xs font-semibold text-slate-700 bg-slate-50">
                                    <span class="h-1.5 w-1.5 rounded-full" style="background-color: ${escapeHtml(tag.color || '#94a3b8')}"></span>
                                    ${escapeHtml(tag.name)}
                                </span>`
                        )
                        .join('')}
                    ${issue.tags.length > 3 ? `<span class="text-xs text-slate-600">+${issue.tags.length - 3}</span>` : ''}
                </div>
            `
                    : ''
            }
        </div>
    `;
}

// Initialize live search
function initializeLiveSearch() {
    const searchInput = document.querySelector('[data-issue-search-input]');
    const searchContainer = document.querySelector('[data-issue-search]');
    const resultsContainer = document.querySelector('[data-search-results]');

    if (!searchInput || !searchContainer) {
        return;
    }

    // Create debounced search function
    const debouncedSearch = debounce((e) => {
        performSearch(e.target.value);
    }, 300);

    // Add input event listener
    searchInput.addEventListener('input', debouncedSearch);

    // Close search results when clicking outside
    document.addEventListener('click', (e) => {
        if (!searchContainer.contains(e.target)) {
            if (resultsContainer) {
                resultsContainer.classList.add('hidden');
            }
        }
    });

    // Show results container on focus if there's text
    searchInput.addEventListener('focus', () => {
        if (searchInput.value.trim() && resultsContainer) {
            resultsContainer.classList.remove('hidden');
        }
    });

    // Prevent form submission if search form exists
    const searchForm = searchContainer.closest('form');
    if (searchForm) {
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
        });
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initializeLiveSearch);
