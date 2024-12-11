@extends('firebase.layouts.judge-app')

<style>
/* Your existing styles remain the same */
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

.score-input-tabulation-judge-user.submitted {
    background-color: #e9ecef !important;
    border-color: #ced4da !important;
    cursor: not-allowed;
}

.submit-score-tabulation-judge-user {
    background-color: #3155FE;
    border-color: #3155FE;
    color: white;
}

.submit-score-tabulation-judge-user:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}

/* Modal Styles */
.confirmation-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    overflow: auto;
}

.confirmation-modal-content {
    background-color: white;
    margin: 10% auto;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
    position: relative;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.score-review-table {
    width: 100%;
    margin: 15px 0;
    border-collapse: collapse;
}

.score-review-table th,
.score-review-table td {
    padding: 8px 12px;
    border: 1px solid #dee2e6;
    text-align: center;
}

.score-review-table th {
    background-color: #f8f9fa;
    font-weight: bold;
}

.modal-buttons {
    text-align: right;
    margin-top: 20px;
}

.modal-buttons button {
    margin-left: 10px;
    padding: 8px 16px;
}

.error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 4px;
    display: none;
}

.event-name-tabulation-judge-user {
    text-align: center;
    margin-bottom: 20px;
    font-weight: bold;
    font-size: 30px;
}
.category-title-tabulation-judge-user {
    color: black;
    font-weight: bold;
    margin-right: 30px;
}
</style>


@section('content')
<h3 class="fw-bold fs-4 mb-1 mt-4 ms-4">Tabulation</h3> 
<div class="event-name-tabulation-judge-user">{{ $eventName }}</div>

<div class="container-fluid p-4 container-tabulation-judge-user">
    <div class="row mb-4 header-row-tabulation-judge-user">
        <div class="col-md-6">
            <div class="search-container-tabulation-judge-user">
                <div class="input-group">
                    <input type="text" id="contestantSearch" 
                           class="form-control search-input-tabulation-judge-user" 
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
                        <tr>
                            <th rowspan="2" class="align-middle">Contestant</th>
                            @foreach($criteria[$currentCategory]['main_criteria'] as $main)
                                <th colspan="{{ count($main['sub_criteria']) }}">
                                    {{ $main['name'] }} ({{ $main['percentage'] }}%)
                                </th>
                            @endforeach
                            <th rowspan="2" class="align-middle">Actions</th>
                        </tr>
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
                        @php
                            $contestantPath = sprintf(
                                "contestant_name/%s/category/%s/scores",
                                $contestant['name'],
                                $currentCategory
                            );
                            $isSubmitted = false;
                            $submittedScore = null;
                            
                            if (isset($submittedScores['contestant_name'][$contestant['name']]['category'][$currentCategory]['scores'])) {
                                $isSubmitted = true;
                                $submittedScore = $submittedScores['contestant_name'][$contestant['name']]['category'][$currentCategory]['scores'];
                            }
                        @endphp
                        <tr class="contestant-row-tabulation-judge-user" data-contestant-id="{{ $contestant['id'] }}">
                            <td class="contestant-cell">
                                <div class="contestant-info-tabulation-judge-user">
                                    <span class="contestant-number-tabulation-judge-user">{{ $contestant['number'] }}.</span>
                                    <span class="contestant-name contestant-name-tabulation-judge-user">{{ $contestant['name'] }}</span>
                                </div>
                            </td>
                            @foreach($criteria[$currentCategory]['main_criteria'] as $main)
                                @foreach($main['sub_criteria'] as $sub)
                                    <td class="text-center">
                                        <input type="number" 
                                               class="form-control score-input-tabulation-judge-user {{ $isSubmitted ? 'submitted' : '' }}"
                                               name="scores[{{ $main['name'] }}][{{ $sub['name'] }}]"
                                               data-max-score="{{ $sub['percentage'] }}"
                                               min="0"
                                               max="{{ $sub['percentage'] }}"
                                               step="0.01"
                                               {{ $isSubmitted ? 'readonly' : '' }}
                                               value="{{ $isSubmitted && isset($submittedScore['scores'][$main['name']][$sub['name']]) ? $submittedScore['scores'][$main['name']][$sub['name']] : '' }}"
                                               required>
                                        <div class="error-message"></div>
                                    </td>
                                @endforeach
                            @endforeach
                            <td class="text-center">
                                <button class="btn {{ $isSubmitted ? 'btn-secondary' : 'btn-primary' }} submit-score-tabulation-judge-user"
                                        data-contestant-id="{{ $contestant['id'] }}"
                                        {{ $isSubmitted ? 'disabled' : '' }}>
                                    {{ $isSubmitted ? 'Submitted' : 'Submit Scores' }}
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="confirmation-modal">
        <div class="confirmation-modal-content">
            <div class="modal-title">Confirm Score Submission</div>
            <p>Are you sure you want to submit the following scores for:</p>
            <p><strong id="confirmContestantName" style="color: #3155FE;"></strong>?</p>
            
            <div class="score-review">
                <table class="score-review-table">
                    <thead>
                        <tr>
                            <th>Criteria</th>
                            <th>Score</th>
                            <th>Max Score</th>
                        </tr>
                    </thead>
                    <tbody id="scoreReviewBody">
                    </tbody>
                </table>
            </div>

            <div class="modal-buttons">
                <button type="button" class="btn btn-secondary" onclick="closeConfirmationModal()">
                    <i class="ri-close-line"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="confirmSubmission()">
                    <i class="ri-check-line"></i> Confirm Submission
                </button>
            </div>
        </div>
    </div>

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
        @endif
    </div>
    @endif
