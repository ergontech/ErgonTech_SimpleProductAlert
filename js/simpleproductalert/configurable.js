if (Product instanceof Object &&
    Product.Config instanceof Object
) {
    (function (configProto, cartButton) {
        'use strict';
        document.observe('dom:loaded', function () {
            cartButton = document.querySelector('#product_addtocart_form [onclick*="productAddToCartForm.submit"]');
        });

        configProto.fillSelect = configProto.fillSelect.wrap(function (_super, element) {
            _super(element);

            var disabledOptions = element.querySelectorAll('option:disabled');

            Array.prototype.slice.call(disabledOptions).forEach(function (disabledOption) {
                disabledOption.setAttribute('data-out-of-stock', true);
                disabledOption.disabled = false;
            });
        });

        configProto.configure = configProto.configure.wrap(function (_super, ev) {
            $$('.simpleproductalert_notify-link').invoke('hide');
            cartButton.style.display = 'initial';
            _super(ev);

            var notifySelector = this.settings.map(function (el) {
                if (el.options[el.selectedIndex].getAttribute('data-out-of-stock')) {
                    cartButton.style.display = 'none';
                }
                return '[' + el.id.replace('attribute', 'data-') + '=' + el.value + ']'
            }).join('');

            $$(notifySelector).invoke('show');
        });
    }(Product.Config.prototype));
}
