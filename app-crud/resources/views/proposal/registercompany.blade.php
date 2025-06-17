<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interim Payment Application Form</title>
    <!--Include Head CDN-->
    @include('partials.headcdn')
    <!--End of Head CDN-->
    <link rel="stylesheet" href="/styles/style.css">
</head>

<body class="d-flex flex-column min-vh-100 bg-white bg-gradient">
    <!--Include Navbar Based on Role-->
    @if (Auth::user()->role->roleName === 'admin')
        @include('partials.adminnav')
    @else
        @include('partials.usernav')
    @endif
    <!--End of Include-->
    <div class="container pt-2 flex-grow-1">
        <h1 class="text-center">Company Registration Form</h1>
        <div class="mt-4">
            <form method="post" action="{{ route('company.store') }}" enctype="multipart/form-data" id="companyForm">
                @csrf

                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('companyName') is-invalid @enderror"
                        name="companyName" placeholder="Company Name" value="{{ old('companyName') }}"
                        id="companyName">
                    <label for="companyName">Company Name</label>
                    @error('companyName')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('registrationNumber') is-invalid @enderror"
                        name="registrationNumber" placeholder="Company Registration Number" value="{{ old('registrationNumber') }}"
                        id="registrationNumber">
                    <label for="registrationNumber">Company Registration Number</label>
                    @error('registrationNumber')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('companyEmail') is-invalid @enderror"
                        name="companyEmail" placeholder="Company Email" value="{{ old('companyEmail') }}"
                        id="companyEmail">
                    <label for="companyEmail">Company Email</label>
                    @error('companyEmail')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('companyAddress') is-invalid @enderror"
                        name="companyAddress" placeholder="Company Address" value="{{ old('companyAddress') }}"
                        id="companyAddress">
                    <label for="companyAddress">Company Address</label>
                    @error('companyAddress')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="mt-2 mb-2">
                    <button type="submit" class="btn btn-primary text-light shadow-lg w-100">
                        Submit
                    </button>
                </div>
                <div class="mt-2 mb-2">
                    <button type="reset" class="btn btn-light shadow-lg w-100">Reset</button>
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
