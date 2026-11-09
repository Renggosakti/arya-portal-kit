<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Beranda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-lg font-semibold">Halo, {{ auth()->user()->name }}.</p>
                    <p class="mt-2 text-gray-600">
                        Akunmu belum memiliki role admin atau staf, jadi panel admin tidak dapat dibuka.
                        Hubungi administrator jika kamu membutuhkan akses.
                    </p>
                    <p class="mt-4 text-sm text-gray-500">
                        Portal Akademik oleh Arya Rangga Putra Pratama (5025241072).
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
