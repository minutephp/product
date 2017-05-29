/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module App {
    export class CompleteController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');

            $timeout(() => top.location.href = '/_auth/reload', 4000);
        }
    }

    angular.module('CompleteApp', ['MinuteFramework', 'gettext'])
        .controller('CompleteController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', CompleteController]);
}
