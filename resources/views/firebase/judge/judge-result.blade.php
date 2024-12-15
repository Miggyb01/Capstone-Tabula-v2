{{-- resources/views/firebase/judge/judge-result.blade.php --}}
@extends('firebase.layouts.judge-app')

@section('content')
<div class="container-fluid p-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="fw-bold fs-4 mb-3">Results Overview</h3>
        </div>
    </div>

    <!-- Event Details Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="event-title mb-2">{{ $eventDetails['ename'] ?? 'Event Name' }}</h4>
                            <p class="text-muted mb-1">
                                <i class="ri-calendar-line me-2"></i>
                                {{ isset($eventDetails['edate']) ? date('F d, Y', strtotime($eventDetails['edate'])) : 'Event Date' }}
                            </p>
                            <p class="text-muted mb-1">
                                <i class="ri-map-pin-line me-2"></i>
                                {{ $eventDetails['evenue'] ?? 'Venue' }}
                            </p>
                            <p class="text-muted mb-0">
                                <i class="ri-user-line me-2"></i>
                                Judge: {{ $judgeName }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <button type="button" class="btn btn-outline-primary me-2" onclick="window.print()">
                                <i class="ri-printer-line me-1"></i> Print
                            </button>
                            <button type="button" class="btn btn-primary" onclick="exportResults()">
                                <i class="ri-download-line me-1"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Contestant Rankings</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 80px;">Rank</th>
                                    <th>Contestant Number</th>
                                    <th>Contestant Name</th>
                                    @foreach($categories as $category)
                                        <th class="text-center">{{ $category }}</th>
                                    @endforeach
                                    <th class="text-center">Total Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rankings as $index => $contestant)
                                    <tr class="{{ $index < 3 ? 'table-' . ['warning', 'light', 'info'][$index] : '' }}">
                                        <td class="text-center">
                                            @if($index < 3)
                                                <span class="position-badge position-{{ $index + 1 }}">
                                                    {{ $index + 1 }}
                                                </span>
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </td>
                                        <td>{{ $contestant['number'] ?? 'N/A' }}</td>
                                        <td>{{ $contestant['name'] }}</td>
                                        @foreach($categories as $category)
                                            <td class="text-center">
                                                {{ number_format($contestant['category_scores'][$category] ?? 0, 2) }}
                                            </td>
                                        @endforeach
                                        <td class="text-center fw-bold">
                                            {{ number_format($contestant['total_score'], 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ count($categories) + 4 }}" class="text-center">
                                            No results available
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.event-title {
    color: #3155FE;
    font-weight: bold;
}

.card {
    border-radius: 10px;
}

.position-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    font-weight: bold;
}

.position-1 {
    background-color: #ffd700;
    color: #000;
}

.position-2 {
    background-color: #C0C0C0;
    color: #000;
}

.position-3 {
    background-color: #CD7F32;
    color: #fff;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.table-light {
    background-color: rgba(248, 249, 250, 0.1) !important;
}

.table-info {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

@media print {
    .btn {
        display: none;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>

<script>
function exportResults() {
    // You can implement export functionality here
    alert('Export functionality to be implemented');
}
</script>
@endsection