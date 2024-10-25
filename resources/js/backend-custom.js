"use strict";

var App = function($) {

    let date = function (date){
        if(date){
            return new Date(date).toLocaleDateString("it-IT")
        }
        return null;
    };

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
        date,
        money
    };

}(jQuery);

window.App = App;
