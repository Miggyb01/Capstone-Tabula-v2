@extends('firebase.layouts.judge-app')

@section('content')
<div class="container-fluid p-4">
    <h3 class="fw-bold fs-4 mb-3">Scoring Summary</h3>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Event: {{ $eventName }}</h4>
        </div>
        <div class="card-body">
            @if(empty($scores))
                <div class="alert alert-info">
                    {{ $message ?? 'No scores available yet.' }}
                </div>
            @else
                @foreach($scores as $contestant)
                    <div class="contestant-scores mb-4">
                        <h5 class="border-bottom pb-2">
                            Contestant: {{ $contestant['name'] }}
                        </h5>
                        
                        @foreach($contestant['categories'] as $categoryName => $category)
                            <div class="category-section mb-4">
                                <h6 class="bg-light p-2 rounded">Category: {{ $categoryName }}</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Main Criteria</th>
                                                <th>Sub Criteria</th>
                                                <th>Score</th>
                                                <th>Max Score</th>
                                                <th>Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($category['main_criteria'] as $mainName => $mainData)
                                                @php $firstRow = true; @endphp
                                                @foreach($mainData['sub_scores'] as $subName => $subData)
                                                    <tr>
                                                        @if($firstRow)
                                                            <td rowspan="{{ count($mainData['sub_scores']) }}" class="align-middle">
                                                                {{ $mainName }}
                                                                <div class="small text-muted">
                                                                    Total: {{ number_format($mainData['total'], 2) }}/{{ $mainData['max_score'] }}
                                                                    ({{ number_format($mainData['percentage'], 1) }}%)
                                                                </div>
                                                            </td>
                                                            @php $firstRow = false; @endphp
                                                        @endif
                                                        <td>{{ $subName }}</td>
                                                        <td class="text-center">{{ number_format($subData['score'], 2) }}</td>
                                                        <td class="text-center">{{ $subData['max_score'] }}</td>
                                                        <td class="text-center">
                                                            {{ number_format($subData['percentage'], 1) }}%
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <td colspan="2" class="text-end fw-bold">Category Total:</td>
                                                <td colspan="3" class="text-center fw-bold">
                                                    {{ number_format($category['total'], 2) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        @endforeach

                        <div class="overall-score bg-light p-3 rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-0">Total Score:</h6>
                                    <h4 class="mb-0">{{ number_format($contestant['total_score'], 2) }}</h4>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <h6 class="mb-0">Average Score:</h6>
                                    <h4 class="mb-0">{{ number_format($contestant['average_score'], 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-4">
                @endforeach
            @endif
        </div>
    </div>
</div>

<style>
.contestant-scores {
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.category-section {
    margin-left: 15px;
}

.table th {
    background-color: #f8f9fa;
}

.overall-score {
    border-top: 2px solid #dee2e6;
    margin-top: 20px;
    padding-top: 20px;
}

.table td, .table th {
    vertical-align: middle;
}

.small {
    font-size: 0.875rem;
}
</style>
@endsection