@extends('layouts.app')

@section('title', 'SIGERCEP - Daftar Laporan Perilaku')

{{-- Style --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/loading.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/filter-responsive.css') }}">
@endpush

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                {{-- Header --}}
                <x-page-header icon="fa-face-smile" title="Daftar Laporan Perilaku" subtitle="Kelola laporan perilaku" />

                {{-- Filter Section --}}
                @include('layouts.partials.komite-mutu.laporan-perilaku.filter')

                {{-- DataTable --}}
                @include('layouts.partials.komite-mutu.laporan-perilaku.datatable')

                {{-- Loading Overlay --}}
                @include('layouts.partials.loading-overlay')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/datatable/datatable-laporan-perilaku.js') }}"></script>
    <script src="{{ asset('assets/js/loading-filter.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/alert-delete-swal.js') }}"></script>
    <script>
        $.fn.dataTable.ext.errMode = "none";

        // Filter
        document.addEventListener("DOMContentLoaded", function() {
            initDateRange("{{ request('periode_dari') }}", "{{ request('periode_sampai', now()->format('d-m-Y')) }}");
        });
    </script>
@endpush
