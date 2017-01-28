/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var ListEditController = (function () {
        function ListEditController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.addSQL = function (type) {
                var samples = {
                    'positive': 'SELECT user_id from `users` WHERE user_id IN (1, 2, 3)',
                    'negative': "SELECT user_id from `mail_unsubscribes` where mail_type in ('tip', 'offer')"
                };
                var sql = _this.$scope.list.sqls.create().attr('type', type).attr('sql', samples[type]);
                _this.editSQL(sql);
            };
            this.editSQL = function (sql) {
                _this.$ui.popupUrl('/sql-popup.html', false, null, { sql: sql, ctrl: _this });
            };
            this.save = function () {
                _this.$scope.list.save(_this.gettext('List saved successfully'));
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.list = $scope.lists[0] || $scope.lists.create();
        }
        return ListEditController;
    }());
    Admin.ListEditController = ListEditController;
    angular.module('listEditApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('listEditController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ListEditController]);
})(Admin || (Admin = {}));
