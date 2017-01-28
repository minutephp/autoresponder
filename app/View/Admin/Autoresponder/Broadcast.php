<div class="content-wrapper ng-cloak" ng-app="broadcastListApp" ng-controller="broadcastListController as mainCtrl" ng-init="init()">
    <div class="admin-content">
        <section class="content-header">
            <h1><span translate="">List of broadcasts</span></h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                <li class="active"><i class="fa fa-broadcast"></i> <span translate="">Broadcast list</span></li>
            </ol>
        </section>

        <section class="content">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span translate="">All broadcasts</span>
                    </h3>

                    <div class="box-tools">
                        <a class="btn btn-sm btn-primary btn-flat" ng-href="/admin/autoresponder/campaigns/edit?type=broadcast">
                            <i class="fa fa-plus-circle"></i> <span translate="">Create new broadcast</span>
                        </a>
                    </div>
                </div>

                <div class="box-body">
                    <div class="list-group">
                        <div class="list-group-item list-group-item-bar list-group-item-bar-{{campaign.broadcast.status == 'sent' && 'success' || 'danger'}}"
                             ng-repeat="campaign in campaigns" ng-click-container="mainCtrl.actions(campaign)">
                            <div class="pull-left">
                                <h4 class="list-group-item-heading">{{(campaign.broadcast.status || 'draft') | ucfirst}}: {{campaign.name | ucfirst}} ({{campaign.description}})</h4>
                                <p class="list-group-item-text hidden-xs">
                                    <span translate="">Send date:</span> {{campaign.send_at | timeAgo}}.
                                    <span translate="">Target:</span> {{campaign.list.name}} ({{campaign.list.description}}).
                                </p>
                            </div>
                            <div class="md-actions pull-right">
                                <ng-switch on="campaign.broadcast.status == 'draft'">
                                    <button ng-switch-when="true" class="btn btn-default btn-flat btn-sm" ng-click="mainCtrl.queueBroadcast(campaign.broadcast)">
                                        <i class="fa fa-bolt"></i> <span translate="">Queue now</span>
                                    </button>
                                    <a ng-switch-when="false" class="btn btn-default btn-flat btn-sm" ng-href="/admin/autoresponder/campaigns/edit/{{campaign.ar_campaign_id}}">
                                        <i class="fa fa-pencil-square-o"></i> <span translate="">Edit..</span>
                                    </a>
                                </ng-switch>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <div class="row">
                        <div class="col-xs-12 col-md-6 col-md-push-6">
                            <minute-pager class="pull-right" on="campaigns" no-results="{{'No broadcasts found' | translate}}"></minute-pager>
                        </div>
                        <div class="col-xs-12 col-md-6 col-md-pull-6">
                            <minute-search-bar on="campaigns" columns="name, description" label="{{'Search broadcast..' | translate}}"></minute-search-bar>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
