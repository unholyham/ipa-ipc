<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Accounts</title>
    <!--Include Head CDN-->
    @include('partials.headcdn')
    <!--End of Head CDN-->
    <link rel="stylesheet" href="/styles/style.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-white bg-gradient">
  <!--Include Navbar Based on Designation-->
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
<div class="container pt-2">
  <h1 class="text-center">Manage User Accounts</h1>

  <!--Start of Pending User Accounts Table-->
  <div class="row">
    <div class="col mt-5 p-4 bg-white border-top rounded shadow-sm">
      <h2><i class="bi bi-hourglass-split"></i>Pending User Accounts</h2>
    <div>
        <table class="table table-striped table-bordered table-hover" id="pendingUsersTable">
          <thead>
            <tr>
            <th class="tableheader text-bg-warning">Name</th>
            <th class="tableheader text-bg-warning">Created On</th>
            <th class="tableheader text-bg-warning">Email</th>
            <th class="tableheader text-bg-warning">Designation</th>
            <th class="tableheader text-bg-warning">Verification Status</th>
            </tr>
          </thead>
        @foreach($accounts as $account)
        @if($account->verificationStatus == 'pending')
            <tr>
                <td><a href="{{route('admin.users.view', ['account' => $account])}}" class="viewUserLink"><strong>{{$account->employeeName}}</strong></a></td>
                <td>{{$account->created_at->format('j F Y g:i a') }}</td>
                <td>{{$account->email}}</td>
                <td>{{$account->designation}}</td>
                <td><span class="badge rounded-pill text-bg-warning">{{$account->verificationStatus}}</span></td>
            </tr>
        @endif
        @endforeach
        </table>
    </div>
    </div>
  </div>
  <!--End of Pending User Accounts Table-->

  <!--Start of Verified User Accounts Table-->
  <div class="row">
    <div class="col mt-5 p-4 bg-white border-top rounded shadow-sm">
      <h2><i class="bi bi-check2-square"></i> Verified User Accounts</h2>
    <div>
        <table class="table table-striped table-bordered table-hover" id="verifiedUsersTable">
          <thead>
            <tr>
            <th class="tableheader text-bg-success">Name</th>
            <th class="tableheader text-bg-success">Created On</th>
            <th class="tableheader text-bg-success">Email</th>
            <th class="tableheader text-bg-success">Designation</th>
            <th class="tableheader text-bg-success">Active Status</th>
            </tr>
          </thead>
          @foreach($accounts as $account)
          @if($account->verificationStatus == 'verified' && $account->role->roleName != 'admin')
            <tr>
                <td><a href="{{route('admin.users.view', ['account' => $account])}}" class="viewUserLink"><strong>{{$account->employeeName}}</strong></a></td>
                <td>{{$account->created_at->format('j F Y g:i a') }}</td>
                <td>{{$account->email}}</td>
                <td>{{$account->designation}}</td>
                <td>
                  <span class="badge rounded-pill {{ $account->accountStatus == 'inactive' ? 'text-bg-danger' : 'text-bg-success' }}">
                    {{ $account->accountStatus }}
                  </span>
                </td>
            </tr>
        @endif
        @endforeach
        </table>
    </div>
    </div>
  </div>
  <!--End of Verified User Accounts Table-->

  <!--Start of Rejected User Accounts-->
  <div class="row">
    <div class="col mt-5 p-4 bg-white border-top rounded shadow-sm">
      <h2><i class="bi bi-x-square"></i> Rejected User Accounts</h2>
    <div>
        <table class="table table-striped table-bordered table-hover" id="rejectedUsersTable">
          <thead>
            <tr>
            <th class="tableheader text-bg-danger">Name</th>
            <th class="tableheader text-bg-danger">Created On</th>
            <th class="tableheader text-bg-danger">Email</th>
            <th class="tableheader text-bg-danger">Designation</th>
            <th class="tableheader text-bg-danger">Remarks</th>
            </tr>
          </thead>
        @foreach($accounts as $account)
        @if($account->verificationStatus == 'rejected')
            <tr>
                <td><a href="{{route('admin.users.view', ['account' => $account])}}" class="viewUserLink"><strong>{{$account->employeeName}}</strong></a></td>
                <td>{{$account->created_at->format('j F Y g:i a') }}</td>
                <td>{{$account->email}}</td>
                <td>{{$account->designation}}</td>
                <td>{{$account->verificationRejectRemarks}}</td>
            </tr>
        @endif
        @endforeach
        </table>
    </div>
    </div>
  </div>
  <!--End of Rejected User Accounts Table-->
  </div>
</div>

<!--Include Footer-->
@include('partials.footer')
<!--End of Footer-->

<!--Include Body CDN-->
@include('partials.bodycdn')
<!--End of Body CDN-->
    <script>
        $(document).ready( function () {
            new DataTable('#pendingUsersTable', {
                scrollX: true,
                order:[[1, 'desc']],
                language: {
                  emptyTable: "No pending user registrations found."
                },
                layout: {
                    bottomStart: [
                      {
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                className: 'btn btn-success me-2',
                                title: 'Pending User Registrations - ' + new Date().toLocaleDateString(),

                            },
                            {
                                extend: 'pdfHtml5',
                                className: 'btn btn-danger',
                                title: 'Pending User Registrations - ' + new Date().toLocaleDateString(),
                            }
                          ]
                    },
                    'info'
                    ]
                }
            });
        } );

        $(document).ready( function () {
            new DataTable('#verifiedUsersTable', {
                scrollX: true,
                order:[[1, 'desc']],
                language: {
                  emptyTable: "No verifed user accounts found."
                },
                layout: {
                    bottomStart: [
                      {
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                className: 'btn btn-success me-2',
                                title: 'Verified User Accounts - ' + new Date().toLocaleDateString(),

                            },
                            {
                                extend: 'pdfHtml5',
                                className: 'btn btn-danger',
                                title: 'Verified User Accounts - ' + new Date().toLocaleDateString(),
                            }
                          ]
                    },
                    'info'
                    ]
                }
            });
        } );

        $(document).ready( function () {
            new DataTable('#rejectedUsersTable', {
                scrollX: true,
                order:[[1, 'desc']],
                language: {
                  emptyTable: "No rejected user accounts found."
                },
                layout: {
                    bottomStart: [
                      {
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                className: 'btn btn-success me-2',
                                title: 'Rejected User Accounts - ' + new Date().toLocaleDateString(),

                            },
                            {
                                extend: 'pdfHtml5',
                                className: 'btn btn-danger',
                                title: 'Rejected User Accounts - ' + new Date().toLocaleDateString(),
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