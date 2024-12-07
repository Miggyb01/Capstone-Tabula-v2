@extends('firebase.layouts.judge-app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Contestant Scoring</h4>
                        <div class="input-group" style="width: 250px;">
                            <input type="text" class="form-control" placeholder="Search contestants..." id="searchContestants">
                            <span class="input-group-text">
                                <i class="ri-search-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($contestants) && count($contestants) > 0)
        @foreach($contestants as $contestantId => $contestant)
        <div class="contestant-card mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="mb-1">{{ $contestant['cfname'] }} {{ $contestant['clname'] }}</h5>
                            <small class="text-muted">Number: {{ $contestant['number'] ?? '001' }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary">Category A</span>
                        </div>
                    </div>

                    <form class="scoring-form" data-contestant-id="{{ $contestantId }}">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event['id'] ?? '' }}">
                        <input type="hidden" name="contestant_id" value="{{ $contestantId }}">

                        @if(isset($criteria['categories']))
                            @foreach($criteria['categories'] as $categoryId => $category)
                                <!-- Voice Quality Section -->
                                <div class="criteria-section mb-4">
                                    <h6 class="mb-3">{{ $category['category_name'] }} ({{ $category['percentage'] }}%)</h6>
                                    
                                    @if(isset($category['main_criteria']))
                                        @foreach($category['main_criteria'] as $mainId => $main)
                                            <div class="row align-items-center mb-2">
                                                <div class="col">
                                                    <label class="form-label mb-0">
                                                        {{ $main['name'] }} ({{ $main['percentage'] }}%)
                                                    </label>
                                                </div>
                                                <div class="col-auto">
                                                    <input type="number" 
                                                           class="form-control score-input" 
                                                           style="width: 100px;"
                                                           name="scores[{{ $categoryId }}][{{ $mainId }}]"
                                                           min="0" 
                                                           max="100" 
                                                           step="0.1"
                                                           data-weight="{{ $main['percentage'] }}"
                                                           required>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endforeach
                        @endif

                        <div class="border-top pt-3">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="mb-0">Total Score: <span class="total-score">0.00</span></h5>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-secondary me-2 clear-btn">Clear</button>
                                    <button type="submit" class="btn btn-primary submit-btn">Submit Score</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="alert alert-info">No contestants found for this event.</div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchContestants');
    const contestantCards = document.querySelectorAll('.contestant-card');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        
        contestantCards.forEach(card => {
            const contestantName = card.querySelector('h5').textContent.toLowerCase();
            card.style.display = contestantName.includes(searchTerm) ? 'block' : 'none';
        });
    });

    // Score calculation
    document.querySelectorAll('.scoring-form').forEach(form => {
        const inputs = form.querySelectorAll('.score-input');
        const totalDisplay = form.querySelector('.total-score');
        
        inputs.forEach(input => {
            input.addEventListener('input', () => calculateTotal(form));
        });

        // Clear button
        form.querySelector('.clear-btn').addEventListener('click', () => {
            inputs.forEach(input => input.value = '');
            calculateTotal(form);
        });

        // Submit form
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            submitScore(form);
        });
    });
});

function calculateTotal(form) {
    let total = 0;
    form.querySelectorAll('.score-input').forEach(input => {
        const score = parseFloat(input.value) || 0;
        const weight = parseFloat(input.dataset.weight) / 100;
        total += score * weight;
    });
    
    form.querySelector('.total-score').textContent = total.toFixed(2);
}

function submitScore(form) {
    const formData = new FormData(form);
    
    fetch('/judge/tabulation/save-score', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Score saved successfully!');
            form.querySelector('.clear-btn').click();
        } else {
            alert('Error saving score: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error saving score: ' + error.message);
    });
}
</script>
@endpush
@endsection