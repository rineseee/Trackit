<div class="relative" data-issue-search>
    <form class="flex items-center gap-3">
        <div class="relative flex-1">
            <div class="flex items-center gap-3 rounded-lg border border-slate-300 bg-white px-4 py-3 shadow-sm focus-within:border-emerald-500 focus-within:ring-2 focus-within:ring-emerald-100">
                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    type="text"
                    class="flex-1 bg-transparent outline-none placeholder-slate-400 text-slate-900"
                    placeholder="Search issues by title or description... (min 2 characters)"
                    data-issue-search-input
                    autocomplete="off"
                >
                <button
                    type="button"
                    class="text-slate-400 hover:text-slate-600 transition"
                    id="clear-search"
                    style="display: none;"
                    aria-label="Clear search"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Search Results Dropdown -->
            <div
                class="absolute top-full left-0 right-0 z-50 mt-2 hidden rounded-lg border border-slate-200/90 bg-white/95 shadow-lg shadow-slate-200/70 max-h-96 overflow-y-auto"
                data-search-results
            >
                <!-- Loading Spinner -->
                <div
                    class="hidden flex-col items-center justify-center px-4 py-8"
                    data-search-loading
                >
                    <div class="relative h-8 w-8">
                        <div class="absolute inset-0 animate-spin rounded-full border-4 border-slate-200 border-t-emerald-600"></div>
                    </div>
                    <p class="mt-3 text-sm text-slate-600">Searching...</p>
                </div>

                <!-- Results List -->
                <div
                    class="divide-y divide-slate-200"
                    data-search-results-list
                >
                </div>

                <!-- No Results Message -->
                <div
                    class="hidden px-4 py-8 text-center"
                    data-search-no-results
                >
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10a4 4 0 018 0m-8 8l4 4m4-4l4-4m0 0a4 4 0 00-5.656-5.656m0 0L9 9" />
                    </svg>
                    <p class="mt-3 text-sm font-medium text-slate-900">No results found</p>
                    <p class="mt-1 text-xs text-slate-500">Try searching with different keywords</p>
                </div>

                <!-- Results Footer -->
                <div class="border-t border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-600" data-search-total></div>
            </div>
        </div>

        <!-- Filter Button (Optional) -->
        <button
            type="button"
            class="rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50"
            title="Advanced filters (coming soon)"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
        </button>
    </form>

    <script>
        // Clear button functionality
        const searchInput = document.querySelector('[data-issue-search-input]');
        const clearButton = document.getElementById('clear-search');
        const resultsContainer = document.querySelector('[data-search-results]');

        if (searchInput && clearButton) {
            searchInput.addEventListener('input', function() {
                clearButton.style.display = this.value ? 'block' : 'none';
            });

            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                clearButton.style.display = 'none';
                if (resultsContainer) {
                    resultsContainer.classList.add('hidden');
                }
                searchInput.focus();
            });
        }
    </script>
</div>
