@extends('dashboard.index')
@if (Auth::user()->role === 'admin')
    @section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h2 class="text-center m-0 font-weight-bold text-primary">Edit Bahan yang Diinisiasi</h2>
                        <form action="{{ route('updatebahan_inisiasi', $bahanInisiasi->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="nama_bahan">Nama Bahan</label>
                                <input type="text" class="form-control" id="nama_bahan" name="nama_bahan"
                                    value="{{ $bahanInisiasi->nama_bahan }}">
                                @error('nama_bahan')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <input type="hidden" name="outlet_id"
                                value="{{ request()->input('outlet_id', $bahanInisiasi->outlet_id) }}">

                            <div class="form-group">
                                <label for="qty_inisiasi">Quantity</label>
                                <input type="text" class="form-control" id="qty_inisiasi" name="qty_inisiasi"
                                    value="{{ $bahanInisiasi->qty_inisiasi }}">
                            </div>

                            <div class="form-group">
                                <label for="unit_id">Unit</label>
                                <select id="unit_id" name="unit_id" class="form-control" required>
                                    @foreach ($units as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $bahanInisiasi->unit_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->unit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
@elseif(Auth::user()->role === 'user')
    @section('main')
        kosong
    @endsection
@endif
