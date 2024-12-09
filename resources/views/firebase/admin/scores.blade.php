@extends('firebase.layouts.admin-app')

@section('content')
<div class="container-fluid p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold fs-4 mb-0">Scoring Overview</h3>
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
                <option value="">All Events</option>
                @foreach($events ?? [] as $event)
                    <option value="{{ $event['id'] }}">{{ $event['ename'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 ms-auto">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search...">
                <button class="btn btn-outline-secondary">
                    <i class="ri-search-line"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="card">
        <div class="card-header bg-white">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#overall" data-bs-toggle="tab">Overall Scores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#judges" data-bs-toggle="tab">Judge Scoring</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#criteria" data-bs-toggle="tab">Criteria Analysis</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <!-- Overall Scores Tab -->
                <div class="tab-pane fade show active" id="overall">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Contestant</th>
                                    <th>Total Score</th>
                                    <th>Average</th>
                                    <th>Judges Scored</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td>Sample Event {{$i}}</td>
                                    <td>Contestant {{$i}}</td>
                                    <td>{{ 85 + $i }}</td>
                                    <td>{{ 87 + $i }}</td>
                                    <td>3/5</td>
                                    <td>2024-03-09 14:30</td>
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

                <!-- Judge Scoring Tab -->
                <div class="tab-pane fade" id="judges">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Judge Name</th>
                                    <th>Event</th>
                                    <th>Scores Submitted</th>
                                    <th>Average Score Given</th>
                                    <th>Last Activity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td>Judge {{$i}}</td>
                                    <td>Sample Event {{$i}}</td>
                                    <td>{{$i}}/10</td>
                                    <td>{{ 85 + $i }}</td>
                                    <td>2024-03-09 14:30</td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Criteria Analysis Tab -->
                <div class="tab-pane fade" id="criteria">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Criteria</th>
                                    <th>Weight</th>
                                    <th>Average Score</th>
                                    <th>Highest Score</th>
                                    <th>Lowest Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td>Criteria {{$i}}</td>
                                    <td>{{20}}%</td>
                                    <td>{{ 85 + $i }}</td>
                                    <td>{{ 90 + $i }}</td>
                                    <td>{{ 80 + $i }}</td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection