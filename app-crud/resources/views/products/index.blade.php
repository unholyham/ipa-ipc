<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Index</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/nodeJS@1.0.0/index.min.js"></script>
    
    <link rel="stylesheet" href="/styles/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <div class="d-flex align-items-center">
      <a class="navbar-brand" href="{{route('product.index')}}">
        <img src="/images/SSB_Logo.jpg" alt="Your Logo" height="70">
      </a>
    </div>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="vendorsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        VENDORS
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="vendorsDropdown">
                        <li><a class="dropdown-item" href="{{route('product.index')}}">Technical Proposal Submissions</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                    </ul>
                </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('product.create')}}">SUBMIT PROPOSAL</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">WHAT WE DO</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">CONTACT US</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
  <div class="row justify-content-between align-items-center">
    <div class="col-auto">
      <p class="m-0">Welcome, <span class="text-primary lead">{{ Auth::user()->name }}</span></p>
    </div>
    
    <div class="col-auto">
      <form method="post" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger" style="font-size:9px;" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="logout-button-tooltip" data-bs-title="Logout"><i class="bi bi-power"></i></button>
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
        @foreach($products as $product)
        @if($product->approvedStatus != 'Approved')
            <tr>
                <td><a href="{{route('product.edit', ['product' => $product])}}" class="editProductLink"><strong>{{$product->projectTitle}}</strong></a></td>
                <td>{{$product->created_at->format('j F Y g:i a') }}</td>
                <td>{{$product->projectNumber}}</td>
                <td>{{$product->region}}</td>
                <td>{{$product->preparedBy}}</td>
                <td>{{$product->mainContractor}}</td>
                <td>{{$product->reviewStatus}}</td>
                <td>{{$product->approvedStatus}}</td>
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
          @foreach($products as $product)
        @if($product->approvedStatus == 'Approved')
            <tr>
                <td><a href="{{route('product.edit', ['product' => $product])}}" class="editProductLink"><strong>{{$product->projectTitle}}</strong></a></td>
                <td>{{$product->created_at->format('j F Y g:i a') }}</td>
                <td>{{$product->projectNumber}}</td>
                <td>{{$product->region}}</td>
                <td>{{$product->preparedBy}}</td>
                <td>{{$product->mainContractor}}</td>
                <td>{{$product->reviewStatus}}</td>
                <td>{{$product->approvedStatus}}</td>
            </tr>
        @endif
        @endforeach
        </table>
    </div>
    </div>
  </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.3/js/dataTables.buttons.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.dataTables.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.print.min.js"></script>
    <script>
      const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
      const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        $(document).ready( function () {
            new DataTable('#pendingTable', {
                scrollX: true,
                order:[[1, 'desc']],
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