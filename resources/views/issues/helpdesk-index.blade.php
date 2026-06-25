<x-helpdesk-layout>
    <!-- Page Header -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1>Issues</h1>
            <p>Manage and track all support tickets</p>
        </div>
        <a href="{{ route('issues.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Issue
        </a>
    </div>

    <!-- Filters Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select filter-select" data-filter="status" id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Priority</label>
                    <select class="form-select filter-select" data-filter="priority" id="priorityFilter">
                        <option value="">All Priorities</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Project</label>
                    <select class="form-select filter-select" data-filter="project" id="projectFilter">
                        <option value="">All Projects</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Assigned To</label>
                    <select class="form-select filter-select" data-filter="assigned" id="assignedFilter">
                        <option value="">All Users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row g-2 mt-3">
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-secondary" id="resetFilters">
                        <i class="fas fa-redo me-1"></i>Reset
                    </button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-success" id="exportBtn">
                        <i class="fas fa-download me-1"></i>Export CSV
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Issues Table -->
    <div class="card">
        <div class="table-responsive">
            <table id="issuesTable" class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Project</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Assigned</th>
                        <th>Due Date</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($issues as $issue)
                        <tr data-issue-id="{{ $issue->id }}"
                            data-status="{{ $issue->status }}"
                            data-priority="{{ $issue->priority }}"
                            data-project="{{ $issue->project_id }}"
                            data-assigned="{{ $issue->assigned_to_id }}">
                            <td><strong>#{{ $issue->id }}</strong></td>
                            <td>
                                <a href="{{ route('issues.show', $issue) }}" class="text-decoration-none fw-500">
                                    {{ Str::limit($issue->title, 50) }}
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ $issue->project->name }}</span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $issue->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                                </span>
                            </td>
                            <td>
                                <span class="priority-badge priority-{{ $issue->priority }}">
                                    {{ ucfirst($issue->priority) }}
                                </span>
                            </td>
                            <td>
                                @if($issue->assignedTo)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar" style="width: 30px; height: 30px; background: linear-gradient(135deg, #2563eb, #0ea5e9); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700;">
                                            {{ substr($issue->assignedTo->name, 0, 1) }}
                                        </div>
                                        <span>{{ $issue->assignedTo->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($issue->due_date)
                                    <small>{{ $issue->due_date->format('M d, Y') }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $issue->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('issues.show', $issue) }}" class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('issues.edit', $issue) }}" class="btn btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger delete-btn" data-issue-id="{{ $issue->id }}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No issues found</p>
                                    <a href="{{ route('issues.create') }}" class="btn btn-sm btn-primary">Create your first issue</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@push('styles')
<style>
    .fw-500 {
        font-weight: 500;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.85rem;
    }

    .dataTables_filter, .dataTables_length {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
    let issuesTable;

    $(document).ready(function() {
        // Initialize DataTable
        issuesTable = $('#issuesTable').DataTable({
            pageLength: 25,
            lengthMenu: [10, 25, 50, 100],
            order: [[7, 'desc']],
            columnDefs: [
                { orderable: false, targets: 8 }
            ],
            dom: 'lrtip',
            searching: true
        });

        // Filter functionality
        $('.filter-select').on('change', function() {
            applyFilters();
        });

        $('#resetFilters').on('click', function() {
            $('.filter-select').val('').trigger('change');
            issuesTable.search('').draw();
        });

        // Global search
        $('#issuesTable_filter input').on('keyup', function() {
            issuesTable.search(this.value).draw();
        });

        // Export to CSV
        $('#exportBtn').on('click', function() {
            const csv = generateCSV();
            downloadCSV(csv);
        });

        // Delete functionality
        $(document).on('click', '.delete-btn', function() {
            const issueId = $(this).data('issue-id');
            Swal.fire({
                title: 'Delete Issue?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/issues/${issueId}`,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function() {
                            Swal.fire('Deleted!', 'Issue has been deleted.', 'success');
                            issuesTable.row($(`tr[data-issue-id="${issueId}"]`)).remove().draw();
                        },
                        error: function() {
                            Swal.fire('Error!', 'Failed to delete issue.', 'error');
                        }
                    });
                }
            });
        });
    });

    function applyFilters() {
        const status = $('#statusFilter').val();
        const priority = $('#priorityFilter').val();
        const project = $('#projectFilter').val();
        const assigned = $('#assignedFilter').val();

        issuesTable.rows().every(function(index) {
            const row = this.node();
            let show = true;

            if (status && row.dataset.status !== status) show = false;
            if (priority && row.dataset.priority !== priority) show = false;
            if (project && row.dataset.project !== project) show = false;
            if (assigned && row.dataset.assigned !== assigned) show = false;

            $(row).toggle(show);
        });
    }

    function generateCSV() {
        const headers = ['ID', 'Title', 'Project', 'Status', 'Priority', 'Assigned', 'Due Date', 'Created'];
        const rows = [];

        issuesTable.rows({search: 'applied'}).data().each(function(row, index) {
            // Extract data from visible table cells
            const cells = $(row).closest('tr').find('td');
            rows.push([
                cells.eq(0).text(),
                cells.eq(1).text(),
                cells.eq(2).text(),
                cells.eq(3).text(),
                cells.eq(4).text(),
                cells.eq(5).text(),
                cells.eq(6).text(),
                cells.eq(7).text()
            ]);
        });

        let csv = headers.join(',') + '\n';
        rows.forEach(row => {
            csv += row.map(cell => `"${cell.trim()}"`).join(',') + '\n';
        });

        return csv;
    }

    function downloadCSV(csv) {
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `issues_${new Date().getTime()}.csv`;
        a.click();
    }
</script>
@endpush
</x-helpdesk-layout>
