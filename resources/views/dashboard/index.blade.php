<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('halaman_dashboard/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Custom styles for this template-->
    <link href="{{ asset('halaman_dashboard/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Custom styles for the outlet dropdown */
        .outlet-dropdown {
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
            font-size: 16px;
            background-color: #f8f9fa;
            border: 2px solid #094d2b;
            color: #495057;
            transition: all 0.3s ease;
        }

        .outlet-dropdown:focus {
            border-color: #094d2b;
            box-shadow: 0px 4px 8px rgba(1, 47, 25, 0.25);
        }

        /* Custom scrollbar for WebKit browsers */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f4f4f4;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #0fad73;
            border-radius: 10px;
            border: 2px solid #f4f4f4;
        }

        /* For Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: #0fad73 #f4f4f4;
        }

        /* Hover effect for sidebar links */
        .collapse-item:hover {
            background-color: #094d2b;
            color: #fff;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
        }

        #sidebarToggle {
            display: inline-block;
            margin: 0 auto;
            text-align: center;
        }

        #wrapper {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #accordionSidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px; /* Adjusted width */
            background-color: #0fad73; /* Green for the sidebar */
            z-index: 1000;
        }

        #content-wrapper {
            margin-left: 250px; /* Match sidebar width */
            width: calc(100% - 250px);
            background-color: white;
            position: relative;
        }

        .topbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 999;
            background-color: white;
            padding-left: 250px; /* Match sidebar width */
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
        }

        .topbar .navbar-nav {
            margin-left: auto;
            display: flex;
            align-items: center;
        }

        .navbar-nav .nav-item {
            margin-left: 20px;
        }

        .container-fluid {
            margin-top: 80px; /* Increased top margin for spacing */
        }

        #accordionSidebar .scrollable {
            height: calc(100vh - 80px); /* Sidebar scrollable section */
            overflow-y: auto;
        }

        .user-info {
            margin-right: 15px;
            display: flex;
            align-items: center;
        }

        .user-info span {
            margin-right: 10px;
            color: #5a5c69;
        }

        .nav-link i {
            margin-right: 5px;
        }
    </style>
</head>

<body id="page-top">

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light topbar static-top shadow">
    <!-- Sidebar Toggle -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <div class="navbar-nav ml-auto">
        




        <!-- User Info -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600">{{ Auth::user()->role . ' : ' . Auth::user()->name }}</span>
                <i class="fas fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                </a>
            </div>
        </li>
    </div>
