// Select2 - satu daftar placeholder untuk semua dropdown
var SELECT2_PLACEHOLDERS = {
    unit: "Pilih Unit",
    unit_asal: "Pilih Unit Asal",
    unit_tujuan: "Pilih Unit Tujuan",
    lantai: "Pilih Lantai",
    toner: "Pilih Toner",
    jenis_spo: "Pilih Jenis SPO",
    tujuan_unit: "Pilih Tujuan Unit",
    status: "Pilih Status",
    ruang: "Pilih Ruang",
    approval: "Pilih Approval",
    jenis_kendaraan: "Pilih Jenis Kendaraan",
    jumlah_penumpang: "Pilih Jumlah Penumpang",
    waktu_tempuh: "Pilih  Waktu Tempuh",
    jarak_tempuh: "Pilih  Jarak Tempuh",
    jenis_layanan: "Pilih Jenis Layanan",
    tim: "Pilih Tim",
    analisis_tingkat: "Pilih Tingkat",
    satuan: "Pilih Ukuran",
    detik: "Pilih Durasi",
    menit: "Pilih Durasi",
    perawat: "Pilih Perawat",
    kelompok_umur: "Pilih Kelompok Umur",
    jenis_kelamin: "Pilih Jenis Kelamin",
    penanggung_jawab: "Pilih Penanggung Jawab",
    jenis_kejadian: "Pilih Jenis Kejadian",
    jenis_insiden: "Pilih Jenis Insiden",
    insiden_pasien: "Pilih Insiden Pasien",
    jenis_spesialisasi_pasien: "Pilih Jenis Spesialisasi Pasien",
    akibat_insiden: "Pilih Jenis Akibat Insiden",
    tindakan_dilakukan_oleh: "Pilih Jenis Tindakan Dilakukan Oleh",
    kejadian_serupa: "Pilih Jenis Kejadian Serupa",
    grading_risiko: "Pilih Jenis Grading Risiko",
    jenis_dokumen: "Pilih Jenis Jenis Dokumen",
    permintaan_fitur: "Pilih Permintaan Fitur",
    permintaan_pengajuan: "Pilih Jenis Permintaan Pengajuan",
    kategori_pengajuan: "Pilih Jenis Kategori Pengajuan",
    alasan_pengajuan: "Pilih Jenis Alasan Pengajuan",
    kategori_laporan: "Pilih Jenis Kategori Laporan",
    urgency: "Pilih Jenis Urgensi",
    category: "Pilih Jenis Kategori",
    approval_status: "Pilih Status Approval",
};

$(document).ready(function () {
    $.each(SELECT2_PLACEHOLDERS, function (id, placeholder) {
        var $el = $("#" + id);
        if ($el.is("select")) {
            $el.select2({ placeholder: placeholder, allowClear: true });
        }
    });
});

// Flatpickr
flatpickr("#tanggal", {
    dateFormat: "Y-m-d",
    allowInput: false,
});

flatpickr("#periode_dari", {
    dateFormat: "Y-m-d",
    allowInput: false,
});

flatpickr("#periode_sampai", {
    dateFormat: "Y-m-d",
    allowInput: false,
});

flatpickr("#tanggal_lahir", {
    dateFormat: "Y-m-d",
    allowInput: false,
});

flatpickr("#tanggal_masuk_rs", {
    dateFormat: "Y-m-d",
    allowInput: false,
});

flatpickr("#tanggal_kejadian", {
    dateFormat: "Y-m-d",
    allowInput: false,
});

flatpickr("#tanggal_pengajuan", {
    dateFormat: "Y-m-d",
    allowInput: false,
});

flatpickr("#estimated_completion", {
    enableTime: true,
    dateFormat: "d-m-Y H:i",
    time_24hr: true,
    allowInput: false,
    defaultDate: new Date() 
});

flatpickr("#waktu_kejadian", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
});

flatpickr("#jam", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
});

flatpickr("#jam_mulai", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
});

flatpickr("#jam_selesai", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
});

flatpickr("#jam_berangkat", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
});

flatpickr("#jam_pulang", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
});
