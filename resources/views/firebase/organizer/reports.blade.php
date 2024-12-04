<!-- resources/views/firebase/tabulation/reports.blade.php -->
@extends('firebase.app')

@section('content')
<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col">
            <h2>Tabulation Reports</h2>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Contestants</h5>
                    <h2 class="mb-0">{{ $summary['total_contestants'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Average Score</h5>
                    <h2 class="mb-0">{{ number_format($summary['average_score'] ?? 0, 2) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Judges</h5>
                    <h2 class="mb-0">{{ $summary['total_judges'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Score Distribution
                </div>
                <div class="card-body">
                    <canvas id="scoreDistribution"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Criteria Averages
                </div>
                <div class="card-body">
                    <canvas id="criteriaAverages"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Score Distribution Chart
const scoreCtx = document.getElementById('scoreDistribution').getContext('2d');
new Chart(scoreCtx, {
    type: 'bar',
    data: {
        labels: ['90-100', '80-89', '70-79', '60-69', 'Below 60'],
        datasets: [{
            label: 'Number of Scores',
            data: [10, 15, 8, 5, 2],
            backgroundColor: 'rgba(54, 162, 235, 0.5)'
        }]
    }
});

// Criteria Averages Chart
const criteriaCtx = document.getElementById('criteriaAverages').getContext('2d');
new Chart(criteriaCtx, {
    type: 'radar',
    data: {
        labels: ['Criteria 1', 'Criteria 2', 'Criteria 3', 'Criteria 4', 'Criteria 5'],
        datasets: [{
            label: 'Average Scores',
            data: [85, 88, 92, 78, 90],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgb(255, 99, 132)',
            borderWidth: 1
        }]
    }
});
</script>
@endsection