$(function(){

    var menuToggle = {

        toggleSelector: '',

        menusSelector: '',

        globalSelector: '',

        init: function(toggleSelector, menuSelector){
            this.toggleSelector = toggleSelector;
            this.menuSelector = menuSelector;
            this.globalSelector = this.toggleSelector + ', ' + this.menuSelector + ' li';
            this.listenClick();
        },

        toggle: function(){
            $(this.menuSelector).toggleClass('mobile-hidden');
        },

        listenClick: function(){
            $(this.globalSelector).on('click', function(){
              menuToggle.toggle();
              menuToggle.handleScroll();
            })
        },

        handleScroll: function () {
            if($(this.toggleSelector).offset().top > $(window).scrollTop()){
                $('html, body').animate({scrollTop: $(menuToggle.toggleSelector).offset().top}, 500);
            }

        }

    };

    menuToggle.init('#menu-toggle', '#main-nav');

});