</div>
@endsection


<script>
let currentContestantRow = null;
let collectedScores = null;

document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.querySelector('#contestantSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.contestant-row-tabulation-judge-user').forEach(row => {
                const nameElement = row.querySelector('.contestant-name-tabulation-judge-user');
                if (nameElement) {
                    const name = nameElement.textContent.toLowerCase();
                    row.style.display = name.includes(searchTerm) ? '' : 'none';
                }
            });
        });
    }

    // Score validation
    document.querySelectorAll('.score-input-tabulation-judge-user').forEach(input => {
        if (input.classList.contains('submitted')) {
            input.readOnly = true;
        }
        
        input.addEventListener('input', function() {
            const maxScore = parseFloat(this.dataset.maxScore);
            const score = parseFloat(this.value);
            const errorDiv = this.nextElementSibling;

            if (score > maxScore || score < 0) {
                errorDiv.textContent = `Score must be between 0 and ${maxScore}`;
                errorDiv.style.display = 'block';
                this.classList.add('is-invalid');
            } else {
                errorDiv.style.display = 'none';
                this.classList.remove('is-invalid');
            }
        });
    });

    // Submit button click handler
    document.querySelectorAll('.submit-score-tabulation-judge-user').forEach(button => {
        button.addEventListener('click', function() {
            if (this.disabled) return;

            const row = this.closest('tr');
            currentContestantRow = row;
            const contestantName = row.querySelector('.contestant-name-tabulation-judge-user').textContent.trim();
            const scores = {};
            let allInputsFilled = true;
            let isValid = true;

            row.querySelectorAll('.score-input-tabulation-judge-user').forEach(input => {
                if (input.classList.contains('is-invalid')) {
                    isValid = false;
                    return;
                }

                const score = parseFloat(input.value);
                if (isNaN(score)) {
                    allInputsFilled = false;
                    return;
                }

                const name = input.name.match(/\[(.*?)\]/g).map(m => m.slice(1, -1));
                if (!scores[name[0]]) scores[name[0]] = {};
                scores[name[0]][name[1]] = score;
            });

            if (!allInputsFilled) {
                alert('Please fill in all scores before submitting.');
                return;
            }

            if (!isValid) {
                alert('Please fix the invalid scores before submitting.');
                return;
            }

            collectedScores = scores;
            showConfirmationModal(contestantName, scores);
        });
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeConfirmationModal();
        }
    });
});

