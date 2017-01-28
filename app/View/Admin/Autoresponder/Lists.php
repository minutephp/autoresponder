<div class="content-wrapper ng-cloak" ng-app="listListApp" ng-controller="listListController as mainCtrl" ng-init="init()">
    <div class="admin-content">
        <section class="content-header">
            <h1><span translate="">Mailing lists</span></h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                <li class="active"><i class="fa fa-list"></i> <span translate="">List list</span></li>
            </ol>
        </section>

        <section class="content">
            <minute-event name="IMPORT_LIST_DETAILS" as="data.lists"></minute-event>

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span translate="">All lists</span>
                    </h3>

                    <div class="box-tools">
                        <a class="btn btn-sm btn-primary btn-flat" ng-href="/admin/autoresponder/lists/edit">
                            <i class="fa fa-plus-circle"></i> <span translate="">Create new list</span>
                        </a>
                    </div>
                </div>

                <div class="box-body">
                    <div class="list-group">
                        <div class="list-group-item list-group-item-bar list-group-item-bar-{{list.enabled && 'success' || 'danger'}}"
                             ng-repeat="list in lists" ng-click-container="mainCtrl.actions(list)">
                            <div class="pull-left">
                                <h4 class="list-group-item-heading">{{list.name | ucfirst}}
                                    ({{data.lists[list.ar_list_id] | number}} <span translate="">subscribers</span>)
                                </h4>
                                <p class="list-group-item-text hidden-xs">
                                    <span translate="">Description:</span> {{list.description}}.
                                    <span translate="">Contains:</span> {{list.sqls.getTotalItems()}} <span translate="">SQL statements</span>.
                                </p>
                            </div>
                            <div class="md-actions pull-right">
                                <a class="btn btn-default btn-flat btn-sm" ng-href="/admin/autoresponder/lists/edit/{{list.ar_list_id}}">
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
                            <minute-pager class="pull-right" on="lists" no-results="{{'No lists found' | translate}}"></minute-pager>
                        </div>
                        <div class="col-xs-12 col-md-6 col-md-pull-6">
                            <minute-search-bar on="lists" columns="name,description" label="{{'Search list..' | translate}}"></minute-search-bar>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
