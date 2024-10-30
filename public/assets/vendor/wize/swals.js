function swal_success(message) {
    Swal.fire({
        icon: "success",
        text: message,
        customClass: {
            confirmButton: "btn btn-primary",
        },
        buttonsStyling: false,
        timer: 1200,
    });
}

function swal_error(message) {
    Swal.fire({
        icon: "error",
        text: message,
        showClass: {
            popup: "animate__animated animate__shakeX",
        },
        customClass: { confirmButton: "btn btn-primary" },
        buttonsStyling: false,
    });
}

let swalLoading = null;
function swal_loading(message = "Mohon menunggu...") {
    swalLoading = Swal.fire({
        html: `
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">${message}</p>
            </div>
        `,
        allowOutsideClick: false,
        buttonsStyling: false,
        showConfirmButton: false,
    });
}
