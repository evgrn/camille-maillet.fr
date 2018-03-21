$(function(){

    var scrollDownButton = {
        buttonSelector: '',
        sectionSelector: '',
        speed: 750,

        init: function (buttonSelector, sectionSelector, speed) {
            this.buttonSelector = buttonSelector;
            this.sectionSelector = sectionSelector
            this.speed = speed;
            setTimeout(this.toggleButton, 1500);
            this.listenClick();
            this.listenScroll();
        },

        listenClick: function () {
            $(this.buttonSelector).on('click', function () {
                $('html, body').animate({scrollTop: $(scrollDownButton.sectionSelector).height()}, scrollDownButton.speed);
            });
        },

        toggleButton: function () {
            if ($(scrollDownButton.sectionSelector).offset().top < $(window).scrollTop()) {
                scrollDownButton.hideButton();
            }
            else {
                scrollDownButton.showButton();
            }
        },

        hideButton: function () {
            $(this.buttonSelector).css('animation', 'none').hide("fade", 500);
        },

        showButton: function () {
            $(this.buttonSelector).show("drop", {direction: "down"}, 500, function () {
                $(scrollDownButton.buttonSelector).css('animation', 'shake 3s infinite ease');
            });
        },

        listenScroll: function () {
            $(window).on('scroll', function () {
                scrollDownButton.toggleButton();
            });
        }


    };

    scrollDownButton.init('#intro-scrolldown', '#intro', 750);
});