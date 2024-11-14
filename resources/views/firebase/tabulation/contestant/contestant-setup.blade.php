@extends('firebase.app')

@section('content')
<div class="contestant-setup-form-container">
    <div class="event-setup-form-header  justify-content-center">
        <div class="event-icon-container  align-items-center">
            <i class="ri-group-line"></i>
            <span>Contestant Setup</span>
        </div>
    </div>

    <form action="{{ url('contestant-list') }}" method="POST">
        @csrf
        
        <!-- Event Dropdown -->
        <div class="contestant-form-row row">
            <div class="col">
                <label for="event" class="form-label mt-1 ms-2">Select Event</label>
                <select name="event_name" id="event" class="form-control" required>
                    <option value="" disabled selected>Select Event</option>
                    @foreach($events as $eventId => $event)
                        <option value="{{ $event['ename'] }}">{{ $event['ename'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <!-- First row -->
        <div class="contestant-form-row row">
            <div class="col">
                <label for="contestantFirstName" class="form-label mt-1 ms-2">First Name</label>
                <input type="text" class="form-control" name="Contestant_firstname" id="contestantFirstName" placeholder="First Name" required>
            </div>
            <div class="col">
                <label for="contestantMiddleName" class="form-label">Middle Name</label>
                <input type="text" class="form-control" name="Contestant_middlename" id="contestantMiddleName" placeholder="Middle Name" required>
            </div>
            <div class="col">
                <label for="contestantLastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="Contestant_lastname" id="contestantLastName" placeholder="Last Name" required>
            </div>
        </div>

        <!-- Second row -->
        <div class="contestant-form-row row">
            <div class="col">
                <label for="age" class="form-label mt-1 ms-2">Age</label>
                <input type="text" class="form-control" name="Contestant_age" id="age" placeholder="Age" required>
            </div>
            <div class="col">
                <label for="gender" class="form-label">Gender</label>
                <input type="text" class="form-control" name="Contestant_gender" id="gender" placeholder="Gender" required>
            </div>
        </div>

        <!-- Personal Background -->
        <div class="contestant-form-row row">
            <label for="contestantBackground" class="form-label">Personal Background</label>
            <textarea class="form-control" name="Contestant_background" id="contestantBackground" rows="3" required></textarea>
        </div>

        <!-- Buttons -->
        <div class="form-group text-center">
            <button type="button" class="btn-cancel" onclick="window.location.href='{{ route('dashboard') }}'">Cancel</button>
            <button type="submit" class="btn-add">Add</button>
        </div>
    </form>
</div>
@endsection
