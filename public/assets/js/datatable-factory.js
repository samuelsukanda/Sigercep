// Factory DataTable: opsi yang sama di 32 file (processing, serverSide, language, initComplete).
// Tiap file datatable-*.js cukup specify ajax, columns, order (+ opsi khusus bila perlu).
window.SigTable = function (selector, options) {
    $.fn.dataTable.ext.errMode = "none";

    return $(selector).DataTable(
        $.extend(
            true,
            {
                processing: true,
                serverSide: true,
                language: {
                    zeroRecords: "No matching records found",
                    emptyTable: "No data available in table",
                },
                initComplete: function () {
                    $(this.api().table().container()).addClass(
                        "datatable-custom-wrapper",
                    );
                },
            },
            options || {},
        ),
    );
};
