/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class CampaignListController {
        constructor(public $scope:any, public $minute:any, public $ui:any, public $timeout:ng.ITimeoutService,
                    public gettext:angular.gettext.gettextFunction, public gettextCatalog:angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
        }

        actions = (item) => {
            let gettext = this.gettext;
            let actions = [
                {'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit campaign'), 'href': '/admin/autoresponder/campaigns/edit/' + item.ar_campaign_id},
                {'text': gettext('Clone'), 'icon': 'fa-copy', 'hint': gettext('Clone campaign'), 'click': 'ctrl.clone(item)'},
                {'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this campaign'), 'click': 'item.removeConfirm("Removed")'},
            ];

            this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, this.$scope, {item: item, ctrl: this});
        };

        clone = (campaign) => {
            let gettext = this.gettext;

            campaign.messages.setItemsPerPage(99, false);
            campaign.messages.reloadAll(true).then(() => {
                this.$ui.prompt(gettext('Enter new campaign name'), gettext('new-name')).then(function (name) {
                    campaign.clone().attr('name', name).save(gettext('Campaign duplicated')).then(function (copy) {
                        angular.forEach(campaign.messages, (content) => copy.item.messages.cloneItem(content).save());
                    });
                });
            });
        }
    }

    angular.module('campaignListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('campaignListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', CampaignListController]);
}
