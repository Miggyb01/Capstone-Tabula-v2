<div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex gap-3">
                <button class="toggle-btn" type="button">
                    <i class="ri-menu-line"></i>
                </button> 
            </div>
            <ul class="sidebar-nav  ">
                <li class="sidebar-item">
                    <a href="{{ route('judge.dashboard') }}" class="sidebar-link">
                        <i class="ri-dashboard-line"></i>
                        <span >Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('judge.calendar') }}" class="sidebar-link">
                        <i class="ri-calendar-todo-line"></i>
                        <span>Calendar</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('judge.tabulation') }}" class="sidebar-link">
                        <i class="ri-artboard-line"></i>
                        <span>Tabulation</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('judge.score') }}" class="sidebar-link">
                        <i class="ri-file-list-line"></i>
                        <span>Scores</span>
                    </a>
                </li>
                <li class="sidebar-item">    
                    <a href="{{ route('judge.report') }}" class="sidebar-link">
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
                    <a href="{{ route('judge.dashboard') }}">
                    <img src="{{ asset('tabulaLOGO.png') }}" >
                    </a>
                </div>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-link dropdown-toggle d-flex align-items-center">
                                <i class="ri-account-circle-line me-1"></i>
                                <span>{{ Session::get('user')['name'] }} (Judge)</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end rounded">
                                <div class="dropdown-header">
                                    <span class="fw-bold">Judge Account</span>
                                    <div class="small text-muted">{{ Session::get('user')['username'] }}</div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item">
                                    <i class="ri-user-settings-line me-2"></i>Profile Settings
                                </a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="ri-logout-box-line me-2"></i>Logout
                                    </button>
                                </form>
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
