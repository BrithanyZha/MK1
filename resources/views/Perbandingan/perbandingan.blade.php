@extends('dashboard.index')

@section('main')
<div class="container-fluid text-center">
    <div class="row">
        <div class="col">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h2  class="text-center m-0 font-weight-bold text-primary">Comparison Initial Stock dan Latest Stock</h2>
                </div>
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col">
                                <input class="form-control" id="searchTable" type="text" placeholder="Search..">
                                <br>
                                <h4>Initial Stock</h4>
                                <table id="bahanTable" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th>Nama Bahan</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Outlet</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($historysebelum as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama_bahan }}</td>
                                            <td>{{ $item->qty_stok }}</td>
                                            <td>{{ $item->unit->unit }}</td>
                                            <td>{{ $item->outlet->nama_outlet }}</td>
                                            <td>{{ $item->created_at }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <h4>Latest Stock</h4>
                                <table id="bahanTable2" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th>Nama Bahan</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Outlet</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bahans as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama_bahan }}</td>
                                            <td>{{ $item->qty_stok }}</td>
                                            <td>{{ $item->unit->unit }}</td>
                                            <td>{{ $item->outlet->nama_outlet }}</td>
                                            <td>{{ $item->created_at }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('searchTable').addEventListener('keyup', function() {
    let filter = this.value.toUpperCase();
    let tables = [document.getElementById('bahanTable'), document.getElementById('bahanTable2')];
    
    tables.forEach(table => {
        let tr = table.getElementsByTagName('tr');
        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName('td');
            let display = false;
            for (let j = 0; j < td.length; j++) {
                if (td[j].textContent.toUpperCase().indexOf(filter) > -1) {
                    display = true;
                    break;
                }
            }
            tr[i].style.display = display ? '' : 'none';
        }
    });
});
</script>
@endsection
