<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <!--Include Head CDN-->
    @include('partials.headcdn')
    <!--End of Head CDN-->
    <link rel="stylesheet" href="/styles/style.css">
</head>
<body class="login-user-page d-flex flex-column min-vh-100 bg-gradient">
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bi bi-check-square"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
    <div class="container">
        <div class="row mt-2">
        <div class="text-center">
            <a class="navbar-brand" href="{{route('login')}}">
                <img src="/images/SSB_Logo.jpg" alt="Your Logo" height="70">
            </a>
        </div>
        </div>
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4">Login</h1>
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
                        <form method="POST" action="{{ route('post.login') }}">
                            @csrf

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
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="showPassword">
                                <label class="form-check-label" for="showPassword">Show Password</label>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="login-button-tooltip" data-bs-title="Login">Login</button>
                        </form>
                    </div>
                    <p class="text-center">Don't Have an Account? <a href="{{route('register')}}">Register Account</a></p>
                    <p class="text-center"><a href="#">Forgot Your Password?</a></p>
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
