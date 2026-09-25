{{-- Periode Dari / Periode Until - shared by all filter pages.
     Optional: $periodeDariDefault (e.g. Helpdesk Report = awal bulan) --}}
<div class="flex flex-col mr-1 filter-item" style="min-width:148px; flex:1 1 148px; max-width:180px;">
    <label class="text-xs font-semibold text-gray-600 mb-1.5">Periode Dari</label>
    <input type="text" name="periode_dari"
        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent flatpickr"
        value="{{ $periodeDariDefault ?? request('periode_dari') }}"
        placeholder="Pilih tanggal">
</div>

<div class="flex flex-col mr-1 filter-item" style="min-width:148px; flex:1 1 148px; max-width:180px;">
    <label class="text-xs font-semibold text-gray-600 mb-1.5">Periode Sampai</label>
    <input type="text" name="periode_sampai"
        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent flatpickr"
        value="{{ request('periode_sampai', now()->format('d-m-Y')) }}" placeholder="Pilih tanggal">
</div>
