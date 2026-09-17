@extends('layouts.app')

@section('title', 'SIGERCEP - Detail Pengembalian Aset')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Pengembalian Aset</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama</label>
                                <p class="text-slate-600">{{ $pengadaan->nama }}</p>
                            </div>

                            {{-- Unit --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Unit</label>
                                <p class="text-slate-600">{{ $pengadaan->unit }}</p>
                            </div>

                            {{-- Keperluan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Keperluan</label>
                                <p class="text-slate-600">{{ $pengadaan->keperluan }}</p>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($pengadaan->tanggal)->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            {{-- Nama Barang --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama Barang</label>
                                <p class="text-slate-600">{{ $pengadaan->nama_barang }}</p>
                            </div>

                            {{-- Tempat Asal Barang --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tempat Asal Barang</label>
                                <p class="text-slate-600">{{ $pengadaan->tempat_asal_barang }}</p>
                            </div>

                            {{-- Foto Barang --}}
                            <div class="md:col-span-2">
                                @if ($pengadaan->foto_barang)
                                    @include('layouts.partials.komplain.foto-preview', ['foto' => $pengadaan->foto_barang, 'label' => 'Foto Barang'])
                                @else
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">Foto Barang</label>
                                    <p class="mt-2 text-sm text-slate-600">Tidak ada foto barang</p>
                                @endif
                            </div>

                            {{-- Foto Barcode --}}
                            <div class="md:col-span-2">
                                @if ($pengadaan->foto_barcode)
                                    @include('layouts.partials.komplain.foto-preview', ['foto' => $pengadaan->foto_barcode, 'label' => 'Foto Barcode'])
                                @else
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">Foto Barcode</label>
                                    <p class="mt-2 text-sm text-slate-600">Tidak ada foto barcode</p>
                                @endif
                            </div>

                        </div>

                        <div class="mt-6">
                            <a href="{{ route('pengadaan-aset.pengembalian-aset.index') }}"
                                class="inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/preview.js') }}"></script>
    @endpush
@endsection
