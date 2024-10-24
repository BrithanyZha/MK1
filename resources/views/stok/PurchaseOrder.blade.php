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

            {{-- Form Tambah Bahan Terbeli --}}
            <div class="row justify-content-center">
                <div class="col-lg-12 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3 bg-success text-white">
                            <h2 class="text-center m-0 font-weight-bold h3">Tambah Bahan Terbeli</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('tambahpurchaseOrder') }}" method="POST">
                                @csrf
                                
                                <p class="text-danger mb-4" style="font-size: 0.8rem;">
                                    *Gunakan formulir ini untuk menambahkan bahan yang telah dibeli ke dalam sistem.
                                </p>
                                
                                {{-- Hidden outlet ID --}}
                                <input type="hidden" name="outlet_id" value="{{ request()->input('outlet_id') }}">

                                {{-- Bahan Dinamis --}}
                                <div id="bahan-container">
                                    <div class="form-row align-items-center mb-3" id="bahan-group-0">
                                        <div class="col-md-2">
                                            <label for="bahan_id_0" class="font-weight-bold">Bahan:</label>
                                            <select id="bahan_id_0" name="bahan_id[]" class="form-control" onchange="fetchUnitPo(0)">
                                                <option value="">Pilih Bahan</option>
                                                @foreach ($bahan as $item)
                                                    <option value="{{ $item->id }}">{{ $item->bahan_inisiasi->nama_bahan }}</option>
                                                @endforeach
                                            </select>
                                            @error('bahan_id.*')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <input type="hidden" id="unit_id_0" name="unit_id[]">

                                        <div class="col-md-2">
                                            <label for="qty_order_0" class="font-weight-bold">Kuantitas Order:</label>
                                            <input id="qty_order_0" name="qty_order[]" type="number" class="form-control" step="any" placeholder="contoh: 15" onchange="calculateSubtotal(0)" min=0>
                                            @error('qty_order.*')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                         
                                        <div class="col-md-2">
                                            <label for="unit_cost_0" class="font-weight-bold">Harga:</label>
                                            <input id="unit_cost_0" name="unit_cost[]" type="number" class="form-control" step="any" onchange="calculateSubtotal(0)" placeholder="contoh: 26000" min=0>
                                            @error('unit_cost.*')
                                                <div class="alert alert-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-2">
                                            <label for="subtotal_0" class="font-weight-bold">Subtotal:</label>
                                            <input id="subtotal_0" name="subtotal[]" type="number" class="form-control" step="any" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="in_stock_0" class="font-weight-bold">In Stock:</label>
                                            <input id="in_stock_0" name="in_stock[]" type="number" class="form-control" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label>&nbsp;</label><br>
                                            <button type="button" class="btn btn-danger fas fa-trash-alt remove-bahan"></button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Note Section --}}
                                <div class="form-group row">
                                    <label for="note" class="font-weight-bold col-sm-2 col-form-label">Note:</label>
                                    <div class="col-sm-10">
                                        <textarea id="note" name="note" class="form-control"></textarea>
                                        @error('note')
                                            <div class="alert alert-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Tombol Tambah Bahan dan Submit --}}
                                <div class="form-group mt-4 text-right">
                                    <button type="button" class="btn btn-secondary" onclick="addBahanGroup()">
                                        <i class="fas fa-plus-circle"></i> Tambah Bahan
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                let groupIndex = 1;
            
                // Fungsi untuk mendapatkan bahan dari outlet
                function fetchBahans(outletId, dropdown, initialLoad = false) {
                    fetch(`/get-bahans/${outletId}`)
                        .then(response => response.json())
                        .then(data => {
                            dropdown.innerHTML = '<option value="">Pilih Bahan</option>';
                            data.forEach(bahan => {
                                dropdown.innerHTML += `<option value="${bahan.id}">${bahan.bahan_inisiasi.nama_bahan}</option>`;
                            });
            
                            if (initialLoad && dropdown.options.length > 0) {
                                dropdown.selectedIndex = 0;
                                dropdown.dispatchEvent(new Event('change')); // Trigger change event
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            
                // Fungsi untuk mendapatkan unit berdasarkan bahan dan stok
                function fetchUnitPo(index) {
                    const bahanId = document.getElementById(`bahan_id_${index}`).value;
                    const outletId = document.querySelector('input[name="outlet_id"]').value;
            
                    if (bahanId && outletId) {
                        fetch(`/purchaseOrder/get-unit/${bahanId}?outlet_id=${outletId}`)
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById(`unit_id_${index}`).value = data.unit_id || '';
                                
                            })
                            .catch(error => console.error('Error:', error));
            
                        fetch(`/purchaseOrder/get-instock/${bahanId}?outlet_id=${outletId}`)
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById(`in_stock_${index}`).value = data.qty_stok || '';
                            })
                            .catch(error => console.error('Error:', error));
                    }
                }
            
                // Fungsi untuk mengikat event change pada dropdown bahan
                function attachUnitChangeEvent(index) {
                    const bahanDropdown = document.getElementById(`bahan_id_${index}`);
                    if (bahanDropdown) {
                        bahanDropdown.addEventListener('change', function () {
                            const bahanId = this.value;
                            fetchUnitPo(index);
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
            
                // Fungsi untuk menambahkan grup bahan baru secara dinamis
                function addBahanGroup() {
                    const container = document.getElementById('bahan-container');
                    const newGroup = document.createElement('div');
                    newGroup.classList.add('form-row', 'align-items-center', 'mb-3');
                    newGroup.id = `bahan-group-${groupIndex}`;
            
                    newGroup.innerHTML = `
                        <div class="col-md-2">
                            <label for="bahan_id_${groupIndex}">Nama Bahan:</label>
                            <select id="bahan_id_${groupIndex}" name="bahan_id[]" class="form-control" required>
                                <option value="">Pilih Bahan</option>
                            </select>
                        </div>
                        <input type="hidden" id="unit_id_${groupIndex}" name="unit_id[]">
                        <div class="col-md-2">
                            <label for="qty_order_${groupIndex}">Kuantitas Order:</label>
                            <input id="qty_order_${groupIndex}" name="qty_order[]" type="number" class="form-control" step="any" onchange="calculateSubtotal(${groupIndex})" placeholder="contoh: 15" min=0>
                        </div>
                 
                        <div class="col-md-2">
                            <label for="unit_cost_${groupIndex}">Harga:</label>
                            <input id="unit_cost_${groupIndex}" name="unit_cost[]" type="number" class="form-control" step="any" onchange="calculateSubtotal(${groupIndex})" placeholder="contoh: 26000" min=0>
                        </div>
                        <div class="col-md-2">
                            <label for="subtotal_${groupIndex}">Subtotal:</label>
                            <input id="subtotal_${groupIndex}" name="subtotal[]" type="number" class="form-control" step="any" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="in_stock_${groupIndex}">In Stock:</label>
                            <input id="in_stock_${groupIndex}" name="in_stock[]" type="number" class="form-control" readonly>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label><br>
                            <button type="button" class="btn btn-danger fas fa-trash-alt remove-bahan"></button>
                        </div>
                    `;
            
                    container.appendChild(newGroup);
            
                    // Dapatkan data bahan yang tersedia
                    fetchBahans(document.querySelector('input[name="outlet_id"]').value, newGroup.querySelector('select[name="bahan_id[]"]'), true);
                    attachUnitChangeEvent(groupIndex);
                    updateDropdownOptions();
            
                    groupIndex++;
                }
            
                // Fungsi untuk menghitung subtotal setiap kali kuantitas atau harga berubah
                function calculateSubtotal(index) {
                    const qtyOrder = parseFloat(document.getElementById(`qty_order_${index}`).value) || 0;
                    const unitCost = parseFloat(document.getElementById(`unit_cost_${index}`).value) || 0;
                    const subtotal = qtyOrder * unitCost;
                    document.getElementById(`subtotal_${index}`).value = subtotal.toFixed(2);
                }
            
                // Event listener untuk menghapus bahan dinamis
                document.addEventListener('click', function (event) {
                    if (event.target.closest('.remove-bahan')) {
                        if (confirm('Apakah Anda yakin ingin menghapus bahan ini?')) {
                            event.target.closest('.form-row').remove();
                            updateDropdownOptions();
                        }
                    }
                });
            
                // Fungsi untuk menampilkan bahan yang belum dipilih
                function getBahanOptions() {
                    @php
                        $options = '';
                        foreach ($bahan as $item) {
                            $options .= "<option value=\"{$item->id}\">{$item->bahan_inisiasi->nama_bahan}</option>";
                        }
                    @endphp
                    return `{!! $options !!}`;
                }
            
                document.addEventListener('DOMContentLoaded', function () {
                    fetchUnitPo(0); // Pastikan untuk memanggil fungsi ini untuk item pertama (array ke-0)
                    attachUnitChangeEvent(0); // Attach the event listener for the first item
                });
            </script>
            
            
            
    @endsection
@endif
