define([
    'jquery'
], function($){
    'use strict';
    return function (){
        $.ajax({
            url:'/demo/timer/config',
            method: "GET",
            success: function (res) {
                timerCountLogic(res)
            }
        });

        function timerCountLogic(config) {
            let currentTime = new Date();
            let currentTimeMilliseconds = Date.parse(currentTime);
            let lastResetTime = config.last_reset_time.replace(/-/g, '/').replace(/[a-z]+/gi, ' ');
            let resetTimeout = config.reset_timeout;
            const nextResetTime = Date.parse(lastResetTime) + Math.abs(currentTime.getTimezoneOffset() * 60 * 1000) + resetTimeout * 60 * 1000;
            let timeToReset = (nextResetTime - currentTimeMilliseconds) / 1000;
            const bodyTag = $('body');

            bodyTag.removeClass('active-reload');

            // Format reset timeout text
            let resetTimeoutText;
            if (resetTimeout >= 1440) {
                let days = Math.floor(resetTimeout / 1440);
                resetTimeoutText = days + (days === 1 ? ' day' : ' days');
            } else if (resetTimeout >= 60) {
                let hours = Math.floor(resetTimeout / 60);
                resetTimeoutText = hours + (hours === 1 ? ' hour' : ' hours');
            } else {
                resetTimeoutText = resetTimeout + (resetTimeout === 1 ? ' minute' : ' minutes');
            }
            $('#reset_timeout_text').text(resetTimeoutText);

            let timer = setInterval(function tick (){
                if (timeToReset !== 0 && timeToReset > 10) {
                    timeToReset = timeToReset - 1;
                    let hours = Math.floor(timeToReset / 3600);
                    let minutes = Math.floor((timeToReset % 3600) / 60);
                    let seconds = Math.floor(timeToReset % 60);

                    let timeString;
                    if (hours > 0) {
                        timeString = `${hours < 10 ? '0' + hours : hours}:${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
                    } else {
                        timeString = `${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
                    }

                    $('#reset_timer').text(timeString);
                } else{
                    // ajax
                    clearInterval(timer);
                    bodyTag.addClass('active-reload')
                    setTimeout(function (){
                        document.location.reload();
                    }, 30000)
                }
            }, 1000);
        }
    }
});
