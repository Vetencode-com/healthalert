(function ($) {
    $.fn.wizeCounter = function (options) {
        const settings = $.extend(
            {
                interval: null,
                timeout: null,
                onProgress: function () {},
                onComplete: function () {},
                onInterval: function () {},
                onEachSecond: null,
            },
            options
        );

        return this.each(function () {
            const $this = $(this);
            const interval =
                settings.interval || Number($this.data("wz-interval")) * 1000;
            const timeout =
                settings.timeout || Number($this.data("wz-timeout")) * 1000;

            let intervalId, timeoutId, secondTimerId;
            let startTime = Date.now();

            if (interval) {
                intervalId = setInterval(function () {
                    settings.onInterval.call($this);
                }, interval);
            }

            if (timeout && timeout > 0) {
                timeoutId = setTimeout(function () {
                    clearInterval(intervalId);
                    clearInterval(secondTimerId);
                    settings.onComplete.call($this);
                }, timeout);

                const progressCheck = setInterval(function () {
                    const elapsedTime = Date.now() - startTime;
                    if (elapsedTime >= timeout) {
                        clearInterval(progressCheck);
                    } else {
                        settings.onProgress.call($this, elapsedTime);
                    }
                }, interval || 100);
            }

            // Setup onEachSecond callback if provided
            if (settings.onEachSecond) {
                secondTimerId = setInterval(function () {
                    const elapsedTime = Math.floor(
                        (Date.now() - startTime) / 1000
                    );
                    const currentTime = new Date(); // Get the current time
                    settings.onEachSecond.call($this, elapsedTime, currentTime);
                }, 1000);
            }

            return { intervalId, timeoutId, secondTimerId };
        });
    };
})(jQuery);
