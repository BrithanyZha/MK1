@extends('dashboard.index')
@if(Auth::user()->role==='admin')
    
    @section('topbar')
    <ul class="navbar-nav ml-auto">
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>

    </nav>
    @endsection

                                                        {{-- NAVITEM --}}

    @section('navitem')
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Stok Barang
            </div>

            
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Stok</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Olah Data Stok:</h6>
                        <a class="collapse-item" href="{{ route('tambah')}}">Add Stok</a>
                        <a class="collapse-item" href="{{ route('satuan')}}">Add Satuan</a>
                        <a class="collapse-item" href="{{ route('showbahan')}}">List Stok</a>
                        <a class="collapse-item" href="{{ route('history')}}">History</a>


                        
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Menu</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Olah Data Menu:</h6>
                        <a class="collapse-item" href="{{ route('addmenu.index')}}">Add Menu</a>
                        <a class="collapse-item" href="{{ route('listmenu') }}">List Menu</a>
                        <a class="collapse-item" href="{{ route('historymenu') }}">History</a>
                        <a class="collapse-item" href="{{ route('menuterjual') }}">Menu Terjual</a>
                        <a class="collapse-item" href="{{ route('menuterjual.show') }}"> History Menu Terjual</a>

                    
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Laporan Stok
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Cetak Laporan</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Detail:</h6>
                        <a class="collapse-item" href="perbandingan.html">Perbandingan</a>
                        <a class="collapse-item" href="perbandingan.html">Perhitungan</a>
 
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li>

    @endsection
    @section('main')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4>Tambah Menu</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('addmenu.store') }}" method="POST">
                        @csrf
    
                        <div class="form-group">
                            <label for="nama_menu">Nama Menu:</label>
                            <input type="text" id="nama_menu" name="nama_menu" class="form-control" required>
                            @error('nama_menu')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
    
                        <div id="bahan-container">
                            <div class="bahan-group">
                                <div class="form-group">
                                    <label for="nama_bahan_0">Nama Bahan:</label>
                                    <select id="nama_bahan_0" name="nama_bahan[]" class="form-control" required>
                                        @foreach($bahans as $bahan)
                                            <option value="{{ $bahan->nama_bahan }}">{{ $bahan->nama_bahan }}</option>
                                        @endforeach
                                    </select>
                                </div>
    
                                <div class="form-group">
                                    <label for="jml_takaran_0">Jumlah Takaran:</label>
                                    <input type="text" id="jml_takaran_0" name="jml_takaran[]" class="form-control" required>
                                </div>
    
                                <div class="form-group">
                                    <label for="satuan_0">Satuan:</label>
                                    <select id="satuan_0" name="satuan[]" class="form-control" required>
                                        @foreach($satuans as $satuan)
                                            <option value="{{ $satuan->satuan }}">{{ $satuan->satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <button type="button" id="add-more" class="btn btn-secondary">Add More</button>
                        <button type="submit" class="btn btn-primary">Tambahkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let counter = 1;
    
            document.getElementById('add-more').addEventListener('click', function () {
                const container = document.getElementById('bahan-container');
                const newGroup = document.createElement('div');
                newGroup.classList.add('bahan-group');
                newGroup.innerHTML = `
                    <div class="form-group">
                        <label for="nama_bahan_${counter}">Nama Bahan:</label>
                        <select id="nama_bahan_${counter}" name="nama_bahan[]" class="form-control" required>
                            @foreach($bahans as $bahan)
                                <option value="{{ $bahan->nama_bahan }}">{{ $bahan->nama_bahan }}</option>
                            @endforeach
                        </select>
                    </div>
    
                    <div class="form-group">
                        <label for="jml_takaran_${counter}">Jumlah Takaran:</label>
                        <input type="text" id="jml_takaran_${counter}" name="jml_takaran[]" class="form-control" required>
                    </div>
    
                    <div class="form-group">
                        <label for="satuan_${counter}">Satuan:</label>
                        <select id="satuan_${counter}" name="satuan[]" class="form-control" required>
                            @foreach($satuans as $satuan)
                                <option value="{{ $satuan->satuan }}">{{ $satuan->satuan}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="button" class="btn btn-danger remove-btn">Remove</button>
                    <br> <br> 
                `;
                container.appendChild(newGroup);
                counter++;
            });
    
            document.getElementById('bahan-container').addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-btn')) {
                    event.target.parentElement.remove();
                }
            });
        });
    </script>
    

    @endsection
   




@elseif(Auth::user()->role==='user')
    @section('navitem')

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Stok Barang
            </div>

            
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Stok</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Olah Data Stok:</h6>
                        {{-- <a class="collapse-item" href="">Add Stok</a> --}}
                        <a class="collapse-item" href="{{ route('showbahan') }}">List Stok</a>
                        {{-- <a class="collapse-item" href="ListStok.html">Stok Keluar</a> --}}
                        <a class="collapse-item" href="{{ route('history') }}">History</a>
 
                    </div>
                </div>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Menu</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Olah Data Menu:</h6>
                        {{-- <a class="collapse-item" href="utilities-color.html">Add Menu</a> --}}
                        <a class="collapse-item" href="{{ route('listmenu') }}">List Menu</a>
                    
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Laporan Stok
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Perbandingan</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Detail:</h6>
                        <a class="collapse-item" href="perbandingan.html">Perbandingan</a>
 
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li>

    @endsection

    @section('main')
            kosong
    @endsection
@endif