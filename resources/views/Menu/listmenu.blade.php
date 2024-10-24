@extends('dashboard.index')

    @section('main')
        <div class="container-fluid text-center">
            <div class="row">
                <div class="col">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h2  class="text-center m-0 font-weight-bold text-primary">List dari Menu</h2>
                            <form id="searchForm"
                                class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control bg-light border-1 small"
                                        placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit" id="searchButton">
                                            <i class="fas fa-search fa-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Menu</th>
                                        <th>Outlet</th>
                                        <th>Nama Bahan</th>
                                        <th>Jumlah Takaran</th>
                                        <th>Satuan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($menus as $menu)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $menu->nama_menu }}</td>
                                            <td>{{ $menu->outlet }}</td>
                                            <td>{{ implode(', ', $menu->nama_bahan) }}</td>
                                            <td>{{ implode(', ', $menu->jml_takaran) }}</td>
                                            <td>{{ implode(', ', $menu->satuan) }}</td>
                                            <td>
                                                <a href="{{ route('addmenu.edit', $menu->id) }}"
                                                    class="btn btn-primary">Edit</a>
                                                <form action="{{ route('addmenu.destroy', $menu->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

