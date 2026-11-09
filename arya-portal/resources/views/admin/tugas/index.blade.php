@extends('adminlte::page')

@section('title', 'Tugas Kuliah')

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
            <h1>Tugas Kuliah</h1>
            <p class="arya-sub">{{ $tugas->total() }} tugas ditemukan</p>
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
                <form method="GET" action="{{ route('admin.tugas.index') }}" class="form-row">
                    <div class="col-md-5 mb-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control" placeholder="Cari judul tugas...">
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
                        <a href="{{ route('admin.tugas.index') }}" class="btn btn-light">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Mata Kuliah</th>
                            <th>Departemen</th>
                            <th>Tenggat</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tugas as $t)
                            <tr style="--i: {{ $loop->index }}">
                                <td>{{ $t->judul }}</td>
                                <td>{{ $t->mata_kuliah }}</td>
                                <td>{{ $t->departemen->nama }}</td>
                                <td>{{ $t->tenggat->translatedFormat('d M Y') }}</td>
                                <td>
                                    @if ($t->isAktif())
                                        <span class="arya-badge hijau">Aktif</span>
                                    @else
                                        <span class="arya-badge merah">Lewat</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @can('hapus-tugas')
                                        <form method="POST" action="{{ route('admin.tugas.destroy', $t) }}"
                                              data-confirm="Hapus {{ $t->judul }}?" class="d-inline">
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
                                <td colspan="6" class="text-center text-muted py-4">Tidak ada data yang cocok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-transparent border-0 d-flex justify-content-center">
                {{ $tugas->links() }}
            </div>
        </div>
    </div>
@stop

@section('footer')
    @include('admin.partials.footer')
@stop
