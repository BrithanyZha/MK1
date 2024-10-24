@extends('dashboard.index')

@section('main')
    <div class="container-fluid">

        {{-- Pesan Sukses atau Error --}}
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

        {{-- Tambah Bahan Inisiasi Section --}}
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 bg-success text-white">
                        <h2 class="text-center m-0 font-weight-bold h3">Tambah Bahan Inisiasi</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('add_bahan_inisiasi') }}" method="POST">
                            @csrf
                            {{-- Deskripsi Singkat Form --}}
                            <p class="text-danger mb-4" style="font-size: 0.8rem;">
                                *Gunakan formulir ini untuk menambahkan bahan baru ke dalam sistem.
                            </p>
                    
                            {{-- Hidden field to keep the selected outlet ID --}}
                            <input type="hidden" name="outlet_id" value="{{ request()->input('outlet_id') }}">
                    
                            {{-- Nama Bahan --}}
                            <div class="form-group row">
                                <label for="nama_bahan" class="font-weight-bold col-sm-2 col-form-label">Nama Bahan:</label>
                                <div class="col-sm-10">
                                    <input type="text" id="nama_bahan" name="nama_bahan" class="form-control" placeholder="Contoh: Susu" required>
                                    @error('nama_bahan')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                    
                            {{-- Isi Bersih --}}
                            <div class="form-group row">
                                <label for="qty_inisiasi" class="font-weight-bold col-sm-2 col-form-label">Isi Bersih:</label>
                                <div class="col-sm-10">
                                    <input type="number" id="qty_inisiasi" name="qty_inisiasi" class="form-control" placeholder="Contoh: 1000" required>
                                    @error('qty_inisiasi')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                            {{-- Kuantitas --}}
                            <div class="form-group row">
                                <label for="qty_stok" class="font-weight-bold col-sm-2 col-form-label">Kuantitas:</label>
                                <div class="col-sm-10">
                                    <input type="number" id="qty_stok" name="qty_stok" class="form-control" step="0.01" placeholder="Masukkan kuantitas berat per satu bungkus, contoh: 2000" min="0" required>
                                    @error('qty_stok')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Unit --}}
                            <div class="form-group row">
                                <label for="unit_id" class="font-weight-bold col-sm-2 col-form-label">Unit:</label>
                                <div class="col-sm-10">
                                    <select name="unit_id" class="form-control" required>
                                        <option value="" disabled selected>Pilih Unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Tombol Simpan --}}
                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah kamu yakin untuk menambahkan item ini?');">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- End of Tambah Bahan Inisiasi Section --}}

        {{-- Daftar Bahan Inisiasi Section --}}
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 text-white" style="background-color: #0fad73;">
                        <h2 class="text-center m-0 font-weight-bold h3">Daftar Bahan Inisiasi</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="bahanTable" class="table table-striped table-bordered table-hover" width="100%" cellspacing="0">
                                <thead class="thead-white">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama Bahan</th>
                                        <th class="text-center">Isi Bersih</th>
                                        

                                        <th class="text-center">Unit</th>
                                       
                                        <th class="text-center">Outlet</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bahanInisiasi as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $item->nama_bahan }}</td>
                                            <td class="text-center">{{ $item->qty_inisiasi }}</td>
                                        
                                            <td class="text-center">{{ $item->unit->unit }}</td>
                                            

                                            <td class="text-center">{{ $item->outlet->nama_outlet }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('editbahan_inisiasi', ['id' => $item->id, 'outlet_id' => $selectedOutlet]) }}" 
                                                   class="btn btn-warning btn-sm w-auto">
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
        </div>
    </div>

    {{-- DataTable Scripts --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#bahanTable').DataTable({
                pagingType: "simple_numbers",
                lengthMenu: [[10, 25, 50, 200, -1], [10, 25, 50, 200, "All"]],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ bahan",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ bahan"
                }
            });
        });
    </script>
@endsection
