/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var ProductEditController = (function () {
        function ProductEditController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.addAccess = function () {
                _this.editAccess(_this.$scope.product.levels.create());
            };
            this.editAccess = function (level) {
                _this.$ui.closePopup();
                _this.$ui.popupUrl('/edit-level-popup.html', false, null, { level: level, ctrl: _this });
            };
            this.viewAccess = function () {
                _this.$ui.popupUrl('/levels-popup.html', false, null, { levels: _this.$scope.product.levels, ctrl: _this });
            };
            this.saveAccess = function (level) {
                level.save(_this.gettext('Access updated'));
                _this.$ui.closePopup();
            };
            this.addCoupon = function () {
                _this.editCoupon(_this.$scope.product.coupons.create());
            };
            this.editCoupon = function (coupon) {
                _this.$ui.closePopup();
                _this.$ui.popupUrl('/edit-coupon-popup.html', false, null, { coupon: coupon, ctrl: _this });
            };
            this.saveCoupon = function (coupon) {
                coupon.save(_this.gettext('Coupon updated'));
                _this.$ui.closePopup();
            };
            this.viewCoupons = function () {
                _this.$ui.popupUrl('/coupons-popup.html', false, null, { coupons: _this.$scope.product.coupons, ctrl: _this });
            };
            this.viewPurchaseLinks = function () {
                _this.$ui.popupUrl('/purchase-popup.html', false, null, { ctrl: _this, product: _this.$scope.product });
            };
            this.copied = function () {
                _this.$ui.toast(_this.gettext('URL successfully copied to clipboard'));
            };
            this.save = function () {
                _this.$scope.product.save(_this.gettext('Product saved successfully'));
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.product = $scope.products[0] || $scope.products.create().attr('enabled', true);
            $scope.data = {};
        }
        return ProductEditController;
    }());
    Admin.ProductEditController = ProductEditController;
    angular.module('productEditApp', ['MinuteFramework', 'AdminApp', 'gettext', 'ngClipboard'])
        .config(['ngClipProvider', function (ngClipProvider) { return ngClipProvider.setPath("/static/bower_components/zeroclipboard/dist/ZeroClipboard.swf"); }])
        .controller('productEditController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ProductEditController]);
})(Admin || (Admin = {}));
