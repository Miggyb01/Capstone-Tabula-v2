@extends('firebase.layouts.judge-app')

@section('content')
<div class="container-fluid-judge-tabulation">
    <div class="card-judge-tabulation">
        <div class="card-header">
            <h3>Event Tabulation</h3>
        </div>

        <div class="card-body-judge-tabulation">
            <!-- Debug Info Section (can be removed in production) -->
            <div id="debugInfo" class="alert alert-info" style="display: none;">
                <pre></pre>
            </div>

            <!-- Event Selection -->
            <div class="select-container-judge-tabulation mb-4">
                <label for="eventSelect" class="form-label-judge-tabulation">Select Event</label>
                <select class="form-select select-judge-tabulation" id="eventSelect" name="event_name">
                    <option value="">Select Event</option>
                    @if($events)
                        @foreach($events as $eventId => $event)
                            <option value="{{ $event['ename'] }}">
                                {{ $event['ename'] }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Loading Indicator -->
            <div id="loadingIndicator" style="display: none;" class="text-center my-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <!-- Contestants Section -->
            <div id="contestantsSection" class="mt-4">
                <!-- Contestants will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const debugInfo = $('#debugInfo');
    const loadingIndicator = $('#loadingIndicator');
    const contestantsSection = $('#contestantsSection');

    function showLoading() {
        loadingIndicator.show();
        contestantsSection.hide();
    }

    function hideLoading() {
        loadingIndicator.hide();
        contestantsSection.show();
    }

    function showDebug(info) {
        debugInfo.find('pre').text(JSON.stringify(info, null, 2));
        debugInfo.show();
    }

    // Event selection handler
    $('#eventSelect').change(function() {
        const selectedEvent = $(this).val();
        debugInfo.hide();
        
        if (selectedEvent) {
            loadContestants(selectedEvent);
        } else {
            contestantsSection.empty();
        }
    });

    function loadContestants(eventName) {
        showLoading();
        
        const url = `/judge/tabulation/contestants/${encodeURIComponent(eventName)}`;
        console.log('Fetching contestants from:', url);

        $.ajax({
            url: url,
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                console.log('Received response:', response);
                showDebug({
                    eventName: eventName,
                    response: response
                });
                displayContestants(response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showDebug({
                    error: error,
                    status: status,
                    response: xhr.responseText
                });
                contestantsSection.html(`
                    <div class="alert alert-danger">
                        Error loading contestants. Status: ${status}
                        <br>
                        Details: ${error}
                    </div>
                `);
            },
            complete: function() {
                hideLoading();
            }
        });
    }

    function displayContestants(contestants) {
        contestantsSection.empty();

        if (!Array.isArray(contestants) || contestants.length === 0) {
            contestantsSection.html(`
                <div class="alert alert-info">
                    No contestants found for this event.
                </div>
            `);
            return;
        }

        const contestantGrid = $('<div class="row g-4"></div>');

        contestants.forEach((contestant) => {
            const contestantCard = `
                <div class="col-md-6 col-lg-4">
                    <div class="contestant-card" data-contestant-id="${contestant.id}">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    ${contestant.cfname || ''} ${contestant.clname || ''}
                                </h5>
                                <div class="card-text">
                                    <p class="mb-1">
                                        <strong>Number:</strong> ${contestant.cnumber || 'N/A'}
                                    </p>
                                    <p class="mb-1">
                                        <strong>Category:</strong> ${contestant.category || 'N/A'}
                                    </p>
                                </div>
                                <button class="btn btn-primary mt-3 score-button w-100">
                                    Score Contestant
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            contestantGrid.append(contestantCard);
        });

        contestantsSection.append(contestantGrid);

        // Add click handler for contestant cards
        $('.score-button').click(function(e) {
            e.preventDefault();
            const card = $(this).closest('.contestant-card');
            const contestantId = card.data('contestant-id');
            
            // Remove active class from all cards
            $('.contestant-card').removeClass('active');
            // Add active class to selected card
            card.addClass('active');
            
            // Show scoring section for selected contestant
            $('.scoring-section').hide();
            $(`#scoring-${contestantId}`).show();
        });
    }
});
</script>
@endpush

<style>
/* Your existing styles plus these additions */
.contestant-card {
    transition: all 0.3s ease;
}

.contestant-card:hover {
    transform: translateY(-5px);
}

.contestant-card.active .card {
    border-color: #0d6efd;
    box-shadow: 0 0 0 1px #0d6efd;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

#loadingIndicator {
    padding: 2rem;
}

.card {
    height: 100%;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.card-body {
    padding: 1.5rem;
}

.score-button {
    transition: all 0.2s ease;
}

.score-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 4px rgba(13, 110, 253, 0.3);
}
</style>
@endsection