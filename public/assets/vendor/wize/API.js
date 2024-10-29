class API {
    constructor() {
        this.usedHeaders = ["csrf"];
        this.defaultHeaders = {};
        this.useRecaptcha = false;
        this.swalLoading = null;
    }

    // Method to initialize AJAX settings
    request(method, url, config = {}) {
        const defaultConfig = {
            type: "POST",
            cache: true,
            processData: true,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            loading: false, // Default loading is disabled
            beforeSend: () => {
                if (config.loading) {
                    this.swalLoading = swal_loading();
                }
            },
            addons_beforeSend: () => {},
            success: (res) => {
                if (res && res.hasOwnProperty("message")) {
                    const resMsg = res.message;
                    let swal = swal_success(resMsg);
                    return swal;
                }
            },
            addons_success: (res) => {},
            error: (res) => {
                Swal.close();

                if (res.status === 422) {
                    const errors = res.responseJSON?.errors;
                    let hasFocus = false;
                    $.each(errors, (key, value) => {
                        const input = $(`[name=${key}]`);
                        input.addClass("is-invalid");
                        input.nextAll(".invalid-feedback").html(value[0]);
                        if (!hasFocus) {
                            hasFocus = true;
                            input.focus();
                        }
                    });
                } else {
                    const errorMessage = res.responseJSON
                        ? res.responseJSON.message
                            ? res.responseJSON.message
                            : "Something went wrong!"
                        : "Something went wrong!";
                    swal_error(errorMessage);
                }
            },
            addons_error: (res) => {},
            complete: (data) => {
                if (swalLoading !== null) {
                    swalLoading.close();
                }
            },
            addons_complete: (data) => {},
        };

        // Merge defaultConfig and user config
        config = { ...defaultConfig, ...config };

        // Merge headers
        this.defaultHeaders = {
            ...this.defaultHeaders,
            ...useHeaders(...this.usedHeaders),
        };
        const headers = { ...this.defaultHeaders, ...config.headers };

        // Handle multipart/form-data
        if (config.data instanceof FormData) {
            config.contentType = false;
            config.enctype = 'multipart/form-data';
            // config.contentType = "multipart/form-data";
            config.processData = false; // Prevents jQuery from transforming data into query string
        }

        // Define the settings object
        const settings = {
            method: method,
            url: url,
            headers: headers,
            data: config.data,
            contentType: config.contentType,
            processData: config.processData,
            beforeSend: () => {
                config.beforeSend();
                config.addons_beforeSend();
            },
            success: (res) => {
                config.success(res);
                config.addons_success(res);
            },
            error: (res) => {
                config.error(res);
                config.addons_error(res);
            },
            complete: (data) => {
                config.complete(data);
                config.addons_complete(data);
            },
        };

        return this.send(settings);
    }

    // Method to execute the AJAX request
    send(settings) {
        clearValidationErrors();
        if (this.useRecaptcha) {
            let recaptcha_site_key = $('meta[name="recaptcha_site_key"]').attr(
                "content"
            );
            if (!recaptcha_site_key)
                throw new Error(
                    "Please add recaptcha site key inside meta tag content with name 'recaptchate_site_key'"
                );
            grecaptcha.ready(() => {
                grecaptcha
                    .execute(recaptcha_site_key, { action: "submit" })
                    .then((token) => {
                        $('meta[name="recaptcha-token"]').attr(
                            "content",
                            token
                        );

                        // Add Recaptcha Headers here
                        settings.headers = {
                            ...settings.headers,
                            ...useHeaders("recaptcha"),
                        };

                        $.ajax(settings);
                    });
            });
            return;
        }
        return $.ajax(settings);
    }

    // Method to add reCAPTCHA token from meta tag
    withCaptcha() {
        this.useRecaptcha = true;
        // this.usedHeaders.push("recaptcha");
        return this;
    }

    withHeaders(...keys) {
        this.usedHeaders = [...this.usedHeaders, ...keys];
        return this;
    }

    // Convenience methods for each HTTP verb
    get(url, config = {}) {
        return this.request("GET", url, config);
    }

    post(url, config = {}) {
        return this.request("POST", url, config);
    }

    patch(url, config = {}) {
        return this.request("PATCH", url, config);
    }

    put(url, config = {}) {
        return this.request("PUT", url, config);
    }

    delete(url, config = {}) {
        return this.request("DELETE", url, config);
    }
}

const api = new API();
