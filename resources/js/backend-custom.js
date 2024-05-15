"use strict";

var App = function($) {

    let money = function (value, currency = 'EUR'){

        let options = {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        };

        if(currency){
            options.style = 'currency';
            options.currency = currency;
        }

        let number = new Intl.NumberFormat('it-IT', options);
        return number.format(value);
    };

    return {
        money,
    };

}(jQuery);

window.App = App;
