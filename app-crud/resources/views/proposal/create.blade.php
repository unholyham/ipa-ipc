<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Technical Proposal Form</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/nodeJS@1.0.0/index.min.js"></script>
    
    <link rel="stylesheet" href="/styles/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
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
                    <a class="nav-link dropdown-toggle" href="#" id="vendorsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        VENDORS
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="vendorsDropdown">
                        <li><a class="dropdown-item" href="{{route('proposal.index')}}">Technical Proposal Submissions</a></li>
                        <li><a class="dropdown-item" href="{{route('profile.view')}}">Profile</a></li>
                    </ul>
                </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{route('proposal.create')}}">SUBMIT PROPOSAL</a>
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

<div class="container pt-2">
    <h1 class="text-center mt-4">Technical Proposal Form</h1>
    <div class="mt-4">
        <form method="post" action="{{route('proposal.store')}}" enctype="multipart/form-data">
        @csrf
        @if ($errors->any())
          <div class="alert alert-danger">
            <strong>Whoops! Something went wrong.</strong>
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
            <div class="form-floating mb-3">
                <input type="text" class="form-control @error('projectTitle') is-invalid @enderror" name="projectTitle" placeholder="Project Title*" value="{{ old('projectTitle') }}" id="project_title">
                <label for="project_title">Project Title*</label>
                @error('projectTitle')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control @error('projectNumber') is-invalid @enderror" name="projectNumber" placeholder="Project Number" value="{{ old('projectNumber') }}" id="project_number">
                <label for="project_number">Project Number</label>
                @error('projectNumber')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control @error('region') is-invalid @enderror" name="region" placeholder="Region*" value="{{ old('region') }}" id="project_region">
                <label for="project_region">Region*</label>
                @error('region')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control @error('preparedBy') is-invalid @enderror" name="preparedBy" placeholder="Prepared By(Full Name as per IC)*" value="{{ old('preparedBy') }}" id="project_preparedBy">
                <label for="project_preparedBy">Prepared By(Full Name as per IC)*</label>
                @error('preparedBy')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control @error('mainContractor') is-invalid @enderror" name="mainContractor" placeholder="Main Contractor Name*" value="{{ old('mainContractor') }}" id="project_mainContractor">
                <label for="project_mainContractor">Main Contractor Name*</label>
                @error('mainContractor')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
            </div>
          <div>
            <label for="#tpLabel">Technical Proposal Document*</label>
            <input type="file" class="form-control" name="pathToTP" id="tpLabel">
            <p>Max filesize: 20MB</p>
          </div>
          <div class="mt-3">
            <button type="button" class="form-control bg-primary text-light" data-bs-toggle="modal" data-bs-target="#confirmSubmitModal">
                Submit
            </button>
          </div>
          <div class="mb-1">
            <input type="reset" class="form-control bg-light" value="Reset">
          </div>
          <div class="modal fade" id="confirmSubmitModal" tabindex="-1" aria-labelledby="confirmSubmitModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmSubmitModalLabel">Confirm Submission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to submit this technical proposal?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="realSubmitButton">Submit Proposal</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
</div>    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
</body>
</html>