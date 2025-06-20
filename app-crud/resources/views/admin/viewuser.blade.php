<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
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
        <h1 class="text-center">Applicant Details</h1>
        <div class="row border-top border-start border-end p-1 text-white mt-4 rounded-top" id="tp_table_info">
            <div class="col-md-3">
                <h6 class="subject">Name</h6>
            </div>
            <div class="col-md-3">
                <h6>{{ $account->employeeName }}</h6>
            </div>
        </div>
        <div class="row border-top border-start border-end p-1">
            <div class="col-md-3">
                <h6 class="subject">Company</h6>
            </div>
            <div class="col-md-3">
                <h6>{{ $account->company->companyName }}</h6>
            </div>
        </div>
        <div class="row border-top border-start border-end p-1">
            <div class="col-md-3">
                <h6 class="subject">Designation</h6>
            </div>
            <div class="col-md-3">
                <h6>{{ $account->designation }}</h6>
            </div>
        </div>
        <div class="row border-top border-start border-end p-1">
            <div class="col-md-3">
                <h6 class="subject">Email</h6>
            </div>
            <div class="col-md-3">
                <h6>{{ $account->email }}</h6>
            </div>
        </div>
        <div class="row border-top border-start border-end p-1">
            <div class="col-md-3">
                <h6 class="subject">Contact Number</h6>
            </div>
            <div class="col-md-3">
                <h6>{{ $account->contactNumber }}</h6>
            </div>
        </div>
        <div class="row bg-light border-top border-start border-end p-1">
            <div class="col-md-3">
                <h6 class="subject">Role</h6>
            </div>
            <div class="col-md-3">
                <h6>{{ $account->role->roleName }}</h6>
            </div>
        </div>
        <div class="row border p-1">
            <div class="col-md-3">
                <h6 class="subject">Verification Status</h6>
            </div>
            <div class="col-md-3">
                <h6>
                    <span class="badge rounded-pill {{ $account->verificationStatus == 'verified' ? 'text-bg-success' : ($account->verificationStatus == 'pending' ? 'text-bg-warning' : 'text-bg-danger') }}">
                        {{ $account->verificationStatus }}
                    </span>
                </h6>
                {{--  Conditional buttons for Verify Status --}}
                @if (Auth::user()->role->roleName === 'admin' && $account->verificationStatus === 'pending')
                    <button type="button" class="btn btn-sm btn-success text-white" data-bs-toggle="modal"
                        data-bs-target="#confirmVerifyModal">
                        Verify
                    </button>
                    <form id="verifyForm" action="{{ route('admin.users.updateVerificationStatus', $account->accountID) }}"
                        method="POST" style="display: none;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="verificationStatus" value="verified">
                    </form>
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-toggle="modal"
                        data-bs-target="#confirmRejectModal">
                        Reject
                    </button>
                    <form id="rejectForm" action="{{ route('admin.users.updateVerificationStatus', $account->accountID) }}"
                        method="POST" style="display: none;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="verificationStatus" value="rejected">
                    </form>
                @endif
            </div>
        </div>
        <div class="row bg-light border p-1">
            <div class="col-md-3">
                <h6 class="subject">Account Status</h6>
            </div>
            <div class="col-md-3">
                <h6>
                    <span
                        class="badge rounded-pill {{ $account->accountStatus == 'active' ? 'text-bg-success' : 'text-bg-danger' }}">
                        {{ $account->accountStatus }}
                    </span>
                </h6>
                {{-- Conditional button for Activate/Deactivate Account Status --}}
                @if (Auth::user()->role->roleName === 'admin' && $account->verificationStatus === 'verified')
                    <form action="{{ route('admin.users.updateAccountStatus', $account->accountID) }}" method="POST"
                        class="mt-2">
                        @csrf
                        @method('PATCH')
                        @if ($account->accountStatus === 'active')
                            <button type="submit" class="btn btn-sm btn-danger">
                                Deactivate Account
                            </button>
                        @else
                            <button type="submit" class="btn btn-sm btn-success">
                                Activate Account
                            </button>
                        @endif
                    </form>
                @endif
            </div>
        </div>
        {{-- Remarks Should only be visible if verification status is rejected --}}
        @if ($account->verificationStatus === 'rejected')
            <div class="row bg-light border p-1">
                <div class="col-md-3">
                    <h6 class="subject">Rejection Remarks</h6>
                </div>
                <div class="col-md-3">
                    <h6>{{ $account->verificationRejectRemarks }}</h6>
                </div>
            </div>
        @endif

    </div>
    <div class="modal fade" id="confirmVerifyModal" tabindex="-1" aria-labelledby="confirmVerifyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmVerifyModalLabel">Confirm Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to <strong>"Verify"</strong> this user's registration application?
                    <br><br>
                    This action will notify the user that their application has been verified.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="verifyRegistration()"
                        data-bs-dismiss="modal">Agree</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmRejectModal" tabindex="-1" aria-labelledby="confirmRejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmRejectModalLabel">Confirm Rejection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to <strong>"Reject"</strong> this user's registration application?
                    <br><br>
                    This action will notify the user that their application has been rejected.
                    <hr>
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Reason for Rejection*:</label>
                        <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" rows="3" name="remarks"></textarea>
                        <div id="rejectErrorMessage" class="text-danger mt-2 d-none">
                            Remarks field is required
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="rejectRegistration()">Agree</button>
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
        function verifyRegistration() {
            document.getElementById('verifyForm').submit();
        }

        function rejectRegistration() {
            const remarksInput = document.getElementById('remarks');
            const rejectErrorMessage = document.getElementById('rejectErrorMessage');
            const remarksValue = remarksInput.value.trim();

            if (remarksValue === '') {
                rejectErrorMessage.classList.remove('d-none'); //Show Error Message if remarks field is empty
                remarksInput.classList.add('is-invalid');
            } else {
                rejectErrorMessage.classList.add('d-none'); //Hide Error Message if remarks is filled
                remarksInput.classList.remove('is-invalid');

                // Dynamically add the remarks to the form before submission
                const form = document.getElementById('rejectForm');
                let hiddenRemarksInput = form.querySelector('input[name="remarks"]');
                if (!hiddenRemarksInput) {
                    hiddenRemarksInput = document.createElement('input');
                    hiddenRemarksInput.type = 'hidden';
                    hiddenRemarksInput.name = 'remarks';
                    form.appendChild(hiddenRemarksInput);
                }
                hiddenRemarksInput.value = remarksValue;

                console.log('Rejecting with remarks:', remarksValue);

                form.submit();

                //Close the modal after successful submission
                const rejectModal = bootstrap.Modal.getInstance(document.getElementById('confirmRejectModal'));
                if (rejectModal) {
                    rejectModal.hide();
                }
            }
        }
        document.getElementById('confirmRejectModal').addEventListener('hidden.bs.modal', function() {
            const remarksInput = document.getElementById('remarks');
            const rejectErrorMessage = document.getElementById('rejectErrorMessage');

            // Reset input field and error message
            remarksInput.value = '';
            rejectErrorMessage.classList.add('d-none');
            remarksInput.classList.remove('is-invalid');
        });
    </script>
</body>

</html>