function showConfirmationModal(contestantName, scores) {
    const modal = document.getElementById('confirmationModal');
    const contestantNameSpan = document.getElementById('confirmContestantName');
    const scoreReviewBody = document.getElementById('scoreReviewBody');

    if (!modal || !contestantNameSpan || !scoreReviewBody) {
        console.error('Modal elements not found');
        return;
    }

    contestantNameSpan.textContent = contestantName;
    scoreReviewBody.innerHTML = '';

    for (const [mainCriteria, subScores] of Object.entries(scores)) {
        for (const [subCriteria, score] of Object.entries(subScores)) {
            const maxScore = currentContestantRow.querySelector(
                `input[name="scores[${mainCriteria}][${subCriteria}]"]`
            ).dataset.maxScore;
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${mainCriteria} - ${subCriteria}</td>
                <td class="text-center">${score}</td>
                <td class="text-center">${maxScore}%</td>
            `;
            scoreReviewBody.appendChild(row);
        }
    }

    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeConfirmationModal() {
    const modal = document.getElementById('confirmationModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

async function confirmSubmission() {
    try {
        if (!currentContestantRow || !collectedScores) {
            throw new Error('Invalid submission state');
        }

        const contestantId = currentContestantRow.dataset.contestantId;
        const contestantName = currentContestantRow.querySelector('.contestant-name-tabulation-judge-user').textContent.trim();

        const response = await fetch("{{ route('judge.tabulation.save-score') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                contestant_id: contestantId,
                contestant_name: contestantName,
                category: '{{ $currentCategory }}',
                scores: collectedScores,
                event_name: '{{ $eventName }}'
            })
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || result.error || 'Failed to save scores');
        }

        // Disable inputs and mark as submitted
        currentContestantRow.querySelectorAll('.score-input-tabulation-judge-user').forEach(input => {
            input.readOnly = true;
            input.classList.add('submitted');
            // Store the submitted value in localStorage
            const key = `submitted_score_${contestantId}_${input.name}`;
            localStorage.setItem(key, input.value);
        });
        
        const submitButton = currentContestantRow.querySelector('.submit-score-tabulation-judge-user');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Submitted';
            submitButton.classList.add('btn-secondary');
            submitButton.classList.remove('btn-primary');
            // Store button state
            localStorage.setItem(`submitted_button_${contestantId}_${currentCategory}`, 'true');
        }
        
        closeConfirmationModal();
        alert('Scores submitted successfully!');

        // Reset state
        currentContestantRow = null;
        collectedScores = null;

    } catch (error) {
        console.error('Error saving scores:', error);
        closeConfirmationModal();
        alert(error.message || 'Failed to save scores. Please try again.');
    }
}

// Restore submitted states on page load
function restoreSubmittedStates() {
    document.querySelectorAll('.contestant-row-tabulation-judge-user').forEach(row => {
        const contestantId = row.dataset.contestantId;
        
        // Check if this contestant's scores were submitted
        const isSubmitted = localStorage.getItem(`submitted_button_${contestantId}_{{ $currentCategory }}`);
        
        if (isSubmitted === 'true') {
            // Restore input states
            row.querySelectorAll('.score-input-tabulation-judge-user').forEach(input => {
                const key = `submitted_score_${contestantId}_${input.name}`;
                const savedValue = localStorage.getItem(key);
                
                if (savedValue) {
                    input.value = savedValue;
                    input.readOnly = true;
                    input.classList.add('submitted');
                }
            });
            
            // Restore button state
            const submitButton = row.querySelector('.submit-score-tabulation-judge-user');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Submitted';
                submitButton.classList.add('btn-secondary');
                submitButton.classList.remove('btn-primary');
            }
        }
    });
}

// Close modal if clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('confirmationModal');
    if (event.target === modal) {
        closeConfirmationModal();
    }
};

// Prevent form submission when pressing enter
document.addEventListener('keydown', function(event) {
    if (event.key === 'Enter' && event.target.classList.contains('score-input-tabulation-judge-user')) {
        event.preventDefault();
        const inputs = Array.from(document.querySelectorAll('.score-input-tabulation-judge-user:not([readonly])'));
        const currentIndex = inputs.indexOf(event.target);
        const nextInput = inputs[currentIndex + 1];
        if (nextInput) {
            nextInput.focus();
        }
    }
});

// Call restore function when page loads
document.addEventListener('DOMContentLoaded', function() {
    restoreSubmittedStates();
});

</script>
