$('.delete_confirm').click(function (event) {

    let form = $(this).closest("form");
    let name = $(this).data("name");

    event.preventDefault();

    swal({
        title: `Are you sure you want to delete this record?`,
        text: "If you delete this, it will be gone forever.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                form.submit();
                swal("Poof! Your Record has been deleted!", {
                    icon: "success",
                });
            }

        });

});