@extends('dashboard.index')

@section('main')
    <div class="container-fluid">
        {{-- Pesan Sukses --}}
        @if(session('success'))
            <div class="alert alert-success mt-2">
                {{ session('success') }}
            </div>
        @endif

        {{-- Riwayat Stok Bahan --}}
        <div class="row justify-content-center">
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 bg-success text-white">
                        <h2 class="text-center m-0 font-weight-bold h3" style="font-size: 1.5rem;">History Bahan</h2>
                    </div>
                    <div class="card-body">
                        {{-- Dropdown Filter --}}
                        <div class="mb-3 d-flex justify-content-start align-items-center">
                            <label for="filterDropdown" class="mr-2 font-weight-bold">Filter Berdasarkan:</label>
                            <div class="form-group mb-0">
                                <select id="filterDropdown" class="form-control">
                                    <option value="all">Semua</option>
                                    <option value="Transfer">Transfer</option>
                                    <option value="Menerima">Menerima</option>
                                    <option value="Add">Tambah</option>
                                    <option value="Edit">Edit</option>
                                    <option value="Dikurangi">Dikurangi</option>
                                </select>
                            </div>
                        </div>

                        {{-- Tabel Riwayat Stok Bahan --}}
                        <div class="table-responsive">
                            <table id="bahanTable" class="table table-striped table-bordered table-hover">
                                <thead class="thead-white">
                                    <tr>
                                        <th class="text-center">Nama Bahan</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Outlet</th>
                                        <th class="text-center">Keterangan</th>
                                        <th class="text-center">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($history as $item)
                                        <tr class="history-row" data-keterangan="{{ $item->keterangan }}">
                                            <td class="text-center">{{ $item->bahan_inisiasi->nama_bahan }}</td>
                                            <td class="text-center">{{ $item->qty_stok }}</td>
                                            <td class="text-center">{{ $item->unit->unit }}</td>
                                            <td class="text-center">{{ $item->outlet->nama_outlet }}</td>
                                            <td class="text-center">
                                                @switch($item->keterangan)
                                                    @case('Transfer')
                                                        <span class="badge bg-info text-white">Transfer</span>
                                                        @break
                                                    @case('Menerima')
                                                        <span class="badge bg-success text-white">Menerima</span>
                                                        @break
                                                    @case('Add')
                                                        <span class="badge bg-primary text-white">Tambah</span>
                                                        @break
                                                    @case('Edit')
                                                        <span class="badge bg-warning text-white">Edit</span>
                                                        @break
                                                    @case('Dikurangi')
                                                        <span class="badge bg-danger text-white">Dikurangi</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="text-center">{{ $item->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No history records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- DataTables Scripts --}}
                        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
                        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
                        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
                        <script>
                            $(document).ready(function() {
                                $('#bahanTable').DataTable();

                                // Dropdown filter logic
                                $('#filterDropdown').on('change', function() {
                                    const selectedFilter = $(this).val();
                                    filterRows(selectedFilter);
                                });

                                function filterRows(keterangan) {
                                    const rows = document.querySelectorAll('.history-row');
                                    rows.forEach(row => {
                                        const rowKeterangan = row.getAttribute('data-keterangan');

                                        if (keterangan === 'all' || rowKeterangan === keterangan) {
                                            row.style.display = ''; // Tampilkan baris
                                        } else {
                                            row.style.display = 'none'; // Sembunyikan baris
                                        }
                                    });
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
