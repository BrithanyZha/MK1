@extends('dashboard.index')

@if (Auth::user()->role === 'admin')
    @section('main')
    <div class="col-md-12">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h2 class="text-center m-0 font-weight-bold text-primary">Tambah Nama Menu</h2>
                        <form action="{{ route('addnamamenu') }}" method="POST">
                            @csrf

                            <label for="nama_menu">Nama Menu:</label><br>
                            <input type="text" id="nama_menu" name="nama_menu" required>
                            @error('nama_menu')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <div class="form-group">
                            
                                <label for="outlet_id">Outlet:</label>
                                <select id="outlet_id" name="outlet_id" class="form-control" required>
                                    <option value="" disabled selected>Select Outet</option>
                                    @foreach ($outlet as $outlets)
                                    <option value="{{ $outlets->id }}">{{ $outlets->nama_outlet }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br><br>

                            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to Add this Menu?');">Tambahkan</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h2  class="text-center m-0 font-weight-bold text-primary">List dari Nama Menu</h2>
                           
                            <div class="container mt-2">
                                <table id="bahanTable" class="table table-striped">

                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Menu</th>
                                            <th>Outlet</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($menu as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama_menu }}</td>
                                                <td>{{ $item->outlet->nama_outlet}}</td>

                                                <td>
                                                    <a href="{{ route('editnamamenu', $item->id) }}" class="btn btn-primary">Edit</a>
                                                    <form action="{{ route('deletenamamenu', $item->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
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


@elseif(Auth::user()->role === 'user')

    @section('main')
    
@endsection

@endif
