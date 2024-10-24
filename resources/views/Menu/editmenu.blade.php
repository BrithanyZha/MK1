@extends('dashboard.index')

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h2 class="text-center m-0 font-weight-bold text-primary">Edit Resep</h2>
                    <form action="{{ route('menu.update', $menuId) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <!-- Hidden field to keep the selected outlet ID -->
                        <input type="hidden" id="outlet_id" name="outlet_id" value="{{ request()->input('outlet_id') }}">
                        <div class="form-group">
                            <label for="menu_id">Nama Menu:</label>
                            <input type="text" class="form-control" id="menu_id" name="menu_id" value="{{ $menu->nama_menu }}" required>
                        </div>
                   
                        {{-- <div class="form-group">
                            <label for="outlet_id">Outlet:</label>
                            <select id="outlet_id" name="outlet_id" class="form-control" required>
                                @foreach ($outlet as $outlet)
                                    <option value="{{ $outlet->id }}" {{ $outlet->id == $menu->outlet_id ? 'selected' : '' }}>{{ $outlet->nama_outlet }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div id="bahan-container">
                            @foreach ($details as $index => $detail)
                                <div class="d-flex mb-3" id="bahan-group-{{ $index }}">
                                    <label for="bahan_id_{{ $index }}" class="mr-2">Nama Bahan:</label>
                                    <select id="bahan_id_{{ $index }}" name="bahan_id[]" class="form-control mr-3" value="{{ $detail->bahan_inisiasi->nama_bahan}}"required>
                                        @foreach ($bahans as $bahan)
                                            <option value="{{ $bahan->id }}" {{ $bahan->id == $detail->bahan_id ? 'selected' : '' }}>{{ $bahan->bahan_inisiasi->nama_bahan }}</option>
                                        @endforeach
                                    </select>

                                    <label for="qty_takaran_{{ $index }}" class="mr-2">Quantity Takaran:</label>
                                    <input type="number" step="0.01" id="qty_takaran_{{ $index }}" name="qty_takaran[]" class="form-control mr-3" value="{{ $detail->qty_takaran }}" required>

                                    <label for="unit_name_{{ $index }}" class="mr-2">Unit:</label>
                                    <input type="hidden" id="unit_id_{{ $index }}" name="unit_id[]" value="{{ $detail->unit_id }}">
                                    <input type="text" id="unit_name_{{ $index }}" name="unit_name[]" class="form-control" value="{{ $detail->unit->unit }}" readonly>

                                    <button type="button" class="btn btn-danger btn-sm remove-bahan" data-index="{{ $index }}"><i class="fa fa-trash"></i></button>
                                    <input type="hidden" name="deleted_items[]" id="deleted_items_{{ $index }}" value="0">
                                    {{-- jika bahan yang sebelumnya di remove  di dalam edit  maka akan terhapus di database nya --}}
                                </div>
                            @endforeach
                        </div>

                        <div class="btn-group mt-3">
                            <button type="button" id="add-more" class="btn btn-secondary">Add More</button>
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to change this Recipe?');">Save changes</button>
                        </div>
                    </form>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        let counter = {{ count($details) }};

                        function fetchBahans(outletId, dropdown) {
                            fetch(`/addmenu/get-bahansmenu/${outletId}`)
                                .then(response => response.json())
                                .then(data => {
                                    dropdown.innerHTML = '<option value="" disabled selected>Select Nama Bahan</option>';
                                    data.forEach(bahan => {
                                        dropdown.innerHTML += `<option value="${bahan.id}">${bahan.nama_bahan}</option>`;
                                    });
                                })
                                .catch(error => console.error('Error:', error));
                        }

                        function fetchUnit(bahanId, index) {
                            fetch(`/addmenu/get-unitmenu/${encodeURIComponent(bahanId)}`)
                                .then(response => response.json())
                                .then(data => {
                                    document.getElementById(`unit_id_${index}`).value = data.unit_id || '';
                                    document.getElementById(`unit_name_${index}`).value = data.unit_name || '';
                                })
                                .catch(error => console.error('Error:', error));
                        }

                        document.getElementById('outlet_id').addEventListener('change', function() {
                            const outletId = this.value;
                            document.querySelectorAll('select[id^="bahan_id_"]').forEach(dropdown => {
                                fetchBahans(outletId, dropdown);
                            });
                        });

                        document.getElementById('add-more').addEventListener('click', function() {
                            const container = document.getElementById('bahan-container');
                            const newGroup = document.createElement('div');
                            newGroup.classList.add('d-flex', 'mb-3');
                            newGroup.id = `bahan-group-${counter}`;
                            newGroup.innerHTML = `
                                <label for="bahan_id_${counter}" class="mr-2">Nama Bahan:</label>
                                <select id="bahan_id_${counter}" name="bahan_id[]" class="form-control mr-3" required>
                                    <option value="" disabled selected>Select Nama Bahan</option>
                                    @foreach ($bahans as $bahan)
                                        <option value="{{ $bahan->id }}">{{ $bahan->nama_bahan }}</option>
                                    @endforeach
                                </select>

                                <label for="qty_takaran_${counter}" class="mr-2">Quantity Takaran:</label>
                                <input type="number" step="0.01" id="qty_takaran_${counter}" name="qty_takaran[]" class="form-control mr-3" placeholder="0.0" required>

                                <label for="unit_name_${counter}" class="mr-2">Unit:</label>
                                <input type="hidden" id="unit_id_${counter}" name="unit_id[]">
                                <input type="text" id="unit_name_${counter}" name="unit_name[]" class="form-control" readonly>

                                <button type="button" class="btn btn-danger btn-sm remove-bahan" data-index="${counter}"><i class="fa fa-trash"></i></button>
                                <input type="hidden" name="deleted_items[]" id="deleted_items_${counter}" value="0">
                            `;

                            container.appendChild(newGroup);
                            fetchBahans(document.getElementById('outlet_id').value, newGroup.querySelector('select'));

                            newGroup.querySelector('.remove-bahan').addEventListener('click', function() {
                                newGroup.remove();
                            });

                            newGroup.querySelector(`select[id^="bahan_id_"]`).addEventListener('change', function() {
                                const index = this.id.split('_').pop();
                                fetchUnit(this.value, index);
                            });

                            counter++;
                        });

                        document.querySelectorAll('.remove-bahan').forEach(button => {
                            button.addEventListener('click', function() {
                                const index = this.getAttribute('data-index');
                                document.getElementById(`bahan-group-${index}`).remove();
                                document.getElementById(`deleted_items_${index}`).value = '1';
                            });
                        });

                        document.querySelectorAll('select[id^="bahan_id_"]').forEach((dropdown, index) => {
                            dropdown.addEventListener('change', function() {
                                fetchUnit(this.value, index);
                            });
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>
@endsection
