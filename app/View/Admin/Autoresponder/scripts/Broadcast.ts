/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class BroadcastListController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.data = {now: new Date()};
        }

        actions = (item) => {
            let gettext = this.gettext;
            let actions = [
                {'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit broadcast'), 'href': '/admin/autoresponder/campaigns/edit/' + item.ar_campaign_id},
                {'text': gettext('Queue'), 'icon': 'fa-bolt', 'hint': gettext('Queue broadcast'), 'click': 'ctrl.queueBroadcast(item.broadcast)', show: 'item.broadcast.status == "draft"'},
                {'text': gettext('Clone'), 'icon': 'fa-copy', 'hint': gettext('Clone broadcast'), 'click': 'ctrl.clone(item)'},
                {'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this broadcast'), 'click': 'item.removeConfirm("Removed")'},
            ];

            this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, this.$scope, {item: item, ctrl: this});
        };

        queueBroadcast = (broadcast) => {
            broadcast.attr('status', 'queued').save(this.gettext('Broadcast successfully queued for delivery'));
        };

        clone = (broadcast) => {
            let gettext = this.gettext;

            broadcast.messages.setItemsPerPage(99, false);
            broadcast.messages.reloadAll(true).then(() => {
                this.$ui.prompt(gettext('Enter new broadcast name'), gettext('new-name')).then(function (name) {
                    broadcast.clone().attr('name', name).attr('send_at', window['moment']().add(1, 'days').toDate()).save(gettext('Broadcast duplicated')).then(function (copy) {
                        angular.forEach(broadcast.messages, (content) => copy.item.messages.cloneItem(content).save());
                    });
                });
            });
        }
    }

    angular.module('broadcastListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('broadcastListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', BroadcastListController]);
}
