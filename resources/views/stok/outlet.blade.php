@extends('dashboard.index')

@if (Auth::user()->role === 'admin')

@section('main')
<div class="container-fluid">

    {{-- Tambah Outlet Section --}}
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-success text-white">
                    <h2 class="text-center m-0 font-weight-bold h3" style="font-size: 1.5rem;">Tambah Outlet</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('tambahoutlet') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label for="nama_outlet" class="font-weight-bold col-sm-2 col-form-label">Nama Outlet:</label>
                            <div class="col-sm-10">
                                <input type="text" id="nama_outlet" name="nama_outlet" class="form-control" required>
                                @error('nama_outlet')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="font-weight-bold col-sm-2 col-form-label">Address:</label>
                            <div class="col-sm-10">
                                <textarea id="address" name="address" class="form-control" required></textarea>
                                @error('address')
                                <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary"
                                    onclick="return confirm('Are you sure you want to add this Outlet?');">
                                <i class="fas fa-save"></i> Tambahkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- End of Tambah Outlet Section --}}

    {{-- List dari Outlet Section --}}
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 text-white" style="background-color: #0fad73;">
                    <h2 class="text-center m-0 font-weight-bold h3" style="font-size: 1.5rem;">List dari Outlet</h2>
                </div>
                <div class="card-body">
                    <table id="outletTable" class="table table-striped table-bordered table-hover">
                        <thead class="thead-white">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Outlet</th>
                                <th class="text-center">Address</th>
                                {{-- <th class="text-center">Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($outlet as $outlets)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $outlets->nama_outlet }}</td>
                                    <td class="text-center">{{ $outlets->address }}</td>
                                    {{-- <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('editoutlet', $outlets->id) }}" class="btn btn-primary mr-2">Edit</a>
                                        </div>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- End of List dari Outlet Section --}}

</div>

{{-- DataTable Scripts --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#outletTable').DataTable({
            "pagingType": "simple_numbers",
            "lengthMenu": [[10, 25, 50, 200, -1], [10, 25, 50, 200, "All"]],
            "language": {
                "search": "Cari Outlet:",
                "lengthMenu": "Tampilkan _MENU_ outlet",
                "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ outlet"
            }
        });
    });
</script>

@endsection

@endif
