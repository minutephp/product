/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class ProductEditController {
        constructor(public $scope:any, public $minute:any, public $ui:any, public $timeout:ng.ITimeoutService,
                    public gettext:angular.gettext.gettextFunction, public gettextCatalog:angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.product = $scope.products[0] || $scope.products.create().attr('enabled', true);
            $scope.data = {};
        }

        addAccess = () => {
            this.editAccess(this.$scope.product.levels.create());
        };

        editAccess = (level) => {
            this.$ui.closePopup();
            this.$ui.popupUrl('/edit-level-popup.html', false, null, {level: level, ctrl: this});
        };

        viewAccess = () => {
            this.$ui.popupUrl('/levels-popup.html', false, null, {levels: this.$scope.product.levels, ctrl: this});
        };

        saveAccess = (level) => {
            level.save(this.gettext('Access updated'));
            this.$ui.closePopup();
        };

        addCoupon = () => {
            this.editCoupon(this.$scope.product.coupons.create());
        };

        editCoupon = (coupon) => {
            this.$ui.closePopup();
            this.$ui.popupUrl('/edit-coupon-popup.html', false, null, {coupon: coupon, ctrl: this});
        };

        saveCoupon = (coupon) => {
            coupon.save(this.gettext('Coupon updated'));
            this.$ui.closePopup();
        };

        viewCoupons = () => {
            this.$ui.popupUrl('/coupons-popup.html', false, null, {coupons: this.$scope.product.coupons, ctrl: this});
        };

        viewPurchaseLinks = () => {
            this.$ui.popupUrl('/purchase-popup.html', false, null, {ctrl: this, product: this.$scope.product});
        };

        copied = () => {
            this.$ui.toast(this.gettext('URL successfully copied to clipboard'));
        };

        save = () => {
            this.$scope.product.save(this.gettext('Product saved successfully'));
        };
    }

    angular.module('productEditApp', ['MinuteFramework', 'AdminApp', 'gettext', 'ngClipboard'])
        .config(['ngClipProvider', (ngClipProvider) => ngClipProvider.setPath("/static/bower_components/zeroclipboard/dist/ZeroClipboard.swf")])
        .controller('productEditController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ProductEditController]);
}
