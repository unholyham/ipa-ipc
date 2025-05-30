<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!--Include Head CDN-->
    @include('partials.headcdn')
    <!--End of Head CDN-->
    <link rel="stylesheet" href="/styles/style.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-white bg-gradient">
  <!--Include Navbar Based on Role-->
  @if(Auth::user()->role ==='admin')
    @include('partials.adminnav')
  @else
    @include('partials.usernav')
  @endif
  <!--End of Include-->

<div class="container pt-2 flex-grow-1">
    <h1 class="text-center">Edit Profile</h1>
    <div>
        <form method="post" action="{{route('profile.update')}}" enctype="multipart/form-data">
        @method('PUT')
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
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" value="{{ $user->name }}" id="name">
                <label for="name">Company Name</label>
                @error('name')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ $user->email }}" id="email">
                <label for="email">Email</label>
                @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
            </div>
          <div class="mt-3">
            <button type="button" class="btn btn-primary text-light w-100" data-bs-toggle="modal" data-bs-target="#confirmSubmitModal">
                Save
            </button>
          </div>
          <div class="modal fade" id="confirmSubmitModal" tabindex="-1" aria-labelledby="confirmSubmitModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmSubmitModalLabel">Confirm Submission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to update your profile information?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="realSubmitButton">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
</div>    
<!--Include Footer-->
@include('partials.footer')
<!--End of Include-->

<!--Include Body CDN-->
@include('partials.bodycdn')
<!--End of Body CDN-->
</body>
</html>