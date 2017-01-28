/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class ProductListController {
        constructor(public $scope:any, public $minute:any, public $ui:any, public $timeout:ng.ITimeoutService,
                    public gettext:angular.gettext.gettextFunction, public gettextCatalog:angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
        }

        actions = (item) => {
            let gettext = this.gettext;
            let actions = [
                {'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit product'), 'href': '/admin/products/edit/' + item.product_id},
                {'text': gettext('Clone'), 'icon': 'fa-copy', 'hint': gettext('Clone product'), 'click': 'ctrl.clone(item)'},
                {'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this product'), 'click': 'item.removeConfirm("Removed")'},
            ];

            this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, this.$scope, {item: item, ctrl: this});
        };

        clone = (product) => {
            let gettext = this.gettext;

            product.levels.setItemsPerPage(99, false);
            product.levels.reloadAll(true).then(() => {
                product.coupons.setItemsPerPage(99, false);
                product.coupons.reloadAll(true).then(() => {
                    this.$ui.prompt(gettext('Enter new product name'), gettext('new-name')).then(function (name) {
                        product.clone().attr('name', name).save(gettext('Product duplicated')).then(function (copy) {
                            angular.forEach(product.coupons, (coupon) => copy.item.coupons.cloneItem(coupon).save());
                            angular.forEach(product.levels, (level) => copy.item.levels.cloneItem(level).save());
                        });
                    });
                });
            });
        }
    }

    angular.module('productListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('productListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ProductListController]);
}
