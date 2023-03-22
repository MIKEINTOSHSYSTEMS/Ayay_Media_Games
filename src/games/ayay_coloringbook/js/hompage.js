$(document).ready(function() { 

    // HIGHLIGHT NAV LINK
    $(".navLink").click(function () {
        $(".navLink").not(this).removeClass("active");
        $(this).addClass("active");
        
    });

    // GET A DIV TO APPEAR ON SCROLL
    // Resource: http://jsfiddle.net/mohammadAdil/DFeqH/
    // Resource: http://jsfiddle.net/mohammadAdil/DFeqH/
    

    $(window).scroll(function() {

        $(".toFadeIn").each (function(i) {

            let bottom_of_object = $(this).position().top + $(this).outerHeight();
            let bottom_of_window = $(window).scrollTop() + $(window).height();

            if ($(this).hasClass("what-else")) {
                if (bottom_of_window > bottom_of_object*0.8) {

                    $(this).animate(
                        {
                            "opacity":'1'
                        }, 500
                    );
                    
                }
                                  
            } else {

                if (bottom_of_window > bottom_of_object) {

                    $(this).animate(
                        {
                            "opacity":'1'
                        }, 500
                    );
                    
                }
            }
        });
    });

    // SLIDE LEFT TO RIGHT ON SCROLL

    $(window).scroll(function() {
        $(".footer-header").each (function(i) {

            let bottom_of_object = $(this).position().top + $(this).outerHeight(); 
            let bottom_of_window = $(window).scrollTop() + $(window).height();

            if (bottom_of_window > bottom_of_object) {
                $(this).addClass("transformRight");
            }
        });
    });

});
