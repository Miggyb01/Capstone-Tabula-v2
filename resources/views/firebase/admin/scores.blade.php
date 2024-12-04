<!-- resources/views/firebase/tabulation/scores.blade.php -->
@extends('firebase.app')

@section('content')
<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Event Scores</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('tabulation.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Scores
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#overall" data-bs-toggle="tab">Overall Scores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#by-judge" data-bs-toggle="tab">Scores by Judge</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#by-criteria" data-bs-toggle="tab">Scores by Criteria</a>
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
                                    <th>Rank</th>
                                    <th>Contestant</th>
                                    <th>Total Score</th>
                                    <th>Average</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scores ?? [] as $index => $score)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $score['contestant_name'] }}</td>
                                    <td>{{ number_format($score['total'], 2) }}</td>
                                    <td>{{ number_format($score['average'], 2) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $score['id'] }}">
                                            <i class="fas fa-eye"></i> Details
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Scores by Judge Tab -->
                <div class="tab-pane fade" id="by-judge">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Judge</th>
                                    <th>Scores Submitted</th>
                                    <th>Average Score</th>
                                    <th>Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($judgeScores ?? [] as $judge)
                                <tr>
                                    <td>{{ $judge['name'] }}</td>
                                    <td>{{ $judge['submitted_count'] }}</td>
                                    <td>{{ number_format($judge['average'], 2) }}</td>
                                    <td>{{ $judge['last_updated'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Scores by Criteria Tab -->
                <div class="tab-pane fade" id="by-criteria">
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
                                @foreach($criteriaScores ?? [] as $criteria)
                                <tr>
                                    <td>{{ $criteria['name'] }}</td>
                                    <td>{{ $criteria['weight'] }}%</td>
                                    <td>{{ number_format($criteria['average'], 2) }}</td>
                                    <td>{{ number_format($criteria['highest'], 2) }}</td>
                                    <td>{{ number_format($criteria['lowest'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Score Details Modal -->
@foreach($scores ?? [] as $score)
<div class="modal fade" id="detailsModal{{ $score['id'] }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Score Details - {{ $score['contestant_name'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Detailed scores here -->
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection