$(function(){

    var stick = {
        stickyElement: '',
        stickyElementOffset: '',

        init: function (stickyElement) {
            this.stickyElement = stickyElement;
            this.initStickyElementOffset();
            this.handleStickyness();
            this.listenResize();
            this.listenScroll();
        },

        initStickyElementOffset: function(){
            this.stickyElementOffset = $(this.stickyElement).offset().top;
        },

        stick: function () {
            $(this.stickyElement).addClass('fixed-top');
        },

        unstick: function () {
            $(this.stickyElement).removeClass('fixed-top');
        },

        isSticky: function () {
            if (this.stickyElementOffset - $(window).scrollTop() <= 0) {
                return true;
            }
            return false;
        },

        handleStickyness: function () {
            if (this.isSticky()) {
                this.stick();
            } else {
                this.unstick();
            }
        },

        listenScroll: function () {
            $(window).on('scroll', function () {
                stick.handleStickyness();
            })
        },

        listenResize: function(){
            $(window).on('resize', function(){
                if(!stick.isSticky()){
                    stick.initStickyElementOffset();
                }
                else{
                    var hasBeenResized = false;
                    $(this).on('scroll', function(){
                        if(!hasBeenResized){
                            if(!stick.isSticky()){
                                stick.initStickyElementOffset();
                                hasBeenResized = true;
                            }
                        }
                    });
                }

            })
        }


    };

    stick.init('#global-nav');

})