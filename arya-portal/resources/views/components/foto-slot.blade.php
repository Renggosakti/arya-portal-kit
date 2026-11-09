{{--
    Slot foto otomatis. Selama file belum ada, tampil kotak "Tambah gambar di sini".
    Begitu kamu menyimpan file bernama public/img/landing/{name}.jpg (atau .jpeg / .png / .webp),
    kotak ini otomatis berganti menjadi fotonya, tanpa mengubah kode.
--}}
@props(['name', 'label' => 'Tambah gambar di sini'])

@php
    $path = null;
    foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
        if (file_exists(public_path("img/landing/{$name}.{$ext}"))) {
            $path = "img/landing/{$name}.{$ext}";
            break;
        }
    }
@endphp

@if ($path)
    <img src="{{ asset($path) }}" alt="{{ $label }}" {{ $attributes->merge(['class' => 'slot-img']) }}>
@else
    <div {{ $attributes->merge(['class' => 'slot-ph']) }}>
        <svg viewBox="0 0 24 24" width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
        <strong>{{ $label }}</strong>
        <small>simpan sebagai public/img/landing/{{ $name }}.jpg</small>
    </div>
@endif
