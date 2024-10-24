@extends('dashboard.index')

@if (Auth::user()->role === 'admin')
    @section('main')
    <div class="container-fluid">

        {{-- Pesan Sukses --}}
        @if(session('success'))
            <div class="alert alert-success mt-2">
                {{ session('success') }}
            </div>
        @endif
    
        {{-- Pesan Error --}}
        @if($errors->any())
            <div class="alert alert-danger mt-2">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    
        {{-- Form Transfer Bahan --}}
        <div class="row justify-content-center">
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 bg-success text-white">
                        <h2 class="text-center m-0 font-weight-bold h3" style="font-size: 1.5rem;">Transfer Bahan</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tambahtransferbahan') }}" method="POST">
                            @csrf
                            <p class="text-danger mb-4" style="font-size: 0.8rem;">
                                
                                *Gunakan formulir ini untuk memindahkan bahan antar outlet.
                            </p>
    
                            <!-- Menyimpan outlet asal -->
                            <input type="hidden" name="outlet_id" value="{{ request()->input('outlet_id') }}" id="outlet_id">
    
                            {{-- Transfer ke Outlet --}}
                            <div class="form-group row">
                                <label for="tfto" class="font-weight-bold col-sm-2 col-form-label">Transfer ke:</label>
                                <div class="col-sm-10">
                                    <select id="tfto" name="tfto" class="form-control" required>
                                        <option value="">Pilih Outlet Tujuan</option>
                                        @foreach ($outlet as $outlets)
                                            @if ($outlets->id != request()->input('outlet_id'))
                                                <option value="{{ $outlets->id }}">{{ $outlets->nama_outlet }}</option>
                                            @else
                                                <option value="{{ $outlets->id }}" disabled>{{ $outlets->nama_outlet }} (Asal)</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
    
                            {{-- Note --}}
                            <div class="form-group row">
                                <label for="note" class="font-weight-bold col-sm-2 col-form-label">Note:</label>
                                <div class="col-sm-10">
                                    <textarea id="note" name="note" class="form-control"></textarea>
                                    @error('note')
                                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
    
                            {{-- Bahan Dinamis --}}
                            <div id="bahan-container">
                                <div class="form-row align-items-center mb-3" id="bahan-group-0">
                                    <div class="col-md-2">
                                        <label for="bahan_id_0" class="font-weight-bold">Bahan:</label>
                                        <select id="bahan_id_0" name="bahan_id[]" class="form-control" required>
                                            <option value="" disabled selected>Pilih Bahan</option>
                                            @foreach ($bahans as $item)
                                                <option value="{{ $item->id }}">{{ $item->bahan_inisiasi->nama_bahan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
    
                                    <div class="col-md-2">
                                        <label for="qty_stok_0" class="font-weight-bold">Kuantitas:</label>
                                        <input type="number" step="0.01" id="qty_stok_0" name="qty_stok[]" class="form-control" placeholder="Contoh: 10000.7" min=0 required>
                                    </div>
    
                                    <div class="col-md-2">
                                        <label for="unit_name_0" class="font-weight-bold">Unit:</label>
                                        <input type="hidden" id="unit_id_0" name="unit_id[]">
                                        <input type="text" id="unit_name_0" name="unit_name[]" class="form-control" readonly>
                                    </div>
    
                                    <div class="col-md-2">
                                        <label>&nbsp;</label><br>
                                        <button type="button" class="btn btn-danger fas fa-trash-alt remove-bahan"></button>
                                    </div>
                                </div>
                            </div>
    
                            {{-- Tombol Tambah dan Transfer --}}
                            <div class="form-group mt-4 text-right">
                                <button type="button" id="add-more" class="btn btn-secondary">
                                    <i class="fas fa-plus-circle"></i> Tambah Bahan
                                </button>
                                <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin mentransfer item ini?');">
                                    <i class="fas fa-save"></i> Pindahkan
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
                let counter = 1;

                // Fungsi untuk mendapatkan bahan dari outlet
                function fetchBahans(outletId, dropdown, initialLoad = false) {
                    fetch(`/get-bahans/${outletId}`)
                        .then(response => response.json())
                        .then(data => {
                            dropdown.innerHTML = '<option value="" selected>Pilih Bahan</option>';
                            data.forEach(bahan => {
                                dropdown.innerHTML += `<option value="${bahan.id}">${bahan.bahan_inisiasi.nama_bahan}</option>`;
                            });

                            if (initialLoad && dropdown.options.length > 0) {
                                dropdown.selectedIndex = 0;
                                dropdown.dispatchEvent(new Event('change'));
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }

                // Fungsi untuk mendapatkan unit berdasarkan bahan
                function fetchUnit(bahanId, index) {
                    fetch(`/transferbahan/get-unittf/${encodeURIComponent(bahanId)}`)
                        .then(response => response.json())
                        .then(data => {
                            const unitIdInput = document.getElementById(`unit_id_${index}`);
                            const unitNameInput = document.getElementById(`unit_name_${index}`);

                            if (data && data.unit_id && data.unit_name) {
                                unitIdInput.value = data.unit_id;
                                unitNameInput.value = data.unit_name;
                            } else {
                                unitIdInput.value = '';
                                unitNameInput.value = '';
                            }
                        })
                        .catch(error => console.error('Error fetching unit:', error));
                }

                // Fungsi untuk mengikat event change pada dropdown bahan
                function attachUnitChangeEvent(index) {
                    const bahanDropdown = document.getElementById(`bahan_id_${index}`);
                    if (bahanDropdown) {
                        bahanDropdown.addEventListener('change', function() {
                            const bahanId = this.value;
                            fetchUnit(bahanId, index);
                            updateDropdownOptions();
                        });
                    }
                }

                // Fungsi untuk memperbarui dropdown bahan
                function updateDropdownOptions() {
                    const selectedBahanIds = Array.from(document.querySelectorAll('select[name="bahan_id[]"]'))
                        .map(dropdown => dropdown.value)
                        .filter(value => value);

                    document.querySelectorAll('select[name="bahan_id[]"]').forEach(dropdown => {
                        const currentValue = dropdown.value;
                        const options = dropdown.querySelectorAll('option');

                        options.forEach(option => {
                            if (selectedBahanIds.includes(option.value) && option.value !== currentValue) {
                                option.style.display = 'none';
                            } else {
                                option.style.display = 'block';
                            }
                        });
                    });
                }

                // Menambah baris bahan dinamis
                document.getElementById('add-more').addEventListener('click', function() {
                    const container = document.getElementById('bahan-container');
                    const newGroup = document.createElement('div');
                    newGroup.classList.add('form-row', 'align-items-center', 'mb-3');
                    newGroup.id = `bahan-group-${counter}`;

                    newGroup.innerHTML = `
                        <div class="col-md-2">
                            <label for="bahan_id_${counter}"class="font-weight-bold">Bahan:</label>
                            <select id="bahan_id_${counter}" name="bahan_id[]" class="form-control" required>
                                <option value="" selected>Pilih Bahan</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label for="qty_stok_${counter}"class="font-weight-bold">Kuantitas:</label>
                            <input type="number" step="0.01" id="qty_stok_${counter}" name="qty_stok[]" class="form-control" placeholder="contoh: 10000.7" min=0 required>
                        </div>

                        <div class="col-md-2">
                            <label for="unit_name_${counter}" class="font-weight-bold">Unit:</label>
                            <input type="hidden" id="unit_id_${counter}" name="unit_id[]">
                            <input type="text" id="unit_name_${counter}" name="unit_name[]" class="form-control" readonly>
                        </div>

                        <div class="col-md-2">
                            <label>&nbsp;</label><br>
                            <button type="button" class="btn btn-danger fas fa-trash-alt remove-bahan"></button>
                        </div>
                    `;

                    container.appendChild(newGroup);

                    fetchBahans(document.getElementById('outlet_id').value, newGroup.querySelector('select[name="bahan_id[]"]'), true);
                    attachUnitChangeEvent(counter);
                    updateDropdownOptions();

                    counter++;
                });

                // Menghapus baris bahan
                document.getElementById('bahan-container').addEventListener('click', function(event) {
                    if (event.target.closest('.remove-bahan')) {
                        if (confirm('Apakah Anda yakin ingin menghapus bahan ini?')) {
                            event.target.closest('.form-row').remove();
                            updateDropdownOptions();
                        }
                    }
                });

                // Initial event attachment
                attachUnitChangeEvent(0);
            });
        </script>
    @endsection
@endif
