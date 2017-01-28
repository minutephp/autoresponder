<div class="content-wrapper ng-cloak" ng-app="listEditApp" ng-controller="listEditController as mainCtrl" ng-init="init()">
    <div class="admin-content">
        <section class="content-header">
            <h1>
                <span translate="" ng-show="!list.ar_list_id">Create new</span>
                <span translate="" ng-show="!!list.ar_list_id">Edit</span>
                <span translate="">list</span>
            </h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                <li><a href="" ng-href="/admin/autoresponder/lists"><i class="fa fa-list"></i> <span translate="">Lists</span></a></li>
                <li class="active"><i class="fa fa-edit"></i> <span translate="">Edit list</span></li>
            </ol>
        </section>

        <section class="content">
            <form class="form-horizontal" name="listForm" ng-submit="mainCtrl.save()">
                <div class="box box-{{listForm.$valid && 'success' || 'danger'}}">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <span translate="" ng-show="!list.ar_list_id">New list</span>
                            <span ng-show="!!list.ar_list_id"><span translate="">Edit</span> {{list.name}}</span>
                        </h3>

                        <div class="box-tools" ng-show="!!list.sqls.length">
                            <a class="btn btn-xs btn-default btn-flat" ng-href="/admin/autoresponder/lists/download/{{list.ar_list_id}}" target="_blank" tooltip="{{'For results verification' | translate}}">
                                <i class="fa fa-download"></i> <span translate="">Export list</span>
                            </a>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="name"><span translate="">Name:</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" placeholder="Enter Name" ng-model="list.name" ng-required="true">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="description"><span translate="">Description:</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="description" placeholder="Enter Description" ng-model="list.description" ng-required="false">
                            </div>
                        </div>

                        <div ng-repeat="type in ['positive', 'negative']" ng-if="list.ar_list_id">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="positive_sqls"><span translate="">{{type | ucfirst}} SQLs:</span></label>
                                <div class="col-sm-10">
                                    <ul class="list-group">
                                        <li class="list-group-item" ng-repeat="sql in list.sqls" ng-if="sql.type===type">
                                            <span class="badge"><a href="" ng-click="sql.removeConfirm()" title="remove"><i class="fa fa-remove fa-inverse"></i></a></span>
                                            <a href="" class="graylinks" ng-click="mainCtrl.editSQL(sql)">{{sql.sql | truncate:50:'..'}}</a> ({{sql.name}})
                                        </li>
                                        <li class="list-group-item"><a href="" class="btn btn-default btn-flat btn-xs" ng-click="mainCtrl.addSQL(type)">Add new {{type}} SQL..</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer with-border">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-flat btn-primary">
                                    <span translate="" ng-show="!list.ar_list_id">Create</span>
                                    <span translate="" ng-show="!!list.ar_list_id">Update</span>
                                    <span translate="">list</span>
                                    <i class="fa fa-fw fa-angle-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>

    <script type="text/ng-template" id="/sql-popup.html">
        <div class="box">
            <div class="box-header with-border">
                <b class="pull-left"><span translate="">{{sql.type | ucfirst}} SQL Statement</span></b>
                <a class="pull-right close-button" href=""><i class="fa fa-times"></i></a>
            </div>

            <div class="box-body">
                <div class="alert alert-warning alert-dismissible" role="alert" ng-if="sql.type ==='positive'">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <p translate="">Positive SQL statement returns a list of <code>user_id</code> to send the mail to. The SQL result should contain the <code>user_id</code>
                        column only.</p>
                </div>
                <div class="alert alert-danger alert-dismissible" role="alert" ng-if="sql.type ==='negative'">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <p translate="">Negative SQL statement returns a list of <code>user_id</code> that will be removed (or suppressed) from the final list.
                        The SQL result should contain the <code>user_id</code> column only.</p>
                </div>

                <form name="sqlForm">
                    <div class="form-group">
                        <label class="control-label">{{sql.type | ucfirst}} SQL Statement</label>
                        <div>
                            <textarea rows="3" ng-model="sql.sql" class="form-control" title="Sql statement" ng-required="true"></textarea>
                            <p class="help-block"><span translate="">(table names are case-sensitive, so `USERS` is different from `users`)</span></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Description of statement (optional)</label>
                        <div>
                            <input class="form-control" type="text" ng-model="sql.name" title="description" />
                        </div>
                    </div>
                </form>
            </div>

            <div class="box-footer with-border">
                <button type="button" class="btn btn-flat btn-primary pull-right close-button" ng-disabled="!sqlForm.$valid" ng-click="sql.save()">
                    <span translate>Save changes</span> <i class="fa fa-fw fa-angle-right"></i>
                </button>
            </div>
        </div>
    </script>

</div>
