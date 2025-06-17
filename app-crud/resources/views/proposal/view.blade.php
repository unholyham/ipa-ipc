<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical Proposal Details</title>
    @include('partials.headcdn')
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
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-square"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container pt-2 flex-grow-1">
        <h1 class="text-center">Interim Payment Application Details</h1>
        <div class="row border-top border-start border-end p-1 text-white mt-4 rounded-top" id="tp_table_info">
            <div class="col-md-3">
                <h6 class="subject">Project Title</h6>
            </div>
            <div class="col-md-3">
                <h6><a href="{{route('project.view', ['project' => $proposal->getProject])}}" class="viewProjectLink text-white">{{ $proposal->getProject->projectTitle }}</a></h6>
            </div>
        </div>
        <div class="row border-top border-start border-end p-1">
            <div class="col-md-3">
                <h6 class="subject">Project Number</h6>
            </div>
            <div class="col-md-3">
                <h6>{{ $proposal->getProject->projectNumber }}</h6>
            </div>
        </div>
        <div class="row bg-light border-top border-start border-end p-1">
            <div class="col-md-3">
                <h6 class="subject">Region</h6>
            </div>
            <div class="col-md-3">
                <h6>{{ $proposal->region }}</h6>
            </div>
        </div>
        <div class="row border-top border-start border-end p-1">
            <div class="col-md-3">
                <h6 class="subject">Prepared By</h6>
            </div>
            <div class="col-md-3">
                <h6>{{ $proposal->preparedBy }}</h6>
            </div>
        </div>
        <div class="row bg-light border-top border-start border-end p-1">
            <div class="col-md-3">
                <h6 class="subject">Main Contractor</h6>
            </div>
            <div class="col-md-3">
                <h6><a href="{{route('company.view', ['company' => $proposal->company])}}" class="viewCompanyLink">{{ $proposal->company->companyName }}</a></h6>
            </div>
        </div>
        <div class="row border-top border-start border-end p-1">
            <div class="col-md-3">
                <h6 class="subject">Review Status</h6>
            </div>
            <div class="col-md-3">
                <h6>
                    <span class="badge rounded-pill {{ $proposal->reviewStatus == 'Reviewed' ? 'text-bg-success' : ($proposal->reviewStatus == 'Under Review' ? 'text-bg-info' : 'text-bg-warning') }}">
                        {{ $proposal->reviewStatus }}
                    </span>
                </h6>
                @if (Auth::user()->role->roleName === 'admin' && $proposal->reviewStatus === 'Not Started')
                    <button type="button" class="btn btn-sm btn-success text-white" data-bs-toggle="modal"
                        data-bs-target="#confirmReviewModal">
                        Start Review
                    </button>
                    <form id="updateReviewStatusForm" action="{{ route('proposal.updateReviewStatus', $proposal->id) }}"
                        method="POST" style="display: none;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="reviewStatus" value="Under Review">
                    </form>
                @endif
            </div>
        </div>
        <div class="row bg-light border-top border-start border-end p-1">
            <div class="col-md-3">
                <h6 class="subject">Approved Status</h6>
            </div>
            <div class="col-md-3">
                <h6>
                    <span
                        class="badge rounded-pill {{ $proposal->approvedStatus == 'Approved' ? 'text-bg-success' : ($proposal->approvedStatus == 'Rejected' ? 'text-bg-danger' : 'text-bg-warning') }}">
                        {{ $proposal->approvedStatus }}
                    </span>
                </h6>
                {{--  Conditional buttons for Approve Status --}}
                @if (Auth::user()->role->roleName === 'admin' &&
                        $proposal->reviewStatus === 'Under Review' &&
                        $proposal->approvedStatus !== 'Approved')
                    <button type="button" class="btn btn-sm btn-success text-white" data-bs-toggle="modal"
                        data-bs-target="#confirmApproveModal">
                        Approve
                    </button>
                    <form id="updateApprovedStatusForm"
                        action="{{ route('proposal.updateApprovedStatus', $proposal->id) }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="approvedStatus" value="Approved">
                    </form>
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-toggle="modal"
                        data-bs-target="#confirmRejectModal">
                        Reject
                    </button>
                    <form id="updateRejectedStatusForm"
                        action="{{ route('proposal.updateApprovedStatus', $proposal->id) }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="approvedStatus" value="Rejected">
                    </form>
                @endif
            </div>
        </div>
        @if ($proposal->approvedStatus === 'Rejected')
            <div class="row border-top border-start border-end p-1">
                <div class="col-md-3">
                    <h6 class="subject">Remarks</h6>
                </div>
                <div class="col-md-3">
                    <h6>{{ $proposal->remarks }}</h6>
                </div>
            </div>
        @endif
        <div class="row border-top border-start border-end p-1">
            <div class="col-md-3">
                <h6 class="subject">Technical Proposal</h6>
            </div>
            <div class="col-md-3">
                @if ($proposal->pathToTP)
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#tpViewModal-{{ $proposal->id }}">
                        <i class="bi bi-eye-fill"></i> View Document
                    </button>
                @else
                    Attachment not found.
                @endif
            </div>
        </div>
        <div class="row bg-light border p-1">
            <div class="col-md-3">
                <h6 class="subject">Joint Measurement Sheet</h6>
            </div>
            <div class="col-md-3">
                @if ($proposal->pathToJMS)
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#jmsViewModal-{{ $proposal->id }}">
                        <i class="bi bi-eye-fill"></i> View Document
                    </button>
                @else
                    Attachment not found.
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmReviewModal" tabindex="-1" aria-labelledby="confirmReviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmReviewModalLabel">Confirm Review Status Change</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to mark this technical proposal as "<strong>Under Review</strong>"?
                    <br><br>
                    This action will notify the user that their proposal is currently under review.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitReviewStatus()"
                        data-bs-dismiss="modal">Agree</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmApproveModal" tabindex="-1" aria-labelledby="confirmApproveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmApproveModalLabel">Confirm Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to <strong>"Approve"</strong> this technical proposal?
                    <br><br>
                    This action will notify the user that their proposal has been approved.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="submitApproveStatus()"
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
                    Are you sure you want to <strong>"Reject"</strong> this technical proposal?
                    <br><br>
                    This action will notify the user that their proposal has been rejected.
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
                    <button type="button" class="btn btn-danger" onclick="submitRejectStatus()">Agree</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="tpViewModal-{{ $proposal->id }}" tabindex="-1"
        aria-labelledby="tpViewModalLabel-{{ $proposal->id }}" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tpViewModalLabel-{{ $proposal->id }}">{{ $proposal->projectTitle }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <embed src="{{ route('proposal.displayTP', ['proposal' => $proposal->id]) }}"
                        type="application/pdf" width="100%" height="100%">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('proposal.downloadTP', ['proposal' => $proposal->id]) }}"
                        class="btn btn-primary">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="jmsViewModal-{{ $proposal->id }}" tabindex="-1"
        aria-labelledby="jmsViewModalLabel-{{ $proposal->id }}" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jmsViewModalLabel-{{ $proposal->id }}">{{ $proposal->projectTitle }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <embed src="{{ route('proposal.displayJMS', ['proposal' => $proposal->id]) }}"
                        type="application/pdf" width="100%" height="100%">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('proposal.downloadJMS', ['proposal' => $proposal->id]) }}"
                        class="btn btn-primary">
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('partials.footer')
    @include('partials.bodycdn')
    <script>
        function submitReviewStatus() {
            document.getElementById('updateReviewStatusForm').submit();
        }

        function submitApproveStatus() {
            document.getElementById('updateApprovedStatusForm').submit();
        }

        function submitRejectStatus() {
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
                const form = document.getElementById('updateRejectedStatusForm');
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
