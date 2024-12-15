@extends('firebase.layouts.organizer-app')

@section('content')
<div class="container-fluid-calendar-judge-user">
    <div class="row-calendar-judge-user">
        <div class="col-12-calendar-judge-user">
            <div class="card-calendar-judge-user">
                <div class="card-body-calendar-judge-user">
                    <div id='calendar-judge-user'></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
    .container-fluid-calendar-judge-user {
        padding: 20px;
    }

    .row-calendar-judge-user {
        margin: 0;
    }

    .col-12-calendar-judge-user {
        padding: 0;
    }

    .title-calendar-judge-user {
        color: #333;
        margin-left: 20px;
    }

    .card-calendar-judge-user {
        background: white;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin: 20px;
    }

    .card-body-calendar-judge-user {
        padding: 20px;
    }

    #calendar-judge-user {
        background-color: white;
        padding: 15px;
        min-height: 700px;
    }

    /* FullCalendar Custom Styles */
    .calendar-toolbar-calendar-judge-user {
        margin-bottom: 1rem;
    }

    .calendar-title-calendar-judge-user {
        font-size: 1.5em !important;
    }

    .calendar-button-calendar-judge-user {
        background-color: #3155FE !important;
        border-color: #3155FE !important;
        color: white !important;
    }

    .calendar-button-active-calendar-judge-user {
        background-color: #2a46e8 !important;
        border-color: #2a46e8 !important;
    }

    .calendar-event-calendar-judge-user {
        background-color: #3155FE !important;
        border-color: #3155FE !important;
        color: white !important;
        cursor: pointer;
    }

    .calendar-event-hover-calendar-judge-user:hover {
        opacity: 0.9;
    }

    .calendar-loading-calendar-judge-user {
        text-align: center;
        padding: 20px;
    }

    .calendar-error-calendar-judge-user {
        color: #dc3545;
        text-align: center;
        padding: 20px;
    }
    
</style>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar-judge-user');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 800,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        // Add specific classes to FullCalendar elements
        classNames: {
            toolbar: 'calendar-toolbar-calendar-judge-user',
            title: 'calendar-title-calendar-judge-user',
            button: 'calendar-button-calendar-judge-user',
            buttonActive: 'calendar-button-active-calendar-judge-user',
            event: 'calendar-event-calendar-judge-user',
            eventHover: 'calendar-event-hover-calendar-judge-user'
        },
        events: function(info, successCallback, failureCallback) {
            // Add loading indicator
            const loadingEl = document.createElement('div');
            loadingEl.className = 'calendar-loading-calendar-judge-user';
            loadingEl.textContent = 'Loading events...';
            calendarEl.appendChild(loadingEl);

            fetch("{{ route('judge.calendar.events') }}")
                .then(response => response.json())
                .then(data => {
                    const events = data.map(event => ({
                        title: event.ename,
                        start: event.edate,
                        allDay: true,
                        className: 'calendar-event-calendar-judge-user'
                    }));
                    successCallback(events);
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                    // Show error message
                    const errorEl = document.createElement('div');
                    errorEl.className = 'calendar-error-calendar-judge-user';
                    errorEl.textContent = 'Failed to load events';
                    calendarEl.appendChild(errorEl);
                    failureCallback(error);
                })
                .finally(() => {
                    // Remove loading indicator
                    const loadingEl = calendarEl.querySelector('.calendar-loading-calendar-judge-user');
                    if (loadingEl) {
                        loadingEl.remove();
                    }
                });
        },
        eventDidMount: function(info) {
            // Add hover effect class
            info.el.classList.add('calendar-event-hover-calendar-judge-user');
        }
    });

    calendar.render();
});
</script>
