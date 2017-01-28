/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var ListListController = (function () {
        function ListListController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
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
                    { 'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit list'), 'href': '/admin/autoresponder/lists/edit/' + item.ar_list_id },
                    { 'text': gettext('Clone'), 'icon': 'fa-copy', 'hint': gettext('Clone list'), 'click': 'ctrl.clone(item)' },
                    { 'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this list'), 'click': 'item.removeConfirm("Removed")' },
                ];
                _this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, _this.$scope, { item: item, ctrl: _this });
            };
            this.clone = function (list) {
                var gettext = _this.gettext;
                list.sqls.setItemsPerPage(99, false);
                list.sqls.reloadAll(true).then(function () {
                    _this.$ui.prompt(gettext('Enter new list'), gettext('new-name')).then(function (name) {
                        list.clone().attr('name', name).save(gettext('List duplicated')).then(function (copy) {
                            angular.forEach(list.sqls, function (content) { return copy.item.sqls.cloneItem(content).save(); });
                        });
                    });
                });
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
        }
        return ListListController;
    }());
    Admin.ListListController = ListListController;
    angular.module('listListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('listListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', ListListController]);
})(Admin || (Admin = {}));
