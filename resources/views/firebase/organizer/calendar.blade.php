<!-- resources/views/firebase/tabulation/calendar.blade.php -->
@extends('firebase.app')

@section('content')
<div class="container-fluid p-4">
    <div class="row mb-4">
        <div class="col">
            <h2>Event Calendar</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css' rel='stylesheet' />
@endsection

@section('scripts')
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: @json($events ?? []),
        eventClick: function(info) {
            // Handle event click
        }
    });
    calendar.render();
});
</script>
@endsection