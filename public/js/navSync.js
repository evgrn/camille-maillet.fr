$(function(){
    var navSync = {
        menuItems: '',

        init: function(menu){
            this.menuItems = menu + ' li a';
            this.manageActivations();
            this.listenScroll();
        },

        listenScroll: function(){
            $(document).on('scroll', function(){
                navSync.manageActivations();
            })
        },

        manageActivations: function(){
            var scrollTop = $(window).scrollTop();

            $(this.menuItems).each(function(){
                var currentItem = $(this);
                var linkedElement = $(currentItem.attr("href"));

                if ($(linkedElement).offset().top <= scrollTop ) {
                    $(navSync.menuItems).parent().removeClass("active");
                    currentItem.parent().addClass("active");
                }
                else{
                    currentItem.removeClass("active");}
            });
        }

    };

    navSync.init('#main-nav');

});