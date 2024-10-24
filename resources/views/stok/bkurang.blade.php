@extends('dashboard.index')

@if (Auth::user()->role === 'admin')

@section('main')
<div class="container-fluid">

    {{-- Pesan Sukses --}}
    @if (session('success'))
        <div class="alert alert-success mt-2">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan Error --}}
    @if ($errors->any())
        <div class="alert alert-danger mt-2">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Kurangi Stok Bahan --}}
    <div class="row justify-content-center">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-success text-white">
                    <h2 class="text-center m-0 font-weight-bold h3" style="font-size: 1.5rem;">Kurangi Stok Bahan</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('kurang') }}" method="POST" id="kurangForm">
                        @csrf
                        <p class="text-danger mb-4" style="font-size: 0.8rem;">
                            *Gunakan formulir ini untuk mengurangi jumlah stok bahan di outlet.
                        </p>

                        <!-- Hidden field untuk menyimpan outlet ID -->
                        <input type="hidden" name="outlet_id" value="{{ request()->input('outlet_id') }}" id="outlet_id">

                        {{-- Pilihan Bahan --}}
                        <div class="form-group row">
                            <label for="bahan_id" class="font-weight-bold col-sm-2 col-form-label">Bahan:</label>
                            <div class="col-sm-10">
                                <select name="bahan_id" class="form-control" required>
                                    <option value="" disabled selected>Pilih Bahan</option>
                                    @foreach ($bahans as $item)
                                        <option value="{{ $item->id }}">{{ $item->bahan_inisiasi->nama_bahan }}</option>
                                    @endforeach
                                </select>
                                @error('bahan_id')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Unit --}}
                        <div class="form-group row">
                            <label for="unit_name" class="font-weight-bold col-sm-2 col-form-label">Unit:</label>
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
                                <input type="number" step="0.01" id="qty_stok" name="qty_stok" class="form-control"
                                    placeholder="contoh: 10000" min=0 required>
                                @error('qty_stok')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Tombol Kurangi --}}
                        <div class="form-group mt-4 text-right">
                            <button type="submit" class="btn btn-primary"
                                onclick="return confirm('Apakah kamu yakin untuk mengurangi kuantitas item ini?');">
                                <i class="fas fa-minus-circle"></i> Kurangi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const outletId = document.querySelector('input[name="outlet_id"]').value;
            const bahanSelect = document.querySelector('select[name="bahan_id"]');
            const unitIdInput = document.getElementById('unit_id');
            const unitNameInput = document.getElementById('unit_name');

            function loadBahans() {
                fetch(`/bahankurang/get-bahans/${outletId}`)
                    .then(response => response.json())
                    .then(data => {
                        bahanSelect.innerHTML = '<option value="" disabled selected>Pilih Bahan</option>';
                        data.forEach(bahan => {
                            bahanSelect.innerHTML += `<option value="${bahan.id}">${bahan.nama_bahan}</option>`;
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }

            // Load bahan saat halaman dimuat
            loadBahans();

            bahanSelect.addEventListener('change', function() {
                const bahanId = this.value;
                fetch(`/bahankurang/get-unit/${bahanId}/${outletId}`)
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

@endsection

@endif
