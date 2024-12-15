@extends('firebase.layouts.admin-app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3 class="fw-bold fs-4 mb-1 mt-4 ms-4">Event List</h3> 
    <a href="{{ route('admin.event.setup') }}" class="add-criteria-setup-btn btn btn-primary">
        <i class="ri-add-circle-line"></i> Add Event
    </a>
</div>

<!-- Search and Filter Controls -->
<div class="row mb-3 px-4">
    <div class="col-md-3">
        <form action="{{ route('admin.event.list') }}" method="GET" class="d-flex">
            <select name="organizer_filter" class="form-select" onchange="this.form.submit()">
                <option value="all" {{ request('organizer_filter') == 'all' ? 'selected' : '' }}>All Organizers</option>
                <option value="admin" {{ request('organizer_filter') == 'admin' ? 'selected' : '' }}>Admin</option>
                @foreach($organizers as $organizerId => $organizerName)
                    @if($organizerId !== 'admin')
                        <option value="{{ $organizerId }}" {{ request('organizer_filter') == $organizerId ? 'selected' : '' }}>
                            {{ $organizerName }}
                        </option>
                    @endif
                @endforeach
            </select>
        </form>
    </div>
    <div class="col-md-6">
        <form action="{{ route('admin.event.list') }}" method="GET" class="d-flex">
            <input type="hidden" name="organizer_filter" value="{{ request('organizer_filter') }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search event name..." 
                       value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="ri-search-line"></i> Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.event.list', ['organizer_filter' => request('organizer_filter')]) }}" class="btn btn-outline-primary">
                        <i class="ri-close-line"></i> Clear
                    </a>
                @endif
            </div>
        </form>
    </div>
    <div class="col-md-3 text-end">
        <div class="btn-group">
            <a href="{{ route('admin.event.list', array_merge(['sort' => 'newest', 'organizer_filter' => request('organizer_filter')], request()->except('sort'))) }}" 
               class="btn btn-outline-primary btn-sm {{ request('sort', 'newest') === 'newest' ? 'active' : '' }}">
                Newest First
            </a>
            <a href="{{ route('admin.event.list', array_merge(['sort' => 'oldest', 'organizer_filter' => request('organizer_filter')], request()->except('sort'))) }}" 
               class="btn btn-outline-primary btn-sm {{ request('sort') === 'oldest' ? 'active' : '' }}">
                Oldest First
            </a>
        </div>
    </div>
</div>

        <!-- Update the table to show organizer information -->
        <table class="table table-sm table-hover">
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
                    <td>{{ $item['organizer_name'] ?? 'N/A' }}</td>
                    <td>
                       <div class="btn-group" role="group">
                            <a href="{{ route('admin.event.edit', $item['id']) }}" class="btn btn-primary btn-sm me-2">
                                <i class="ri-edit-box-line"></i> Edit
                            </a>
                            <a href="{{ route('admin.event.delete', $item['id']) }}" 
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