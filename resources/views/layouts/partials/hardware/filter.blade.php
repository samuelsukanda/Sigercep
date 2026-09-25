<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
    <div class="px-5 py-4">
        <form method="GET" action="{{ route('hardware.index') }}" id="filterForm">
            <div class="flex flex-wrap gap-3 items-end filter-wrap">

                @include('layouts.partials.filter-periode')
                {{-- Action Buttons --}}
                <div class="flex items-end flex-1 justify-between filter-action filter-action-hw">
                    <div class="flex items-end">
                        <!-- Button Cari -->
                        <button type="submit"
                            class="mr-1 inline-block px-4 py-2 mb-0 text-xs font-semibold text-center text-white uppercase align-middle transition-all rounded-lg shadow-md hover:shadow-xs active:opacity-85"
                            style="background-color: var(--accent) !important;">
                            <i class="fas fa-search text-sm leading-normal"></i>
                        </button>

                        <!-- Button Reset -->
                        <a href="{{ route('hardware.index') }}"
                            class="btn-reset inline-flex items-center justify-center
                                h-9 px-4 text-xs font-semibold text-slate-700 uppercase
                                rounded-lg shadow-md bg-gray-200 hover:shadow-sm active:opacity-85 transition-all">
                            Reset
                        </a>
                    </div>

                    <div class="flex items-end">
                        {{-- Reports --}}
                        @canAccess('hardware', 'read')
                        <a href="{{ route('hardware.reports') }}"
                            class="mr-1 inline-flex items-center justify-center
                            h-9 px-4 text-xs font-semibold text-slate-700 uppercase
                            rounded-lg shadow-md bg-gray-200 hover:shadow-sm active:opacity-85 transition-all">
                            <i class="fas fa-file-alt mr-2"></i> Laporan
                        </a>
                        @endcanAccess

                        {{-- Generate Otomatis --}}
                        @php
                            $canAccessPermissions = \App\Helpers\PermissionHelper::isSuperadmin(Auth::user());
                        @endphp

                        @if ($canAccessPermissions)
                        <button type="button"
                            onclick="document.getElementById('generateModal').classList.remove('hidden'); document.getElementById('generateModal').classList.add('flex');"
                            class="mr-1 inline-flex items-center justify-center
                            h-9 px-4 text-xs font-semibold text-white uppercase
                            rounded-lg shadow-md hover:shadow-sm active:opacity-85 transition-all"
                            style="background-color: #3b82f6 !important;">
                            <i class="fas fa-magic mr-2"></i> Generate
                        </button>
                        @endif

                        {{-- Tambah Data --}}
                        @canAccess('hardware', 'create')
                        <a href="{{ route('hardware.create') }}"
                            class="btn-tambah-data inline-flex items-center justify-center
                            h-9 px-4 text-xs font-semibold text-white uppercase
                            rounded-lg shadow-md hover:shadow-sm active:opacity-85 transition-all"
                            style="background-color: var(--accent) !important;">
                            <i class="fas fa-plus mr-1"></i> Tambah Data
                        </a>
                        @endcanAccess
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
