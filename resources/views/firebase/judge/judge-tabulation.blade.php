@extends('firebase.layouts.judge-app')

@section('styles')
<style>
    .scoring-container {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .search-container {
        position: relative;
        margin-bottom: 20px;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .search-input {
        padding-left: 35px;
        height: 40px;
    }

    .contestant-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .contestant-header {
        padding: 15px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .contestant-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .scoring-section {
        padding: 20px;
    }

    .criteria-group {
        margin-bottom: 25px;
    }

    .criteria-header {
        font-weight: 600;
        margin-bottom: 15px;
        color: #495057;
    }

    .subcriteria-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding: 8px;
        background: #f8f9fa;
        border-radius: 4px;
    }

    .score-input {
        width: 100px;
        text-align: center;
    }

    .total-score {
        font-size: 24px;
        font-weight: bold;
        color: #0d6efd;
    }

    .save-button {
        margin-top: 15px;
        width: 100%;
    }

    .score-error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 4px;
    }

    /* Loading spinner */
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 8px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection

@section('content')
<div class="scoring-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Event Scoring: {{ $eventName }}</h2>
        <div id="saveStatus" class="text-success"></div>
    </div>

    <div class="search-container">
        <i class="ri-search-line search-icon"></i>
        <input 
            type="text" 
            id="contestantSearch" 
            class="form-control search-input" 
            placeholder="Search contestants by name or number..."
        >
    </div>

    <div id="contestantsList">
        @foreach($contestants as $contestant)
        <div class="contestant-card" data-contestant-id="{{ $contestant['id'] }}">
            <div class="contestant-header">
                <div class="contestant-info">
                    <div>
                        <h4 class="mb-1">{{ $contestant['name'] }}</h4>
                        <small class="text-muted">Number: {{ $contestant['number'] }} | {{ $contestant['category'] }}</small>
                    </div>
                    <div class="text-end">
                        <div class="total-score" id="totalScore_{{ $contestant['id'] }}">0.00</div>
                        <small class="text-muted">Total Score</small>
                    </div>
                </div>
            </div>

            <div class="scoring-section">
                <form id="scoringForm_{{ $contestant['id'] }}" class="scoring-form">
                    @csrf
                    <input type="hidden" name="contestant_id" value="{{ $contestant['id'] }}">
                    
                    @foreach($criteria as $categoryName => $mainCriterias)
                    <div class="criteria-group">
                        <div class="criteria-header">{{ $categoryName }}</div>
                        
                        @foreach($mainCriterias as $mainCriteria)
                        <div class="main-criteria-section mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">{{ $mainCriteria['name'] }} ({{ $mainCriteria['percentage'] }}%)</h6>
                            </div>

                            @foreach($mainCriteria['sub_criteria'] as $subCriteria)
                            <div class="subcriteria-row">
                                <label class="mb-0">
                                    {{ $subCriteria['name'] }}
                                    <small class="text-muted">({{ $subCriteria['percentage'] }}%)</small>
                                </label>
                                <input 
                                    type="number" 
                                    class="form-control score-input score-field"
                                    name="scores[{{ $categoryName }}][{{ $mainCriteria['name'] }}][{{ $subCriteria['name'] }}]"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    data-weight="{{ $subCriteria['percentage'] }}"
                                    data-contestant="{{ $contestant['id'] }}"
                                    required
                                >
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary save-button">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Save Scores
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('contestantSearch');
    const contestantCards = document.querySelectorAll('.contestant-card');

    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        
        contestantCards.forEach(card => {
            const contestantName = card.querySelector('h4').textContent.toLowerCase();
            const contestantNumber = card.querySelector('small').textContent.toLowerCase();
            
            if (contestantName.includes(searchTerm) || contestantNumber.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Score calculation and form submission
    const scoringForms = document.querySelectorAll('.scoring-form');

    scoringForms.forEach(form => {
        const contestantId = form.querySelector('[name="contestant_id"]').value;
        const scoreInputs = form.querySelectorAll('.score-field');
        const totalScoreDisplay = document.getElementById(`totalScore_${contestantId}`);

        // Calculate total score when inputs change
        scoreInputs.forEach(input => {
            input.addEventListener('input', function() {
                let total = 0;
                scoreInputs.forEach(field => {
                    const score = parseFloat(field.value) || 0;
                    const weight = parseFloat(field.dataset.weight) || 0;
                    total += (score * weight / 100);
                });
                totalScoreDisplay.textContent = total.toFixed(2);
            });
        });

        // Handle form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitButton = form.querySelector('button[type="submit"]');
            const spinner = submitButton.querySelector('.spinner-border');
            
            try {
                submitButton.disabled = true;
                spinner.classList.remove('d-none');

                const formData = new FormData(form);
                const response = await fetch("{{ route('judge.tabulation.save-score') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });

                if (!response.ok) throw new Error('Failed to save scores');

                const saveStatus = document.getElementById('saveStatus');
                saveStatus.textContent = 'Scores saved successfully!';
                setTimeout(() => saveStatus.textContent = '', 3000);

            } catch (error) {
                console.error('Error saving scores:', error);
                alert('Failed to save scores. Please try again.');
            } finally {
                submitButton.disabled = false;
                spinner.classList.add('d-none');
            }
        });
    });

    // Validate score inputs
    document.querySelectorAll('.score-input').forEach(input => {
        input.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (value < 0 || value > 100) {
                this.setCustomValidity('Score must be between 0 and 100');
            } else {
                this.setCustomValidity('');
            }
        });
    });
});
</script>
@endsection