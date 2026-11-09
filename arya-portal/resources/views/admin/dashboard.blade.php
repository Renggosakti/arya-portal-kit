@extends('adminlte::page')

@section('title', 'Dashboard Admin')

@section('css')
    @include('admin.partials.css')
@stop

@section('js')
    @include('admin.partials.js')
@stop

@section('content_header')
    <div class="arya-hero">
        <div>
            <p class="arya-eyebrow">Selamat datang kembali</p>
            <h1>Halo, {{ auth()->user()->name }}</h1>
            <p class="arya-sub">
                Panel admin Portal Akademik oleh <strong>Arya Rangga Putra Pratama</strong> (5025241072)
            </p>
        </div>
        <span class="arya-role-pill">{{ auth()->user()->getRoleNames()->implode(', ') }}</span>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        {{-- Widget statistik: angka berasal dari kueri count() pada model --}}
        <div class="row mt-3">
            <div class="col-lg-3 col-sm-6">
                <div class="small-box arya-blue">
                    <div class="inner">
                        <h3 data-count="{{ $totalMahasiswa }}">{{ $totalMahasiswa }}</h3>
                        <p>Total Mahasiswa</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-graduate"></i></div>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="small-box-footer">
                        Lihat data <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="small-box arya-pink">
                    <div class="inner">
                        <h3 data-count="{{ $totalDosen }}">{{ $totalDosen }}</h3>
                        <p>Total Staf Dosen</p>
                    </div>
                    <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    <a href="{{ route('admin.dosen.index') }}" class="small-box-footer">
                        Lihat data <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="small-box arya-green">
                    <div class="inner">
                        <h3 data-count="{{ $totalTugas }}">{{ $totalTugas }}</h3>
                        <p>Total Tugas</p>
                    </div>
                    <div class="icon"><i class="fas fa-tasks"></i></div>
                    <a href="{{ route('admin.tugas.index') }}" class="small-box-footer">
                        Lihat data <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="small-box arya-purple">
                    <div class="inner">
                        <h3 data-count="{{ $mahasiswaAktif }}">{{ $mahasiswaAktif }}</h3>
                        <p>Mahasiswa Aktif</p>
                    </div>
                    <div class="icon"><i class="fas fa-user-check"></i></div>
                    <a href="{{ route('admin.mahasiswa.index', ['status' => 'aktif']) }}" class="small-box-footer">
                        Lihat yang aktif <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-bar mr-2 text-primary"></i>Mahasiswa per Departemen</h3>
                    </div>
                    <div class="card-body">
                        @php $maks = max(1, $perDepartemen->max('mahasiswa_count')); @endphp
                        @foreach ($perDepartemen as $d)
                            <div class="arya-bar-row">
                                <div class="arya-bar-label">
                                    <span>{{ $d->nama }}</span>
                                    <strong>{{ $d->mahasiswa_count }}</strong>
                                </div>
                                <div class="arya-bar">
                                    <span style="--w: {{ round($d->mahasiswa_count / $maks * 100) }}%; --i: {{ $loop->index }}"></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-clock mr-2 text-primary"></i>Tugas Terbaru</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Departemen</th>
                                    <th>Tenggat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tugasTerbaru as $t)
                                    <tr style="--i: {{ $loop->index }}">
                                        <td>{{ $t->judul }}</td>
                                        <td>{{ $t->departemen->nama }}</td>
                                        <td>{{ $t->tenggat->translatedFormat('d M Y') }}</td>
                                        <td>
                                            @if ($t->isAktif())
                                                <span class="arya-badge hijau">Aktif</span>
                                            @else
                                                <span class="arya-badge merah">Lewat</span>
                                            @endif
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
@stop

@section('footer')
    @include('admin.partials.footer')
@stop
