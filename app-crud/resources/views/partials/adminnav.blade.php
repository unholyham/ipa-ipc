<div class="sticky-top">
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container-fluid">
    <div class="d-flex align-items-center">
      <a class="navbar-brand" href="{{route('proposal.index')}}">
        <img src="/images/SSB_Logo.jpg" alt="SSB" height="45">
      </a>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link {{ Route::currentRouteNamed('proposal.index') ? 'active' : '' }}" href="{{route('proposal.index')}}">HOME</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ Route::currentRouteNamed('admin.users.pending') ? 'active' : '' }}" href="{{route('admin.users.pending')}}">MANAGE ACCOUNTS</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">CONTACT US</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container-fluid">
<!--Toolbar Nav-->
  <div class="row justify-content-between align-items-center bg-gradient" id="toolbar_nav">
    <div class="col-auto">
      <p class="m-0">Welcome, <a class="profile_link" href="{{route('profile.view')}}"><span class="text-warning lead"><strong>{{ Auth::user()->name }}</strong></span></a></p>
    </div>
    <div class="col-auto">
      <div class="row">
        <div class="col p-0">
          <div class="dropdown">
            <button type="button" class="btn btn-info nav_icon position-relative" style="font-size:15px;" id="notificationsDropdownToggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-bell-fill"></i>
              <span class="position-absolute top-100 start-0 translate-middle badge rounded-pill bg-danger" id="unread-notifications-count" style="display: none;">
                0 <span class="visually-hidden">unread messages</span>
              </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdownToggle" id="notifications-list" style="max-height: 300px; overflow-y: auto; min-width: 300px;">
              <li class="dropdown-header">Notifications</li>
              <li><hr class="dropdown-divider"></li>
              <li class="text-center p-2 text-muted" id="notifications-loading">Loading...</li>
              <li class="text-center p-2 text-muted" id="no-notifications" style="display: none;">No unread notifications.</li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item text-center text-primary" href="#" id="mark-all-as-read-btn">Mark all as read</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col p-0">
          <form method="post" action="{{ route('logout') }}">
          @csrf
            <button type="submit" class="btn btn-danger nav_icon" style="font-size:15px;"><i class="bi bi-power"></i></button>
          </form>
        </div>
      </div>
    </div>
  </div>
<!--End of Toolbar Nav-->
</div>
</div>
