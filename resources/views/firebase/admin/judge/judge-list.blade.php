@extends('firebase.layouts.admin-app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3 class="fw-bold fs-4 mb-1 mt-4 ms-4">Judge List</h3> 
    <a href="{{ route('admin.judge.setup') }}" class="add-criteria-setup-btn btn btn-primary">
        <i class="ri-add-circle-line"></i> Add Judge
    </a>
</div>

<!-- Filter and Search Controls -->
<div class="row mb-3 px-4">
    <div class="col-md-3">
        <form action="{{ route('admin.judge.list') }}" method="GET" class="d-flex">
            <select name="event_filter" class="form-select" onchange="this.form.submit()">
                <option value="all" {{ request('event_filter') == 'all' ? 'selected' : '' }}>All Events</option>
                @foreach($events as $eventId => $event)
                    @if(isset($event['ename']))
                        <option value="{{ $event['ename'] }}" {{ request('event_filter') == $event['ename'] ? 'selected' : '' }}>
                            {{ $event['ename'] }}
                        </option>
                    @endif
                @endforeach
            </select>
        </form>
    </div>
    <div class="col-md-6">
        <form action="{{ route('admin.judge.list') }}" method="GET" class="d-flex">
            <input type="hidden" name="event_filter" value="{{ request('event_filter') }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search judge name or username..." 
                       value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="ri-search-line"></i> Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.judge.list', ['event_filter' => request('event_filter')]) }}" class="btn btn-outline-primary">
                        <i class="ri-close-line"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>
    <div class="col-md-3 text-end">
        <div class="btn-group">
            <a href="{{ route('admin.judge.list', array_merge(['sort' => 'newest', 'event_filter' => request('event_filter')], request()->except('sort'))) }}" 
               class="btn btn-outline-primary btn-sm {{ request('sort', 'newest') === 'newest' ? 'active' : '' }}">
                Newest First
            </a>
            <a href="{{ route('admin.judge.list', array_merge(['sort' => 'oldest', 'event_filter' => request('event_filter')], request()->except('sort'))) }}" 
               class="btn btn-outline-primary btn-sm {{ request('sort') === 'oldest' ? 'active' : '' }}">
                Oldest First
            </a>
        </div>
    </div>
</div>

<!-- Results count -->
@if(request('search'))
<div class="px-4 mb-3">
    <small class="text-muted">
        Found {{ $judges->count() }} result(s) for "{{ request('search') }}"
    </small>
</div>
@endif

@if(session('success'))
<div class="alert alert-success px-4">
    {!! session('success') !!}
</div>
@endif

<div class="table-row mt-3">
    <div class="col-12">
        <table class="table table-sm table-hover">
            <thead class="table-light">
                <tr class="highlightr text-center">
                    <th scope="row">#</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Username</th>
                    <th scope="col">Password</th>
                    <th scope="col">Assigned Event</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider text-center">
                @php $i = 1; @endphp
                @forelse ($judges as $judge)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $judge['jfname'] ?? '' }} {{ $judge['jmname'] ?? '' }} {{ $judge['jlname'] ?? '' }}</td>
                    <td>{{ $judge['jusername'] ?? 'N/A' }}</td>
                    <td>••••••••</td>
                    <td>{{ $judge['event_name'] }}</td>
                    <td>{{ $judge['status'] ?? 'Active' }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.judge.edit', $judge['id']) }}" class="btn btn-primary btn-sm me-2">
                                <i class="ri-edit-box-line"></i> Edit
                            </a>
                            <a href="{{ route('admin.judge.delete', $judge['id']) }}" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this judge?')">
                                <i class="ri-delete-bin-line"></i> Delete
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">
                        @if(request('search'))
                            No judges found matching "{{ request('search') }}"
                        @else
                            No judges found
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
.table th {
    background-color: #f8f9fa;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
}

.btn-group {
    display: flex;
    justify-content: center;
}

.input-group {
    max-width: 100%;
}
</style>
@endsection