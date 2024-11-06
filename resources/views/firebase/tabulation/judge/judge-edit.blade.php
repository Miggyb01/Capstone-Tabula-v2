@extends('firebase.app')

@section('content')

       
<div class="contestant-setup-form-container">
        <div class="event-setup-form-header  justify-content-center">
            <div class="event-icon-container  align-items-center">
                <i class="ri-group-line"></i>
                <span>Judge Edit</span>
            </div>
        </div>

        <form action="{{ url('update-judge/'.$key) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="contestant-form-row row">
                <div class="col">
                    <label for="event" class="form-label mt-1 ms-2">Assign to Event</label>
                    <select name="event_name" id="event" class="form-control" required>
                        <option value="" disabled>Events</option>
                        @foreach($events as $eventId => $event)
                        <option value="{{ $eventId }}" 
                            {{ isset($editdata['event_name']) && $editdata['event_name'] == $eventId ? 'selected' : '' }}>
                            {{ $event['ename'] }}
                        </option>
                        @endforeach
                    </select>
                </div>  
            </div>


             <!-- First row -->
            <div class="contestant-form-row row  ">
                <div class="col">
                    <label for="eventName" class="form-label mt-1 ms-2">First Name</label>
                    <input type="text" class="form-control " name="Judge_firstname" id="judgeFirstName" value="{{$editdata['jfname']}}" required>
                </div>
                <div class="col">
                    <label for="eventType" class="form-label ">Middle Name</label>
                    <input type="text" class="form-control " name="Judge_middlename" id="judgeMiddleName" value="{{$editdata['jmname']}}" required>
                </div>
                <div class="col">
                    <label for="eventType" class="form-label ">Last Name</label>
                    <input type="text" class="form-control " name="Judge_lastname" id="judgeLastName" value="{{$editdata['jlname']}}" required>
                </div>
            </div>
            
            <!-- Achievements -->
            <div class="contestant-form-row row ">
                <label for="eventDescription" class="form-label ">Achievements</label>
                <textarea class="form-control"  name="Judge_achievement" id="contestantBackground" rows="3" value="{{$editdata['jachievement']}}" required></textarea>
            </div>

            <!-- Second row -->
            <div class="contestant-form-row row ">
                <div class="col">
                    <label for="venue" class="form-label form-label mt-1 ms-2 ">Username</label>
                    <input type="text" class="form-control "  name="Judge_username" id="judgeUsername" value="{{$editdata['jusername']}}" required>
                </div>
                <div class="col">
                    <label for="organizer" class="form-label form-label mt-1 ms-2 ">Password</label>
                    <input type="text" class="form-control"  name="Judge_password" id="judgePassword" value="{{$editdata['jpassword']}}" required>
                </div>
            </div>


            <!-- Buttons -->
            <div class="form-group text-center ">
                <button type="button" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-add">Update</button>
              </div>
        </form>
    </div>

@endsection