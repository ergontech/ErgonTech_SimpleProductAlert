/*global document*/
document.observe('dom:loaded', function (e) {
    'use strict';

    var link = document.querySelector('.simpleproductalert_notify-link');
    var addToBox;

    if (!link) {
        return;
    }

    link.parentElement.removeChild(link);

    addToBox = document.querySelector('.product-shop .add-to-box');

    if (!addToBox) {
        return;
    }

    link.hidden = false;
    addToBox.parentElement.insertBefore(link, addToBox);
});
