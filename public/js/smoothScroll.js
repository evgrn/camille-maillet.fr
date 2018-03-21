$(function(){

    var smoothScroll = {
        menuSelector: "",
        speed: 750,

        init: function (menuSelector, speed) {
            this.menuSelector = menuSelector;
            this.speed = speed;
            this.scroll();
        },

        scroll: function (menuItem) {
            var selector = this.menuSelector + ' a';
            $.each($(selector), function () {
                var link = this;
                $(this).parent().on('click', function () {
                    var section = $(link).attr('href');
                    $('html, body').animate({scrollTop: $(section).offset().top - smoothScroll.offsetMargin()}, smoothScroll.speed);
                    return false;

                });


            });
        },

        offsetMargin: function(){
            if($(window).height() >= $(window).width() ){
                return 40;
            }
            else{
                return 0;
            }
        }
    };

    smoothScroll.init('#main-nav', 750);
});