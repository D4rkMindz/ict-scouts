/**
 * @author Dominik Müller (Ashura) ashura@aimei.ch
 */

'use strict';

/**
 * app module with immediately invoked function.
 */
var app = (function(object) {
    // Public functions

    /**
     * Function to initialize the application.
     */
    object.init = function(){
        $(".button-collapse").sideNav();
    };

    object.init();

    return object;
})(module.clone());

