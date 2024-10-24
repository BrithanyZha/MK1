@extends('dashboard.index')

@section('main')
<div class="container-fluid">
    {{-- Pesan Sukses --}}
    @if(session('success'))
        <div class="alert alert-success mt-2">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form untuk Menambahkan Menu Terjual --}}
    <div class="row justify-content-center">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-success text-white">

                    <h2 class="text-center m-0 font-weight-bold h3" style="font-size: 1.5rem;">Tambah Menu Terjual</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('menuterjual.create') }}" method="POST">
                        @csrf
                        <!-- Hidden Outlet ID Field -->
                        <input type="hidden" name="outlet_id" value="{{ request()->input('outlet_id') }}">
                        
                        <div id="bahan-container">
                            <div class="form-row align-items-center mb-3" id="bahan-group-0">
                                <div class="col-md-5">
                                    <label for="menu_id_0" class="font-weight-bold">Nama Menu:</label>
                                    <select id="menu_id_0" name="menu_id[]" class="form-control" required>
                                        <option value="" disabled selected>Select Nama Menu</option>
                                        @foreach ($soldMenus as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_menu }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="qty_mt_0" class="font-weight-bold">Quantity Terjual:</label>
                                    <input type="number" step="0.01" id="qty_mt_0" name="qty_mt[]" class="form-control" placeholder="0.0" required>
                                </div>

                                <div class="col-md-2">
                                    <label>&nbsp;</label><br>
                                    <button type="button" class="btn btn-danger remove-btn" style="width: 100%;">
                                        <i class="fa-solid fa-trash" style="color: #ffffff;"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" id="add-more" class="btn btn-secondary mr-2">Add More</button>
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to add this menu?');">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Menu Terjual --}}
    <div class="row justify-content-center">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-success text-white">
                    <h2 class="text-center m-0 font-weight-bold h3" style="font-size: 1.5rem;">List Menu Terjual</h2>
                    <input type="text" id="search-input" class="form-control mt-3" placeholder="Search Sold Menus">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="soldMenuTable" class="table table-striped table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Nama Menu</th>
                                    <th class="text-center">Outlet</th>
                                    <th class="text-center">Total Quantity Terjual</th>
                                    <th class="text-center">User Name</th>
                                    <th class="text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($soldMenus as $soldMenu)
                                    <tr class="sold-menu-item">
                                        <td class="text-center">{{ $soldMenu->menu->nama_menu }}</td>
                                        <td class="text-center">{{ $soldMenu->outlet->nama_outlet }}</td>
                                        <td class="text-center">{{ $soldMenu->total_qty }}</td>
                                        <td class="text-center">{{ $userDetails[$soldMenu->menu_id]->first()->user_name }}</td>
                                        <td class="text-center">{{ $userDetails[$soldMenu->menu_id]->first()->created_at->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- DataTables Scripts --}}
                    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
                    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
                    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
                    <script>
                        $(document).ready(function() {
                            $('#soldMenuTable').DataTable();

                            // Search functionality
                            $('#search-input').on('input', function() {
                                const searchQuery = this.value.toLowerCase();
                                $('.sold-menu-item').each(function() {
                                    const itemText = $(this).text().toLowerCase();
                                    $(this).toggle(itemText.includes(searchQuery));
                                });
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let counter = 1;

        function fetchMenus(outletId, dropdown) {
            if (!outletId) return;
            fetch(`/menuterjual/get-menus/${outletId}`)
                .then(response => response.json())
                .then(data => {
                    dropdown.innerHTML = '<option value="" disabled selected>Select Nama Menu</option>';
                    data.forEach(menu => {
                        dropdown.innerHTML += `<option value="${menu.id}">${menu.nama_menu}</option>`;
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        const outletIdElement = document.querySelector('[name="outlet_id"]');
        const initialOutletId = outletIdElement.value;

        document.querySelectorAll('select[name="menu_id[]"]').forEach(dropdown => {
            fetchMenus(initialOutletId, dropdown);
        });

        document.getElementById('add-more').addEventListener('click', function () {
            const container = document.getElementById('bahan-container');
            const newGroup = document.createElement('div');
            newGroup.classList.add('form-row', 'align-items-center', 'mb-3');
            newGroup.id = `bahan-group-${counter}`;
            newGroup.innerHTML = `
                <div class="col-md-5">
                    <label for="menu_id_${counter}" class="font-weight-bold">Nama Menu:</label>
                    <select id="menu_id_${counter}" name="menu_id[]" class="form-control" required>
                        <option value="" disabled selected>Select Nama Menu</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="qty_mt_${counter}" class="font-weight-bold">Quantity Terjual:</label>
                    <input type="number" step="0.01" id="qty_mt_${counter}" name="qty_mt[]" class="form-control" placeholder="0.0" required>
                </div>

                <div class="col-md-2">
                    <label>&nbsp;</label><br>
                    <button type="button" class="btn btn-danger remove-btn" style="width: 100%;">
                        <i class="fa-solid fa-trash" style="color: #ffffff;"></i> Remove
                    </button>
                </div>
            `;
            container.appendChild(newGroup);

            const newDropdown = newGroup.querySelector('select[name="menu_id[]"]');
            fetchMenus(initialOutletId, newDropdown);

            attachRemoveEvent(newGroup.querySelector('.remove-btn'));

            counter++;
        });

        function attachRemoveEvent(button) {
            button.addEventListener('click', function () {
                button.parentElement.parentElement.remove();
            });
        }

        document.querySelectorAll('.remove-btn').forEach(button => {
            attachRemoveEvent(button);
        });
    });
</script>
@endsection
