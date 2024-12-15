@extends('firebase.layouts.admin-app')

@section('content')
<div class="container-fluid p-4">
    <h3 class="fw-bold fs-4 mb-3">System-wide Scoring Overview</h3>

    <!-- Filter and Search Controls -->
    <div class="row mb-4">
        <div class="col-md-3">
            <form action="{{ route('admin.score') }}" method="GET" class="d-flex">
                <select name="event_filter" class="form-select" onchange="this.form.submit()">
                    <option value="all" {{ request('event_filter') == 'all' ? 'selected' : '' }}>All Events</option>
                    @foreach($eventsList as $event)
                        <option value="{{ $event['id'] }}" {{ request('event_filter') == $event['id'] ? 'selected' : '' }}>
                            {{ $event['name'] }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="col-md-6">
            <form action="{{ route('admin.score') }}" method="GET" class="d-flex">
                <input type="hidden" name="event_filter" value="{{ request('event_filter') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by contestant, judge, or event..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="ri-search-line"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.score', ['event_filter' => request('event_filter')]) }}" 
                           class="btn btn-outline-primary">
                            <i class="ri-close-line"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
        <div class="col-md-3 text-end">
            <div class="btn-group">
                <a href="{{ route('admin.score', array_merge(['sort' => 'newest', 'event_filter' => request('event_filter')], request()->except('sort'))) }}" 
                   class="btn btn-outline-primary btn-sm {{ request('sort', 'newest') === 'newest' ? 'active' : '' }}">
                    Newest First
                </a>
                <a href="{{ route('admin.score', array_merge(['sort' => 'oldest', 'event_filter' => request('event_filter')], request()->except('sort'))) }}" 
                   class="btn btn-outline-primary btn-sm {{ request('sort') === 'oldest' ? 'active' : '' }}">
                    Oldest First
                </a>
            </div>
        </div>
    </div>

    <!-- Scores Table -->
    <div class="card-score-admin-user">
        <div class="card-body-score-admin-user p-0">
            <div class="table-responsive-score-admin-user">
                <table class="table-score-admin-user">
                    <thead class="header-score-admin-user">
                        <tr>
                            <th>#</th>
                            <th>Event Name</th>
                            <th>Judge Name</th>
                            <th>Contestant</th>
                            <th>Category</th>
                            <th>Main Criteria</th>
                            <th>Sub Criteria</th>
                            <th>Score</th>
                            <th>Submission Date</th>
                        </tr>
                    </thead>
                    <tbody class="body-score-admin-user">
                        @forelse($scores as $index => $score)
                            @foreach($score['scores']['scores'] as $mainCriteria => $subScores)
                                @foreach($subScores as $subCriteria => $subScore)
                                    <tr class="row-score-admin-user">
                                        @if($loop->parent->first && $loop->first)
                                            <td rowspan="{{ count($score['scores']['scores']) * count($subScores) }}">
                                                {{ $index + 1 }}
                                            </td>
                                            <td rowspan="{{ count($score['scores']['scores']) * count($subScores) }}">
                                                {{ $score['event_name'] }}
                                            </td>
                                            <td rowspan="{{ count($score['scores']['scores']) * count($subScores) }}">
                                                {{ $score['judge_name'] }}
                                            </td>
                                            <td rowspan="{{ count($score['scores']['scores']) * count($subScores) }}">
                                                {{ $score['contestant_name'] }}
                                            </td>
                                            <td rowspan="{{ count($score['scores']['scores']) * count($subScores) }}">
                                                {{ $score['category'] }}
                                            </td>
                                        @endif
                                        <td>{{ $mainCriteria }}</td>
                                        <td>{{ $subCriteria }}</td>
                                        <td>{{ number_format($subScore, 2) }}</td>
                                        @if($loop->parent->first && $loop->first)
                                            <td rowspan="{{ count($score['scores']['scores']) * count($subScores) }}">
                                                {{ $score['submission_date'] }}
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                                @if($loop->last)
                                    <tr class="total-row-score-admin-user">
                                        <td colspan="7" class="text-end-score-admin-user">
                                            <strong>Total Score:</strong>
                                        </td>
                                        <td colspan="2">
                                            <strong>{{ number_format($score['total_score'], 2) }}</strong>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="9" class="text-center-score-admin-user">
                                    @if(request('search'))
                                        No scores found matching "{{ request('search') }}"
                                    @else
                                        No scores found
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.table-score-admin-user {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
    background-color: #fff;
}

.header-score-admin-user th {
    background-color: #f8f9fa;
    padding: 12px;
    border: 1px solid #dee2e6;
    font-weight: bold;
    text-align: left;
}

.body-score-admin-user td {
    padding: 12px;
    border: 1px solid #dee2e6;
    vertical-align: middle;
}

.row-score-admin-user:hover {
    background-color: #f8f9fa;
}

.total-row-score-admin-user {
    background-color: #f8f9fa;
    font-weight: bold;
}

.text-end-score-admin-user {
    text-align: right;
}

.text-center-score-admin-user {
    text-align: center;
}

.title-score-admin-user {
    font-weight: bold;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
}

.card-score-admin-user {
    background: white;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.table-responsive-score-admin-user {
    overflow-x: auto;
}
</style>
@endsection