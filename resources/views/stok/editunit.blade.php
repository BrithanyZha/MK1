@extends('dashboard.index')

@if (Auth::user()->role === 'admin')
    @section('main')
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 bg-success text-white">
                            <h2 class="text-center m-0 font-weight-bold h3">Edit Unit</h2>
                        </div>
                        <div class="card-body">
                            {{-- Pesan Sukses --}}
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            {{-- Form Edit Unit --}}
                            <form action="{{ route('updateunit', $units->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                            {{-- Input Unit --}}
                            <div class="form-group row align-items-center">
                                <label for="unit" class="col-md-2 col-form-label font-weight-bold">Unit:</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" id="unit" name="unit" value="{{ $units->unit }}">
                                    @error('unit')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>


                                {{-- Tombol Aksi --}}
                                <div class="d-flex justify-content-end mt-4">
                                    <a href="{{ route('unit') }}" class="btn btn-secondary d-flex align-items-center mr-2">
                                        <i class="fas fa-times-circle mr-1"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary d-flex align-items-center" onclick="return confirm('Apakah Anda yakin untuk mengubah data unit ini?');">
                                        <i class="fas fa-check-circle mr-1"></i> Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Script jQuery --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                const outletId = $('input[name="outlet_id"]').val();
                const unitSelect = $('#unit');

                function loadUnits() {
                    fetch(`/get-units/${outletId}`)
                        .then(response => response.json())
                        .then(data => {
                            unitSelect.html('');
                            data.forEach(unit => {
                                unitSelect.append(`<option value="${unit.id}">${unit.unit}</option>`);
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }

                loadUnits();
            });
        </script>
    @endsection
@endif
