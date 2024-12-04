@extends('firebase.layouts.admin-app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h3 class="fw-bold fs-4 mb-1 mt-4 ms-4">Event List</h3>
    <a href="{{ route('admin.event.setup') }}" class="add-criteria-setup-btn btn btn-primary">
        <i class="ri-add-circle-line"></i> Add Event
    </a>
</div>

<div class="d-flex justify-content-between align-items-center mb-3 px-4">
    <!-- Search Bar -->
    <form action="{{ route('admin.event.list') }}" method="GET" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search Event Name" value="{{ request('search') }}">
        <button type="submit" class="btn btn-secondary"><i class="ri-search-line"></i> Search</button>
    </form>

    <!-- Sort Dropdown -->
    <form action="{{ route('admin.event.list') }}" method="GET">
        <select name="sort" class="form-select" onchange="this.form.submit()">
            <option value="" disabled selected>Sort by</option>
            <option value="recent" {{ request('sort') === 'recent' ? 'selected' : '' }}>Most Recent</option>
            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
        </select>
    </form>
</div>

<div class="table-row mt-3">
    <div class="col-12">
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
                    <td>{{ $item['eorganizer'] ?? 'N/A' }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.event.edit', $key) }}" class="btn btn-primary btn-sm me-2" title="Edit">
                                <i class="ri-edit-box-line"></i> Edit
                            </a>
                            <a href="{{ route('admin.event.delete', $key) }}" class="btn btn-danger btn-sm" 
                               onclick="return confirm('Are you sure you want to delete this event?')" title="Delete">
                                <i class="ri-delete-bin-line"></i> Delete
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">No Record Found</td>
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
</style>
@endsection
