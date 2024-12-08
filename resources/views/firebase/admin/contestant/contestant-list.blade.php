@extends('firebase.layouts.admin-app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3 class="fw-bold fs-4 mb-1 mt-4 ms-4">Contestant List</h3> 
    <a href="{{ route('admin.contestant.setup') }}" class="add-criteria-setup-btn btn btn-primary">
        <i class="ri-add-circle-line"></i> Add Contestant
    </a>
</div>

<!-- Filter and Search Controls -->
<div class="row mb-3 px-4">
    <div class="col-md-3">
        <form action="{{ route('admin.contestant.list') }}" method="GET" class="d-flex">
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
        <form action="{{ route('admin.contestant.list') }}" method="GET" class="d-flex">
            <input type="hidden" name="event_filter" value="{{ request('event_filter') }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search contestant name..." 
                       value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="ri-search-line"></i> Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.contestant.list', ['event_filter' => request('event_filter')]) }}" class="btn btn-outline-primary">
                        <i class="ri-close-line"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>
    <div class="col-md-3 text-end">
        <div class="btn-group">
            <a href="{{ route('admin.contestant.list', array_merge(['sort' => 'newest', 'event_filter' => request('event_filter')], request()->except('sort'))) }}" 
               class="btn btn-outline-primary btn-sm {{ request('sort', 'newest') === 'newest' ? 'active' : '' }}">
                Newest First
            </a>
            <a href="{{ route('admin.contestant.list', array_merge(['sort' => 'oldest', 'event_filter' => request('event_filter')], request()->except('sort'))) }}" 
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
        Found {{ $contestants->count() }} result(s) for "{{ request('search') }}"
    </small>
</div>
@endif

<div class="table-row mt-3">
    <div class="col-12">
        <table class="table table-sm table-hover">
            <thead class="table-light">
                <tr class="highlightr text-center">
                    <th scope="row">#</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Event Name</th>
                    <th scope="col">Age</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Personal Background</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider text-center">
                @php $i = 1; @endphp
                @forelse ($contestants as $contestant)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $contestant['cfname'] ?? '' }} {{ $contestant['cmname'] ?? '' }} {{ $contestant['clname'] ?? '' }}</td>
                    <td>{{ $contestant['event_name'] }}</td>
                    <td>{{ $contestant['cage'] ?? 'N/A' }}</td>
                    <td>{{ $contestant['cgender'] ?? 'N/A' }}</td>
                    <td>{{ \Str::limit($contestant['cbackground'] ?? 'No background provided', 50) }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.contestant.edit', $contestant['id']) }}" class="btn btn-primary btn-sm me-2">
                                <i class="ri-edit-box-line"></i> Edit
                            </a>
                            <a href="{{ route('admin.contestant.delete', $contestant['id']) }}" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this contestant?')">
                                <i class="ri-delete-bin-line"></i> Delete
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">
                        @if(request('search'))
                            No contestants found matching "{{ request('search') }}"
                        @else
                            No contestants found
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