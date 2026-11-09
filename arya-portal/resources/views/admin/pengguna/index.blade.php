@extends('adminlte::page')

@section('title', 'Pengguna dan Role')

@section('css')
    @include('admin.partials.css')
@stop

@section('js')
    @include('admin.partials.js')
@stop

@section('content_header')
    <div class="arya-hero">
        <div>
            <p class="arya-eyebrow">Administrasi</p>
            <h1>Pengguna dan Role</h1>
            <p class="arya-sub">Halaman ini hanya bisa dibuka oleh role admin.</p>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid mt-3">
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengguna as $u)
                            <tr style="--i: {{ $loop->index }}">
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>
                                    @forelse ($u->roles as $role)
                                        <span class="arya-badge {{ $role->name === 'admin' ? 'merah' : 'biru' }}">{{ $role->name }}</span>
                                    @empty
                                        <span class="arya-badge kuning">tanpa role</span>
                                    @endforelse
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('footer')
    @include('admin.partials.footer')
@stop