</nav>
<!-- End of Topbar -->

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand (Outside scrollable content) -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('history') }}">
                <div class="sidebar-brand-text mx-3">STOK LAKESIDE F&B GROUP</div>
            </a>

            <!-- Scrollable section (Only this part scrolls) -->
            <div style="height: 100vh; overflow-y: auto; overflow-x: hidden; scrollbar-width: thin; scrollbar-color: #0fad73 #f4f4f4;">
                <!-- Divider -->
                <hr class="sidebar-divider my-0">

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Outlet Selection Dropdown -->
                <li class="nav-item">
                    <div class="form-group mx-2">
                        @if (isset($outlet))
                            <form method="GET" action="{{ url()->current() }}">
                                <select class="form-control outlet-dropdown" id="outletSelect" name="outlet_id" onchange="this.form.submit()">
                                    <option value="" {{ request('outlet_id') ? '' : 'selected' }}>Pilih Outlet</option>
                                    @foreach ($outlet as $outletItem)
                                        <option value="{{ $outletItem->id }}" {{ request('outlet_id') == $outletItem->id ? 'selected' : '' }}>
                                            {{ $outletItem->nama_outlet }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        @else
                            <p class="text-center text-white">No outlets available</p>
                        @endif
                    </div>
                </li>

                <!-- Admin Role Navigation -->
                @if (Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                            <i class="fas fa-fw fa-cog"></i>
                            <span>Per Outlet</span>
                        </a>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <h6 class="collapse-header">Olah Stok:</h6>
                                <a class="collapse-item" href="{{ route('bahan_inisiasi') }}?outlet_id={{ request('outlet_id') }}">Inisiasi Bahan</a>
 
                                <a class="collapse-item" href="{{ route('tambah.index') }}?outlet_id={{ request('outlet_id') }}">Tambah Bahan</a>
                                <a class="collapse-item" href="{{ route('purchaseOrder') }}?outlet_id={{ request('outlet_id') }}">Bahan Terbeli</a>
                                <a class="collapse-item" href="{{ route('transferbahan') }}?outlet_id={{ request('outlet_id') }}">Transfer</a>
                                <a class="collapse-item" href="{{ route('kurangview') }}?outlet_id={{ request('outlet_id') }}">Kurangi</a>
                                <a class="collapse-item" href="{{ route('history') }}?outlet_id={{ request('outlet_id') }}">Riwayat Bahan</a>

                                <hr class="sidebar-divider">

                                <h6 class="collapse-header">Olah Menu:</h6>

                                <a class="collapse-item" href="{{ route('addmenu.index') }}?outlet_id={{ request('outlet_id') }}">Resep</a>
                                <a class="collapse-item" href="{{ route('menuterjual') }}">Menu Terjual</a>
                                <a class="collapse-item" href="{{ route('historymt') }}?outlet_id={{ request('outlet_id') }}">Riwayat Menu Terjual</a>
                            </div>
                        </div>
                    </li>

                    <hr class="sidebar-divider">

                    <!-- Admin Management Navigation -->
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
                            <i class="fas fa-fw fa-wrench"></i>
                            <span>Manajemen</span>
                        </a>
                        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <h6 class="collapse-header">Olah Manajemen:</h6>
                                <a class="collapse-item" href="{{ route('outlet') }}">Outlet</a>
                                <a class="collapse-item" href="{{ route('unit') }}">Unit</a>

                            </div>
                        </div>
                    </li>

                    <!-- Sidebar Toggler -->
                    <div class="text-center">
                        <button class="rounded-circle border-0" id="sidebarToggle"></button>
                    </div>
                @elseif(Auth::user()->role === 'user')
                    <!-- User Role Navigation -->



                    <!-- Sidebar Toggler -->
                    <div class="text-center">
                        <button class="rounded-circle border-0" id="sidebarToggle"></button>
                    </div>
                @endif

            </div> <!-- End of Scrollable Section -->

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Area to display search results -->
                    <div id="searchResults" class="mt-3"></div>
                    @yield('main')
                </div>

                <!-- Scroll to Top Button-->
                <a class="scroll-to-top rounded" href="#page-top">
                    <i class="fas fa-angle-up"></i>
                </a>

                <!-- Logout Modal -->
                <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="btn btn-primary" type="submit">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Font Awesome -->
                <script src="https://kit.fontawesome.com/3275fd4139.js" crossorigin="anonymous"></script>

                <!-- Bootstrap core JavaScript-->
                <script src="{{ asset('halaman_dashboard/vendor/jquery/jquery.min.js') }}"></script>
                <script src="{{ asset('halaman_dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

                <!-- Core plugin JavaScript-->
                <script src="{{ asset('halaman_dashboard/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

                <!-- Custom scripts for all pages-->
                <script src="{{ asset('halaman_dashboard/js/sb-admin-2.min.js') }}"></script>

                <!-- Page level plugins -->
                <script src="{{ asset('halaman_dashboard/vendor/chart.js/Chart.min.js') }}"></script>

                <!-- Page level custom scripts -->
                <script src="{{ asset('halaman_dashboard/js/demo/chart-area-demo.js') }}"></script>
                <script src="{{ asset('halaman_dashboard/js/demo/chart-pie-demo.js') }}"></script>

                <!-- Include DataTables CSS and JS -->
                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
                <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
                <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
                <!-- Select2 JS -->
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Initialize DataTables -->
<script>
    $(document).ready(function() {
        $('#bahanTable, #bahanTable2, #bahanTable3, #historyTable').DataTable();

        $('#outletSelect').change(function() {
            var selectedOutlet = $(this).val();
            var currentUrl = window.location.href.split('?')[0];
            window.location.href = currentUrl + "?outlet_id=" + selectedOutlet;
        });

        $('.form-group #menu_id').select2({
            placeholder: "Select Nama Menu",
            allowClear: true
        });
    });
    
</script>







@stack('scripts')

</body>

</html>
