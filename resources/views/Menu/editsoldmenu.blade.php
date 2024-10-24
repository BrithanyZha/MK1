@extends('dashboard.index')

@section('main')
<div class="container">
    <h2>Edit Sold Menu</h2>
    
    <form action="{{ route('sold-menu.update', ['soldMenu' => $soldMenu->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="menu_id">Menu</label>
            <input type="text" class="form-control" id="menu_id" value="{{ $soldMenu->menu->nama_menu }}" readonly>
        </div>

        <div class="form-group">
            <label for="outlet_id">Outlet</label>
            <input type="text" class="form-control" id="outlet_id" value="{{ $soldMenu->outlet->nama_outlet }}" readonly>
        </div>

        <div class="form-group">
            <label for="qty_mt">Quantity Terjual</label>
            <input type="number" class="form-control" id="qty_mt" name="qty_mt" value="{{ old('qty_mt', $soldMenu->qty_mt) }}" required>
            @error('qty_mt')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('menuterjual', ['outlet_id' => $soldMenu->outlet->id]) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
