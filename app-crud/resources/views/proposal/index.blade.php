<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
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
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-square"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="container pt-2">
        <h1 class="text-center">Interim Payment Application Submissions</h1>
        <div class="row">
            <!--Start of Pending Proposal Table-->
            <div class="col mt-5 p-4 bg-white border-top rounded shadow-sm">
                <h2><i class="bi bi-hourglass-split"></i>Pending Submissions</h2>
                <div>
                    <table class="table table-striped table-bordered table-hover" id="pendingTable">
                        <thead>
                            <tr>
                                <th class="tableheader text-bg-warning">Project Title</th>
                                <th class="tableheader text-bg-warning">Submitted On</th>
                                <th class="tableheader text-bg-warning">Project Number</th>
                                <th class="tableheader text-bg-warning">Region</th>
                                <th class="tableheader text-bg-warning">Prepared By</th>
                                <th class="tableheader text-bg-warning">Main Contractor</th>
                                <th class="tableheader text-bg-warning">Review Status</th>
                                <th class="tableheader text-bg-warning">Approved Status</th>
                            </tr>
                        </thead>
                        @foreach ($proposals as $proposal)
                            @if ($proposal->reviewStatus == 'Not Started' || $proposal->reviewStatus == 'Under Review')
                                <tr>
                                    <td><a href="{{ route('proposal.view', ['proposal' => $proposal]) }}"
                                            class="editProposalLink"><strong>{{ $proposal->getProject->projectTitle }}</strong></a>
                                    </td>
                                    <td>{{ $proposal->created_at->format('j F Y g:i a') }}</td>
                                    <td>{{ $proposal->getProject->projectNumber }}</td>
                                    <td>{{ $proposal->region }}</td>
                                    <td>{{ $proposal->preparedBy }}</td>
                                    <td>{{ $proposal->company->companyName }}</td>
                                    <td><span class="badge rounded-pill {{ $proposal->reviewStatus == 'Not Started' ? 'text-bg-warning' : 'text-bg-info' }}">{{ $proposal->reviewStatus }}</span></td>
                                    <td><span class="badge rounded-pill text-bg-warning">{{ $proposal->approvedStatus }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
            <!--End of Pending Proposal Table-->
        </div>
        <!--Start of Approved Proposal Table-->
        <div class="row">
            <div class="col mt-5 mb-5 p-4 bg-white border-top rounded shadow-sm">
                <h2><i class="bi bi-check2-square"></i> Approved Submissions</h2>
                <div>
                    <table class="table table-striped table-bordered table-hover" id="approvedTable">
                        <thead>
                            <tr>
                                <th class="tableheader text-bg-success">Project Title</th>
                                <th class="tableheader text-bg-success">Submitted On</th>
                                <th class="tableheader text-bg-success">Project Number</th>
                                <th class="tableheader text-bg-success">Region</th>
                                <th class="tableheader text-bg-success">Prepared By</th>
                                <th class="tableheader text-bg-success">Main Contractor</th>
                                <th class="tableheader text-bg-success">Review Status</th>
                                <th class="tableheader text-bg-success">Approved Status</th>
                            </tr>
                        </thead>
                        @foreach ($proposals as $proposal)
                            @if ($proposal->approvedStatus == 'Approved')
                                <tr>
                                    <td><a href="{{ route('proposal.view', ['proposal' => $proposal]) }}"
                                            class="editProposalLink"><strong>{{ $proposal->getProject->projectTitle }}</strong></a>
                                    </td>
                                    <td>{{ $proposal->created_at->format('j F Y g:i a') }}</td>
                                    <td>{{ $proposal->getProject->projectNumber }}</td>
                                    <td>{{ $proposal->region }}</td>
                                    <td>{{ $proposal->preparedBy }}</td>
                                    <td>{{ $proposal->company->companyName }}</td>
                                    <td><span class="badge rounded-pill text-bg-success">{{ $proposal->reviewStatus }}</span></td>
                                    <td><span class="badge rounded-pill text-bg-success">{{ $proposal->approvedStatus }}</span></td>
                                    
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <!--End of Approved Proposal Table-->
        <!--Start of Rejected Proposal Table-->
        <div class="row">
            <div class="col mt-5 p-4 bg-white border-top rounded shadow-sm">
                <h2><i class="bi bi-x-square"></i> Rejected Submissions</h2>
                <div>
                    <table class="table table-striped table-bordered table-hover" id="rejectedTable">
                        <thead>
                            <tr>
                                <th class="tableheader text-bg-danger">Project Title</th>
                                <th class="tableheader text-bg-danger">Submitted On</th>
                                <th class="tableheader text-bg-danger">Project Number</th>
                                <th class="tableheader text-bg-danger">Region</th>
                                <th class="tableheader text-bg-danger">Prepared By</th>
                                <th class="tableheader text-bg-danger">Main Contractor</th>
                                <th class="tableheader text-bg-danger">Remarks</th>
                            </tr>
                        </thead>
                        @foreach ($proposals as $proposal)
                            @if ($proposal->approvedStatus == 'Rejected')
                                <tr>
                                    <td><a href="{{ route('proposal.view', ['proposal' => $proposal]) }}"
                                            class="editProposalLink"><strong>{{ $proposal->getProject->projectTitle }}</strong></a>
                                    </td>
                                    <td>{{ $proposal->created_at->format('j F Y g:i a') }}</td>
                                    <td>{{ $proposal->getProject->projectNumber }}</td>
                                    <td>{{ $proposal->region }}</td>
                                    <td>{{ $proposal->preparedBy }}</td>
                                    <td>{{ $proposal->company->companyName }}</td>
                                    <td>{{ $proposal->remarks }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <!--End of Rejected Proposal Table-->
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
            new DataTable('#pendingTable', {
                scrollX: true,
                order: [
                    [1, 'desc']
                ],
                language: {
                    emptyTable: "No pending technical proposal submissions found."
                },
                layout: {
                    bottomStart: [{
                            buttons: [{
                                    extend: 'excelHtml5',
                                    className: 'btn btn-success me-2',
                                    title: 'Pending Proposals - ' + new Date().toLocaleDateString(),

                                },
                                {
                                    extend: 'pdfHtml5',
                                    className: 'btn btn-danger',
                                    title: 'Pending Proposals - ' + new Date().toLocaleDateString(),
                                }
                            ]
                        },
                        'info'
                    ]
                }
            });
            new DataTable('#approvedTable', {
                scrollX: true,
                order: [
                    [1, 'desc']
                ],
                language: {
                    emptyTable: "No approved technical proposal submissions found."
                },
                layout: {
                    bottomStart: [{
                            buttons: [{
                                    extend: 'excelHtml5',
                                    className: 'btn btn-success me-2',
                                    title: 'Approved Proposals - ' + new Date()
                                    .toLocaleDateString(),
                                },
                                {
                                    extend: 'pdfHtml5',
                                    className: 'btn btn-danger',
                                    title: 'Approved Proposals - ' + new Date()
                                    .toLocaleDateString(),
                                }
                            ]
                        },
                        'info'
                    ]
                }
            });
            new DataTable('#rejectedTable', {
                scrollX: true,
                order: [
                    [1, 'desc']
                ],
                language: {
                    emptyTable: "No rejected technical proposal submissions found."
                },
                layout: {
                    bottomStart: [{
                            buttons: [{
                                    extend: 'excelHtml5',
                                    className: 'btn btn-success me-2',
                                    title: 'Rejected Proposals - ' + new Date()
                                    .toLocaleDateString(),

                                },
                                {
                                    extend: 'pdfHtml5',
                                    className: 'btn btn-danger',
                                    title: 'Rejected Proposals - ' + new Date()
                                    .toLocaleDateString(),
                                }
                            ]
                        },
                        'info'
                    ]
                }
            });
        });
    </script>
</body>

</html>
