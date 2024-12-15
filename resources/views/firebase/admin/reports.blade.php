@extends('firebase.layouts.admin-app')

@section('content')
<div class="container-fluid-reports-admin-user">
    <div class="d-flex-reports-admin-user justify-content-between-reports-admin-user align-items-center-reports-admin-user mb-4">
        <h3 class="fw-bold-reports-admin-user">Reports Dashboard</h3>
        <div class="btn-group-reports-admin-user">
            <button class="btn-reports-admin-user btn-primary-reports-admin-user" id="printReport-reports-admin-user">
                <i class="ri-printer-line"></i> Print
            </button>
            <button class="btn-reports-admin-user btn-success-reports-admin-user ms-2-reports-admin-user" id="exportReport-reports-admin-user">
                <i class="ri-download-line"></i> Export
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row-reports-admin-user">
        <div class="col-xl-2-reports-admin-user col-md-4-reports-admin-user mb-3-reports-admin-user">
            <div class="summary-card-reports-admin-user">
                <div class="card-body-reports-admin-user">
                    <h6 class="text-muted-reports-admin-user">Total Events</h6>
                    <h3 class="mb-0-reports-admin-user">{{ $summaryData['totalEvents'] }}</h3>
                    <small class="text-success-reports-admin-user">
                        {{ $summaryData['activeEvents'] }} Active
                    </small>
                </div>
            </div>
        </div>
        <div class="col-xl-2-reports-admin-user col-md-4-reports-admin-user mb-3-reports-admin-user">
            <div class="summary-card-reports-admin-user">
                <div class="card-body-reports-admin-user">
                    <h6 class="text-muted-reports-admin-user">Total Judges</h6>
                    <h3 class="mb-0-reports-admin-user">{{ $summaryData['totalJudges'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-2-reports-admin-user col-md-4-reports-admin-user mb-3-reports-admin-user">
            <div class="summary-card-reports-admin-user">
                <div class="card-body-reports-admin-user">
                    <h6 class="text-muted-reports-admin-user">Total Contestants</h6>
                    <h3 class="mb-0-reports-admin-user">{{ $summaryData['totalContestants'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-2-reports-admin-user col-md-4-reports-admin-user mb-3-reports-admin-user">
            <div class="summary-card-reports-admin-user">
                <div class="card-body-reports-admin-user">
                    <h6 class="text-muted-reports-admin-user">Total Criteria</h6>
                    <h3 class="mb-0-reports-admin-user">{{ $summaryData['totalCriteria'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-2-reports-admin-user col-md-4-reports-admin-user mb-3-reports-admin-user">
            <div class="summary-card-reports-admin-user">
                <div class="card-body-reports-admin-user">
                    <h6 class="text-muted-reports-admin-user">Total Organizers</h6>
                    <h3 class="mb-0-reports-admin-user">{{ $summaryData['totalOrganizers'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Charts Row -->
    <div class="row-reports-admin-user mb-4-reports-admin-user">
        <!-- Monthly Activity Chart -->
        <div class="col-xl-6-reports-admin-user mb-4-reports-admin-user">
            <div class="card-reports-admin-user">
                <div class="card-header-reports-admin-user">
                    <h5 class="card-title-reports-admin-user mb-0-reports-admin-user">Monthly Activity</h5>
                </div>
                <div class="card-body-reports-admin-user">
                    <div class="chart-container-reports-admin-user">
                        <canvas id="monthlyActivityChart-reports-admin-user"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weekly Activity Chart -->
        <div class="col-xl-6-reports-admin-user mb-4-reports-admin-user">
            <div class="card-reports-admin-user">
                <div class="card-header-reports-admin-user">
                    <h5 class="card-title-reports-admin-user mb-0-reports-admin-user">Weekly Activity</h5>
                </div>
                <div class="card-body-reports-admin-user">
                    <div class="chart-container-reports-admin-user">
                        <canvas id="weeklyActivityChart-reports-admin-user"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Charts Row -->
    <div class="row-reports-admin-user mb-4-reports-admin-user">
        <!-- Event Type Distribution -->
        <div class="col-xl-4-reports-admin-user mb-4-reports-admin-user">
            <div class="card-reports-admin-user">
                <div class="card-header-reports-admin-user">
                    <h5 class="card-title-reports-admin-user mb-0-reports-admin-user">Event Types</h5>
                </div>
                <div class="card-body-reports-admin-user">
                    <div class="pie-chart-container-reports-admin-user">
                        <canvas id="eventTypeChart-reports-admin-user"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demographics Charts -->
        <div class="col-xl-8-reports-admin-user mb-4-reports-admin-user">
            <div class="card-reports-admin-user">
                <div class="card-header-reports-admin-user">
                    <h5 class="card-title-reports-admin-user mb-0-reports-admin-user">Contestant Demographics</h5>
                </div>
                <div class="card-body-reports-admin-user">
                    <div class="row-reports-admin-user">
                        <div class="col-md-6-reports-admin-user">
                            <div class="pie-chart-container-reports-admin-user">
                                <canvas id="contestantAgeChart-reports-admin-user"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6-reports-admin-user">
                            <div class="pie-chart-container-reports-admin-user">
                                <canvas id="contestantGenderChart-reports-admin-user"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Organizer Statistics -->
    <div class="row-reports-admin-user">
        <div class="col-12-reports-admin-user">
            <div class="card-reports-admin-user">
                <div class="card-header-reports-admin-user">
                    <h5 class="card-title-reports-admin-user mb-0-reports-admin-user">Organizer Statistics</h5>
                </div>
                <div class="card-body-reports-admin-user">
                    <div class="table-responsive-reports-admin-user">
                        <table class="table-reports-admin-user">
                            <thead>
                                <tr>
                                    <th>Organizer Name</th>
                                    <th>Events</th>
                                    <th>Judges</th>
                                    <th>Contestants</th>
                                    <th>Criteria Sets</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($organizerStats as $stat)
                                <tr>
                                    <td>{{ $stat['name'] }}</td>
                                    <td>{{ $stat['events'] }}</td>
                                    <td>{{ $stat['judges'] }}</td>
                                    <td>{{ $stat['contestants'] }}</td>
                                    <td>{{ $stat['criteria'] }}</td>
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


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const commonChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    padding: 8,
                    font: { size: 11 }
                }
            }
        }
    };

    new Chart(document.getElementById('monthlyActivityChart-reports-admin-user'), {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_map(function($item) { return $item['month']; }, $monthlyData)) !!},
        datasets: [
            {
                label: 'Events',
                data: {!! json_encode(array_map(function($item) { return (int)$item['events']; }, $monthlyData)) !!},
                backgroundColor: '#3155FE'
            }, 
            {
                label: 'Judges',
                data: {!! json_encode(array_map(function($item) { return (int)$item['judges']; }, $monthlyData)) !!},
                backgroundColor: '#82ca9d'
            }, 
            {
                label: 'Contestants',
                data: {!! json_encode(array_map(function($item) { return (int)$item['contestants']; }, $monthlyData)) !!},
                backgroundColor: '#ffc658'
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    padding: 8,
                    font: {
                        size: 11
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    font: { size: 11 }
                }
            },
            x: {
                ticks: {
                    font: { size: 11 }
                }
            }
        }
    }
});

// Weekly Activity Chart
new Chart(document.getElementById('weeklyActivityChart-reports-admin-user'), {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_map(function($item) { return $item['week']; }, $weeklyData)) !!},
        datasets: [
            {
                label: 'Events',
                data: {!! json_encode(array_map(function($item) { return (int)$item['events']; }, $weeklyData)) !!},
                backgroundColor: '#3155FE'
            }, 
            {
                label: 'Judges',
                data: {!! json_encode(array_map(function($item) { return (int)$item['judges']; }, $weeklyData)) !!},
                backgroundColor: '#82ca9d'
            }, 
            {
                label: 'Contestants',
                data: {!! json_encode(array_map(function($item) { return (int)$item['contestants']; }, $weeklyData)) !!},
                backgroundColor: '#ffc658'
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    padding: 8,
                    font: {
                        size: 11
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    font: { size: 11 }
                }
            },
            x: {
                ticks: {
                    font: { size: 11 }
                }
            }
        }
    }
});

    // Event Type Distribution Chart
    new Chart(document.getElementById('eventTypeChart-reports-admin-user'), {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($eventTypeDistribution)) !!},
            datasets: [{
                data: {!! json_encode(array_values($eventTypeDistribution)) !!},
                backgroundColor: ['#3155FE', '#82ca9d', '#ffc658', '#ff8042', '#a4a4a4']
            }]
        },
        options: {
            ...commonChartOptions,
            plugins: {
                ...commonChartOptions.plugins,
                legend: {
                    ...commonChartOptions.plugins.legend,
                    position: 'right'
                }
            }
        }
    });

    // Contestant Age Chart
    new Chart(document.getElementById('contestantAgeChart-reports-admin-user'), {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($contestantStats['byAge'])) !!},
            datasets: [{
                data: {!! json_encode(array_values($contestantStats['byAge'])) !!},
                backgroundColor: ['#3155FE', '#82ca9d', '#ffc658', '#ff8042']
            }]
        },
        options: {
            ...commonChartOptions,
            plugins: {
                ...commonChartOptions.plugins,
                title: {
                    display: true,
                    text: 'Age Distribution',
                    font: { size: 12 }
                }
            }
        }
    });

    // Contestant Gender Chart
    new Chart(document.getElementById('contestantGenderChart-reports-admin-user'), {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($contestantStats['byGender'])) !!},
            datasets: [{
                data: {!! json_encode(array_values($contestantStats['byGender'])) !!},
                backgroundColor: ['#3155FE', '#ff8042', '#82ca9d']
            }]
        },
        options: {
            ...commonChartOptions,
            plugins: {
                ...commonChartOptions.plugins,
                title: {
                    display: true,
                    text: 'Gender Distribution',
                    font: { size: 12 }
                }
            }
        }
    });
});
</script>

