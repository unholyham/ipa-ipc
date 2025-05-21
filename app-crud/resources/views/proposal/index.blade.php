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
<body class="d-flex flex-column min-vh-100">
  <!--Include Navbar Based on Role-->
  @if(Auth::user()->role ==='admin')
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
<div class="container p-0 flex-grow-1">
  <div class="row justify-content-between align-items-center">
    <div class="col-auto">
      <p class="m-0">Welcome, <a class="profile_link" href="{{route('profile.view')}}"><span class="text-primary lead">{{ Auth::user()->name }}</span></a></p>
    </div>
    
    <div class="col-auto">
      <form method="post" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger" style="font-size:15px;" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="logout-button-tooltip" data-bs-title="Logout"><i class="bi bi-power"></i></button>
      </form>
    </div>
  </div>
</div>

<div class="container pt-2">
  <h1 class="text-center">Technical Proposal Submissions</h1>
  <div class="row">
    <div class="col mt-5 p-4 bg-white border-top rounded shadow-sm">
      <h2><i class="bi bi-hourglass-split"></i>Pending Proposals</h2>
    <div>
    </div>
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
        @foreach($proposals as $proposal)
        @if($proposal->approvedStatus != 'Approved')
            <tr>
                <td><a href="{{route('proposal.view', ['proposal' => $proposal])}}" class="editProposalLink"><strong>{{$proposal->projectTitle}}</strong></a></td>
                <td>{{$proposal->created_at->format('j F Y g:i a') }}</td>
                <td>{{$proposal->projectNumber}}</td>
                <td>{{$proposal->region}}</td>
                <td>{{$proposal->preparedBy}}</td>
                <td>{{$proposal->mainContractor}}</td>
                <td>{{$proposal->reviewStatus}}</td>
                <td>{{$proposal->approvedStatus}}</td>
            </tr>
        @endif
        @endforeach
        </table>
    </div>
    </div>
    <div class="col mt-5 mb-5 p-4 bg-white border-top rounded shadow-sm">
      <h2><i class="bi bi-check2-square"></i> Approved Proposals</h2>
    <div>
    </div>
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
          @foreach($proposals as $proposal)
        @if($proposal->approvedStatus == 'Approved')
            <tr>
                <td><a href="{{route('proposal.view', ['proposal' => $proposal])}}" class="editProposalLink"><strong>{{$proposal->projectTitle}}</strong></a></td>
                <td>{{$proposal->created_at->format('j F Y g:i a') }}</td>
                <td>{{$proposal->projectNumber}}</td>
                <td>{{$proposal->region}}</td>
                <td>{{$proposal->preparedBy}}</td>
                <td>{{$proposal->mainContractor}}</td>
                <td>{{$proposal->reviewStatus}}</td>
                <td>{{$proposal->approvedStatus}}</td>
            </tr>
        @endif
        @endforeach
        </table>
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
        $(document).ready( function () {
            new DataTable('#pendingTable', {
                scrollX: true,
                order:[[1, 'desc']],
                language: {
                  emptyTable: "No pending technical proposals found. All proposals are either approved or there are no submissions yet."
                },
                layout: {
                    bottomStart: [
                      {
                        buttons: [
                            {
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
                order:[[1, 'desc']],
                language: {
                  emptyTable: "No approved technical proposals found. Check the pending proposals section."
                },
                layout: {
                    bottomStart: [
                      {
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                className: 'btn btn-success me-2',
                                title: 'Approved Proposals - ' + new Date().toLocaleDateString(),
                            },
                            {
                                extend: 'pdfHtml5',
                                className: 'btn btn-danger',
                                title: 'Approved Proposals - ' + new Date().toLocaleDateString(),
                            }
                          ]
                    },
                    'info'
                    ]
                }
            });
        } );
    </script>
</body>
</html>