if (Product instanceof Object &&
    Product.Config instanceof Object
) {
    (function (configProto, cartButtonView) {
        'use strict';

        configProto.fillSelect = configProto.fillSelect.wrap(function (_super, element) {
            _super(element);

            var disabledOptions = element.querySelectorAll('option:disabled');

            Array.prototype.slice.call(disabledOptions).forEach(function (disabledOption) {
                disabledOption.setAttribute('data-out-of-stock', true);
                disabledOption.disabled = false;
            });
        });

        configProto.configureElement = configProto.configureElement.wrap(function (_super, el) {
            $$('.simpleproductalert_notify-link').invoke('hide');
            cartButtonView.show();
            _super(el);

            var notifySelector = this.settings.map(function (el) {
                if (el.options[el.selectedIndex].getAttribute('data-out-of-stock')) {
                    cartButtonView.hide();
                }
                return '[' + el.id.replace('attribute', 'data-') + '=' + el.value + ']'
            }).join('');

            $$(notifySelector).invoke('show');
        });
    }(Product.Config.prototype, (function (cartButtonSelector) {
        var cartButton;
        var state = 'initial';

        document.observe('dom:loaded', function () {
            cartButton = document.querySelector(cartButtonSelector);
            renderState();
        });
        function renderState() {
            if (cartButton) {
                cartButton.style.display = state;
            }
        }
        return {
            hide: function () {
                state = 'none';
                renderState();
            },
            show: function () {
                state = 'initial';
                renderState();
            }
        }
    }('#product_addtocart_form [onclick*="productAddToCartForm.submit"]'))));
}
