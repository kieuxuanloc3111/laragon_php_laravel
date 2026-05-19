const deleteButtons = document.querySelectorAll('.delete-btn');

deleteButtons.forEach(button => {

    button.addEventListener('click', function(){

        const form = this.closest('.delete-form');

        Swal.fire({

            title: 'Xóa môn học?',
            text: 'Bạn có chắc muốn xóa môn học này không?',
            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',

            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'

        }).then((result) => {

            if(result.isConfirmed){

                form.submit();

            }

        });

    });

});