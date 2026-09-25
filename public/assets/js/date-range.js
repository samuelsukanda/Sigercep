/* Filter periode (Periode Dari / Periode Sampai) untuk semua halaman.
   Nilai awal dikirim per halaman agar default tiap modul tetap berbeda. */
window.initDateRange = function (dariValue, sampaiValue) {
    var elDari = document.querySelector("input[name='periode_dari']");
    var elSampai = document.querySelector("input[name='periode_sampai']");
    if (!elDari || !elSampai || typeof flatpickr === "undefined") return;

    var dari = flatpickr(elDari, {
        dateFormat: "d-m-Y",
        allowInput: false,
        onChange: function (selectedDates, dateStr) {
            sampai.set("minDate", dateStr);
        },
    });

    var sampai = flatpickr(elSampai, {
        dateFormat: "d-m-Y",
        allowInput: false,
        onChange: function (selectedDates, dateStr) {
            dari.set("maxDate", dateStr);
        },
    });

    if (dariValue) dari.setDate(dariValue);
    if (sampaiValue) sampai.setDate(sampaiValue);
    if (dariValue) sampai.set("minDate", dariValue);
    if (sampaiValue) dari.set("maxDate", sampaiValue);
};
