@extends('firebase.app')

@section('content')

       
<div class="contestant-setup-form-container">
        <div class="event-setup-form-header  justify-content-center">
            <div class="event-icon-container  align-items-center">
            <i class="ri-scales-3-line"></i>
                <span>Judge Setup</span>
            </div>
        </div>

        <form action="{{ url('judge-list') }}" method="POST">
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
            <div class="contestant-form-row row  ">
                <div class="col">
                    <label for="eventName" class="form-label mt-1 ms-2">First Name</label>
                    <input type="text" class="form-control " name="Judge_firstname" id="judgeFirstName" placeholder="First Name" required>
                </div>
                <div class="col">
                    <label for="eventType" class="form-label ">Middle Name</label>
                    <input type="text" class="form-control " name="Judge_middlename" id="judgeMiddleName" placeholder="Middle Name" required>
                </div>
                <div class="col">
                    <label for="eventType" class="form-label ">Last Name</label>
                    <input type="text" class="form-control " name="Judge_lastname" id="judgeLastName" placeholder="Last Name" required>
                </div>
            </div>
            
            <!-- Achievements -->
            <div class="contestant-form-row row ">
                <label for="eventDescription" class="form-label ">Achievements</label>
                <textarea class="form-control"  name="Judge_achievement" id="contestantBackground" rows="3" required></textarea>
            </div>

            <!-- Second row -->
            <div class="contestant-form-row row ">
                <div class="col">
                    <label for="venue" class="form-label form-label mt-1 ms-2 ">Username</label>
                    <input type="text" class="form-control "  name="Judge_username" id="judgeUsername" placeholder="Username" required>
                </div>
                <div class="col">
                    <label for="organizer" class="form-label form-label mt-1 ms-2 ">Password</label>
                    <input type="text" class="form-control"  name="Judge_password" id="judgePassword" placeholder="Password" required>
                </div>
            </div>

             <!-- Assign Event Dropdown -->
        
        <!-- <div class="contestant-form-row row">
            <label for="assignEvent" class="form-label">Assign Event</label>
            <select class="form-control" name="assign_event" id="assignEvent" required>
                <option value="" selected disabled>Select Event</option>
                <option value="event1">Event 1</option>
                <option value="event2">Event 2</option>
                <option value="event3">Event 3</option>
           
            </select>
        </div> -->

            <!-- Buttons -->
            <div class="form-group text-center ">
                <button type="button" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-add">Add</button>
              </div>
        </form>
    </div>

@endsection