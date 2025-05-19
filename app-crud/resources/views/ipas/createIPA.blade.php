<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css" />
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
        <li class="nav-item">
          <a class="nav-link" href="{{route('product.index')}}">VENDORS</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{route('product.create')}}">REGISTER</a>
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
    <h1>Create A Product</h1>
    <div class="mt-4 rounded">
        <form method="post" action="{{route('product.store')}}">
        @csrf
        <div class="row mb-2">
            <div class="col">
                <input type="text" class="form-control" name="name" placeholder="Name">
            </div>
            <div class="col">
                <input type="text" class="form-control" name="qty" placeholder="Quantity">
            </div>
        </div>
        <div class="row mb-2">
            <div class="col">
                <input type="text" class="form-control" name="price" placeholder="Price">
            </div>
            <div class="col">
                <input type="text" class="form-control" name="description" placeholder="Description">
            </div>
        </div>
        
        <div class="row">
            <div class="col">
                <input type="submit" class="form-control bg-primary text-light" value="Submit">
            </div>
            <div class="col">
                <input type="reset" class="form-control bg-light" value="Reset">
            </div>
        </div>
    </form>
    </div>
</div>    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
</body>
</html>