<style>
    .card-header-reports-admin-user {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
    }

    .card-body-reports-admin-user {
        padding: 15px;
    }

    .table-reports-admin-user {
        width: 100%;
        margin-bottom: 0;
    }

    .table-reports-admin-user th,
    .table-reports-admin-user td {
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
        font-size: 0.9rem;
    }

    .btn-reports-admin-user {
        padding: 8px 15px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .btn-primary-reports-admin-user {
        background: #3155FE;
        color: white;
    }

    .btn-primary-reports-admin-user:hover {
        background: #2a46e8;
    }

    .btn-success-reports-admin-user {
        background: #28a745;
        color: white;
    }

    .btn-success-reports-admin-user:hover {
        background: #218838;
    }

    .text-muted-reports-admin-user {
        color: #6c757d;
    }

    .text-success-reports-admin-user {
        color: #28a745;
    }

    .mb-0-reports-admin-user {
        margin-bottom: 0;
    }

    .mb-3-reports-admin-user {
        margin-bottom: 1rem;
    }

    .mb-4-reports-admin-user {
        margin-bottom: 1.5rem;
    }

    .mt-3-reports-admin-user {
        margin-top: 1rem;
    }

    .ms-2-reports-admin-user {
        margin-left: 0.5rem;
    }

    .fw-bold-reports-admin-user {
        font-weight: 600;
    }

    .row-reports-admin-user {
        display: flex;
        flex-wrap: wrap;
        margin-right: -0.75rem;
        margin-left: -0.75rem;
    }

    .col-12-reports-admin-user {
        flex: 0 0 100%;
        max-width: 100%;
        padding: 0 0.75rem;
    }

    .col-xl-2-reports-admin-user {
        flex: 0 0 20%;
        max-width: 20%;
        padding: 0 0.75rem;
    }

    .col-xl-4-reports-admin-user {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
        padding: 0 0.75rem;
    }

    .col-xl-6-reports-admin-user {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 0.75rem;
    }

    .col-xl-8-reports-admin-user {
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
        padding: 0 0.75rem;
    }

    .col-md-4-reports-admin-user {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
        padding: 0 0.75rem;
    }

    .col-md-6-reports-admin-user {
        flex: 0 0 50%;
        max-width: 50%;
        padding: 0 0.75rem;
    }

    .d-flex-reports-admin-user {
        display: flex;
    }

    .justify-content-between-reports-admin-user {
        justify-content: space-between;
    }

    .align-items-center-reports-admin-user {
        align-items: center;
    }

    .table-responsive-reports-admin-user {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    @media print {
        .btn-group-reports-admin-user {
            display: none;
        }
        
        .container-fluid-reports-admin-user {
            padding: 0;
        }
        
        .chart-container-reports-admin-user {
            page-break-inside: avoid;
        }
    }

    @media (max-width: 1200px) {
        .col-xl-2-reports-admin-user,
        .col-xl-4-reports-admin-user,
        .col-xl-6-reports-admin-user,
        .col-xl-8-reports-admin-user {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .chart-container-reports-admin-user {
            height: 220px;
        }

        .pie-chart-container-reports-admin-user {
            height: 180px;
        }
    }

    @media (max-width: 768px) {
        .col-md-4-reports-admin-user,
        .col-md-6-reports-admin-user {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .container-fluid-reports-admin-user {
            padding: 10px;
        }

        .chart-container-reports-admin-user {
            height: 200px;
        }

        .pie-chart-container-reports-admin-user {
            height: 160px;
        }

        .card-body-reports-admin-user {
            padding: 10px;
        }

        .table-reports-admin-user th,
        .table-reports-admin-user td {
            padding: 6px 10px;
            font-size: 0.85rem;
        }

        .btn-reports-admin-user {
            padding: 6px 12px;
            font-size: 0.85rem;
        }
    }
</style>

@endsection