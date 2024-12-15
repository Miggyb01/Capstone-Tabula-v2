{{-- result.blade.php --}}
@extends('firebase.layouts.admin-app')

@section('content')
<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">Event Results</h2>
        </div>
    </div>

    <!-- Filters Row -->
    <div class="row mb-3">
        <!-- Event Filter -->
        <div class="col-md-3">
            <form action="{{ route('admin.result') }}" method="GET">
                <select name="event_filter" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('event_filter') == 'all' ? 'selected' : '' }}>All Events</option>
                    @foreach($events as $eventId => $event)
                        <option value="{{ $event['ename'] }}" {{ request('event_filter') == $event['ename'] ? 'selected' : '' }}>
                            {{ $event['ename'] }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Top N Filter -->
        <div class="col-md-2">
            <form action="{{ route('admin.result') }}" method="GET">
                <input type="hidden" name="event_filter" value="{{ request('event_filter') }}">
                <select name="top_filter" class="form-select" onchange="this.form.submit()">
                    <option value="">All Rankings</option>
                    <option value="top3" {{ request('top_filter') == 'top3' ? 'selected' : '' }}>Top 3</option>
                    <option value="top5" {{ request('top_filter') == 'top5' ? 'selected' : '' }}>Top 5</option>
                    <option value="top10" {{ request('top_filter') == 'top10' ? 'selected' : '' }}>Top 10</option>
                </select>
            </form>
        </div>

        <!-- Search -->
        <div class="col-md-4">
            <form action="{{ route('admin.result') }}" method="GET">
                <input type="hidden" name="event_filter" value="{{ request('event_filter') }}">
                <input type="hidden" name="top_filter" value="{{ request('top_filter') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search contestant..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="ri-search-line"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.result', array_merge(['event_filter' => request('event_filter')], request()->except('search'))) }}" 
                           class="btn btn-outline-secondary">
                            <i class="ri-close-line"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Results Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Event Name</th>
                    <th>Contestant Name</th>
                    <th>Total Score</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rankings as $index => $ranking)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $ranking['event_name'] }}</td>
                        <td>{{ $ranking['contestant_name'] }}</td>
                        <td>{{ number_format($ranking['total_score'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            @if(request('search'))
                                No results found for "{{ request('search') }}"
                            @else
                                No results available
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection