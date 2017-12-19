/** Created by acrazing on 2015/7/18. */
$(function() {
    $("body>.header>.btn-return").on("click", function() {
        var $this = $(this),
            returnUrl = $this.data("return-url");
        if(!returnUrl) {
            history.go(-1);
        } else {
            location.href = returnUrl;
        }
    });
});