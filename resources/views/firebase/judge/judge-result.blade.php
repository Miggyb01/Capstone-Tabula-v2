@extends('firebase.layouts.judge-app')

@section('content')
<div class="container-fluid p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold fs-4 mb-0">Event Results</h3>
        <div class="btn-group">
            <button class="btn btn-primary">
                <i class="ri-file-excel-line me-2"></i>Export
            </button>
            <button class="btn btn-secondary">
                <i class="ri-printer-line me-2"></i>Print
            </button>
        </div>
    </div>

    <!-- Event Info Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="fw-bold">Current Event</h5>
                    <p class="text-muted mb-0">Sample Event 2024</p>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold">Total Contestants</h5>
                    <p class="text-muted mb-0">10</p>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold">Status</h5>
                    <span class="badge bg-success">Completed</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 3 Winners -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Winners</h5>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <!-- 2nd Place -->
                <div class="col-md-4">
                    <div class="position-relative mb-3">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" 
                             style="width: 120px; height: 120px;">
                            <i class="ri-medal-2-line" style="font-size: 48px;"></i>
                        </div>
                        <span class="position-absolute top-0 end-0 translate-middle badge bg-secondary">2nd</span>
                    </div>
                    <h4>Contestant 2</h4>
                    <p class="text-muted">89.5 points</p>
                </div>

                <!-- 1st Place -->
                <div class="col-md-4">
                    <div class="position-relative mb-3">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" 
                             style="width: 150px; height: 150px;">
                            <i class="ri-medal-line" style="font-size: 64px; color: #ffd700;"></i>
                        </div>
                        <span class="position-absolute top-0 end-0 translate-middle badge bg-warning text-dark">1st</span>
                    </div>
                    <h3>Contestant 1</h3>
                    <p class="text-muted">92.8 points</p>
                </div>

                <!-- 3rd Place -->
                <div class="col-md-4">
                    <div class="position-relative mb-3">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" 
                             style="width: 120px; height: 120px;">
                            <i class="ri-medal-line" style="font-size: 48px; color: #cd7f32;"></i>
                        </div>
                        <span class="position-absolute top-0 end-0 translate-middle badge" 
                              style="background-color: #cd7f32;">3rd</span>
                    </div>
                    <h4>Contestant 3</h4>
                    <p class="text-muted">87.3 points</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Rankings Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Complete Rankings</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Contestant</th>
                            <th>Production</th>
                            <th>Talent</th>
                            <th>Q&A</th>
                            <th>Total Score</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 1; $i <= 10; $i++)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>Contestant {{ $i }}</td>
                                <td>{{ 90 - ($i * 0.5) }}</td>
                                <td>{{ 88 - ($i * 0.3) }}</td>
                                <td>{{ 89 - ($i * 0.4) }}</td>
                                <td class="fw-bold">{{ 89 - ($i * 0.4) }}</td>
                                <td>
                                    @if($i <= 3)
                                        <span class="badge bg-success">Winner</span>
                                    @else
                                        <span class="badge bg-secondary">Finalist</span>
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection