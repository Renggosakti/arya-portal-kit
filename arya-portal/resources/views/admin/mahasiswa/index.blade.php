@extends('adminlte::page')

@section('title', 'Data Mahasiswa')

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
            <h1>Data Mahasiswa</h1>
            <p class="arya-sub">{{ $mahasiswa->total() }} data ditemukan</p>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid mt-3">
        @if (session('success'))
            <div class="alert alert-success arya-alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.mahasiswa.index') }}" class="form-row">
                    <div class="col-md-4 mb-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control" placeholder="Cari nama atau NRP...">
                    </div>
                    <div class="col-md-3 mb-2">
                        <select name="departemen_id" class="form-control">
                            <option value="">Semua departemen</option>
                            @foreach ($departemen as $d)
                                <option value="{{ $d->id }}" @selected(request('departemen_id') == $d->id)>{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="status" class="form-control">
                            <option value="">Semua status</option>
                            <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                            <option value="cuti" @selected(request('status') === 'cuti')>Cuti akademik</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i> Cari</button>
                        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-light">Reset</a>
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
                            <th>NRP</th>
                            <th>Departemen</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswa as $m)
                            <tr style="--i: {{ $loop->index }}">
                                <td>{{ $m->nama }}</td>
                                <td>{{ $m->nrp }}</td>
                                <td>{{ $m->departemen->nama }}</td>
                                <td>
                                    @if ($m->is_active)
                                        <span class="arya-badge hijau">Aktif</span>
                                    @else
                                        <span class="arya-badge kuning">Cuti</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    {{-- Tombol hanya dirender untuk admin; eksekusinya juga dikunci di controller --}}
                                    @can('hapus-mahasiswa')
                                        <form method="POST" action="{{ route('admin.mahasiswa.destroy', $m) }}"
                                              data-confirm="Hapus data {{ $m->nama }}?" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    @else
                                        <span class="arya-badge ungu">Hanya admin</span>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Tidak ada data yang cocok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-transparent border-0 d-flex justify-content-center">
                {{ $mahasiswa->links() }}
            </div>
        </div>
    </div>
@stop

@section('footer')
    @include('admin.partials.footer')
@stop
