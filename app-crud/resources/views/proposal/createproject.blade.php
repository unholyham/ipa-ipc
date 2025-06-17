<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Project</title>
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
        <h1 class="text-center">Project Form</h1>
        <div class="mt-4">
            <form method="post" action="{{ route('project.store') }}" enctype="multipart/form-data" id="projectForm">
                @csrf

                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('projectTitle') is-invalid @enderror"
                        name="projectTitle" placeholder="Project Title" value="{{ old('projectTitle') }}"
                        id="projectTitle">
                    <label for="projectTitle">Project Title</label>
                    @error('projectTitle')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('projectNumber') is-invalid @enderror"
                        name="projectNumber" placeholder="Project Number" value="{{ old('projectNumber') }}"
                        id="projectNumber">
                    <label for="projecNumber">Project Number</label>
                    @error('projectNumber')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <select class="form-select @error('subContractor') is-invalid @enderror" id="subContractor" name="subContractor">
                    <option value="" disabled selected>Select Sub Contractor</option>
                        @foreach($companies->sortBy('companyName') as $company)
                            @if($company->contractorType === 'sub contractor') 
                                <option value="{{ $company->companyID }}" {{ old('subContractor') == $company->companyID ? 'selected' : '' }}>
                                    {{ $company->companyName }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <label for="subContractor">Company</label>
                    @error('subContractor')
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
    <script>
        $(document).ready(function() {
        $('#subContractor').select2({
            theme: 'bootstrap-5'
        });
    });
    </script>
</body>

</html>
