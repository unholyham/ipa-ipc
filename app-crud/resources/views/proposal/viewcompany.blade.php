<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $company->companyName }}</title>
    <!--Include Head CDN-->
    @include('partials.headcdn')
    <!--End of Head CDN-->
    <link rel="stylesheet" href="/styles/style.css">
</head>

<body class="d-flex flex-column min-vh-100 bg-white bg-gradient">
    <!--Include Navbar Based on Role-->
  @if(Auth::user()->designation === 'Contract Executive')
    @include('partials.cenav')
  @elseif (Auth::user()->designation === 'Assistant Contract Manager')
    @include('partials.acmnav')
  @elseif (Auth::user()->designation === 'Contract Manager')
    @include('partials.cmnav')
  @else
    @include('partials.adminnav')
  @endif
    <!--End of Include-->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-square"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container pt-2 flex-grow-1">
        <h1 class="text-center">Company Details</h1>
        <div class="row border-top border-start border-end p-1 text-bg-dark mt-4 rounded-top">
            <div class="col-md-3">
                <h6 class="subject">Company Name</h6>
            </div>
            <div class="col-md-3">
                <h6>{{ $company->companyName }}</h6>
            </div>
        </div>
        <div class="row border-top border-start border-end p-1">
            <div class="col-md-3">
                <h6 class="subject">Registration Number</h6>
            </div>
            <div class="col-md-3">
                <h6>{{ $company->registrationNumber }}</h6>
            </div>
        </div>
        <div class="row bg-light border-top border-start border-end p-1">
            <div class="col-md-3">
                <h6 class="subject">Company Email</h6>
            </div>
            <div class="col-md-3">
                <h6>{{ $company->companyEmail }}</h6>
            </div>
        </div>
        <div class="row border p-1">
            <div class="col-md-3">
                <h6 class="subject">Address</h6>
            </div>
            <div class="col-md-3">
                <h6>{{ $company->companyAddress }}</h6>
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
