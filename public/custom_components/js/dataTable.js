$(function () {
    $('#example2').DataTable({
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true,
        buttons: [
            {
                extend: 'copy',
                text: 'Copy to clipboard'
            },
            'excel',
            'pdf'
        ]
    });

    $('.modal').on('show.bs.modal', reposition);
});

