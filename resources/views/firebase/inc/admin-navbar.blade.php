<div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex gap-3">
                <button class="toggle-btn" type="button">
                    <i class="ri-menu-line"></i>
                </button> 
            </div>
            <ul class="sidebar-nav  ">
                <li class="sidebar-item">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                        <i class="ri-dashboard-line"></i>
                        <span >Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('admin.calendar')}}" class="sidebar-link">
                        <i class="ri-calendar-todo-line"></i>
                        <span>Calendar</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse" 
                        data-bs-target="#event" aria-expanded="false" aria-controls="event">
                        <i class="ri-calendar-todo-fill"></i>
                        <span>Events</span>
                    </a>
                    <ul id="event" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="{{ route('admin.event.setup') }}" class="sidebar-link">Event Setup</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('admin.event.list') }}" class="sidebar-link">Event List</a>
                        </li>
                    </ul>                    
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#tabulation" aria-expanded="false" aria-controls="tabulation">
                        <i class="ri-file-chart-line"></i>
                        <span>Tabulation</span>
                    </a>
                    <ul id="tabulation" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse"
                                data-bs-target="#criteria" aria-expanded="false" aria-controls="criteria">
                                <i class="ri-survey-line"></i>
                                Criteria
                                
                            </a>
                            <ul id="criteria" class="sidebar-sub-dropdown list-unstyled collapse">
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.criteria.setup') }}" class="sidebar-link">Criteria Setup</a>
                                    
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.criteria.list') }}" class="sidebar-link">Criteria List</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul id="tabulation" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse"
                                data-bs-target="#contestant" aria-expanded="false" aria-controls="contestant">
                                <i class="ri-group-line"></i>
                                Contestant
                            </a>
                            <ul id="contestant" class="sidebar-sub-dropdown list-unstyled collapse">
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.contestant.setup') }}" class="sidebar-link">Contestant Setup</a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.contestant.list') }}" class="sidebar-link">Contestant List</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul id="tabulation" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse"
                                data-bs-target="#judge" aria-expanded="false" aria-controls="judge">
                                <i class="ri-scales-3-line"></i>
                                Judges
                            </a>
                            <ul id="judge" class="sidebar-sub-dropdown list-unstyled collapse">
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.judge.setup') }}" class="sidebar-link">Judges Setup</a>           
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('admin.judge.list') }}" class="sidebar-link">Judges List</a>
                                </li>
                            </ul>
                        </li>
                        <!-- Inside tabulation dropdown -->
                        <li class="sidebar-item">
                            <a href="{{ route('tabulation.scores') }}" class="sidebar-link">
                                <i class="ri-file-list-line"></i>
                                Scores
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">
                                <i class="ri-bar-chart-line"></i>
                                Result
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">    
                    <a href="#" class="sidebar-link">
                        <i class="ri-line-chart-line"></i>
                        <span>Report</span>
                    </a>
                </li>
            </ul>
        </aside>
        <div class="main" >
            <nav class="navbar navbar-expand px-4 py-3" style="border-bottom: 1px solid #F2F1EF;">
                <form action="#" class="d-none d-sm-inline-block">
                </form>
                <div class="navbar-collapse collapse">
                <div class="sidebar-logo ">
                    <a href="{{ route('dashboard') }}">
                    <img src="{{ asset('tabulaLOGO.png') }}" >
                    </a>
                </div>
                    <ul class="navbar-nav ms-auto">
                        <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <i class="ri-account-circle-line"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end rounded">
                                <span class="fw-bold">
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
    
    
    <script>
  const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});

</script>