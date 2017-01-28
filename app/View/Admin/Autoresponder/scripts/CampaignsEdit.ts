/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />

module Admin {
    export class CampaignEditController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');

            $scope.data = {allDays: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], waits: [], times: [], days: []};
            $scope.campaign = $scope.campaigns[0] || $scope.campaigns.create().attr('enabled', true).attr('type', $scope.session.request.type || 'autoresponder').attr('priority', 0);

            if ($scope.campaign.type == 'broadcast') {
                $scope.campaign.broadcast = $scope.campaign.broadcasts[0] || $scope.campaign.broadcasts.create().attr('mailing_time', 1).attr('status', 'ready').attr('send_at', new Date());
                $scope.campaign.attr('priority', 99);
                $scope.data.schedule = {type: 'fixed', date: $scope.campaign.broadcast.send_at, time: this.getDate($scope.campaign.broadcast.send_at, $scope.campaign.broadcast.send_at)};

                $scope.$watch('data.schedule', (schedule) => {
                    $scope.campaign.broadcast.send_at = (schedule.type === 'fixed') ? this.getDate(schedule.date, schedule.time) : new Date();
                }, true);
            }

            if (!angular.isArray($scope.campaign.schedule_json)) {
                $scope.campaign.attr('schedule_json', []);
            }

            $scope.schedules = $scope.campaign.schedule_json;

            for (let i = 1; i < 35; i++) {
                $scope.data.waits[i * 24] = i + this.gettext(" days after last message");
            }

            for (let i = 0; i <= 23; i++) {
                $scope.data.times.push((i < 10 ? '0' : '') + i + ':00');
            }
        }

        getDate = (date, time) => {
            var str = date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate() + ' ' + time.getHours() + ':' + time.getMinutes();
            return new Date(str);
        };

        pickList = () => {
            this.$ui.popupUrl('/ar-lists.html', false, null, {ctrl: this})
        };

        pickMessage = () => {
            this.$ui.popupUrl('/ar-mails.html', false, null, {ctrl: this})
        };

        insertMsg = (mail) => {
            let campaign = this.$scope.campaign;
            let message = campaign.messages.create().attr('wait_hrs', 24).attr('mail_id', mail.attr('mail_id')).attr('sequence', campaign.messages.length - 1);
            message.mail = mail;
        };

        removeMsg = (mail) => {
            let index = this.findMessage(mail);
            if (index !== -1) {
                this.$scope.campaign.messages[index].remove();
            }
        };

        getWaits = (msg) => {
            var hrs = msg.attr('wait_hrs');

            if (!this.$scope.data.waits.hasOwnProperty(hrs)) {
                this.$scope.data.waits[hrs] = hrs + this.gettext(' hours after last message');
            }

            return this.$scope.data.waits;
        };

        getHours = (msg) => {
            let hrs = msg.attr('wait_hrs');
            this.$ui.prompt('Enter wait time in number of hours', hrs > 0 ? hrs.toString() : 24).then((value) => {
                if (value > 4) {
                    msg.attr('wait_hrs', parseInt(value));
                }
            })
        };

        findMessage = (mail) => {
            for (var i = 0; i < this.$scope.campaign.messages.length; i++) {
                let message = this.$scope.campaign.messages[i];
                if (message.attr('mail_id') === mail.attr('mail_id')) {
                    return i;
                }
            }

            return -1;
        };

        setList = (list) => {
            this.$scope.campaign.list = list;
            this.$scope.campaign.attr('ar_list_id', list.attr('ar_list_id'));
            this.$ui.closePopup();
        };

        remains = (arr, start) => {
            var index = arr.indexOf(start);
            return (index > -1 ? arr.slice(index + 1) : arr).concat('23:59');
        };

        inarray = (schedule, day) => {
            return schedule.days && schedule.days.indexOf(day) > -1;
        };

        toggle = (schedule, day) => {
            if (!schedule.days || !angular.isArray(schedule.days)) {
                schedule.days = [];
            }

            var index = schedule.days.indexOf(day);

            if (index > -1) {
                schedule.days.splice(index, 1);
            } else {
                var newArray = [];
                angular.forEach(this.$scope.data.allDays, function (v, k) {
                    if ((schedule.days.indexOf(v) > -1) || (v === day)) {
                        newArray.push(v);
                    }
                });
                schedule.days = newArray;
            }
        };

        addSchedule = () => {
            this.$scope.schedules.push({days: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'], 'start_time': '09:00', 'end_time': '18:00'});
        };

        removeSchedule = (schedule) => {
            var index = this.$scope.schedules.indexOf(schedule);
            if (index > -1) {
                this.$scope.schedules.splice(index, 1);
            }
        };

        isValid = () => {
            return !this.$scope.campaign.ar_campaign_id || (!!this.$scope.campaign.messages.length && !!this.$scope.campaign.ar_list_id);
        };

        remove = (message) => {
            let seq = message.attr('sequence');

            message.removeConfirm().then(() => {
                angular.forEach(this.$scope.campaign.messages, (msg) => {
                    let cur = parseInt(msg.attr('sequence'));
                    if (cur > seq) {
                        msg.attr('sequence', cur - 1).save();
                    }
                });
            });
        };

        save = () => {
            this.$scope.campaign.save(false).then(() => {
                if (this.$scope.campaign.messages.length > 0) {
                    this.$scope.campaign.messages.saveAll(this.gettext('Campaign saved. All messages updated'));
                }
            });

            if ((this.$scope.campaign.ar_campaign_id > 0) && (this.$scope.campaign.type == 'broadcast')) {
                let extra = this.$scope.campaign.broadcast.status == 'draft' ? this.gettext('Remember to queue your broadcast when you are ready to send!') : '';
                this.$scope.campaign.broadcast.save(this.gettext('Broadcast updated. ' + extra));
            }
        };
    }

    angular.module('campaignEditApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('campaignEditController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', CampaignEditController]);
}
