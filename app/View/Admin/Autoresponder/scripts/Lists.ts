/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class ListListController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
        }

        actions = (item) => {
            let gettext = this.gettext;
            let actions = [
                {'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit list'), 'href': '/admin/autoresponder/lists/edit/' + item.ar_list_id},
                {'text': gettext('Clone'), 'icon': 'fa-copy', 'hint': gettext('Clone list'), 'click': 'ctrl.clone(item)'},
                {'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this list'), 'click': 'item.removeConfirm("Removed")'},
            ];

            this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, this.$scope, {item: item, ctrl: this});
        };

        clone = (list) => {
            let gettext = this.gettext;
            list.sqls.setItemsPerPage(99, false);
            list.sqls.reloadAll(true).then(() => {
                this.$ui.prompt(gettext('Enter new list'), gettext('new-name')).then(function (name) {
                    list.clone().attr('name', name).save(gettext('List duplicated')).then(function (copy) {
                        angular.forEach(list.sqls, (content) => copy.item.sqls.cloneItem(content).save());
                    });
                });
            });
        }
    }

    angular.module('listListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('listListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ListListController]);
}
