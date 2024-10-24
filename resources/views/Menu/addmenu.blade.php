@extends('dashboard.index')

@section('main')
    <div class="container-fluid">
        <div class="col-md-12">
            {{-- Alert Sukses --}}
            @if (session('success'))
                <div class="alert alert-success mt-2">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Alert Error --}}
            @if (session('error'))
                <div class="alert alert-danger mt-2">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Tampilkan Semua Error --}}
            @if ($errors->any())
                <div class="alert alert-danger mt-2">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Tambah Resep --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-success text-white">
                    <h2 class="text-center m-0 font-weight-bold h3">Tambahkan Resep</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('addmenu.store') }}" method="POST">
                        @csrf
                        <p class="text-danger mb-4" style="font-size: 0.8rem;">
                            *Gunakan formulir ini untuk menambahkan dan menentukan takaran bahan-bahan yang diperlukan dalam resep menu.
                        </p>
                        <!-- Field Hidden untuk Outlet ID -->
                        <input type="hidden" id="outlet_id" name="outlet_id" value="{{ $outlet_id }}">

                        <!-- Nama Menu as a regular text field -->
                        <div class="form-group">
                            <label for="nama_menu" class="mr-2">Nama Menu:</label>
                            <input type="text" id="nama_menu" name="nama_menu" class="form-control" required>
                        </div>

                        <div id="bahan-container">
                            <div class="form-row align-items-center mb-3" id="bahan-group-0">
                                <div class="col-md-4">
                                    <label for="bahan_id_0" class="font-weight-bold">Bahan:</label>
                                    <select id="bahan_id_0" name="bahan_id[]" class="form-control" required>
                                        <option value="">Pilih Bahan</option>
                                        <!-- Bahan di-load dengan JS -->
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="qty_takaran_0" class="font-weight-bold">Kuantitas:</label>
                                    <input type="number" step="0.01" id="qty_takaran_0" name="qty_takaran[]"
                                        class="form-control" placeholder="0.0" min=0 required>
                                </div>

                                <div class="col-md-3">
                                    <label for="unit_name_0" class="font-weight-bold">Unit:</label>
                                    <input type="hidden" id="unit_id_0" name="unit_id[]">
                                    <input type="text" id="unit_name_0" name="unit_name[]" class="form-control" readonly>
                                </div>

                                <div class="col-md-2 text-center">
                                    <label>&nbsp;</label><br>
                                    <button type="button" class="btn btn-danger remove-btn" style="padding: 6px 10px;">
                                        <i class="fas fa-trash-alt" style="font-size: 1rem;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" id="add-more" class="btn btn-secondary d-flex align-items-center mr-2">
                                <i class="fas fa-plus-circle mr-1"></i> Add More
                            </button>
                            <button type="submit" class="btn btn-primary d-flex align-items-center"
                                onclick="return confirm('Apakah kamu yakin untuk menambahkan resep ini?');">
                                <i class="fas fa-check-circle mr-1"></i> Tambahkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Daftar Menu --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3 text-white" style="background-color: #0fad73;">
                    <h2 class="text-center m-0 font-weight-bold h3">Daftar Menu</h2>
                    <input type="text" id="search-input" class="form-control mt-3" placeholder="Cari Resep">
                </div>
                <div class="card-body" id="resep-list">
                    @foreach ($menus->groupBy('nama_menu') as $menuName => $items)
                        @if ($items->first()->outlet_id == $outlet_id)
                            <div class="card mb-3 menu-card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4 class="h6 m-0">{{ $items->first()->menu->nama_menu }}
                                            (Outlet: {{ $items->first()->menu->outlet->nama_outlet }})
                                        </h4>
                                        <button class="btn btn-info details-btn" data-target="#details-{{ $menuName }}">
                                            Details
                                        </button>
                                    </div>
                                </div>

                                <div id="details-{{ $menuName }}" class="details-container" style="display: none;">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Bahan</th>
                                                <th class="text-center">Takaran</th>
                                                <th class="text-center">Unit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($items as $item)
                                                <tr>
                                                    <td class="text-center">{{ $item->bahan_inisiasi->nama_bahan }}</td>
                                                    <td class="text-center">{{ $item->qty_takaran }}</td>
                                                    <td class="text-center">{{ $item->unit->unit }}</td>
                                                </tr>
                                            @endforeach
                                                <!-- Edit and Delete buttons in the menu list -->
                                                <tr>
                                                    <td colspan="3" class="text-right">
                                                        <a href="{{ route('menu.editresep', ['menuId' => $items->first()->menu->id]) }}?outlet_id={{ $outlet_id }}"
                                                            class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                        <form action="{{ route('addmenu.destroy', ['menuId' => $items->first()->menu->id, 'outlet_id' => $outlet_id]) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Apakah kamu yakin untuk menghapus item ini?');">
                                                                <i class="fas fa-trash-alt"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let counter = 1;

        // Fungsi pencarian menu
        const searchInput = document.getElementById('search-input');
        const menuCards = document.querySelectorAll('.menu-card');

        searchInput.addEventListener('input', function () {
            const searchTerm = searchInput.value.toLowerCase();

            menuCards.forEach(card => {
                const menuName = card.querySelector('h4').textContent.toLowerCase();
                if (menuName.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Fungsi untuk fetch bahan dari API
        function fetchBahans(outletId, dropdown, initialLoad = false) {
            fetch(`/addmenu/get-bahansmenu/${outletId}`)
                .then(response => response.json())
                .then(data => {
                    dropdown.innerHTML = '<option value="" selected>Pilih Bahan</option>';
                    data.forEach(bahan => {
                        dropdown.innerHTML += `<option value="${bahan.id}">${bahan.bahan_inisiasi.nama_bahan}</option>`;
                    });

                    if (initialLoad && dropdown.options.length > 1) {
                        dropdown.selectedIndex = 0;
                        dropdown.dispatchEvent(new Event('change'));
                    }

                    updateDropdownOptions();
                })
                .catch(error => console.error('Error:', error));
        }

        // Fungsi untuk fetch unit berdasarkan bahan
        function fetchUnit(bahanId, index) {
            fetch(`/addmenu/get-unitmenu/${encodeURIComponent(bahanId)}`)
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

        function attachUnitChangeEvent(index) {
            const bahanDropdown = document.getElementById(`bahan_id_${index}`);
            if (bahanDropdown) {
                bahanDropdown.addEventListener('change', function () {
                    const bahanId = this.value;
                    fetchUnit(bahanId, index);
                    updateDropdownOptions();
                });
            }
        }

        function updateDropdownOptions() {
            const selectedBahanIds = Array.from(document.querySelectorAll('select[name="bahan_id[]"]'))
                .map(dropdown => dropdown.value)
                .filter(value => value);

            document.querySelectorAll('select[name="bahan_id[]"]').forEach(dropdown => {
                const options = dropdown.querySelectorAll('option');
                options.forEach(option => {
                    if (selectedBahanIds.includes(option.value) && option.value !== dropdown.value) {
                        option.style.display = 'none';
                    } else {
                        option.style.display = 'block';
                    }
                });
            });
        }

        // Fungsi untuk menghapus bahan dinamis
        function attachRemoveButtonEvent(button, group) {
            button.addEventListener('click', function () {
                if (confirm('Apakah Anda yakin ingin menghapus bahan ini?')) {
                    group.remove();
                    updateDropdownOptions();
                }
            });
        }

        document.getElementById('add-more').addEventListener('click', function () {
            const container = document.getElementById('bahan-container');
            const newGroup = document.createElement('div');
            newGroup.classList.add('form-row', 'align-items-center', 'mb-3');
            newGroup.id = `bahan-group-${counter}`;
            newGroup.innerHTML = `
                <div class="col-md-4">
                    <label for="bahan_id_${counter}" class="font-weight-bold">Bahan:</label>
                    <select id="bahan_id_${counter}" name="bahan_id[]" class="form-control" required>
                        <option value="">Pilih Bahan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="qty_takaran_${counter}" class="font-weight-bold">Kuantitas:</label>
                    <input type="number" step="0.01" id="qty_takaran_${counter}" name="qty_takaran[]" class="form-control" placeholder="0.0" required>
                </div>
                <div class="col-md-3">
                    <label for="unit_name_${counter}" class="font-weight-bold">Unit:</label>
                    <input type="hidden" id="unit_id_${counter}" name="unit_id[]">
                    <input type="text" id="unit_name_${counter}" name="unit_name[]" class="form-control" readonly>
                </div>

                <div class="col-md-2 text-center">
                    <label>&nbsp;</label><br>
                    <button type="button" class="btn btn-danger remove-btn" style="padding: 6px 10px;">
                        <i class="fas fa-trash-alt" style="font-size: 1rem;"></i>
                    </button>
                </div>
            `;
            container.appendChild(newGroup);

            const outletId = document.getElementById('outlet_id').value;
            const newBahanDropdown = newGroup.querySelector('select[name="bahan_id[]"]');
            fetchBahans(outletId, newBahanDropdown, true);

            attachUnitChangeEvent(counter);

            const removeButton = newGroup.querySelector('.remove-btn');
            attachRemoveButtonEvent(removeButton, newGroup);
            counter++;
        });

        const firstRemoveButton = document.querySelector('#bahan-group-0 .remove-btn');
        const firstBahanGroup = document.getElementById('bahan-group-0');
        attachRemoveButtonEvent(firstRemoveButton, firstBahanGroup);

        const outletId = document.getElementById('outlet_id').value;
        const firstBahanDropdown = document.getElementById('bahan_id_0');
        fetchBahans(outletId, firstBahanDropdown, true);

        attachUnitChangeEvent(0);

        document.querySelectorAll('.details-btn').forEach(button => {
            button.addEventListener('click', function () {
                const target = document.querySelector(button.getAttribute('data-target'));
                if (target.style.display === 'none' || target.style.display === '') {
                    target.style.display = 'block';
                } else {
                    target.style.display = 'none';
                }
            });
        });
    });
</script>
