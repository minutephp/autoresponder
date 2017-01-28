/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class ListEditController {
        constructor(public $scope:any, public $minute:any, public $ui:any, public $timeout:ng.ITimeoutService,
                    public gettext:angular.gettext.gettextFunction, public gettextCatalog:angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.list = $scope.lists[0] || $scope.lists.create();
        }

        addSQL = (type) => {
            var samples = {
                'positive': 'SELECT user_id from `users` WHERE user_id IN (1, 2, 3)',
                'negative': "SELECT user_id from `mail_unsubscribes` where mail_type in ('tip', 'offer')"
            };
            var sql = this.$scope.list.sqls.create().attr('type', type).attr('sql', samples[type]);
            this.editSQL(sql);
        };

        editSQL = (sql) => {
            this.$ui.popupUrl('/sql-popup.html', false, null, {sql: sql, ctrl: this});
        };

        save = () => {
            this.$scope.list.save(this.gettext('List saved successfully'));
        };
    }

    angular.module('listEditApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('listEditController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ListEditController]);
}
