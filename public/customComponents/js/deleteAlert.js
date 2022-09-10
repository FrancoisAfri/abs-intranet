function swal(val) {

    $('.'+val).click(function (event) {

        let form = $(this).closest("form");

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger mr-2'
            },
            buttonsStyling: false,
        })

        event.preventDefault();

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'mr-2',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {

            if (result.value) {
                swalWithBootstrapButtons.fire(
                    'Deleted!',
                    'Role has been deleted.',
                    'success'
                )
                form.submit();
            } else if (
                // Read more about handling dismissals
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Delete action has been Cancelled ',
                    'error'
                )
            }
        })
    });
}
