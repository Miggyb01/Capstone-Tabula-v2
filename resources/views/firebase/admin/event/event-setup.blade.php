@extends('firebase.layouts.admin-app')
@section('content')

       
<div class="event-setup-form-container">
        <div class="event-setup-form-header  justify-content-center">
            <div class="event-icon-container  align-items-center">
                <i class="ri-calendar-todo-fill "></i>
                <span>Event Setup</span>
            </div>
        </div>


        <form action="{{ route('admin.event.store') }}" method="POST">
            
            @csrf
            
            <!-- First row -->
            <div class="event-form-row row  ">
                <div class="col">
                    <label for="eventName" class="form-label mt-1 ms-2">Events Name</label>
                    <input type="text" class="form-control " name="Event_name" id="eventName" placeholder="Events Name" required>
                </div>
                <!-- In event-setup.blade.php, replace the Event Type input -->
                <div class="col">
                    <label for="eventType" class="form-label">Event Type</label>
                    <select class="form-control" id="eventTypeSelect" onchange="checkEventType(this.value)">
                        <option value="" selected disabled>Select Event Type</option>
                        <option value="Beauty Pageants">Beauty Pageants</option>
                        <option value="Talent Shows">Talent Shows</option>
                        <option value="Singing Competitions">Singing Competitions</option>
                        <option value="Dance Competitions">Dance Competitions</option>
                        <option value="Sports Events">Sports Events</option>
                        <option value="custom">Other (Specify)</option>
                    </select>
                    
                    <!-- Hidden input for custom event type -->
                    <input type="text" class="form-control mt-2" name="Event_type" id="customEventType" 
                        placeholder="Enter custom event type" style="display: none;">
                </div>

                <div class="col">
                    <label for="chooseBanner" class="form-label ">Choose Banner</label>
                    <input type="file" class="form-control-file " name="Event_banner"  id="chooseBanner" required>
                </div>
            </div>

            <!-- Event Description -->
            <div class="event-form-row row ">
                <label for="eventDescription" class="form-label ">Event Description</label>
                <textarea class="form-control"  name="Event_description" id="eventDescription" rows="3" required></textarea>
            </div>

            <!-- Second row -->
            <div class="event-form-row row ">
                <div class="col">
                    <label for="venue" class="form-label form-label mt-1 ms-2 ">Venue</label>
                    <input type="text" class="form-control "  name="Event_venue" id="venue" placeholder="Venue" required>
                </div>
                <div class="col">
                    <label for="organizer" class="form-label ">Organizer</label>
                    <input type="text" class="form-control"  name="Event_organizer" id="organizer" placeholder="Organizer" required>
                </div>
                <div class="col">
                    <label for="date" class="form-label ">Date</label>
                    <input type="date" class="form-control"  name="Event_date" id="date" required>
                </div>
            </div>

            <!-- Start and End time -->
            <div class="event-form-row row mb-4 mt-4">
                <div class="col-md-6">
                    <label for="startTime" class="form-label mt-1 ms-2">Start</label>
                    <input type="time" class="form-control mt-1" name="Event_start" id="startTime" required>
                </div>
                <div class="col-md-6">
                    <label for="endTime" class="form-label mt-1">End</label>
                    <input type="time" class="form-control mt-1" name="Event_end" id="endTime" required>
                </div>
            </div>

            <!-- Buttons -->
            <div class="form-group text-center ">
                <button type="button" class="btn-cancel" onclick="window.location.href='{{ route('admin.dashboard') }}'">Cancel</but>
                <button type="submit" class="btn-add">Add</button>
              </div>
        </form>
    </div>

<script>
    function checkEventType(value) {
        const customInput = document.getElementById('customEventType');
        if (value === 'custom') {
            customInput.style.display = 'block';
            customInput.required = true;
            customInput.value = '';
        } else {
            customInput.style.display = 'none';
            customInput.required = false;
            customInput.value = value;
        }
    }s

    // Initialize the hidden input with the select value
    document.addEventListener('DOMContentLoaded', function() {
        const selectElement = document.getElementById('eventTypeSelect');
        const customInput = document.getElementById('customEventType');
        if (selectElement.value && selectElement.value !== 'custom') {
            customInput.value = selectElement.value;
        }
    });
</script>

@endsection