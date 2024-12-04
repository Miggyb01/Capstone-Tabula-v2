<!-- resources/views/firebase/tabulation/results.blade.php -->
@extends('firebase.app')

@section('content')
<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2>Competition Results</h2>
            <div>
                <button class="btn btn-success me-2" onclick="printResults()">
                    <i class="ri-printer-line"></i> Print Results
                </button>
                <button class="btn btn-primary" onclick="exportResults()">
                    <i class="ri-download-line"></i> Export
                </button>
            </div>
        </div>
    </div>

    <!-- Winner Podium -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row text-center">
                        <!-- Second Place -->
                        <div class="col-md-4">
                            <div class="position-relative mb-3">
                                <div class="border rounded-circle overflow-hidden mx-auto" style="width: 150px; height: 150px;">
                                    <img src="/placeholder-image.jpg" class="w-100 h-100 object-fit-cover">
                                </div>
                                <span class="position-absolute top-0 end-0 badge rounded-pill bg-silver">2nd</span>
                            </div>
                            <h4>{{ $winners[1]['name'] ?? 'TBA' }}</h4>
                            <h5 class="text-muted">{{ number_format($winners[1]['score'] ?? 0, 2) }}</h5>
                        </div>
                        <!-- First Place -->
                        <div class="col-md-4">
                            <div class="position-relative mb-3">
                                <div class="border rounded-circle overflow-hidden mx-auto" style="width: 180px; height: 180px;">
                                    <img src="/placeholder-image.jpg" class="w-100 h-100 object-fit-cover">
                                </div>
                                <span class="position-absolute top-0 end-0 badge rounded-pill bg-gold">1st</span>
                            </div>
                            <h3>{{ $winners[0]['name'] ?? 'TBA' }}</h3>
                            <h4 class="text-muted">{{ number_format($winners[0]['score'] ?? 0, 2) }}</h4>
                        </div>
                        <!-- Third Place -->
                        <div class="col-md-4">
                            <div class="position-relative mb-3">
                                <div class="border rounded-circle overflow-hidden mx-auto" style="width: 150px; height: 150px;">
                                    <img src="/placeholder-image.jpg" class="w-100 h-100 object-fit-cover">
                                </div>
                                <span class="position-absolute top-0 end-0 badge rounded-pill bg-bronze">3rd</span>
                            </div>
                            <h4>{{ $winners[2]['name'] ?? 'TBA' }}</h4>
                            <h5 class="text-muted">{{ number_format($winners[2]['score'] ?? 0, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Results Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent">
            <h5 class="mb-0">Complete Rankings</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Contestant</th>
                            @foreach($criteriaList as $criteria)
                                <th class="text-center">{{ $criteria['name'] }}</th>
                            @endforeach
                            <th class="text-center">Total Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rankings as $index => $contestant)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $contestant['name'] }}</td>
                                @foreach($criteriaList as $criteria)
                                    <td class="text-center">
                                        {{ number_format($contestant['scores'][$criteria['id']] ?? 0, 2) }}
                                    </td>
                                @endforeach
                                <td class="text-center fw-bold">
                                    {{ number_format($contestant['total_score'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gold {
    background-color: #FFD700;
    color: #000;
}
.bg-silver {
    background-color: #C0C0C0;
    color: #000;
}
.bg-bronze {
    background-color: #CD7F32;
    color: #fff;
}
</style>
@endsection