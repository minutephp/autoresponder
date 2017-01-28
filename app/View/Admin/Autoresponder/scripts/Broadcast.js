/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var BroadcastListController = (function () {
        function BroadcastListController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
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
                    { 'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit broadcast'), 'href': '/admin/autoresponder/campaigns/edit/' + item.ar_campaign_id },
                    { 'text': gettext('Queue'), 'icon': 'fa-bolt', 'hint': gettext('Queue broadcast'), 'click': 'ctrl.queueBroadcast(item.broadcast)', show: 'item.broadcast.status == "draft"' },
                    { 'text': gettext('Clone'), 'icon': 'fa-copy', 'hint': gettext('Clone broadcast'), 'click': 'ctrl.clone(item)' },
                    { 'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this broadcast'), 'click': 'item.removeConfirm("Removed")' },
                ];
                _this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, _this.$scope, { item: item, ctrl: _this });
            };
            this.queueBroadcast = function (broadcast) {
                broadcast.attr('status', 'queued').save(_this.gettext('Broadcast successfully queued for delivery'));
            };
            this.clone = function (broadcast) {
                var gettext = _this.gettext;
                broadcast.messages.setItemsPerPage(99, false);
                broadcast.messages.reloadAll(true).then(function () {
                    _this.$ui.prompt(gettext('Enter new broadcast name'), gettext('new-name')).then(function (name) {
                        broadcast.clone().attr('name', name).attr('send_at', window['moment']().add(1, 'days').toDate()).save(gettext('Broadcast duplicated')).then(function (copy) {
                            angular.forEach(broadcast.messages, function (content) { return copy.item.messages.cloneItem(content).save(); });
                        });
                    });
                });
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.data = { now: new Date() };
        }
        return BroadcastListController;
    }());
    Admin.BroadcastListController = BroadcastListController;
    angular.module('broadcastListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('broadcastListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', BroadcastListController]);
})(Admin || (Admin = {}));
