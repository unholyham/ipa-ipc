<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects</title>
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
<div class="container pt-2">
  <h1 class="text-center">Projects</h1>

  <!--Start of Projects Table-->
  <div class="row">
    <div class="col mt-5 p-4 bg-white border-top rounded shadow-sm">
      <h2><i class="bi bi-ui-checks-grid"></i> Projects</h2>
    <div>
        <table class="table table-striped table-bordered table-hover" id="projectsTable">
          <thead>
            <tr>
            <th class="tableheader text-bg-dark">Project Title</th>
            <th class="tableheader text-bg-dark">Project Number</th>
            <th class="tableheader text-bg-dark">Sub Contractor</th>
            <th class="tableheader text-bg-dark">Main Contractor</th>
            </tr>
          </thead>
        @foreach($projects as $project)
            <tr>
                <td><a href="{{route('project.view', ['project' => $project->projectID])}}" class="viewProjectLink"><strong>{{$project->projectTitle}}</strong></a></td>
                <td>{{$project->projectNumber}}</td>
                <td>{{$project->subContractorCompany->companyName}}</td>
                <td>{{$project->mainContractorCompany->companyName}}</td>
            </tr>
        @endforeach
        </table>
    </div>
    </div>
  </div>
  <!--End of Projects Table-->

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
            new DataTable('#projectsTable', {
                scrollX: true,
                order:[[0, 'asc']],
                language: {
                  emptyTable: "No projects found."
                },
                layout: {
                    bottomStart: [
                      {
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                className: 'btn btn-success me-2',
                                title: 'Projects - ' + new Date().toLocaleDateString(),

                            },
                            {
                                extend: 'pdfHtml5',
                                className: 'btn btn-danger',
                                title: 'Projects - ' + new Date().toLocaleDateString(),
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