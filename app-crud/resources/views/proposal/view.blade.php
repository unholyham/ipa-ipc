<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technical Proposal Details</title>
    @include('partials.headcdn')
    <link rel="stylesheet" href="/styles/style.css">
</head>
<body class="d-flex flex-column min-vh-100">
    @if(Auth::user()->role ==='admin')
        @include('partials.adminnav')
    @else
        @include('partials.usernav')
    @endif
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-square"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="container mt-2 pt-2 flex-grow-1">
    <h1 class="text-center mt-4">Technical Proposal Details</h1>
    <div class="row border-top border-start border-end p-1 text-white mt-4" id="tp_table_info">
        <div class="col-md-3"><h6 class="subject">Project Title</h6></div>
        <div class="col-md-3"><h6>{{$proposal->projectTitle}}</h6></div>
    </div>
    <div class="row border-top border-start border-end p-1">
        <div class="col-md-3"><h6 class="subject">Project Number</h6></div>
        <div class="col-md-3"><h6>{{$proposal->projectNumber}}</h6></div>
    </div>
    <div class="row bg-light border-top border-start border-end p-1">
        <div class="col-md-3"><h6 class="subject">Region</h6></div>
        <div class="col-md-3"><h6>{{$proposal->region}}</h6></div>
    </div>
    <div class="row border-top border-start border-end p-1">
        <div class="col-md-3"><h6 class="subject">Prepared By</h6></div>
        <div class="col-md-3"><h6>{{$proposal->preparedBy}}</h6></div>
    </div>
    <div class="row bg-light border-top border-start border-end p-1">
        <div class="col-md-3"><h6 class="subject">Main Contractor</h6></div>
        <div class="col-md-3"><h6>{{$proposal->mainContractor}}</h6></div>
    </div>
    <div class="row border-top border-start border-end p-1">
        <div class="col-md-3"><h6 class="subject">Review Status</h6></div>
        <div class="col-md-3">
            <h6>{{$proposal->reviewStatus}}</h6>
            @if(Auth::user()->role === 'admin' && $proposal->reviewStatus === 'Not Started')
                <button type="button" class="btn btn-sm btn-info text-white"
                        data-bs-toggle="modal" data-bs-target="#confirmReviewModal">
                    Start Review
                </button>
                <form id="updateReviewStatusForm" action="{{ route('proposal.updateReviewStatus', $proposal->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="reviewStatus" value="In Review">
                </form>
            @endif
        </div>
    </div>
    <div class="row bg-light border-top border-start border-end p-1">
        <div class="col-md-3"><h6 class="subject">Approved Status</h6></div>
        <div class="col-md-3">
            <h6>{{$proposal->approvedStatus}}</h6>
            {{--  Conditional button for Approve Status --}}
            @if(Auth::user()->role === 'admin' && $proposal->reviewStatus === 'In Review' && $proposal->approvedStatus !== 'Approved')
                <button type="button" class="btn btn-sm btn-success text-white"
                        data-bs-toggle="modal" data-bs-target="#confirmApproveModal">
                    Approve
                </button>
                <form id="updateApprovedStatusForm" action="{{ route('proposal.updateApprovedStatus', $proposal->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="approvedStatus" value="Approved">
                </form>
            @endif
        </div>
    </div>
    <div class="row border p-1">
        <div class="col-md-3"><h6 class="subject">Uploaded Files</h6></div>
        <div class="col-md-3">
            @if($proposal->pathToTP)
                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#pdfViewModal-{{ $proposal->id }}">
                    <i class="bi bi-eye-fill"></i> View PDF
                </button>
            @else
                Attachment not found.
            @endif
        </div>
    </div>
</div>
<div class="modal fade" id="confirmReviewModal" tabindex="-1" aria-labelledby="confirmReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmReviewModalLabel">Confirm Review Status Change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to mark this technical proposal as "<strong>In Review</strong>"?
                <br><br>
                This action will notify the user that their proposal is currently under review.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitReviewStatus()" data-bs-dismiss="modal">Agree</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmApproveModal" tabindex="-1" aria-labelledby="confirmApproveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmApproveModalLabel">Confirm Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to <strong>"Approve"</strong> this technical proposal?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitApproveStatus()" data-bs-dismiss="modal">Agree</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pdfViewModal-{{ $proposal->id }}" tabindex="-1" aria-labelledby="pdfViewModalLabel-{{ $proposal->id }}" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfViewModalLabel-{{ $proposal->id }}">{{ $proposal->projectTitle }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <embed src="{{ route('proposal.displayPdf', ['proposal' => $proposal->id]) }}"
                        type="application/pdf"
                        width="100%"
                        height="100%">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="{{ route('proposal.downloadPdf', ['proposal' => $proposal->id]) }}" class="btn btn-primary">
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
</script>
</body>
</html>