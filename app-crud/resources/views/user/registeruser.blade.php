<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Account</title>
    <!--Include Head CDN-->
    @include('partials.headcdn')
    <!--End of Head CDN-->
    <link rel="stylesheet" href="/styles/style.css">
</head>

<body class="register-user-page d-flex flex-column min-vh-100 bg-gradient">
    <div class="container">
        <div class="row mt-2">
            <div class="text-center">
                <a class="navbar-brand" href="{{ route('register') }}">
                    <img src="/images/SSB_Logo.jpg" alt="Your Logo" height="70">
                </a>
            </div>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4">Register Account</h1>

                        <form method="POST" action="{{ route('user.store') }}">
                            @csrf

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('employeeName') is-invalid @enderror"
                                    id="employeeName" name="employeeName" value="{{ old('employeeName') }}" placeholder="Name">
                                <label for="employeeName">Name</label>
                                @error('employeeName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select @error('companyID') is-invalid @enderror" id="companyID" name="companyID">
                                    <option value="" disabled selected>Select your Company</option>
                                        @foreach($companies->sortBy('companyName') as $company)
                                            @if($company->contractorType === 'main contractor') 
                                            <option value="{{ $company->companyID }}" {{ old('companyID') == $company->companyID ? 'selected' : '' }}>
                                                {{ $company->companyName }}
                                            </option>
                                            @endif
                                        @endforeach
                                </select>
                                <label for="companyID">Company</label>
                                @error('companyID')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <select class="form-select @error('designation') is-invalid @enderror" id="designation" name="designation">
                                    <option value="" disabled selected>Select your Designation</option>
                                    <option value="Contract Executive" {{ old('designation') == 'Contract Executive' ? 'selected' : '' }}>Contract Executive</option>
                                    <option value="Assistant Contract Manager" {{ old('designation') == 'Assistant Contract Manager' ? 'selected' : '' }}>Assistant Contract Manager</option>
                                    <option value="Contract Manager" {{ old('designation') == 'Contract Manager' ? 'selected' : '' }}>Contract Manager</option>
                                    <option value="Regional Project Manager" {{ old('designation') == 'Regional Project Manager' ? 'selected' : '' }}>Regional Project Manager</option>
                                    <option value="Project Director" {{ old('designation') == 'Project Director' ? 'selected' : '' }}>Project Director</option>
                                </select>
                                <label for="designation">Designation</label>
                                @error('designation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('contactNumber') is-invalid @enderror"
                                    id="contactNumber" name="contactNumber" value="{{ old('contactNumber') }}" placeholder="Contact Number">
                                <label for="contactNumber">Contact Number</label>
                                @error('contactNumber')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" placeholder="Email">
                                <label for="email">Email</label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Password">
                                <label for="password">Password</label>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Confirm Password">
                                <label for="password_confirmation">Confirm Password</label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="showPassword">
                                <label class="form-check-label" for="showPassword">Show Password</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" data-bs-toggle="tooltip"
                                data-bs-placement="top" data-bs-custom-class="register-button-tooltip"
                                data-bs-title="Register">Register</button>
                            <button type="reset" class="btn btn-secondary w-100 mt-1">Reset</button>
                        </form>
                    </div>
                    <p class="text-center">Already Have an Account? <a href="{{ route('login') }}">Log in</a></p>
                </div>
            </div>
        </div>
    </div>
    <!--Include Footer-->
    @include('partials.footer')
    <!--End of Include-->

    <!--Include Show Password CDN-->
     @include('partials.showpasswordcdn')
    <!--End of Show Password CDN-->
</body>

</html>