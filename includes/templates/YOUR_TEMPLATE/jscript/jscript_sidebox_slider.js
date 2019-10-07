// -----
// Cycles through the configured elements, changing on the specified interval.
//
// Example: $("div.whatsNewScroller").cycle(5000);
//
$.fn.cycle = function(timeout) {
    show_cycle_elem = function($theSelections, which) {
        if (which == $theSelections.length) {
            which = 0;
        }
        $theSelections.hide().eq(which).fadeIn('fast');
        setTimeout(function() {
            show_cycle_elem($theSelections, ++which)
        }, timeout);
    
    }
    show_cycle_elem(this, 0);
}