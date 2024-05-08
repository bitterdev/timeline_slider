(function($) {
    $(function(){
        $(".btn-year").click(function(e) {
            e.stopPropagation();
            e.preventDefault();
            $(".btn-year").removeClass("active");
            $(this).addClass("active");
            $(".timeline-container").addClass("d-none");
            $(".timeline-container[data-year='" + $(this).data("year") + "']").removeClass("d-none");
            return false;
        }).first().trigger("click");
    });
})(jQuery);