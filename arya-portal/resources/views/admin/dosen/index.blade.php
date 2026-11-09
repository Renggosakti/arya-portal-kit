@extends('adminlte::page')

@section('title', 'Data Dosen')

@section('css')
    @include('admin.partials.css')
@stop

@section('js')
    @include('admin.partials.js')
@stop

@section('content_header')
    <div class="arya-hero">
        <div>
            <p class="arya-eyebrow">Data akademik</p>
            <h1>Data Dosen</h1>
            <p class="arya-sub">{{ $dosen->total() }} dosen terdaftar</p>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid mt-3">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.dosen.index') }}" class="form-row">
                    <div class="col-md-5 mb-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control" placeholder="Cari nama atau NIP...">
                    </div>
                    <div class="col-md-4 mb-2">
                        <select name="departemen_id" class="form-control">
                            <option value="">Semua departemen</option>
                            @foreach ($departemen as $d)
                                <option value="{{ $d->id }}" @selected(request('departemen_id') == $d->id)>{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Cari</button>
                        <a href="{{ route('admin.dosen.index') }}" class="btn btn-light">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Departemen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dosen as $d)
                            <tr style="--i: {{ $loop->index }}">
                                <td>{{ $d->nama }}</td>
                                <td>{{ $d->nip }}</td>
                                <td><span class="arya-badge biru">{{ $d->departemen->nama }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Tidak ada data yang cocok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-transparent border-0 d-flex justify-content-center">
                {{ $dosen->links() }}
            </div>
        </div>
    </div>
@stop

@section('footer')
    @include('admin.partials.footer')
@stop
