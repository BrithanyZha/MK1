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

        @if (Auth::user()->role === 'admin')
            {{-- Form Tambah Stok --}}
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3 bg-success text-white">
                            <h2 class="text-center m-0 font-weight-bold h3" style="font-size: 1.5rem;">Tambah Stok</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('tambah') }}" method="POST">
                                @csrf
                                <p class="text-danger mb-4" style="font-size: 0.8rem;">
                                    *Gunakan formulir ini untuk menambah stok bahan yang tersedia di outlet.
                                </p>
                                
                                {{-- Hidden field to keep the selected outlet ID --}}
                                <input type="hidden" name="outlet_id" value="{{ request()->input('outlet_id') }}">

                                {{-- Bahan --}}
                                <div class="form-group row">
                                    <label for="bahan_id" class="font-weight-bold col-sm-2 col-form-label">Bahan:</label>
                                    <div class="col-sm-10">
                                        <select name="bahan_id" class="form-control" required>
                                            <option value="">Pilih Bahan</option>
                                            @foreach ($bahan_inisiasi as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama_bahan }}</option>
                                            @endforeach
                                        </select>
                                        @error('bahan_id')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Unit --}}
                                <div class="form-group row">
                                    <label for="unit" class="font-weight-bold col-sm-2 col-form-label">Unit:</label>
                                    <div class="col-sm-10">
                                        <input type="hidden" id="unit_id" name="unit_id">
                                        <input type="text" id="unit_name" name="unit_name" class="form-control" readonly>
                                        @error('unit_id')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Kuantitas --}}
                                <div class="form-group row">
                                    <label for="qty_stok" class="font-weight-bold col-sm-2 col-form-label">Kuantitas:</label>
                                    <div class="col-sm-10">
                                        <input type="number" step="0.01" id="qty_stok" name="qty_stok" class="form-control" placeholder="Isi kuantitas stok, contoh: 750" min="0" required>
                                        @error('qty_stok')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Tombol Simpan --}}
                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah kamu yakin untuk menambahkan item ini ke dalam stok?');">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End of Form Tambah Stok --}}
        @endif

        {{-- List Stok Bahan --}}
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 text-white" style="background-color: #0fad73;">
                        <h2 class="text-center m-0 font-weight-bold h3" style="font-size: 1.5rem;">Daftar Bahan Dalam Stok</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="bahanTable" width="100%" cellspacing="0">
                                <thead class="thead-white">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Bahan</th>
                                        <th class="text-center">Kuantitas</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">In Stock</th>
                                  
                                        <th class="text-center">Outlet</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bahans as $bahan)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $bahan->bahan_inisiasi->nama_bahan }}</td>
                                            <td class="text-center">{{ $bahan->qty_stok }}</td>
                                            <td class="text-center">{{ $bahan->unit->unit }}</td>
                                            <td class="text-center">{{ $bahan->instock }}</td>
                                            
                                            <td class="text-center">{{ $bahan->outlet->nama_outlet }}</td>
                                            <td class="text-center">
                                                @if (Auth::user()->role === 'admin')
                                                    <a href="{{ route('editbahan', ['id' => $bahan->id]) }}?outlet_id={{ $bahan->outlet_id }}" class="btn btn-warning btn-sm w-auto">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                @else
                                                    <button class="btn btn-secondary btn-sm" disabled>View</button>
                                                @endif
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
        {{-- End of List Stok Bahan --}}
    </div>
@endsection

{{-- DataTable Scripts --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#bahanTable').DataTable({
            "pagingType": "simple_numbers",
            "lengthMenu": [
                [10, 25, 50, 200, -1],
                [10, 25, 50, 200, "All"]
            ],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ bahan",
                "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ bahan"
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const outletId = document.querySelector('input[name="outlet_id"]').value;
        const bahanSelect = document.querySelector('select[name="bahan_id"]');
        const unitIdInput = document.getElementById('unit_id');
        const unitNameInput = document.getElementById('unit_name');

        function loadBahans() {
            fetch(`/Addbahan/get-bahans/${outletId}`)
                .then(response => response.json())
                .then(data => {
                    bahanSelect.innerHTML = '<option value="">Pilih Bahan</option>';
                    data.forEach(bahan => {
                        bahanSelect.innerHTML += `<option value="${bahan.id}">${bahan.nama_bahan}</option>`;
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        loadBahans();

        bahanSelect.addEventListener('change', function() {
            const bahanId = this.value;
            fetch(`/Addbahan/get-unit/${bahanId}/${outletId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.unit_id && data.unit_name) {
                        unitIdInput.value = data.unit_id;
                        unitNameInput.value = data.unit_name;
                    } else {
                        unitIdInput.value = '';
                        unitNameInput.value = 'Unit not found';
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>
