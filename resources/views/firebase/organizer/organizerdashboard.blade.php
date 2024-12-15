@extends('firebase.layouts.organizer-app')


@section('content')

<main class="content px-3 py-4">
    <div class="container-fluid">
        <div class="mb-3">
            <h3 class="fw-bold fs-4 mb-1">Oraganizer Dashboard</h3>
            <div class="row">
            <div class="col-12 col-md-4">
                    <a href="{{ route('organizer.event.list') }}"  class="text-decoration-none">
                        <div class="card border-0">
                            <div class="card-body py-4">
                                <div class="icon-container mt-2">
                                    <i class="ri-calendar-todo-fill"></i>
                                </div>
                                <h2 class="mb-2 mt-4">Events</h2>
                                <h1 class="display-4 fw-bold text-end"> {{ $total_events }} </h1>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-4">
                    <a href="{{ route('organizer.judge.list') }}" class="text-decoration-none">
                        <div class="card border-0">
                            <div class="card-body py-4">   
                                <div class="icon-container mt-2">
                                        <i class="ri-scales-3-line"></i>
                                    </div>
                                    <h2 class="mb-1 mt-4">
                                        Judges
                                    </h2>
                                    <h1 class="display-4 fw-bold text-end"> {{ $total_judges }} </h1>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-4">
                    <a  href="{{ route('organizer.contestant.list') }}"   class="text-decoration-none">
                        <div class="card border-0">
                            <div class="card-body py-4">
                                <div class="icon-container mt-2">
                                        <i class="ri-group-line"></i>
                                        </div>
                                        <h2 class="mb-2 mt-4">
                                            Contestants
                                        </h2>
                                        <h1 class="display-4 fw-bold text-end"> {{ $total_contestants }} </h1>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <h3 class="fw-bold mt-3 fs-4 mb-1">Ongoing Events</h3>
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="card border-0">
                        <div class="card-body py-4">
                                <h1 class="display-6 fw-bold ">
                                        Date
                                </h1>
                                <h5 class=" fw-bolder">MONTH / YEAR</h5>
                                <h4 class="mt-3 fw-bolder"> Event name</h4>
                                <h6 class="mt-1 fw-bolder"> Event Time</h6>
                                <h6 class=" fw-bolder"> Event Venue</h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card border-0">
                        <div class="card-body py-4">
                                <h1 class="display-6 fw-bold ">
                                        Date
                                </h1>
                                <h5 class=" fw-bolder">MONTH / YEAR</h5>
                                <h4 class="mt-3 fw-bolder"> Event name</h4>
                                <h6 class="mt-1 fw-bolder"> Event Time</h6>
                                <h6 class=" fw-bolder"> Event Venue</h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card border-0">
                        <div class="card-body py-4">
                                <h1 class="display-6 fw-bold ">
                                        Date
                                </h1>
                                <h5 class=" fw-bolder">MONTH / YEAR</h5>
                                <h4 class="mt-3 fw-bolder"> Event name</h4>
                                <h6 class="mt-1 fw-bolder"> Event Time</h6>
                                <h6 class=" fw-bolder"> Event Venue</h6>
                        </div>
                    </div> 
                </div>
                <div class="col-12 col-md-3">
                    <div class="card border-0">
                        <div class="card-body py-4">
                                <h1 class="display-6 fw-bold ">
                                        Date
                                </h1>
                                <h5 class=" fw-bolder">MONTH / YEAR</h5>
                                <h4 class="mt-3 fw-bolder"> Event name</h4>
                                <h6 class="mt-1 fw-bolder" > Event Time</h6>
                                <h6 class=" fw-bolder"> Event Venue</h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <h3 class="fw-bold mt-3 fs-4 mb-1">Upcoming Events</h3>
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="card border-0">
                        <div class="card-body py-4">
                                <h1 class="display-6 fw-bold ">
                                        Date
                                </h1>
                                <h5 class=" fw-bolder">MONTH / YEAR</h5>
                                <h4 class="mt-3 fw-bolder"> Event name</h4>
                                <h6 class="mt-1 fw-bolder"> Event Time</h6>
                                <h6 class=" fw-bolder"> Event Venue</h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card border-0">
                        <div class="card-body py-4">
                                <h1 class="display-6 fw-bold ">
                                        Date
                                </h1>
                                <h5 class=" fw-bolder">MONTH / YEAR</h5>
                                <h4 class="mt-3 fw-bolder"> Event name</h4>
                                <h6 class="mt-1 fw-bolder"> Event Time</h6>
                                <h6 class=" fw-bolder"> Event Venue</h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card border-0">
                        <div class="card-body py-4">
                                <h1 class="display-6 fw-bold ">
                                        Date
                                </h1>
                                <h5 class=" fw-bolder">MONTH / YEAR</h5>
                                <h4 class="mt-3 fw-bolder"> Event name</h4>
                                <h6 class="mt-1 fw-bolder"> Event Time</h6>
                                <h6 class=" fw-bolder"> Event Venue</h6>
                        </div>
                    </div> 
                </div>
                <div class="col-12 col-md-3">
                    <div class="card border-0">
                        <div class="card-body py-4">
                                <h1 class="display-6 fw-bold ">
                                        Date
                                </h1>
                                <h5 class=" fw-bolder">MONTH / YEAR</h5>
                                <h4 class="mt-3 fw-bolder"> Event name</h4>
                                <h6 class="mt-1 fw-bolder" > Event Time</h6>
                                <h6 class=" fw-bolder"> Event Venue</h6>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</main>

@endsection