/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var CampaignListController = (function () {
        function CampaignListController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
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
                    { 'text': gettext('Edit..'), 'icon': 'fa-edit', 'hint': gettext('Edit campaign'), 'href': '/admin/autoresponder/campaigns/edit/' + item.ar_campaign_id },
                    { 'text': gettext('Clone'), 'icon': 'fa-copy', 'hint': gettext('Clone campaign'), 'click': 'ctrl.clone(item)' },
                    { 'text': gettext('Remove'), 'icon': 'fa-trash', 'hint': gettext('Delete this campaign'), 'click': 'item.removeConfirm("Removed")' },
                ];
                _this.$ui.bottomSheet(actions, gettext('Actions for: ') + item.name, _this.$scope, { item: item, ctrl: _this });
            };
            this.clone = function (campaign) {
                var gettext = _this.gettext;
                campaign.messages.setItemsPerPage(99, false);
                campaign.messages.reloadAll(true).then(function () {
                    _this.$ui.prompt(gettext('Enter new campaign name'), gettext('new-name')).then(function (name) {
                        campaign.clone().attr('name', name).save(gettext('Campaign duplicated')).then(function (copy) {
                            angular.forEach(campaign.messages, function (content) { return copy.item.messages.cloneItem(content).save(); });
                        });
                    });
                });
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
        }
        return CampaignListController;
    }());
    Admin.CampaignListController = CampaignListController;
    angular.module('campaignListApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('campaignListController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', CampaignListController]);
})(Admin || (Admin = {}));
