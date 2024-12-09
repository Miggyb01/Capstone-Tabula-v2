@extends('firebase.layouts.judge-app')

@section('content')
<div class="container-fluid p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold fs-4 mb-0">My Scoring History</h3>
        <div class="btn-group">
            <button class="btn btn-primary">
                <i class="ri-file-excel-line me-2"></i>Export
            </button>
            <button class="btn btn-secondary">
                <i class="ri-printer-line me-2"></i>Print
            </button>
        </div>
    </div>

    <!-- Event Info -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-1">Current Event</h5>
                    <p class="mb-0 text-muted">Sample Beauty Pageant 2024</p>
                </div>
                <div class="col-md-3">
                    <h5 class="mb-1">Contestants Scored</h5>
                    <p class="mb-0 text-muted">8/10</p>
                </div>
                <div class="col-md-3">
                    <h5 class="mb-1">Average Score Given</h5>
                    <p class="mb-0 text-muted">88.5</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scoring Table -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">My Scores</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Contestant</th>
                            <th>Production</th>
                            <th>Talent</th>
                            <th>Q&A</th>
                            <th>Total Score</th>
                            <th>Submitted At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 1; $i <= 8; $i++)
                        <tr>
                            <td>Contestant {{$i}}</td>
                            <td>{{ 88 + rand(-2, 2) }}</td>
                            <td>{{ 87 + rand(-2, 2) }}</td>
                            <td>{{ 89 + rand(-2, 2) }}</td>
                            <td class="fw-bold">{{ 88 + rand(-1, 1) }}</td>
                            <td>2024-03-09 {{sprintf('%02d', $i+10)}}:30</td>
                            <td>
                                <button class="btn btn-sm btn-info">
                                    <i class="ri-eye-line"></i> View
                                </button>
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