@extends('firebase.layouts.admin-app')

@section('styles')
<style>
.status-dot {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 8px;
}

#calendar {
    background-color: white;
    padding: 15px;
    min-height: 700px;
    margin: 20px;
}

.card {
    margin: 20px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.fc-toolbar-title {
    font-size: 1.5em !important;
}

.fc-button {
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
}

.fc-button-active {
    background-color: #0a58ca !important;
    border-color: #0a58ca !important;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Calendar Column -->
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>

        <!-- Legend Column -->
        <div class="col-lg-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Legend</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <span class="status-dot" style="background-color: #ffa500;"></span>
                            Pending
                        </li>
                        <li class="mb-3">
                            <span class="status-dot" style="background-color: #28a745;"></span>
                            Confirmed
                        </li>
                        <li class="mb-3">
                            <span class="status-dot" style="background-color: #007bff;"></span>
                            Finished
                        </li>
                        <li class="mb-3">
                            <span class="status-dot" style="background-color: #6c757d;"></span>
                            Pencil
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    // For testing, add a sample event
    var sampleEvents = [
        {
            title: 'Test Event',
            start: '2024-03-15',
            status: 'confirmed'
        }
    ];

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 800,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        events: sampleEvents,
        eventDidMount: function(info) {
            const status = info.event.extendedProps.status;
            switch (status) {
                case 'pending':
                    info.el.style.backgroundColor = '#ffecb3';
                    info.el.style.borderColor = '#ffa500';
                    break;
                case 'confirmed':
                    info.el.style.backgroundColor = '#d4edda';
                    info.el.style.borderColor = '#28a745';
                    break;
                case 'finished':
                    info.el.style.backgroundColor = '#cce5ff';
                    info.el.style.borderColor = '#007bff';
                    break;
                case 'pencil':
                    info.el.style.backgroundColor = '#e2e3e5';
                    info.el.style.borderColor = '#6c757d';
                    break;
            }
        }
    });

    calendar.render();
});
</script>
@endsection