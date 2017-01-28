/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var ProductListController = (function () {
        function ProductListController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.actions = function (item) {
                var gettext = _this.gettext;
                var actions = [
                    { 'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit product'), 'href': '/admin/products/edit/' + item.product_id },
                    { 'text': gettext('Clone'), 'icon': 'fa-copy', 'hint': gettext('Clone product'), 'click': 'ctrl.clone(item)' },
                    { 'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this product'), 'click': 'item.removeConfirm("Removed")' },
                ];
                _this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, _this.$scope, { item: item, ctrl: _this });
            };
            this.clone = function (product) {
                var gettext = _this.gettext;
                product.levels.setItemsPerPage(99, false);
                product.levels.reloadAll(true).then(function () {
                    product.coupons.setItemsPerPage(99, false);
                    product.coupons.reloadAll(true).then(function () {
                        _this.$ui.prompt(gettext('Enter new product name'), gettext('new-name')).then(function (name) {
                            product.clone().attr('name', name).save(gettext('Product duplicated')).then(function (copy) {
                                angular.forEach(product.coupons, function (coupon) { return copy.item.coupons.cloneItem(coupon).save(); });
                                angular.forEach(product.levels, function (level) { return copy.item.levels.cloneItem(level).save(); });
                            });
                        });
                    });
                });
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
        }
        return ProductListController;
    }());
    Admin.ProductListController = ProductListController;
    angular.module('productListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('productListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ProductListController]);
})(Admin || (Admin = {}));
