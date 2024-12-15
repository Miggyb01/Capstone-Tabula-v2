@extends('firebase.layouts.admin-app')

@section('content')

       
<div class="contestant-setup-form-container">
        <div class="event-setup-form-header  justify-content-center">
            <div class="event-icon-container  align-items-center">
                <i class="ri-group-line"></i>
                <span>Contestant Edit</span>
            </div>
        </div>

        <form action="{{ route('admin.contestant.update', $key) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="contestant-form-row row">
                <div class="col">
                    <label for="event" class="form-label mt-1 ms-2">Assign to Event</label>
                    <select name="event_name" id="event" class="form-control" required>
                        <option value="" disabled>Events</option>
                        @foreach($events as $eventId => $event)
                            <option value="{{ $event['ename'] }}" 
                                {{ $editdata['ename'] == $event['ename'] ? 'selected' : '' }}>
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
                    <input type="text" class="form-control " name="Contestant_firstname" id="contestantFirstName" value="{{$editdata['cfname']}}" required>
                </div>
                <div class="col">
                    <label for="eventType" class="form-label ">Middle Name</label>
                    <input type="text" class="form-control " name="Contestant_middlename" id="contestantMiddleName" value="{{$editdata['cmname']}}" required>
                </div>
                <div class="col">
                    <label for="eventType" class="form-label ">Last Name</label>
                    <input type="text" class="form-control " name="Contestant_lastname" id="contestantLastName" value="{{$editdata['clname']}}" required>
                </div>
            </div>
            
            <!-- Second row -->
            <div class="contestant-form-row row ">
                <div class="col">
                    <label for="venue" class="form-label form-label mt-1 ms-2 ">Age</label>
                    <input type="text" class="form-control "  name="Contestant_age" id="age" placeholder="Age" value="{{$editdata['cage']}}" required>
                </div>
                <div class="col">
                    <label for="organizer" class="form-label ">Gender</label>
                    <input type="text" class="form-control"  name="Contestant_gender" id="gender" value="{{$editdata['cgender']}}" required>
                </div>
            </div>
           
            <!-- Personal Background -->
            <div class="contestant-form-row row ">
                <label for="eventDescription" class="form-label ">Personal Background</label>
                <textarea class="form-control" name="Contestant_background" id="contestantBackground" rows="3" required>{{ $editdata['cbackground'] }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="form-group text-center ">
                <button type="button" class="btn-cancel" onclick="window.location.href='{{ route('admin.contestant.list') }}'">Cancel</button>
                <button type="submit" class="btn-add">Update</button>
              </div>
        </form>
    </div>

@endsection