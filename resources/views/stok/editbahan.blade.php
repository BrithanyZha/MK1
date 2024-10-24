@extends('dashboard.index')

@if (Auth::user()->role === 'admin')
    @section('main')
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3 bg-success text-white">
                            <h2 class="text-center m-0 font-weight-bold h3">Edit Bahan</h2>
                        </div>
                        <div class="card-body">
                            {{-- Pesan Sukses --}}
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            {{-- Form Edit Ingredient --}}
                            <form action="{{ route('updatebahan', $bahan->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                {{-- Hidden Field untuk Outlet ID --}}
                                <input type="hidden" name="outlet_id" value="{{ $selectedOutlet }}">

                                {{-- Nama Bahan --}}
                                <div class="form-group row align-items-center">
                                    <label for="bahan_id" class="font-weight-bold font-weight-bold col-md-2 col-form-label font-weight-bold">Bahan:</label>
                                    <div class="col-md-10">
                                        <input type="hidden" id="bahan_id" name="bahan_id" value="{{ $bahan->bahan_id }}">
                                        <input type="text" class="form-control" value="{{ $bahan->bahan_inisiasi->nama_bahan }}" readonly>
                                        @error('bahan_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Quantity Input --}}
                                <div class="form-group row align-items-center">
                                    <label for="qty_stok" class="font-weight-bold col-md-2 col-form-label font-weight-bold">Quantity:</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" id="qty_stok" name="qty_stok"
                                            value="{{ old('qty_stok', $bahan->qty_stok) }}">
                                        @error('qty_stok')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Unit Input (Read-only) --}}
                                <div class="form-group row align-items-center">
                                    <label for="unit" class="font-weight-bold col-md-2 col-form-label font-weight-bold">Unit:</label>
                                    <div class="col-md-10">
                                        <input type="hidden" id="unit_id" name="unit_id" value="{{ $bahan->unit_id }}">
                                        <input type="text" id="unit_name" name="unit_name" class="form-control"
                                            value="{{ $bahan->unit->unit }}" readonly>
                                        @error('unit_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Tombol Aksi --}}
                                <div class="d-flex justify-content-end mt-4">
                                    <a href="{{ route('tambah.index', ['outlet_id' => $selectedOutlet]) }}" class="btn btn-secondary d-flex align-items-center mr-2">
                                        <i class="fas fa-times-circle mr-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary d-flex align-items-center" onclick="return confirm('Apakah Anda yakin untuk mengubah kuantitas bahan?');">
                                        <i class="fas fa-check-circle mr-1"></i> Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Script untuk Load Data dan Update Unit --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const outletId = document.querySelector('input[name="outlet_id"]').value;
                const bahanSelect = document.querySelector('select[name="bahan_id"]');
                const unitIdInput = document.getElementById('unit_id');
                const unitNameInput = document.getElementById('unit_name');

                function loadBahans() {
                    fetch(`/Addbahan/get-bahans/${outletId}`)
                        .then(response => response.json())
                        .then(data => {
                            bahanSelect.innerHTML = `<option value="{{ $bahan->bahan_id }}" selected>{{ $bahan->bahan_inisiasi->nama_bahan }}</option>`;
                            data.forEach(bahan => {
                                bahanSelect.innerHTML += `<option value="${bahan.id}">${bahan.nama_bahan}</option>`;
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }

                // Load bahans saat halaman dimuat
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
    @endsection
@endif
