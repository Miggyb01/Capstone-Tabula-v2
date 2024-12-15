@extends('firebase.layouts.organizer-app')

@section('content')
<div class="contestant-setup-form-container">
    <div class="event-setup-form-header  justify-content-center">
        <div class="event-icon-container  align-items-center">
            <i class="ri-scales-3-line"></i>
            <span>Organizer Judge Setup</span>
        </div>
    </div>
    
    @if(session('status'))
    <div class="alert alert-success">
        {!! session('status') !!}
    </div>
    @endif

    <form action="{{ route('organizer.judge.store') }}" method="POST">
        @csrf
        <div class="contestant-form-row row">
            <div class="col">
                <label for="event" class="form-label mt-1 ms-2">Assign to Event</label>
                <select name="event_name" id="event" class="form-control" required>
                    <option value="" disabled selected>Event</option>
                    @foreach($events as $eventId => $event)
                        <option value="{{ $event['ename'] }}">{{ $event['ename'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- First row -->
        <div class="contestant-form-row row">
            <div class="col">
                <label for="eventName" class="form-label mt-1 ms-2">First Name</label>
                <input type="text" class="form-control" name="Judge_firstname" id="judgeFirstName" placeholder="First Name" required>
            </div>
            <div class="col">
                <label for="eventType" class="form-label">Middle Name</label>
                <input type="text" class="form-control" name="Judge_middlename" id="judgeMiddleName" placeholder="Middle Name" required>
            </div>
            <div class="col">
                <label for="eventType" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="Judge_lastname" id="judgeLastName" placeholder="Last Name" required>
            </div>
        </div>
        
        <!-- Achievements -->
        <div class="contestant-form-row row">
            <label for="eventDescription" class="form-label">Achievements</label>
            <textarea class="form-control" name="Judge_achievement" id="contestantBackground" rows="3" required></textarea>
        </div>

        <!-- Buttons -->
        <div class="form-group text-center">
            <button type="button" class="btn-cancel" onclick="window.location.href='{{ route('organizer.dashboard') }}'">Cancel</button>
            <button type="submit" class="btn-add">Add</button>
        </div>
    </form>
</div>
@endsection