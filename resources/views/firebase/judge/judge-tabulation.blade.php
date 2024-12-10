@extends('firebase.layouts.judge-app')

<style>
.table-tabulation-judge-user th {
    text-align: center;
    vertical-align: middle;
    border: 1px solid #dee2e6;
}

.table-tabulation-judge-user thead tr:first-child th {
    border-bottom: 1px solid #fff;
    background-color: #3155FE;
    color: white;
    font-size: 22px;
}

.contestant-cell {
    max-width: 200px;
    white-space: normal;
    font-size: 20px;
    padding: 12px 15px !important;
}

.score-input-tabulation-judge-user {
    width: 80px;
    margin: 0 auto;
    text-align: center;
}

.submit-score-tabulation-judge-user {
    background-color: #3155FE;
    border-color: #3155FE;
    color: white;
}

.table-tabulation-judge-user thead th {
    padding: 12px 8px;
    font-size: 14px;
}

.table-tabulation-judge-user thead tr:first-child th {
    text-align: center;
}

.table-responsive {
    border-radius: 20px;
}   

.table-tabulation-judge-user thead tr:nth-child(2) th {
    background-color: #F8F9FA;
    color: black;
    font-weight: bold;
    text-align: center;
    border: 1px solid #dee2e6;
    padding: 10px;
    font-size: 18px;
    border-top: none;
}

.category-title-tabulation-judge-user {
    color: black;
    font-weight: bold;
    margin-right: 30px;
}

.event-name-tabulation-judge-user {
    text-align: center;

    margin-bottom: 20px;
    font-weight: bold;
    font-size: 30px;
}

.contestant-number-display {
    font-weight: bold;
    color: #3155FE;
    font-size: 16px;
    margin-bottom: 4px;
}

.contestant-name {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 4px;
}

.contestant-cell {
    padding: 12px 15px !important;
}



</style>

@section('content')
<h3 class="fw-bold fs-4 mb-1 mt-4 ms-4">Tabulation</h3> 
<div class="event-name-tabulation-judge-user">{{ $eventName }}</div>

<div class="container-fluid p-4 container-tabulation-judge-user">
    <!-- Header with Search and Category -->
    <div class="row mb-4 header-row-tabulation-judge-user">
        <div class="col-md-6">
            <div class="search-container-tabulation-judge-user">
                <div class="input-group">
                    <input type="text" id="contestantSearch" class="form-control search-input-tabulation-judge-user" 
                           placeholder="Search contestants...">
                    <button class="btn btn-outline-secondary btn-search-tabulation-judge-user" type="button">
                        <i class="ri-search-line"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-end">
            <h4 class="category-title-tabulation-judge-user mt-1">Category: {{ $currentCategory }}</h4>
        </div>
    </div>

    @if($currentCategory)
    <div class="card-scoring-card-tabulation-judge-user">
        <div class="card-body-tabulation-judge-user p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0 table-tabulation-judge-user">
                    <thead>
                        <!-- Main criteria row -->
                        <tr>
                            <th rowspan="2" class="align-middle">Contestant</th>
                            @foreach($criteria[$currentCategory]['main_criteria'] as $main)
                                <th colspan="{{ count($main['sub_criteria']) }}">
                                    {{ $main['name'] }} ({{ $main['percentage'] }}%)
                                </th>
                            @endforeach
                            <th rowspan="2" class="align-middle">Actions</th>
                        </tr>
                        <!-- Sub criteria row -->
                        <tr>
                            @foreach($criteria[$currentCategory]['main_criteria'] as $main)
                                @foreach($main['sub_criteria'] as $sub)
                                    <th class="text-center">
                                        {{ $sub['name'] }}<br>({{ $sub['percentage'] }}%)
                                    </th>
                                @endforeach
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contestants as $contestant)
                        <tr class="contestant-row-tabulation-judge-user">
                            <td class="contestant-cell">
                                <div class="contestant-info-tabulation-judge-user">
                                    <span class="contestant-number-tabulation-judge-user">{{ $contestant['number'] }}.</span>
                                    <span class="contestant-name-tabulation-judge-user">{{ $contestant['name'] }}</span>
                                </div>
                            </td>
                            @foreach($criteria[$currentCategory]['main_criteria'] as $main)
                                @foreach($main['sub_criteria'] as $sub)
                                    <td class="text-center">
                                        <input type="number" 
                                               class="form-control score-input-tabulation-judge-user"
                                               name="scores[{{ $main['name'] }}][{{ $sub['name'] }}]"
                                               min="0"
                                               max="100"
                                               step="0.01"
                                               required>
                                    </td>
                                @endforeach
                            @endforeach
                            <td class="text-center">
                                <button class="btn btn-primary submit-score-tabulation-judge-user"
                                        data-contestant-id="{{ $contestant['id'] }}">
                                    Submit Scores
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Navigation Button -->
    <div class="d-flex justify-content-end mt-3">
        @php
            $currentIndex = array_search($currentCategory, $categories);
            $nextCategory = ($currentIndex !== false && isset($categories[$currentIndex + 1])) 
                ? $categories[$currentIndex + 1] 
                : null;
        @endphp
        
        @if($nextCategory)
            <a href="{{ route('judge.tabulation', ['category' => $nextCategory]) }}" 
               class="btn btn-primary next-category-tabulation-judge-user"
               style="background-color: #3155FE; border-color: #3155FE;">
                Next Category
            </a>
        @else
            <button class="btn btn-primary next-category-tabulation-judge-user" disabled
                    style="background-color: #3155FE; border-color: #3155FE;">
                Next Category
            </button>
        @endif
    </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.querySelector('.search-input-tabulation-judge-user');
    const contestantRows = document.querySelectorAll('.contestant-row-tabulation-judge-user');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        
        contestantRows.forEach(row => {
            const contestantCell = row.querySelector('.contestant-cell');
            const contestantName = contestantCell.querySelector('div:first-child').textContent.toLowerCase();
            const contestantCode = contestantCell.querySelector('.contestant-unique-code').textContent.toLowerCase();
            
            if (contestantName.includes(searchTerm) || contestantCode.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Score submission
    document.querySelectorAll('.submit-score-tabulation-judge-user').forEach(button => {
        button.addEventListener('click', async function() {
            const row = this.closest('tr');
            const contestantId = this.dataset.contestantId;
            const scores = {};
            
            row.querySelectorAll('.score-input-tabulation-judge-user').forEach(input => {
                const name = input.name.match(/\[(.*?)\]/g).map(m => m.slice(1, -1));
                if (!scores[name[0]]) scores[name[0]] = {};
                scores[name[0]][name[1]] = parseFloat(input.value) || 0;
            });

            try {
                const response = await fetch("{{ route('judge.tabulation.save-score') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        contestant_id: contestantId,
                        category: '{{ $currentCategory }}',
                        scores: scores
                    })
                });

                if (!response.ok) throw new Error('Failed to save scores');
                alert('Scores submitted successfully!');

            } catch (error) {
                console.error('Error saving scores:', error);
                alert('Failed to save scores. Please try again.');
            }
        });
    });
});
</script>
@endsection