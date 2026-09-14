{{-- Preview Foto Komplain: thumbnail + modal fullscreen (pola sama dengan lampiran helpdesk) --}}
@php
    $fotoUrl = asset('storage/' . $foto);
    $fotoExt = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
    $isFotoImage = in_array($fotoExt, ['jpg', 'jpeg', 'png']);
@endphp

<div x-data="previewModal()">

    <label class="block mb-1 text-sm font-semibold text-slate-700">{{ $label }}</label>

    {{-- GRID --}}
    <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:8px; margin-top:10px;">

        <div @click="openModal('{{ $fotoUrl }}', '{{ $fotoExt }}')"
            style="position:relative; width:100%; padding-top:100%; cursor:pointer; overflow:hidden; border-radius:8px; border:1px solid #e5e7eb;">

            @if ($isFotoImage)
                <img src="{{ $fotoUrl }}" alt="{{ $label }}"
                    style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover;" />
            @else
                <div
                    style="position:absolute; top:0; left:0; width:100%; height:100%;
                                    display:flex; flex-direction:column; align-items:center; justify-content:center;
                                    background:#f3f4f6; font-size:12px; color:#555;">
                    📄 {{ $fotoExt }}
                </div>
            @endif

        </div>

    </div>

    {{-- MODAL --}}
    <div x-show="show" x-cloak x-transition.opacity @click.self="closeModal()"
        style="
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
        ">

        <div
            style="
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            ">

            {{-- CLOSE BUTTON --}}
            <button @click="closeModal()"
                style="
                position: absolute;
                top: 20px;
                right: 20px;
                background: rgba(255,255,255,0.9);
                border-radius: 50%;
                width: 40px;
                height: 40px;
                border: none;
                cursor: pointer;
                font-size: 18px;
                font-weight: bold;
                z-index: 10;
                ">
                ✕
            </button>

            {{-- IMAGE --}}
            <img x-show="isImage" :src="fileUrl"
                style="
                max-width: 90%;
                max-height: 85vh;
                object-fit: contain;
                border-radius: 10px;
                display: block;
                margin: auto;
                box-shadow: 0 10px 40px rgba(0,0,0,0.5);
                ">

            {{-- NON IMAGE --}}
            <iframe x-show="!isImage" :src="fileUrl"
                style="
                width: 90vw;
                height: 85vh;
                background: white;
                border-radius: 10px;
                border: none;
                ">
            </iframe>

        </div>
    </div>

</div>
