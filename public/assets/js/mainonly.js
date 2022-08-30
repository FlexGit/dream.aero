$(function(){
    $('#varsL').height($('#varsR').height());
    $('#varsL img').height($('#varsR').height());
    $teamer = 0;

    $(".team .owl-carousel").owlCarousel({
        items:4,
        margin:50,
        nav:true,
        responsive:{
            0:{
                items:1,
            },
            500:{
                items:2
            },
            992:{
                items:3
            },
            1200:{
                items:4
            }
        }
    });

    var reviewsCarousel = $(".reviews-carousel");

    reviewsCarousel.owlCarousel({
        items:1,
        nav:false,
        dots:false,
        animateOut:'fadeOut',
        animateIn:'fadeIn',
        smartSpeed:500,
        loop:true
    });

    $('.reviews-prev').click(function(){
        reviewsCarousel.trigger('prev.owl.carousel');
    });

    $('.reviews-next').click(function(){
        reviewsCarousel.trigger('next.owl.carousel');
    });
});
