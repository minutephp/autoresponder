/// <reference path="../../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var Admin;
(function (Admin) {
    var CampaignEditController = (function () {
        function CampaignEditController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.getDate = function (date, time) {
                var str = date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate() + ' ' + time.getHours() + ':' + time.getMinutes();
                return new Date(str);
            };
            this.pickList = function () {
                _this.$ui.popupUrl('/ar-lists.html', false, null, { ctrl: _this });
            };
            this.pickMessage = function () {
                _this.$ui.popupUrl('/ar-mails.html', false, null, { ctrl: _this });
            };
            this.insertMsg = function (mail) {
                var campaign = _this.$scope.campaign;
                var message = campaign.messages.create().attr('wait_hrs', 24).attr('mail_id', mail.attr('mail_id')).attr('sequence', campaign.messages.length - 1);
                message.mail = mail;
            };
            this.removeMsg = function (mail) {
                var index = _this.findMessage(mail);
                if (index !== -1) {
                    _this.$scope.campaign.messages[index].remove();
                }
            };
            this.getWaits = function (msg) {
                var hrs = msg.attr('wait_hrs');
                if (!_this.$scope.data.waits.hasOwnProperty(hrs)) {
                    _this.$scope.data.waits[hrs] = hrs + _this.gettext(' hours after last message');
                }
                return _this.$scope.data.waits;
            };
            this.getHours = function (msg) {
                var hrs = msg.attr('wait_hrs');
                _this.$ui.prompt('Enter wait time in number of hours', hrs > 0 ? hrs.toString() : 24).then(function (value) {
                    if (value > 4) {
                        msg.attr('wait_hrs', parseInt(value));
                    }
                });
            };
            this.findMessage = function (mail) {
                for (var i = 0; i < _this.$scope.campaign.messages.length; i++) {
                    var message = _this.$scope.campaign.messages[i];
                    if (message.attr('mail_id') === mail.attr('mail_id')) {
                        return i;
                    }
                }
                return -1;
            };
            this.setList = function (list) {
                _this.$scope.campaign.list = list;
                _this.$scope.campaign.attr('ar_list_id', list.attr('ar_list_id'));
                _this.$ui.closePopup();
            };
            this.remains = function (arr, start) {
                var index = arr.indexOf(start);
                return (index > -1 ? arr.slice(index + 1) : arr).concat('23:59');
            };
            this.inarray = function (schedule, day) {
                return schedule.days && schedule.days.indexOf(day) > -1;
            };
            this.toggle = function (schedule, day) {
                if (!schedule.days || !angular.isArray(schedule.days)) {
                    schedule.days = [];
                }
                var index = schedule.days.indexOf(day);
                if (index > -1) {
                    schedule.days.splice(index, 1);
                }
                else {
                    var newArray = [];
                    angular.forEach(_this.$scope.data.allDays, function (v, k) {
                        if ((schedule.days.indexOf(v) > -1) || (v === day)) {
                            newArray.push(v);
                        }
                    });
                    schedule.days = newArray;
                }
            };
            this.addSchedule = function () {
                _this.$scope.schedules.push({ days: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'], 'start_time': '09:00', 'end_time': '18:00' });
            };
            this.removeSchedule = function (schedule) {
                var index = _this.$scope.schedules.indexOf(schedule);
                if (index > -1) {
                    _this.$scope.schedules.splice(index, 1);
                }
            };
            this.isValid = function () {
                return !_this.$scope.campaign.ar_campaign_id || (!!_this.$scope.campaign.messages.length && !!_this.$scope.campaign.ar_list_id);
            };
            this.remove = function (message) {
                var seq = message.attr('sequence');
                message.removeConfirm().then(function () {
                    angular.forEach(_this.$scope.campaign.messages, function (msg) {
                        var cur = parseInt(msg.attr('sequence'));
                        if (cur > seq) {
                            msg.attr('sequence', cur - 1).save();
                        }
                    });
                });
            };
            this.save = function () {
                _this.$scope.campaign.save(false).then(function () {
                    if (_this.$scope.campaign.messages.length > 0) {
                        _this.$scope.campaign.messages.saveAll(_this.gettext('Campaign saved. All messages updated'));
                    }
                });
                if ((_this.$scope.campaign.ar_campaign_id > 0) && (_this.$scope.campaign.type == 'broadcast')) {
                    var extra = _this.$scope.campaign.broadcast.status == 'draft' ? _this.gettext('Remember to queue your broadcast when you are ready to send!') : '';
                    _this.$scope.campaign.broadcast.save(_this.gettext('Broadcast updated. ' + extra));
                }
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.data = { allDays: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], waits: [], times: [], days: [] };
            $scope.campaign = $scope.campaigns[0] || $scope.campaigns.create().attr('enabled', true).attr('type', $scope.session.request.type || 'autoresponder').attr('priority', 0);
            if ($scope.campaign.type == 'broadcast') {
                $scope.campaign.broadcast = $scope.campaign.broadcasts[0] || $scope.campaign.broadcasts.create().attr('mailing_time', 1).attr('status', 'ready').attr('send_at', new Date());
                $scope.campaign.attr('priority', 99);
                $scope.data.schedule = { type: 'fixed', date: $scope.campaign.broadcast.send_at, time: this.getDate($scope.campaign.broadcast.send_at, $scope.campaign.broadcast.send_at) };
                $scope.$watch('data.schedule', function (schedule) {
                    $scope.campaign.broadcast.send_at = (schedule.type === 'fixed') ? _this.getDate(schedule.date, schedule.time) : new Date();
                }, true);
            }
            if (!angular.isArray($scope.campaign.schedule_json)) {
                $scope.campaign.attr('schedule_json', []);
            }
            $scope.schedules = $scope.campaign.schedule_json;
            for (var i = 1; i < 35; i++) {
                $scope.data.waits[i * 24] = i + this.gettext(" days after last message");
            }
            for (var i = 0; i <= 23; i++) {
                $scope.data.times.push((i < 10 ? '0' : '') + i + ':00');
            }
        }
        return CampaignEditController;
    }());
    Admin.CampaignEditController = CampaignEditController;
    angular.module('campaignEditApp', ['MinuteFramework', 'AdminApp', 'gettext'])
        .controller('campaignEditController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', CampaignEditController]);
})(Admin || (Admin = {}));
