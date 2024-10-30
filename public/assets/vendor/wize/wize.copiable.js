(function ($) {
    $.fn.copiable = function (options) {
        var settings = $.extend(
            {
                success: null,
                error: null,
            },
            options
        );

        return this.each(function () {
            $(this).on("click", function () {
                let copyText;

                if ($(this).is("input, textarea")) {
                    copyText = $(this).val();
                } else {
                    copyText = $(this).data("wz-copy") ?? $(this).text();
                }

                navigator.clipboard
                    .writeText(copyText)
                    .then(function () {
                        if (typeof settings.success === "function") {
                            settings.success(copyText);
                        }
                    })
                    .catch(function (error) {
                        if (typeof settings.error === "function") {
                            settings.error(error);
                        }
                    });
            });

            $(this).css("cursor", "pointer");
        });
    };
})(jQuery);
