<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
  <div class="container-fluid">
    <div class="d-flex align-items-center">
      <a class="navbar-brand" href="{{route('proposal.index')}}">
        <img src="/images/SSB_Logo.jpg" alt="Your Logo" height="70">
      </a>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle active" href="#" id="actionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            ACTIONS
          </a>
          <ul class="dropdown-menu" aria-labelledby="actionDropdown">
            <li><a class="dropdown-item" href="{{route('proposal.index')}}">Technical Proposal Submissions</a></li>
            <li><a class="dropdown-item" href="{{route('profile.view')}}">View Profile</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('proposal.create')}}">SUBMIT PROPOSAL</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">WHAT WE DO</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">CONTACT US</a>
        </li>
      </ul>
    </div>
  </div>
</nav>