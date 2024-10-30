const requestHeaders = {
    csrf: {
        key: "X-CSRF-TOKEN",
        value: function () {
            return $('meta[name="csrf-token"]').attr("content");
        },
    },
    recaptcha: {
        key: "X-RECAPTCHA-TOKEN",
        value: function () {
            return $('meta[name="recaptcha-token"]').attr("content");
        },
    },
};

function useHeaders(...keys) {
    const headers = {};
    keys.forEach((key) => {
        const item = requestHeaders[key];
        const value =
            typeof item.value === "function" ? item.value() : item.value;
        headers[item.key] = value;
    });
    return headers;
}

function clearValidationErrors() {
    $(".is-invalid").removeClass("is-invalid");
}

function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();

        reader.onload = function (e) {
            let $preview = $("#" + previewId);
            $preview.attr("src", e.target.result);
            $preview.removeClass("d-none"); // Show the image preview
        };

        reader.readAsDataURL(input.files[0]); // Read the file
    }
}

function getValues(prefix = "form", ...inputNames) {
    const data = {};
    inputNames.forEach((inputName) => {
        data[inputName] = $(`${prefix} [name="${inputName}"]`).val();
    });
    return data;
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(";").shift();
    return null;
}

class Interactions {
    confirm({
        title = "Apakah anda yakin?",
        icon = "warning",
        confirmButtonText = "Ya",
        cancelButtonText = "Batal",
        showCancelButton = true,
        onConfirmed = () => {},
        onCanceled = () => {},
    }) {
        Swal.fire({
            title,
            icon,
            confirmButtonText,
            cancelButtonText,
            showCancelButton,
        }).then((result) => {
            if (result.isConfirmed) {
                onConfirmed();
            } else {
                onCanceled();
            }
        });
    }

    info_success({
        title = "Berhasil!",
        text = "Aksi berhasil dilakukan.",
        icon = "success",
        timer = 3000,
    }) {
        Swal.fire({
            title,
            text,
            icon,
            timer,
            showConfirmButton: false,
            toast: false,
            position: "center",
        });
    }
}

const interact = new Interactions();
