@extends('firebase.layouts.organizer-app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3 class="fw-bold fs-4 mb-1 mt-4 ms-4">Event List</h3> 
    <a href="{{ route('organizer.event.setup') }}" class="add-criteria-setup-btn btn btn-primary">
        <i class="ri-add-circle-line"></i> Add Event
    </a>
</div>
@if(session('status'))
    <div class="alert alert-success">
        {!! session('status') !!}
    </div>
    @endif

<!-- Search and Sort Controls -->
<div class="row mb-3 px-4">
    <div class="col-md-6">
        <form action="{{ route('organizer.event.list') }}" method="GET" class="d-flex">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search event name..." 
                       value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="ri-search-line"></i> Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.event.list') }}" class="btn btn-outline-primary">
                        <i class="ri-close-line"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>
    <div class="col-md-6 text-end">
    <div class="btn-group-eventSort">
        <a href="{{ route('organizer.event.list', array_merge(['sort' => 'newest'], request()->except('sort'))) }}" 
           class="btn btn-outline-primary btn-sm {{ request('sort', 'newest') === 'newest' ? 'active' : '' }}">
            Newest First
        </a>
        <a href="{{ route('organizer.event.list', array_merge(['sort' => 'oldest'], request()->except('sort'))) }}" 
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
        Found {{ $events->count() }} result(s) for "{{ request('search') }}"
    </small>
</div>
@endif

<div class="table-row mt-3">
    <div class="col-12">
        <table class="table table-sm table-hover">
            <!-- Your existing table headers -->
            <thead class="table-light">
                <tr class="highlightr text-center">
                    <th scope="row">#</th>
                    <th scope="col">Event Name</th>
                    <th scope="col">Event Type</th>
                    <th scope="col">Date</th>
                    <th scope="col">Time</th>
                    <th scope="col">Venue</th>
                    <th scope="col">Organizer</th>
                    <th scope="col" style="width: 150px;">Action</th>
                </tr>
            </thead>
            <tbody class="table-group-divider text-center">
            @php $i = 1; @endphp
                @forelse ($events as $key => $item)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $item['ename'] ?? 'N/A' }}</td>
                    <td>{{ $item['etype'] ?? 'N/A' }}</td>
                    <td>{{ isset($item['edate']) ? date('M d, Y', strtotime($item['edate'])) : 'N/A' }}</td>
                    <td>
                        @if(isset($item['estart']) && isset($item['eend']))
                            {{ date('h:i A', strtotime($item['estart'])) }} - 
                            {{ date('h:i A', strtotime($item['eend'])) }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $item['evenue'] ?? 'N/A' }}</td>
                    <td>{{ $item['eorganizer'] ?? 'N/A' }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('organizer.event.edit', $item['id']) }}" class="btn btn-primary btn-sm me-2">
                                <i class="ri-edit-box-line"></i> Edit
                            </a>
                            <a href="{{ route('organizer.event.delete', $item['id']) }}" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this event?')">
                                <i class="ri-delete-bin-line"></i> Delete
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">
                        @if(request('search'))
                            No events found matching "{{ request('search') }}"
                        @else
                            No events found
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

.input-group-eventSort {
    max-width: 400px;
}
.sort-buttons .btn {
    width: 100px;
}
</style>
@endsection