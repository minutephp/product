<div class="content-wrapper ng-cloak" ng-app="productListApp" ng-controller="productListController as mainCtrl" ng-init="init()">
    <div class="admin-content">
        <section class="content-header">
            <h1><span translate="">List of products</span></h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                <li class="active"><i class="fa fa-product"></i> <span translate="">Product list</span></li>
            </ol>
        </section>

        <section class="content">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <span translate="">All products</span>
                    </h3>

                    <div class="box-tools">
                        <a class="btn btn-sm btn-primary btn-flat" ng-href="/admin/products/edit">
                            <i class="fa fa-plus-circle"></i> <span translate="">Create new product</span>
                        </a>
                    </div>
                </div>

                <div class="box-body">
                    <div class="list-group">
                        <div class="list-group-item list-group-item-bar list-group-item-bar-{{product.enabled && 'success' || 'danger'}}"
                             ng-repeat="product in products" ng-click-container="mainCtrl.actions(product)">
                            <div class="pull-left">
                                <h4 class="list-group-item-heading">{{product.name | ucfirst}} <span class="hidden-xs hidden-sm text-sm"> - {{product.description}}</span></h4>
                                <p class="list-group-item-text hidden-xs">
                                    <span translate="">Price:</span>
                                    <span ng-show="product.setup_amount > 0">${{product.setup_amount}} <span translate="">setup for</span> {{product.setup_time || 0}} days.</span>
                                    <span ng-show="product.rebill_amount > 0">${{product.rebill_amount}} every {{product.rebill_time}} <span translate="">(recurring)</span>.</span>
                                </p>
                                <p class="list-group-item-text hidden-xs" ng-show="!!product.stats.product_stat_id">
                                    <span translate="">Carts:</span> {{product.stats.carts || 0}}.
                                    <span translate="">Conversions:</span> {{product.stats.conversions || 0}}.
                                    <span translate="">Cancellations:</span> {{product.stats.cancels || 0}}.
                                    <span translate="">Refunds:</span> {{product.stats.refunds || 0}}.
                                </p>
                            </div>
                            <div class="md-actions pull-right">
                                <a class="btn btn-default btn-flat btn-sm" ng-href="/admin/products/edit/{{product.product_id}}">
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
                            <minute-pager class="pull-right" on="products" no-results="{{'No products found' | translate}}"></minute-pager>
                        </div>
                        <div class="col-xs-12 col-md-6 col-md-pull-6">
                            <minute-search-bar on="products" columns="name, setup_amount, rebill_amount" label="{{'Search products..' | translate}}"></minute-search-bar>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
