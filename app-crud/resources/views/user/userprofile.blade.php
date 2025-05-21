<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!--Include Head CDN-->
    @include('partials.headcdn')
    <!--End of Head CDN-->
    <link rel="stylesheet" href="/styles/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
  <!--Include Navbar Based on Role-->
  @if(Auth::user()->role ==='admin')
    @include('partials.adminnav')
  @else
    @include('partials.usernav')
  @endif
  <!--End of Include-->

<div class="container pt-2 flex-grow-1">
    <h1 class="text-center mt-4">Profile</h1>
    <div class="mt-4">
        <div class="row justify-content-center"> <div class="col-md-6"> <div class="card shadow-lg">
                    <div class="card-body">
                        <p><strong>Company Name:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
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