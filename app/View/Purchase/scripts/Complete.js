/// <reference path="E:/var/Dropbox/projects/minutephp/public/static/bower_components/minute/_all.d.ts" />
var App;
(function (App) {
    var CompleteController = (function () {
        function CompleteController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $timeout(function () { return top.location.href = '/_auth/reload'; }, 4000);
        }
        return CompleteController;
    }());
    App.CompleteController = CompleteController;
    angular.module('CompleteApp', ['MinuteFramework', 'gettext'])
        .controller('CompleteController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', CompleteController]);
})(App || (App = {}));
