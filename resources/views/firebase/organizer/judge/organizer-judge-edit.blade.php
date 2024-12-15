@extends('firebase.layouts.organizer-app')

@section('content')
<div class="contestant-setup-form-container">
    <div class="event-setup-form-header justify-content-center">
        <div class="event-icon-container align-items-center">
            <i class="ri-group-line"></i>
            <span>OrganizerJudge Edit</span>
        </div>
    </div>

    @if(session('status'))
    <div class="alert alert-success">
        {!! session('status') !!}
    </div>
    @endif

    <form action="{{ route('organizer.judge.update', $id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="contestant-form-row row">
            <div class="col">
                <label for="event" class="form-label mt-1 ms-2">Assign to Event</label>
                <select name="event_name" id="event" class="form-control" required>
                    <option value="" disabled>Events</option>
                    @foreach($events as $eventId => $event)
                        <option value="{{ $event['ename'] }}" 
                            {{ $editdata['event_name'] == $event['ename'] ? 'selected' : '' }}>
                            {{ $event['ename'] }}
                        </option>
                    @endforeach
                </select>
            </div>  
        </div>

        <!-- First row -->
        <div class="contestant-form-row row">
            <div class="col">
                <label for="eventName" class="form-label mt-1 ms-2">First Name</label>
                <input type="text" class="form-control" name="Judge_firstname" value="{{$editdata['jfname']}}" required>
            </div>
            <div class="col">
                <label for="eventType" class="form-label">Middle Name</label>
                <input type="text" class="form-control" name="Judge_middlename" value="{{$editdata['jmname']}}" required>
            </div>
            <div class="col">
                <label for="eventType" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="Judge_lastname" value="{{$editdata['jlname']}}" required>
            </div>
        </div>
        
        <!-- Achievements -->
        <div class="contestant-form-row row">
            <label for="eventDescription" class="form-label">Achievements</label>
            <textarea class="form-control" name="Judge_achievement" rows="3" required>{{$editdata['jachievement']}}</textarea>
        </div>

        <!-- Credentials row -->
        <div class="contestant-form-row row">
            <div class="col">
                <label for="username" class="form-label mt-1 ms-2">Username</label>
                <input type="text" class="form-control bg-light" value="{{$editdata['jusername']}}" readonly>
                <input type="hidden" name="jusername" value="{{$editdata['jusername']}}">
            </div>
            <div class="col">
                <label for="password" class="form-label mt-1 ms-2">Password</label>
                <div class="input-group">
                    <input type="text" class="form-control bg-light" value="{{$editdata['jpassword']}}" readonly>
                    <input type="hidden" name="jpassword" value="{{$editdata['jpassword']}}">
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="form-group text-center mt-4">
            <button type="button" class="btn-cancel" onclick="window.location.href='{{ route('organizer.judge.list') }}'">Cancel</button>
            <button type="submit" class="btn-add">Update</button>
            <form action="{{ route('organizer.judge.reset-password', $id) }}" method="GET" style="display: inline;">
                <button type="submit" class="btn-update" onclick="return confirm('Are you sure you want to reset this judge\'s password?')">
                    Reset Password
                </button>
            </form>
        </div>
    </form>
</div>
@endsection