@extends('dashboard.index')

@if (Auth::user()->role === 'admin')

    @section('main')
        <div class="container-fluid">

            {{-- Menampilkan pesan sukses atau error --}}
            @if (session('success'))
                <div class="alert alert-success mt-2">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger mt-2">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Tambah Unit Section --}}
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3 bg-success text-white">
                            <h2 class="text-center m-0 font-weight-bold h3" style="font-size: 1.5rem;">Tambah Unit</h2>
                        </div>
                        <div class="card-body">
                            {{-- Form untuk menambah unit --}}
                            <form action="{{ route('addunit') }}" method="POST">
                                @csrf
                                <div class="form-group row">
                                    <label for="unit" class="font-weight-bold col-sm-2 col-form-label">Unit:</label>
                                    <div class="col-sm-10">
                                        <input type="text" id="unit" name="unit" placeholder="contoh: gram"
                                            class="form-control" required>
                                        @error('unit')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary"
                                        onclick="return confirm('Apakah Anda yakin ingin menambahkan unit ini?');">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End of Tambah Unit Section --}}

            {{-- Daftar Unit Section --}}
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3 text-white" style="background-color:#0fad73;">
                            <h2 class="text-center m-0 font-weight-bold h3" style="font-size: 1.5rem;">Daftar Unit</h2>
                        </div>
                        <div class="card-body">
                            <table id="bahanTable" class="table table-striped table-bordered table-hover">
                                <thead class="thead-white">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($units as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $item->unit }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('editunit', $item->id) }}" class="btn btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End of Daftar Unit Section --}}

        </div>

        {{-- DataTable Scripts --}}
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#bahanTable').DataTable({
                    "pagingType": "simple_numbers",
                    "lengthMenu": [[10, 25, 50, 200, -1], [10, 25, 50, 200, "All"]],
                    "language": {
                        "search": "Cari Unit:",
                        "lengthMenu": "Tampilkan _MENU_ unit",
                        "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ unit"
                    }
                });
            });
        </script>
    @endsection

@endif
