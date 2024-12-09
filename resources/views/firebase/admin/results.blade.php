@extends('firebase.layouts.admin-app')

@section('content')
<div class="container-fluid p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold fs-4 mb-0">Competition Results</h3>
        <div class="btn-group">
            <button class="btn btn-primary">
                <i class="ri-file-excel-line me-2"></i>Export
            </button>
            <button class="btn btn-secondary">
                <i class="ri-printer-line me-2"></i>Print
            </button>
        </div>
    </div>

    <!-- Event Filter -->
    <div class="row mb-4">
        <div class="col-md-4">
            <select class="form-select">
                <option value="">Select Event</option>
                @foreach($events ?? [] as $event)
                    <option value="{{ $event['id'] }}">{{ $event['ename'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Winner Podium -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row text-center">
                <!-- Second Place -->
                <div class="col-md-4">
                    <div class="position-relative mb-3">
                        <div class="border rounded-circle overflow-hidden mx-auto" style="width: 150px; height: 150px; background: #f8f9fa;">
                            <i class="ri-user-line" style="font-size: 80px; line-height: 150px;"></i>
                        </div>
                        <span class="position-absolute top-0 end-0 translate-middle badge bg-secondary">2nd</span>
                    </div>
                    <h4>Contestant 2</h4>
                    <h5 class="text-muted">89.5</h5>
                </div>
                <!-- First Place -->
                <div class="col-md-4">
                    <div class="position-relative mb-3">
                        <div class="border rounded-circle overflow-hidden mx-auto" style="width: 180px; height: 180px; background: #f8f9fa;">
                            <i class="ri-user-line" style="font-size: 100px; line-height: 180px;"></i>
                        </div>
                        <span class="position-absolute top-0 end-0 translate-middle badge bg-warning">1st</span>
                    </div>
                    <h3>Contestant 1</h3>
                    <h4 class="text-muted">92.8</h4>
                </div>
                <!-- Third Place -->
                <div class="col-md-4">
                    <div class="position-relative mb-3">
                        <div class="border rounded-circle overflow-hidden mx-auto" style="width: 150px; height: 150px; background: #f8f9fa;">
                            <i class="ri-user-line" style="font-size: 80px; line-height: 150px;"></i>
                        </div>
                        <span class="position-absolute top-0 end-0 translate-middle badge" style="background-color: #CD7F32;">3rd</span>
                    </div>
                    <h4>Contestant 3</h4>
                    <h5 class="text-muted">87.3</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Rankings -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Complete Rankings</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Contestant</th>
                            <th>Total Score</th>
                            <th>Production</th>
                            <th>Talent</th>
                            <th>Q&A</th>
                            <th>Final Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 1; $i <= 10; $i++)
                        <tr>
                            <td>{{$i}}</td>
                            <td>Contestant {{$i}}</td>
                            <td>{{ 93 - $i }}</td>
                            <td>{{ 90 - ($i-1) }}</td>
                            <td>{{ 88 - ($i-2) }}</td>
                            <td>{{ 89 - ($i-1) }}</td>
                            <td class="fw-bold">{{ 90 - ($i-1) }}</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection