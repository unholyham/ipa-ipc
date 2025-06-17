<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Companies</title>
    <!--Include Head CDN-->
    @include('partials.headcdn')
    <!--End of Head CDN-->
    <link rel="stylesheet" href="/styles/style.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-white bg-gradient">
  <!--Include Navbar Based on Role-->
  @if(Auth::user()->role->roleName === 'admin')
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
  <h1 class="text-center">Manage Companies</h1>

  <!--Start of Companies Table-->
  <div class="row">
    <div class="col mt-5 p-4 bg-white border-top rounded shadow-sm">
      <h2><i class="bi bi-buildings"></i> Companies</h2>
    <div>
        <table class="table table-striped table-bordered table-hover" id="companiesTable">
          <thead>
            <tr>
            <th class="tableheader text-bg-dark">Company Name</th>
            <th class="tableheader text-bg-dark">Contractor Type</th>
            <th class="tableheader text-bg-dark">Email</th>
            </tr>
          </thead>
        @foreach($companies as $company)
        @if($company->contractorType == 'sub contractor')
            <tr>
                <td><a href="{{route('company.view', ['company' => $company->companyID])}}" class="viewCompanyLink"><strong>{{$company->companyName}}</strong></a></td>
                <td>{{$company->contractorType}}</td>
                <td>{{$company->companyEmail}}</td>
            </tr>
        @endif
        @endforeach
        </table>
    </div>
    </div>
  </div>
  <!--End of Companies Table-->

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
            new DataTable('#companiesTable', {
                scrollX: true,
                order:[[0, 'asc']],
                language: {
                  emptyTable: "No companies found."
                },
                layout: {
                    bottomStart: [
                      {
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                className: 'btn btn-success me-2',
                                title: 'Companies - ' + new Date().toLocaleDateString(),

                            },
                            {
                                extend: 'pdfHtml5',
                                className: 'btn btn-danger',
                                title: 'Companies - ' + new Date().toLocaleDateString(),
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