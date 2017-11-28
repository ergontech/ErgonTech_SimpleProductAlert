/*global document*/
document.observe('dom:loaded', function (e) {
    'use strict';

    var link = document.querySelector('.simpleproductalert_notify-link');
    var priceBox;

    if (!link) {
        return;
    }

    link.parentElement.removeChild(link);

    priceBox = document.querySelector('.product-shop .add-to-box');

    if (!priceBox) {
        return;
    }

    link.hidden = false;
    priceBox.parentElement.insertBefore(link, priceBox);
});
