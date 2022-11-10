
$(function () {

    $('table.display').DataTable({

        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true,
        dom: 'Bfrtip',
        buttons: [
            // 'copy', 'csv', 'excel',
            {
                extend: 'print',
                title: 'Employee Records',
                exportOptions: {
                    stripHtml: false,
                    columns: ':visible:not(.not-export-col)'
                }
            },
            {
                extend: 'pdfHtml5',
                title: 'Employee Records',
                //download: 'open',
                exportOptions: {
                    stripHtml: true,
                    columns: ':visible:not(.not-export-col)'
                },
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },
            {extend: 'copyHtml5', exportOptions: {columns: ':visible'}},
            {extend: 'csvHtml5', title: 'CSV', exportOptions: {columns: ':visible'}},
            // { extend: 'excelHtml5', title: 'Excel', exportOptions: { columns: ':visible' } },
            {
                text: 'excel',
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible:not(.not-export-col)'
                }
            },

        ]

    });

});

