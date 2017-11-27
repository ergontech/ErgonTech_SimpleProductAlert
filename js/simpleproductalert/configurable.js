if (Product instanceof Object &&
    Product.Config instanceof Object
) {
    var configProto = Product.Config.prototype;

    configProto.fillSelect = configProto.fillSelect.wrap(function (_super, element) {
        _super(element);

        var disabledOptions = element.querySelectorAll('option:disabled');

        Array.prototype.slice.call(disabledOptions).forEach(function (disabledOption) {
            disabledOption.setAttribute('data-allow-notify', true);
            disabledOption.disabled = false;
        });
    });

    configProto.configure = configProto.configure.wrap(function (_super, ev) {
        $$('.simpleproductalert_notify-link').invoke('hide');
        _super(ev);

        var notifySelector = this.settings.map(function (el) {
            return '[' + el.id.replace('attribute', 'data-') + '=' + el.value + ']'
        }).join('');

        $$(notifySelector).invoke('show');
    });
}
