@extends('firebase.layouts.judge-app')

@section('content')
<div class="container-fluid-judge-tabulation">
    <div class="card-judge-tabulation">
        <div class="card-header">
            <h4 class="mb-0">Contestant Scoring</h4>
            <!-- Search Bar -->
            <div class="search-container">
                <input type="text" class="search-input" placeholder="Search contestants...">
            </div>
        </div>

        <div class="card-body-judge-tabulation">
            <!-- Event Selection -->
            <div class="select-container-judge-tabulation mb-4">
                <label for="eventSelect" class="form-label-judge-tabulation">Select Event</label>
                <select class="select-judge-tabulation" id="eventSelect" name="event_name">
                    <option value="">Select Event</option>
                    <option value="1">TEST1</option>
                </select>
            </div>

            <!-- Contestant Card -->
            <div class="contestant-info">
                <div>
                    <div class="contestant-name">John Doe</div>
                    <div class="contestant-number">Number: 001</div>
                </div>
                <div class="contestant-category">Category A</div>
            </div>

            <!-- Scoring Section -->
            <div class="scoring-section">
                <!-- Voice Quality Section -->
                <div class="criteria-category-judge-tabulation">
                    <h6 class="category-title-judge-tabulation">Voice Quality (40%)</h6>
                    
                    <div class="criteria-item-judge-tabulation">
                        <label class="criteria-label-judge-tabulation">Pitch Accuracy (15%)</label>
                        <input type="number" class="score-input-judge-tabulation" min="0" max="100" step="0.01">
                    </div>

                    <div class="criteria-item-judge-tabulation">
                        <label class="criteria-label-judge-tabulation">Tone Quality (15%)</label>
                        <input type="number" class="score-input-judge-tabulation" min="0" max="100" step="0.01">
                    </div>

                    <div class="criteria-item-judge-tabulation">
                        <label class="criteria-label-judge-tabulation">Breath Control (10%)</label>
                        <input type="number" class="score-input-judge-tabulation" min="0" max="100" step="0.01">
                    </div>
                </div>

                <!-- Performance Section -->
                <div class="criteria-category-judge-tabulation">
                    <h6 class="category-title-judge-tabulation">Performance (30%)</h6>
                    
                    <div class="criteria-item-judge-tabulation">
                        <label class="criteria-label-judge-tabulation">Stage Presence (15%)</label>
                        <input type="number" class="score-input-judge-tabulation" min="0" max="100" step="0.01">
                    </div>

                    <div class="criteria-item-judge-tabulation">
                        <label class="criteria-label-judge-tabulation">Interpretation (15%)</label>
                        <input type="number" class="score-input-judge-tabulation" min="0" max="100" step="0.01">
                    </div>
                </div>

                <!-- Overall Impact Section -->
                <div class="criteria-category-judge-tabulation">
                    <h6 class="category-title-judge-tabulation">Overall Impact (30%)</h6>
                    
                    <div class="criteria-item-judge-tabulation">
                        <label class="criteria-label-judge-tabulation">Audience Impact (15%)</label>
                        <input type="number" class="score-input-judge-tabulation" min="0" max="100" step="0.01">
                    </div>

                    <div class="criteria-item-judge-tabulation">
                        <label class="criteria-label-judge-tabulation">Originality (15%)</label>
                        <input type="number" class="score-input-judge-tabulation" min="0" max="100" step="0.01">
                    </div>
                </div>

                <!-- Total Score Section -->
                <div class="final-score-card-judge-tabulation">
                    <div class="final-score-body-judge-tabulation">
                        <h4 class="final-score-title-judge-tabulation">
                            Total Score: <span class="final-score-value-judge-tabulation">0.00</span>
                        </h4>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="submit-container-judge-tabulation">
                    <button type="submit" class="submit-button-judge-tabulation">Submit Scores</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Container Styles */
.container-fluid-judge-tabulation {
    padding: 20px;
    background-color: #f8f9fa;
}

/* Card Styles */
.card-judge-tabulation {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    overflow: hidden;
}

.card-header {
    padding: 1.5rem;
    background-color: white;
    border-bottom: 1px solid #eee;
}

.card-body-judge-tabulation {
    padding: 1.5rem;
}

/* Search Bar */
.search-container {
    margin-top: 1rem;
}

.search-input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    font-size: 0.9rem;
}

/* Select Container */
.select-container-judge-tabulation {
    margin-bottom: 1.5rem;
}

.select-judge-tabulation {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    font-size: 0.9rem;
}

/* Contestant Info */
.contestant-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: white;
    border: 1px solid #eee;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.contestant-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
}

.contestant-number {
    color: #666;
    font-size: 0.9rem;
    margin-top: 0.25rem;
}

.contestant-category {
    background-color: #e9ecef;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.9rem;
    color: #495057;
}

/* Criteria Categories */
.criteria-category-judge-tabulation {
    background: white;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.category-title-judge-tabulation {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1.5rem;
}

/* Criteria Items */
.criteria-item-judge-tabulation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.criteria-label-judge-tabulation {
    font-size: 0.9rem;
    color: #333;
}

.score-input-judge-tabulation {
    width: 120px;
    padding: 0.5rem;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    text-align: right;
}

/* Final Score */
.final-score-card-judge-tabulation {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
}

.final-score-title-judge-tabulation {
    font-size: 1.1rem;
    color: #333;
    text-align: right;
    margin: 0;
}

.final-score-value-judge-tabulation {
    font-weight: 600;
    color: #0d6efd;
}

/* Submit Button */
.submit-button-judge-tabulation {
    width: 100%;
    padding: 0.75rem;
    background-color: #0d6efd;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
}

.submit-button-judge-tabulation:hover {
    background-color: #0b5ed7;
}

/* Responsive Design */
@media (max-width: 768px) {
    .criteria-item-judge-tabulation {
        flex-direction: column;
        align-items: stretch;
    }

    .score-input-judge-tabulation {
        width: 100%;
        margin-top: 0.5rem;
    }

    .contestant-info {
        flex-direction: column;
        text-align: center;
    }

    .contestant-category {
        margin-top: 1rem;
    }
}
</style>
@endsection