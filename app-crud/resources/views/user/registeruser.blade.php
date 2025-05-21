<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <!--Include Head CDN-->
    @include('partials.headcdn')
    <!--End of Head CDN-->
    <link rel="stylesheet" href="/styles/style.css">
</head>
<body class="register-user-page d-flex flex-column min-vh-100">
    <div class="container">
        <div class="row mt-2">
        <div class="text-center">
            <a class="navbar-brand" href="{{route('proposal.index')}}">
                <img src="/images/SSB_Logo.jpg" alt="Your Logo" height="70">
            </a>
        </div>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4">Register Account</h1>

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

                        <form method="POST" action="{{ route('user.store') }}">
                            @csrf

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Name">
                                <label for="name">Company Name</label>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Email">
                                <label for="email">Email</label>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password">
                                <label for="password">Password</label>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                                <label for="password_confirmation">Confirm Password</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="register-button-tooltip" data-bs-title="Register">Register</button>
                            <button type="reset" class="btn btn-secondary w-100 mt-1">Reset</button>
                        </form>
                    </div>
                    <p class="text-center">Already Have an Account? <a href="{{route('login')}}">Log in</a></p>
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
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    </script>
</body>
</html>
