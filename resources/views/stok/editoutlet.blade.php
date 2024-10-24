@extends('dashboard.index')
@if (Auth::user()->role === 'admin')

    @section('main')
        <div class="continer-fluid">
            <div class="row">
                <div class="col">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h2 class="text-center m-0 font-weight-bold text-primary">Edit Outlet</h2>
                            <form action="{{ route('updateoutlet', $outlet->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <label for="nama_outlet">Nama Outlet:</label><br>
                                <input type="text" id="nama_outlet" name="nama_outlet" value="{{ $outlet->nama_outlet }}" required>
                                @error('nama_outlet')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <br>
                                <label for="address">Address:</label><br>
                                <textarea id="address" name="address" required>{{ $outlet->address }}</textarea>
                                @error('address')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <br>
                                <button type="submit">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    @endsection

@endif
