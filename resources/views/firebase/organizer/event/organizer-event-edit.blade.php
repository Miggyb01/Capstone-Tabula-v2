@extends('firebase.organizer-app')

@section('content')

       
<div class="event-setup-form-container">
        <div class="event-setup-form-header  justify-content-center">
            <div class="event-icon-container  align-items-center">
                <i class="ri-calendar-todo-fill "></i>
                <span>Edit Event</span>
            </div>
        </div>

        <form action="{{ url('update-event/'.$key) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- First row -->
            <div class="event-form-row row  ">
                <div class="col">
                    <label for="eventName" class="form-label mt-1 ms-2">Events Name</label>
                    <input type="text" class="form-control " name="Event_name" id="eventName" value="{{$editdata['ename']}}">
                </div>
                <div class="col">
                    <label for="eventType" class="form-label ">Event Type</label>
                    <input type="text" class="form-control " name="Event_type" id="eventType" value="{{$editdata['etype']}}">
                </div>
                <div class="col">
                    <label for="chooseBanner" class="form-label">Choose Banner</label>
                    <input type="file" class="form-control" id="chooseBanner" name="Event_banner" value="{{$editdata['etype']}}">
                </div>
            </div>

            <!-- Event Description -->
            <div class="event-form-row row ">
                <label for="eventDescription" class="form-label ">Event Description</label>
                <textarea class="form-control"  name="Event_description" id="eventDescription" rows="3" required>{{ $editdata['edescription'] }}"></textarea>
            </div>

            <!-- Second row -->
            <div class="event-form-row row ">
                <div class="col">
                    <label for="venue" class="form-label form-label mt-1 ms-2 ">Venue</label>
                    <input type="text" class="form-control "  name="Event_venue" id="venue" value="{{$editdata['evenue']}}">
                </div>
                <div class="col">
                    <label for="organizer" class="form-label ">Organizer</label>
                    <input type="text" class="form-control"  name="Event_organizer" id="organizer" value="{{$editdata['eorganizer']}}">
                </div>
                <div class="col">
                    <label for="date" class="form-label ">Date</label>
                    <input type="date" class="form-control"  name="Event_date" id="date" value="{{$editdata['edate']}}">
                </div>
            </div>

            <!-- Start and End time -->
            <div class="event-form-row row mb-4 mt-4">
                <div class="col-md-6">
                    <label for="startTime" class="form-label mt-1 ms-2">Start</label>
                    <input type="time" class="form-control mt-1" name="Event_start" id="startTime" value="{{$editdata['estart']}}">
                </div>
                <div class="col-md-6">
                    <label for="endTime" class="form-label mt-1">End</label>
                    <input type="time" class="form-control mt-1" name="Event_end" id="endTime" value="{{$editdata['eend']}}">
                </div>
            </div>

            <!-- Buttons -->
            <div class="form-group text-center ">
                <button type="button" class="btn-cancel mr-2" onclick="window.location.href='{{ route('event-list') }}'">Cancel</but>
                <button type="submit" class="btn-add">Update</button>
              </div>
        </form>
    </div>

@endsection