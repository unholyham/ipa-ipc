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
            <th class="tableheader text-bg-warning">Role</th>
            <th class="tableheader text-bg-warning">Verification Status</th>
            </tr>
          </thead>
        @foreach($users as $user)
        @if($user->verificationStatus == 'Pending')
            <tr>
                <td><a href="{{route('admin.users.view', ['user' => $user])}}" class="viewUserLink"><strong>{{$user->name}}</strong></a></td>
                <td>{{$user->created_at->format('j F Y g:i a') }}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->role}}</td>
                <td>{{$user->verificationStatus}}</td>
            </tr>
        @endif
        @endforeach
        </table>
    </div>
    </div>
  </div>
  <!--End of Pending User Accounts Table-->

  <!--Start of Approved User Accounts Table-->
  <div class="row">
    <div class="col mt-5 p-4 bg-white border-top rounded shadow-sm">
      <h2><i class="bi bi-check2-square"></i> Approved User Accounts</h2>
    <div>
        <table class="table table-striped table-bordered table-hover" id="approvedUsersTable">
          <thead>
            <tr>
            <th class="tableheader text-bg-success">Name</th>
            <th class="tableheader text-bg-success">Created On</th>
            <th class="tableheader text-bg-success">Email</th>
            <th class="tableheader text-bg-success">Role</th>
            <th class="tableheader text-bg-success">Verification Status</th>
            </tr>
          </thead>
          @foreach($users as $user)
          @if($user->verificationStatus == 'Approved' && $user->role != 'admin')
            <tr>
                <td><a href="{{route('admin.users.view', ['user' => $user])}}" class="viewUserLink"><strong>{{$user->name}}</strong></a></td>
                <td>{{$user->created_at->format('j F Y g:i a') }}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->role}}</td>
                <td>{{$user->verificationStatus}}</td>
            </tr>
        @endif
        @endforeach
        </table>
    </div>
    </div>
  </div>
  <!--End of Approved User Accounts Table-->

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
            <th class="tableheader text-bg-danger">Role</th>
            <th class="tableheader text-bg-danger">Verification Status</th>
            <th class="tableheader text-bg-danger">Remarks</th>
            </tr>
          </thead>
        @foreach($users as $user)
        @if($user->verificationStatus == 'Rejected')
            <tr>
                <td><a href="{{route('admin.users.view', ['user' => $user])}}" class="viewUserLink"><strong>{{$user->name}}</strong></a></td>
                <td>{{$user->created_at->format('j F Y g:i a') }}</td>
                <td>{{$user->email}}</td>
                <td>{{$user->role}}</td>
                <td>{{$user->verificationStatus}}</td>
                <td>{{$user->remarks}}</td>
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
            new DataTable('#approvedUsersTable', {
                scrollX: true,
                order:[[1, 'desc']],
                language: {
                  emptyTable: "No approved user accounts found."
                },
                layout: {
                    bottomStart: [
                      {
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                className: 'btn btn-success me-2',
                                title: 'Approved User Accounts - ' + new Date().toLocaleDateString(),

                            },
                            {
                                extend: 'pdfHtml5',
                                className: 'btn btn-danger',
                                title: 'Approved User Accounts - ' + new Date().toLocaleDateString(),
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