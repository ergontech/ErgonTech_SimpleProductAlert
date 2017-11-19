if (Product instanceof Object &&
    Product.Config instanceof Object
) {
    Product.Config.prototype.fillSelect = Product.Config.prototype.fillSelect.wrap(function (_super, element) {
        _super(element);

        var disabledOptions = element.querySelectorAll('option:disabled');

        Array.prototype.slice.call(disabledOptions).forEach(function (disabledOption) {
            disabledOption.disabled = null;
        });
    });

}
