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

    <div class="container pt-2 flex-grow-1">
        <h1 class="text-center">Profile</h1>
        <div class="mt-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <p><strong>Employee Name:</strong> {{ $account->employeeName }}</p>
                            <p><strong>Email:</strong> {{ $account->email }}</p>
                            <p><strong>Company Name:</strong> {{ $account->company->companyName }}</p>
                            <p><strong>Designation:</strong> {{ $account->designation }}</p>
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
