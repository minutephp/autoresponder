<div class="content-wrapper ng-cloak" ng-app="campaignEditApp" ng-controller="campaignEditController as mainCtrl" ng-init="init()">
    <div class="admin-content">
        <section class="content-header">
            <h1>
                <span translate="" ng-show="!campaign.ar_campaign_id">Create new</span>
                <span translate="" ng-show="!!campaign.ar_campaign_id">Edit</span>
                <ng-switch on="campaign.type === 'broadcast'">
                    <span translate="" ng-switch-when="true">broadcast</span>
                    <span translate="" ng-switch-default="">campaign</span>
                </ng-switch>
            </h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                <li><a href="" ng-href="/admin/autoresponder/campaigns"><i class="fa fa-campaign"></i> <span translate="">Campaigns</span></a></li>
                <li class="active"><i class="fa fa-edit"></i> <span translate="">Edit campaign</span></li>
            </ol>
        </section>

        <section class="content">
            <form class="form-horizontal" name="campaignForm" ng-submit="mainCtrl.save()">
                <div class="box box-{{campaignForm.$valid && 'success' || 'danger'}}">
                    <div class="box-header with-border">
                        <span translate="" ng-show="!campaign.ar_campaign_id">New campaign</span>
                        <span ng-show="!!campaign.ar_campaign_id"><span translate="">Edit</span> {{campaign.name}}</span>
                    </div>

                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="name"><span translate="">Name:</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" placeholder="Enter Name" ng-model="campaign.name" ng-required="true">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="description"><span translate="">Description:</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="description" placeholder="Enter Description" ng-model="campaign.description" ng-required="false">
                            </div>
                        </div>

                        <div ng-if="!!campaign.ar_campaign_id">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="list"><span translate="">Target list:</span></label>
                                <div class="col-sm-10">
                                    <p class="help-block">
                                        <ng-switch on="!!campaign.list.ar_list_id">
                                            <button type="button" class="btn btn-flat btn-default btn-xs" ng-click="mainCtrl.pickList()">
                                                <span translate="" ng-switch-when="false">Pick a list..</span>
                                                <span ng-switch-when="true">{{(campaign.list.name) | ucfirst}} ({{campaign.list.description}})..</span>
                                            </button>
                                        </ng-switch>
                                    </p>

                                    <p class="help-block"><a href="" ng-href="/admin/autoresponder/lists"><span translate="">Manage lists</span></a></p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="messages"><span translate="">Messages:</span></label>
                                <div class="col-sm-10">
                                    <div minute-list-sorter="campaign.messages" sort-index="sequence">
                                        <div class="list-group-item list-group-item-bar list-group-item-bar-sortable" ng-repeat="message in campaign.messages | orderBy:'sequence'"
                                             ng-init="message.sequence = message.sequence || 0">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h4 class="list-group-item-heading">{{message.mail.name | ucfirst}}</h4>
                                                    <p class="list-group-item-text hidden-xs">
                                                        {{message.mail.description | truncate:50:'..'}}
                                                    </p>
                                                </div>
                                                <div class="col-sm-4">
                                                    <ng-switch on="$index === 0">
                                                        <div class="label label-info" ng-switch-when="true">
                                                            <span translate="">Sent immediately</span>
                                                        </div>
                                                        <div class="form-group form-group-sm" ng-switch-when="false">
                                                            <select class="form-control" ng-model="message.wait_hrs" ng-required="true" title="delay"
                                                                    ng-options="+key as label for (key, label) in mainCtrl.getWaits(message)">
                                                                <option value="">Send after..</option>
                                                            </select>
                                                        </div>
                                                    </ng-switch>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="pull-right">
                                                        <a class="btn btn-default btn-flat btn-xs" tooltip="{{'Custom time' | translate}}" ng-show="!!$index && campaign.advanced"
                                                           ng-click="mainCtrl.getHours(message)">
                                                            <i class="fa fa-clock-o"></i>
                                                        </a>
                                                        <a class="btn btn-default btn-flat btn-xs" tooltip="{{'View email' | translate}}" ng-href="/admin/mails/edit/{{message.mail_id}}" target="view">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <a class="btn btn-default btn-flat btn-xs" tooltip="{{'Remove message' | translate}}" ng-click="mainCtrl.remove(message)">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                    <br ng-show="!!campaign.messages.length">

                                    <button type="button" class="btn btn-flat btn-default btn-sm text-bold" ng-click="mainCtrl.pickMessage()">
                                        <i class="fa fa-plus-circle"></i> <span translate="">Insert new message into sequence..</span>
                                    </button>
                                </div>
                            </div>

                            <ng-switch on="campaign.type === 'broadcast'">
                                <div ng-switch-when="true">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="schedule">Send:</label>

                                        <div class="col-sm-10">
                                            <label class="radio-inline">
                                                <input type="radio" ng-model="data.schedule.type" name="schedule" ng-value="'now'" ng-required="true"> Immediately
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" ng-model="data.schedule.type" name="schedule" ng-value="'fixed'" ng-required="true"> Fixed date
                                            </label>
                                        </div>

                                        <div class="col-sm-10 col-sm-offset-2">
                                            <p class="help-block">({{campaign.send_at | timeAgo}})</p>
                                        </div>
                                    </div>

                                    <div class="form-group" ng-if="data.schedule.type === 'fixed'">
                                        <label class="col-sm-2 control-label" for="send_at">Send date:</label>

                                        <div class="col-sm-4">
                                            <input type="date" ng-model="data.schedule.date" class="form-control" title="date">
                                        </div>

                                        <div class="col-sm-4">
                                            <input type="time" ng-model="data.schedule.time" class="form-control" title="time" step="1">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"><span translate="">Send status:</span></label>
                                        <div class="col-sm-10">
                                            <label class="radio-inline">
                                                <input type="radio" ng-model="campaign.broadcast.status" ng-value="'draft'"> <span translate="">Queue manually later (recommended)</span>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" ng-model="campaign.broadcast.status" ng-value="'queued'"> Queue right now
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div ng-switch-when="false">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="schedule"><span translate="">Schedule:</span></label>
                                        <div class="col-sm-10">
                                            <div class="table-responsive" ng-if="schedules.length">
                                                <table class="table table-bordered table-condensed">
                                                    <tbody>
                                                    <tr ng-repeat="schedule in schedules" class="text-links">
                                                        <td ng-repeat="day in data.allDays" class="{{mainCtrl.inarray(schedule, day) && 'success' || 'danger'}} center-cell clickable"
                                                            ng-click="mainCtrl.toggle(schedule, day)">
                                                            <i class="fa {{mainCtrl.inarray(schedule, day) && 'fa-check-circle' || 'fa-times-circle'}}"></i> {{day}}
                                                        </td>
                                                        <td>
                                                            <select class="form-control" ng-model="schedule.start_time" ng-required="true" ng-options="item for item in data.times" title="start time">
                                                                <option value="">Start time</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select class="form-control" ng-model="schedule.end_time" ng-required="true"
                                                                    ng-options="item for item in mainCtrl.remains(data.times, schedule.start_time)"
                                                                    title="end time">
                                                                <option value="">End time</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div class="pull-right"><a href="#" ng-click="mainCtrl.removeSchedule(schedule)"><i class="fa fa-remove"></i></a></div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <p class="help-block">
                                                <button type="button" class="btn btn-default btn-xs" ng-click="mainCtrl.addSchedule()"><i class="fa fa-plus-circle"></i> Add schedule</button>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="form-group" ng-show="campaign.advanced">
                                        <label class="col-sm-2 control-label" for="priority"><span translate="">Priority:</span></label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" id="priority" placeholder="Enter Priority" ng-model="campaign.priority" ng-required="false" min="0" max="99">
                                            <p class="help-block" translate="">When priority is greater than 0: Users subscribed to more than 1 autoresponder will receive emails from the highest
                                                priority
                                                campaign only (until
                                                it is complete).</p>
                                        </div>
                                    </div>
                                </div>
                            </ng-switch>

                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span translate="">Enabled:</span></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <input type="radio" ng-model="campaign.enabled" ng-value="true"> Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" ng-model="campaign.enabled" ng-value="false"> No
                                    </label>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="box-footer with-border">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-flat btn-primary" ng-disabled="!mainCtrl.isValid()">
                                    <span translate="" ng-show="!campaign.ar_campaign_id">Create</span>
                                    <span translate="" ng-show="!!campaign.ar_campaign_id">Update</span>
                                    <span translate="">campaign</span>
                                    <i class="fa fa-fw fa-angle-right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group" ng-show="campaign.ar_campaign_id">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" ng-model="campaign.advanced"> <span translate="">Show advanced settings</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </section>
    </div>

    <script type="text/ng-template" id="/ar-lists.html">
        <div class="box">
            <div class="box-header with-border">
                <b class="pull-left"><span translate="">Autoresponder lists</span></b>
                <a class="pull-right close-button" href=""><i class="fa fa-times"></i></a>
            </div>

            <div class="box-body">
                <div class="list-group-item list-group-item-bar" ng-repeat="list in all_lists">
                    <div class="pull-left">
                        <h4 class="list-group-item-heading">{{list.name | ucfirst}}</h4>
                        <p class="list-group-item-text hidden-xs">
                            {{list.description}}
                        </p>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-default btn-flat" ng-click="ctrl.setList(list)">
                            <span translate="">Pick</span>
                        </a>
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="box-footer with-border">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-md-push-6">
                        <minute-pager class="pull-right" on="all_lists" no-results="{{'No lists found' | translate}}"></minute-pager>
                    </div>
                    <div class="col-xs-12 col-md-6 col-md-pull-6">
                        <minute-search-bar on="all_lists" columns="name, description" label="{{'Search lists..' | translate}}"></minute-search-bar>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/ng-template" id="/ar-mails.html">
        <div class="box">
            <div class="box-header with-border">
                <b class="pull-left"><span translate="">All mails</span></b>
                <a class="pull-right close-button" href=""><i class="fa fa-times"></i></a>
            </div>

            <div class="box-body">
                <div class="list-group-item list-group-item-bar" ng-repeat="mail in all_mails">
                    <div class="row">
                        <div class="col-xs-9">
                            <h4 class="list-group-item-heading">{{mail.name | ucfirst}}</h4>
                            <p class="list-group-item-text hidden-xs">
                                {{mail.description}}
                            </p>
                        </div>
                        <div class="col-xs-3">
                            <ng-switch on="ctrl.findMessage(mail) !== -1">
                                <a class="btn btn-default btn-flat btn-sm pull-right" ng-click="ctrl.insertMsg(mail)" ng-switch-when="false"><span translate="">Insert</span></a>
                                <a class="btn btn-default btn-flat btn-sm pull-right" ng-click="ctrl.removeMsg(mail)" ng-switch-when="true"><span translate="">Remove</span></a>
                            </ng-switch>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer with-border">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-md-push-6">
                        <minute-pager class="pull-right" on="all_mails" no-results="{{'No mails found' | translate}}"></minute-pager>
                    </div>
                    <div class="col-xs-12 col-md-6 col-md-pull-6">
                        <minute-search-bar on="all_mails" columns="name, description" label="{{'Search mails..' | translate}}"></minute-search-bar>
                    </div>
                </div>
            </div>
        </div>
    </script>
</div>



