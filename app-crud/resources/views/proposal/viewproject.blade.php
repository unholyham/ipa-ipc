<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->projectTitle }}</title>
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

    <div class="container pt-2 flex-grow-1">
        <h1 class="text-center">Project Details</h1>
        <div class="row border-top border-start border-end p-1 text-bg-dark mt-4 rounded-top">
            <div class="col-md-3">
                <h6 class="subject">Project Title</h6>
            </div>
            <div class="col-md-3">
                <h6>{{ $project->projectTitle }}</h6>
            </div>
        </div>
        <div class="row border-top border-start border-end p-1">
            <div class="col-md-3">
                <h6 class="subject">Project Number</h6>
            </div>
            <div class="col-md-3">
                <h6>{{ $project->projectNumber }}</h6>
            </div>
        </div>
        <div class="row bg-light border p-1">
            <div class="col-md-3">
                <h6 class="subject">Sub Contractor</h6>
            </div>
            <div class="col-md-3">
                <h6><a href="{{route('company.view', ['company' => $project->subContractorCompany])}}" class="viewCompanyLink">{{ $project->subContractorCompany->companyName }}</a></h6>
            </div>
        </div>

        <h2 class="text-center mt-5">Proposals</h2>
        @if ($project->proposals->isEmpty())
            <p class="text-center">No proposals linked to this project yet.</p>
        @else
            <div class="row">
            <div class="col mt-5 p-4 bg-white border-top rounded shadow-sm">
                <h2><i class="bi bi-file-earmark-check-fill"></i> Proposals</h2>
                <div>
                    <table class="table table-striped table-bordered table-hover" id="proposalsTable">
                        <thead>
                            <tr>
                                <th class="tableheader text-bg-dark">Project Title</th>
                                <th class="tableheader text-bg-dark">Submitted On</th>
                                <th class="tableheader text-bg-dark">Project Number</th>
                                <th class="tableheader text-bg-dark">Region</th>
                                <th class="tableheader text-bg-dark">Prepared By</th>
                                <th class="tableheader text-bg-dark">Review Status</th>
                                <th class="tableheader text-bg-dark">Approved Status</th>
                                <th class="tableheader text-bg-dark">Remarks</th>
                            </tr>
                        </thead>
                        @foreach ($project->proposals as $proposal)
                                <tr>
                                    <td><a href="{{ route('proposal.view', ['proposal' => $proposal->id]) }}"
                                            class="editProposalLink"><strong>{{ $proposal->getProject->projectTitle }}</strong></a>
                                    </td>
                                    <td>{{ $proposal->created_at->format('j F Y g:i a') }}</td>
                                    <td>{{ $proposal->getProject->projectNumber }}</td>
                                    <td>{{ $proposal->region }}</td>
                                    <td>{{ $proposal->preparedBy }}</td>
                                    <td>
                                        <span class="badge rounded-pill {{ $proposal->reviewStatus == 'Reviewed' ? 'text-bg-success' : ($proposal->reviewStatus == 'Under Review' ? 'text-bg-info' : 'text-bg-warning') }}">
                                            {{ $proposal->reviewStatus }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill {{ $proposal->approvedStatus == 'Approved' ? 'text-bg-success' : ($proposal->approvedStatus == 'Rejected' ? 'text-bg-danger' : 'text-bg-warning') }}">
                                            {{ $proposal->approvedStatus }}
                                        </span>
                                    </td>
                                    <td>{{ $proposal->remarks }}</td>
                                </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    

    

    
    <!--Include Footer-->
    @include('partials.footer')
    <!--End of Include-->

    <!--Include Body CDN-->
    @include('partials.bodycdn')
    <!--End of Body CDN-->
    <script>
        $(document).ready(function() {
            new DataTable('#proposalsTable', {
                scrollX: true,
                order: [
                    [1, 'desc']
                ],
                language: {
                    emptyTable: "No proposals found."
                },
                layout: {
                    bottomStart: [{
                            buttons: [{
                                    extend: 'excelHtml5',
                                    className: 'btn btn-success me-2',
                                    title: 'Proposals - ' + new Date().toLocaleDateString(),

                                },
                                {
                                    extend: 'pdfHtml5',
                                    className: 'btn btn-danger',
                                    title: 'Proposals - ' + new Date().toLocaleDateString(),
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
