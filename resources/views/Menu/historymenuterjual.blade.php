@extends('dashboard.index')

@section('main')
<div class="container-fluid">
    {{-- Pesan Sukses --}}
    @if(session('success'))
        <div class="alert alert-success mt-2">
            {{ session('success') }}
        </div>
    @endif

    {{-- History Menu yang Terjual --}}
    <div class="row justify-content-center">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-success text-white">
                    <h2 class="text-center m-0 font-weight-bold h3" style="font-size: 1.5rem;">History Menu yang Terjual</h2>
                </div>
                <div class="card-body">
                    {{-- Tabel History Menu yang Terjual --}}
                    <div class="table-responsive">
                        <table id="soldMenuTable" class="table table-striped table-bordered table-hover">
                            <thead class="thead-white">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Outlet</th>
                                    <th class="text-center">Nama Menu</th>
                                    <th class="text-center">Quantity Terjual</th>
                                    <th class="text-center">Nama User</th>
                                    <th class="text-center">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($menut as $menu)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $menu->outlet->nama_outlet }}</td>
                                        <td class="text-center">{{ optional($menu->menu)->nama_menu ?? 'Menu not found' }}</td>
                                        <td class="text-center">{{ $menu->qty_mt }}</td>
                                        <td class="text-center">{{ $menu->user_name }}</td>
                                        <td class="text-center">{{ $menu->created_at->format('d M Y H:i') }}</td>
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
                            $('#soldMenuTable').DataTable();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
