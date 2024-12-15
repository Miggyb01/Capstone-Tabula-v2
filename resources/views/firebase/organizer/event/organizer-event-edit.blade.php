
@extends('firebase.layouts.organizer-app')

@section('content')

       
<div class="event-setup-form-container">
        <div class="event-setup-form-header  justify-content-center">
            <div class="event-icon-container  align-items-center">
                <i class="ri-calendar-todo-fill "></i>
                <span>OrganizerEdit Event</span>
            </div>
        </div>

        @if(session('status'))
    <div class="alert alert-success">
        {!! session('status') !!}
    </div>
    @endif
        <form action="{{ route('organizer.event.update', $key) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            
            <!-- First row -->
            <div class="event-form-row row  ">
                <div class="col">
                    <label for="eventName" class="form-label mt-1 ms-2">Events Name</label>
                    <input type="text" class="form-control " name="Event_name" id="eventName" value="{{$editdata['ename']}}">
                </div>
                <!-- In event-edit.blade.php, replace the Event Type input -->
                <div class="col">
                    <label for="eventType" class="form-label">Event Type</label>
                    <select class="form-control" id="eventTypeSelect" onchange="checkEventType(this.value)">
                        <option value="" disabled>Select Event Type</option>
                        <option value="Beauty Pageants" {{ $editdata['etype'] === 'Beauty Pageants' ? 'selected' : '' }}>Beauty Pageants</option>
                        <option value="Talent Shows" {{ $editdata['etype'] === 'Talent Shows' ? 'selected' : '' }}>Talent Shows</option>
                        <option value="Singing Competitions" {{ $editdata['etype'] === 'Singing Competitions' ? 'selected' : '' }}>Singing Competitions</option>
                        <option value="Dance Competitions" {{ $editdata['etype'] === 'Dance Competitions' ? 'selected' : '' }}>Dance Competitions</option>
                        <option value="Sports Events" {{ $editdata['etype'] === 'Sports Events' ? 'selected' : '' }}>Sports Events</option>
                        <option value="custom" {{ !in_array($editdata['etype'], ['Beauty Pageants', 'Talent Shows', 'Singing Competitions', 'Dance Competitions', 'Sports Events']) ? 'selected' : '' }}>Other (Specify)</option>
                    </select>
                    
                    <!-- Hidden input for custom event type -->
                    <input type="text" class="form-control mt-2" name="Event_type" id="customEventType" 
                        value="{{ $editdata['etype'] }}" 
                        style="display: {{ !in_array($editdata['etype'], ['Beauty Pageants', 'Talent Shows', 'Singing Competitions', 'Dance Competitions', 'Sports Events']) ? 'block' : 'none' }};">
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
            <div class="form-group text-center">
                <button type="button" class="btn-cancel mr-2" onclick="window.location.href='{{ route('organizer.event.list') }}'">Cancel</button>
                <button type="submit" class="btn-add">Update</button>
            </div>
        </form>
    </div>

<script>
    function checkEventType(value) {
        const customInput = document.getElementById('customEventType');
        if (value === 'custom') {
            customInput.style.display = 'block';
            customInput.required = true;
        } else {
            customInput.style.display = 'none';
            customInput.required = false;
            customInput.value = value;
        }
    }

    // Initialize the form on load
    document.addEventListener('DOMContentLoaded', function() {
        const selectElement = document.getElementById('eventTypeSelect');
        checkEventType(selectElement.value);
    });
</script>
@endsection