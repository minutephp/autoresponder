<div class="content-wrapper ng-cloak" ng-app="campaignListApp" ng-controller="campaignListController as mainCtrl" ng-init="init()">
    <div class="admin-content">
        <section class="content-header">
            <h1><span translate="">List of campaigns</span></h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                <li class="active"><i class="fa fa-campaign"></i> <span translate="">Campaign list</span></li>
            </ol>
        </section>

        <section class="content">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span translate="">All campaigns</span>
                    </h3>

                    <div class="box-tools">
                        <a class="btn btn-sm btn-primary btn-flat" ng-href="/admin/autoresponder/campaigns/edit">
                            <i class="fa fa-plus-circle"></i> <span translate="">Create new campaign</span>
                        </a>
                    </div>
                </div>

                <div class="box-body">
                    <div class="list-group">
                        <div class="list-group-item list-group-item-bar list-group-item-bar-{{campaign.enabled && 'success' || 'danger'}}"
                             ng-repeat="campaign in campaigns" ng-click-container="mainCtrl.actions(campaign)">
                            <div class="pull-left">
                                <h4 class="list-group-item-heading">{{campaign.name | ucfirst}} <small class="hidden-xs">({{campaign.description}})</small></h4>
                                <p class="list-group-item-text hidden-xs">
                                    <span translate="">Target:</span> {{campaign.list.name}}.
                                    <span translate="">Content:</span> {{campaign.messages.getTotalItems()}} <span translate="">messages</span>.
                                </p>
                            </div>
                            <div class="md-actions pull-right">
                                <a class="btn btn-default btn-flat btn-sm" ng-href="/admin/autoresponder/campaigns/edit/{{campaign.ar_campaign_id}}">
                                    <i class="fa fa-pencil-square-o"></i> <span translate="">Edit..</span>
                                </a>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <div class="row">
                        <div class="col-xs-12 col-md-6 col-md-push-6">
                            <minute-pager class="pull-right" on="campaigns" no-results="{{'No campaigns found' | translate}}"></minute-pager>
                        </div>
                        <div class="col-xs-12 col-md-6 col-md-pull-6">
                            <minute-search-bar on="campaigns" columns="name, description" label="{{'Search campaigns..' | translate}}"></minute-search-bar>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
