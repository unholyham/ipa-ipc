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
        <h1 class="text-center">Interim Payment Application Form</h1>
        <div class="mt-4">
            <form method="post" action="{{ route('proposal.store') }}" enctype="multipart/form-data" id="proposalForm">
                @csrf

                <div class="form-floating mb-3">
                    <select class="form-select @error('project') is-invalid @enderror" id="project" name="project">
                        <option value="" disabled selected>Select a project</option>
                            @foreach($projects->sortBy('projectTitle') as $project)
                                    <option value="{{ $project->projectID }}" {{ old('mainContractor') == $project->projectID ? 'selected' : '' }}>
                                        {{ $project->projectTitle }}
                                    </option>
                            @endforeach 
                    </select>
                    <label for="project">Project</label>
                    @error('project')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select @error('region') is-invalid @enderror" id="region" name="region"
                        aria-label="Select Region">
                        <option value="" disabled selected class="state_option">Select Region</option>
                        <option value="Johor" class="state_option" {{ old('region') == 'Johor' ? 'selected' : '' }}>
                            Johor</option>
                        <option value="Kedah" class="state_option" {{ old('region') == 'Kedah' ? 'selected' : '' }}>
                            Kedah</option>
                        <option value="Kelantan" class="state_option"
                            {{ old('region') == 'Kelantan' ? 'selected' : '' }}>Kelantan</option>
                        <option value="Malacca" class="state_option" {{ old('region') == 'Malacca' ? 'selected' : '' }}>
                            Malacca</option>
                        <option value="Negeri Sembilan" class="state_option"
                            {{ old('region') == 'Negeri Sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                        <option value="Pahang" class="state_option" {{ old('region') == 'Pahang' ? 'selected' : '' }}>
                            Pahang</option>
                        <option value="Penang" class="state_option" {{ old('region') == 'Penang' ? 'selected' : '' }}>
                            Penang</option>
                        <option value="Perak" class="state_option" {{ old('region') == 'Perak' ? 'selected' : '' }}>
                            Perak</option>
                        <option value="Perlis" class="state_option" {{ old('region') == 'Perlis' ? 'selected' : '' }}>
                            Perlis</option>
                        <option value="Sabah" class="state_option" {{ old('region') == 'Sabah' ? 'selected' : '' }}>
                            Sabah</option>
                        <option value="Sarawak" class="state_option"
                            {{ old('region') == 'Sarawak' ? 'selected' : '' }}>Sarawak</option>
                        <option value="Selangor" class="state_option"
                            {{ old('region') == 'Selangor' ? 'selected' : '' }}>Selangor</option>
                        <option value="Terengganu" class="state_option"
                            {{ old('region') == 'Terengganu' ? 'selected' : '' }}>Terengganu</option>
                        <option value="Kuala Lumpur" class="state_option"
                            {{ old('region') == 'Kuala Lumpur' ? 'selected' : '' }}>Kuala Lumpur (Federal Territory)
                        </option>
                        <option value="Labuan" class="state_option" {{ old('region') == 'Labuan' ? 'selected' : '' }}>
                            Labuan (Federal Territory)</option>
                        <option value="Putrajaya" class="state_option"
                            {{ old('region') == 'Putrajaya' ? 'selected' : '' }}>Putrajaya (Federal Territory)</option>
                    </select>
                    <label for="region">Region</label>
                    @error('region')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('preparedBy') is-invalid @enderror"
                        name="preparedBy" placeholder="Prepared By(Full Name as per IC)*"
                        value="{{ old('preparedBy', $excelData['prepared_by'] ?? '') }}" id="prepared_by">
                    <label for="project_preparedBy">Prepared By(Full Name as per IC)*</label>
                    @error('preparedBy')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select @error('mainContractor') is-invalid @enderror" id="mainContractor" name="mainContractor">
                        <option value="" disabled selected>Select your Main Contractor</option>
                            @foreach($companies->sortBy('companyName') as $company)
                                @if($company->contractorType === 'main contractor')
                                    <option value="{{ $company->companyID }}" {{ old('mainContractor') == $company->companyID ? 'selected' : '' }}>
                                        {{ $company->companyName }}
                                    </option>
                                @endif
                            @endforeach 
                    </select>
                    <label for="mainContractor">Main Contractor</label>
                    @error('mainContractor')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div>
                    <label for="#tpLabel">Technical Proposal Document*</label>
                    <input type="file" class="form-control" name="pathToTP" id="tpLabel">
                    <p>Max filesize: 60MB</p>
                </div>
                <div>
                    <label for="#jmsLabel">Joint Measurement Sheet Document*</label>
                    <input type="file" class="form-control" name="pathToJMS" id="jmsLabel">
                    <p>Max filesize: 60MB</p>
                </div>
                <div class="mt-2 mb-2">
                    <button type="button" class="btn btn-primary text-light shadow-lg w-100" data-bs-toggle="modal"
                        data-bs-target="#confirmSubmitModal" id="mainSubmitButton">
                        Submit
                    </button>
                </div>
                <div class="mt-2 mb-2">
                    <button type="reset" class="btn btn-light shadow-lg w-100">Reset</button>
                </div>

                <div class="modal fade" id="confirmSubmitModal" tabindex="-1" data-bs-backdrop="static"
                    data-bs-keyboard="false" aria-labelledby="confirmSubmitModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="confirmSubmitModalLabel">Confirm Submission</h5>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to submit this technical proposal?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                    id="cancelSubmitButton">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="realSubmitButton">Submit
                                    Proposal</button>
                            </div>
                            <small class="text-danger text-center" id="submissionMessage"
                                style="display: none;"><strong>Please stay on this page until submission is
                                    complete.</strong></small>
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
    <script>
        $(document).ready(function() {

        $('#project').select2({
            theme: 'bootstrap-5'
        });

        $('#mainContractor').select2({
            theme: 'bootstrap-5'
        });

        $('#region').select2({
            theme: 'bootstrap-5'
        });

            const proposalForm = document.getElementById('proposalForm');
            const mainSubmitButton = document.getElementById('mainSubmitButton');
            const realSubmitButton = document.getElementById('realSubmitButton');
            const cancelSubmitButton = document.getElementById('cancelSubmitButton'); // Get the cancel button
            const confirmSubmitModal = new bootstrap.Modal(document.getElementById('confirmSubmitModal'));

            realSubmitButton.addEventListener('click', function(event) {
                // Prevent default form submission initially
                event.preventDefault();

                // Disable all relevant buttons
                mainSubmitButton.disabled = true;
                realSubmitButton.disabled = true;
                cancelSubmitButton.disabled = true; // Disable the cancel button

                // Add spinner to the real submit button
                realSubmitButton.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Submitting...
                `;
                //Reveal message
                submissionMessage.style.display = 'block';

                // Submit the form
                proposalForm.submit();
            });
        });
    </script>
</body>

</html>
