@extends('firebase.layouts.judge-app')


<style>
    .scoring-container {
        padding: 20px;
        background: #fff;
    }

    .search-container {
        margin-bottom: 30px;
    }

    .search-input {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 8px 12px;
        width: 300px;
    }

    .contestant-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 30px;
        padding: 20px;
    }

    .contestant-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .contestant-info h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .contestant-number {
        color: #666;
        font-size: 0.9rem;
    }

    .contestant-category {
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
        color: #666;
    }

    .criteria-section {
        margin-bottom: 25px;
    }

    .criteria-title {
        font-weight: 600;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .criteria-weight {
        color: #666;
        font-size: 0.9rem;
    }

    .subcriteria-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .subcriteria-label {
        flex: 1;
        font-size: 0.95rem;
    }

    .subcriteria-weight {
        color: #666;
        margin-left: 5px;
        font-size: 0.85rem;
    }

    .score-input {
        width: 80px;
        text-align: center;
        padding: 4px 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-left: 15px;
    }

    .score-input:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    .total-score {
        text-align: right;
        padding-top: 20px;
        margin-top: 20px;
        border-top: 2px solid #eee;
    }

    .total-score-label {
        font-weight: 600;
        margin-right: 15px;
    }

    .total-score-value {
        font-size: 1.5rem;
        font-weight: bold;
        color: #0d6efd;
    }

    .action-buttons {
        margin-top: 20px;
        text-align: right;
    }

    .btn-save {
        background: #0d6efd;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-save:hover {
        background: #0b5ed7;
    }

    /* Loading state styles */
    .loading {
        opacity: 0.7;
        pointer-events: none;
    }
</style>


@section('content')
<div class="scoring-container">
    <div class="search-container">
        <input type="text" 
               class="search-input" 
               placeholder="Search contestants..." 
               id="contestantSearch">
    </div>

    <div id="contestantsList">
        @foreach($contestants as $contestant)
        <div class="contestant-card" data-contestant-id="{{ $contestant['id'] }}">
            <div class="contestant-header">
                <div class="contestant-info">
                    <h3>{{ $contestant['name'] }}</h3>
                    <span class="contestant-number">Number: {{ $contestant['number'] }}</span>
                </div>
                <span class="contestant-category">{{ $contestant['category'] }}</span>
            </div>

            <form id="scoringForm_{{ $contestant['id'] }}" class="scoring-form">
                @csrf
                <input type="hidden" name="contestant_id" value="{{ $contestant['id'] }}">

                <!-- Voice Quality Section -->
                <div class="criteria-section">
                    <div class="criteria-title">
                        <span>Voice Quality</span>
                        <span class="criteria-weight">(40%)</span>
                    </div>
                    
                    <div class="subcriteria-group">
                        <div class="subcriteria-row">
                            <span class="subcriteria-label">
                                Pitch Accuracy
                                <span class="subcriteria-weight">(15%)</span>
                            </span>
                            <input type="number" 
                                   class="score-input score-field" 
                                   name="scores[voice_quality][pitch]"
                                   min="0" 
                                   max="100" 
                                   step="0.01" 
                                   required>
                        </div>
                        
                        <div class="subcriteria-row">
                            <span class="subcriteria-label">
                                Tone Quality
                                <span class="subcriteria-weight">(15%)</span>
                            </span>
                            <input type="number" 
                                   class="score-input score-field" 
                                   name="scores[voice_quality][tone]"
                                   min="0" 
                                   max="100" 
                                   step="0.01" 
                                   required>
                        </div>

                        <div class="subcriteria-row">
                            <span class="subcriteria-label">
                                Breath Control
                                <span class="subcriteria-weight">(10%)</span>
                            </span>
                            <input type="number" 
                                   class="score-input score-field" 
                                   name="scores[voice_quality][breath]"
                                   min="0" 
                                   max="100" 
                                   step="0.01" 
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Performance Section -->
                <div class="criteria-section">
                    <div class="criteria-title">
                        <span>Performance</span>
                        <span class="criteria-weight">(30%)</span>
                    </div>
                    
                    <div class="subcriteria-group">
                        <div class="subcriteria-row">
                            <span class="subcriteria-label">
                                Stage Presence
                                <span class="subcriteria-weight">(15%)</span>
                            </span>
                            <input type="number" 
                                   class="score-input score-field" 
                                   name="scores[performance][presence]"
                                   min="0" 
                                   max="100" 
                                   step="0.01" 
                                   required>
                        </div>

                        <div class="subcriteria-row">
                            <span class="subcriteria-label">
                                Interpretation
                                <span class="subcriteria-weight">(15%)</span>
                            </span>
                            <input type="number" 
                                   class="score-input score-field" 
                                   name="scores[performance][interpretation]"
                                   min="0" 
                                   max="100" 
                                   step="0.01" 
                                   required>
                        </div>
                    </div>
                </div>

                <div class="total-score">
                    <span class="total-score-label">Total Score:</span>
                    <span class="total-score-value" id="totalScore_{{ $contestant['id'] }}">0.00</span>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn-save">
                        Save Scores
                    </button>
                </div>
            </form>
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
            const contestantName = card.querySelector('h3').textContent.toLowerCase();
            const contestantNumber = card.querySelector('.contestant-number').textContent.toLowerCase();
            
            if (contestantName.includes(searchTerm) || contestantNumber.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Score calculation
    document.querySelectorAll('.scoring-form').forEach(form => {
        const inputs = form.querySelectorAll('.score-input');
        const contestantId = form.querySelector('[name="contestant_id"]').value;
        const totalDisplay = document.getElementById(`totalScore_${contestantId}`);

        inputs.forEach(input => {
            input.addEventListener('input', function() {
                let total = 0;
                let voiceQualityTotal = 0;
                let performanceTotal = 0;

                // Calculate Voice Quality scores (40% total)
                const pitchScore = parseFloat(form.querySelector('[name="scores[voice_quality][pitch]"]').value || 0) * 0.15;
                const toneScore = parseFloat(form.querySelector('[name="scores[voice_quality][tone]"]').value || 0) * 0.15;
                const breathScore = parseFloat(form.querySelector('[name="scores[voice_quality][breath]"]').value || 0) * 0.10;
                voiceQualityTotal = pitchScore + toneScore + breathScore;

                // Calculate Performance scores (30% total)
                const presenceScore = parseFloat(form.querySelector('[name="scores[performance][presence]"]').value || 0) * 0.15;
                const interpretationScore = parseFloat(form.querySelector('[name="scores[performance][interpretation]"]').value || 0) * 0.15;
                performanceTotal = presenceScore + interpretationScore;

                total = voiceQualityTotal + performanceTotal;
                totalDisplay.textContent = total.toFixed(2);
            });
        });
    });

    // Form submission
    document.querySelectorAll('.scoring-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitButton = form.querySelector('.btn-save');
            
            try {
                submitButton.disabled = true;
                form.classList.add('loading');

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
                alert('Scores saved successfully!');

            } catch (error) {
                console.error('Error saving scores:', error);
                alert('Failed to save scores. Please try again.');
            } finally {
                submitButton.disabled = false;
                form.classList.remove('loading');
            }
        });
    });
});
</script>
@endsection