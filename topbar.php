<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bootstrap Sidebar Layout</title>
    <link href="./global-style.css" rel="stylesheet" />
    <link
        href="./bootstrap.min.css"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- //ajax cdn -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="http://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>

    <!-- graph -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
    </script>

    <!-- pdf -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>


    <script src="ajax-library.js"></script>
    <script src="application-helper.js"></script>
    <script src="application-notification.js"></script>

    <style>
        /* Custom styles for the sidebar */
        .sidebar {
            min-height: 100vh;
            /* Full height */
            background-color: #343a40;
            /* Sidebar background */
            color: white;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            /* Remove underline */
        }

        .sidebar a:hover {
            background-color: #495057;
            /* Darker on hover */
            border-radius: 5px;
            /* Rounded corners */
        }

        body {
            overflow-y: auto;
        }
    </style>
    <!-- grap -->

    <style>
        .line-graph {
            width: 100%;
            height: 300px;
            position: relative;
            border-left: 2px solid #333;
            border-bottom: 2px solid #333;
        }

        .line {
            position: absolute;
            width: 100%;
            height: 100%;
        }

        .line img {
            position: absolute;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }

        .line span {
            position: absolute;
            font-size: 12px;
            color: #333;
            transform: translate(-50%, 0);
        }
    </style>

    <!-- 2ndbarGraph -->
    <style>
        .bar-graph {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            height: 200px;
            border-left: 2px solid #333;
            border-bottom: 2px solid #333;
            padding: 10px;
        }

        .bar {
            position: relative;
            width: 50px;
            background-size: cover;
            background-position: center;
        }

        .bar img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }

        .bar span {
            position: absolute;
            top: -20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 12px;
            color: #333;
        }
    </style>

</head>

<body>
    <div class="container-fluid">
        <div class="row">