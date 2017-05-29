<div class="content-wrapper ng-cloak" ng-app="productEditApp" ng-controller="productEditController as mainCtrl" ng-init="init()">
    <div class="admin-content" minute-hot-keys="{'ctrl+s':mainCtrl.save}">
        <section class="content-header">
            <h1>
                <span translate="" ng-show="!product.product_id">Create new</span>
                <span translate="" ng-show="!!product.product_id">Edit</span>
                <span translate="">product</span>
            </h1>

            <ol class="breadcrumb">
                <li><a href="" ng-href="/admin"><i class="fa fa-dashboard"></i> <span translate="">Admin</span></a></li>
                <li><a href="" ng-href="/admin/products"><i class="fa fa-product"></i> <span translate="">Products</span></a></li>
                <li class="active"><i class="fa fa-edit"></i> <span translate="">Edit product</span></li>
            </ol>
        </section>

        <section class="content">
            <minute-event name="IMPORT_PAYMENT_PROCESSORS" as="mainCtrl.data.providers"></minute-event>

            <form class="form-horizontal" name="productForm" ng-submit="mainCtrl.save()">
                <div class="box box-{{productForm.$valid && 'success' || 'danger'}}">
                    <div class="box-header with-border">
                        <span translate="" ng-show="!product.product_id">New product</span>
                        <span ng-show="!!product.product_id"><span translate="">Edit</span> {{product.name}}</span>
                    </div>

                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="name"><span translate="">Name:</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" placeholder="Enter Name" ng-model="product.name" ng-required="true">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="description"><span translate="">Description:</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="description" placeholder="Enter Description" ng-model="product.description" ng-required="false">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="setup_amount"><span translate="">Setup amount:</span></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="setup_amount" placeholder="Enter Setup amount" ng-model="product.setup_amount" ng-required="false">
                            </div>
                            <label class="col-sm-2 control-label" for="setup_amount"><span translate="">Setup time:</span></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="setup_amount" placeholder="Setup time: 3d, 1m, 1y" ng-model="product.setup_time" ng-required="false"
                                       pattern="^\d+[dmyDMY]$">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="rebill_amount"><span translate="">Re-bill amount:</span></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="rebill_amount" placeholder="Enter Re-bill amount" ng-model="product.rebill_amount" ng-required="false">
                            </div>
                            <label class="col-sm-2 control-label" for="rebill_amount"><span translate="">Re-bill time:</span></label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" id="rebill_amount" placeholder="Re-bill time: 3d, 1m, 1y" ng-model="product.rebill_time" ng-required="false"
                                       pattern="^\d+[dmyDMY]$">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="welcome_url"><span translate="">Welcome URL:</span></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="welcome_url" placeholder="Return URL on successful payment" ng-model="product.welcome_url" ng-required="false">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span translate="">Access granted:</span></label>
                            <div class="col-sm-10">
                                <div class="help-block">
                                    <button type="button" class="btn btn-flat btn-default btn-xs" ng-click="mainCtrl.viewAccess()">
                                        <i class="fa fa-eye"></i> <span translate="">View accesses granted</span> ({{product.levels.getTotalItems()}})
                                    </button>
                                    <button type="button" class="btn btn-flat btn-default btn-xs" ng-click="mainCtrl.addAccess()">
                                        <i class="fa fa-plus-circle"></i> <span translate="">Add access..</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span translate="">Coupon codes:</span></label>
                            <div class="col-sm-10">
                                <p class="help-block">
                                    <button type="button" class="btn btn-flat btn-default btn-xs" ng-click="mainCtrl.viewCoupons()">
                                        <i class="fa fa-eye"></i> <span translate="">View existing coupons</span> ({{product.coupons.getTotalItems()}})
                                    </button>
                                    <button type="button" class="btn btn-flat btn-default btn-xs" ng-click="mainCtrl.addCoupon()">
                                        <i class="fa fa-plus-circle"></i> <span translate="">Create coupon..</span>
                                    </button>
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label"><span translate="">Purchase links:</span></label>
                            <div class="col-sm-10">
                                <p class="help-block">
                                    <button type="button" class="btn btn-flat btn-default btn-xs" ng-click="mainCtrl.viewPurchaseLinks()">
                                        <i class="fa fa-eye"></i> <span translate="">View purchase links..</span>
                                    </button>
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" ng-model="product.enabled"> <span translate="">Product Enabled</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer with-border">
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-flat btn-primary">
                                    <span translate="" ng-show="!product.product_id">Create</span>
                                    <span translate="" ng-show="!!product.product_id">Update</span>
                                    <span translate="">product</span>
                                    <i class="fa fa-fw fa-angle-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>

    <script type="text/ng-template" id="/levels-popup.html">
        <div class="box">
            <div class="box-header with-border">
                <b class="pull-left"><span translate="">All levels</span></b>
                <a class="pull-right close-button" href=""><i class="fa fa-times"></i></a>
            </div>

            <div class="box-body">
                <div class="list-group-item list-group-item-bar-none" ng-repeat="level in levels">
                    <div class="row">
                        <div class="col-xs-8">
                            <span translate="">{{level.group_name | ucfirst}} access with {{level.credits || '0'}} credits for {{level.extend_expiry_days}} days (on {{level.payment_type}}).</span>
                        </div>
                        <div class="pull-xs-4">
                            <div class="pull-right">
                                <a class="btn btn-default btn-flat btn-xs" ng-click="ctrl.editAccess(level)"><span translate="">Edit</span></a>
                                <a class="btn btn-default btn-flat btn-xs" ng-click="level.remove()"><span translate="">Remove</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer with-border">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-md-push-6">
                        <minute-pager class="pull-right" on="levels" no-results="{{'No levels found' | translate}}"></minute-pager>
                    </div>
                    <div class="col-xs-12 col-md-6 col-md-pull-6">
                        <minute-search-bar on="levels" columns="level, credits, extend_expiry_days" label="{{'Search levels..' | translate}}"></minute-search-bar>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/ng-template" id="/coupons-popup.html">
        <div class="box">
            <div class="box-header with-border">
                <b class="pull-left"><span translate="">All coupons</span></b>
                <a class="pull-right close-button" href=""><i class="fa fa-times"></i></a>
            </div>

            <div class="box-body">
                <div class="list-group-item list-group-item-bar-none" ng-repeat="coupon in coupons">
                    <div class="row">
                        <div class="col-xs-8">
                            <h4 class="list-group-item-heading">{{coupon.code}} <small class="hidden-xs hidden-sm"> - {{coupon.comment}}</small></h4>
                            <p class="list-group-item-text hidden-xs">
                                <span translate="">Price:</span>
                                <span ng-show="coupon.setup_amount > 0">${{coupon.setup_amount}} <span translate="">setup for</span> {{coupon.setup_time || 0}} days.</span>
                                <span ng-show="coupon.rebill_amount > 0">${{coupon.rebill_amount}} / {{coupon.rebill_time}}.</span>
                            </p>
                        </div>
                        <div class="pull-xs-4">
                            <div class="pull-right">
                                <a class="btn btn-default btn-flat btn-xs" ng-click="ctrl.editCoupon(coupon)"><span translate="">Edit</span></a>
                                <a class="btn btn-default btn-flat btn-xs" ng-click="coupon.remove()"><span translate="">Remove</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-footer with-border">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-md-push-6">
                        <minute-pager class="pull-right" on="coupons" no-results="{{'No coupons found' | translate}}"></minute-pager>
                    </div>
                    <div class="col-xs-12 col-md-6 col-md-pull-6">
                        <minute-search-bar on="coupons" columns="code, comment" label="{{'Search coupons..' | translate}}"></minute-search-bar>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/ng-template" id="/edit-level-popup.html">
        <div class="box box-md">
            <div class="box-header with-border">
                <b class="pull-left"><span translate="">Edit level:</span> {{level.level || 'New level'}}</b>
                <a class="pull-right close-button" href=""><i class="fa fa-times"></i></a>
            </div>

            <form class="form-horizontal" ng-submit="ctrl.saveAccess(level)">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="level"><span translate="">Group name:</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control auto-focus" id="level" placeholder="Enter group name" ng-model="level.group_name" ng-required="true">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label"><span translate="">Payment type:</span></label>
                        <div class="col-sm-8">
                            <label class="radio-inline">
                                <input type="radio" ng-model="level.payment_type" ng-value="'setup'"> <span translate="">Setup fees</span>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" ng-model="level.payment_type" ng-value="'rebill'"> <span translate="">Recurring payment</span>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" ng-model="level.payment_type" ng-value="'processing'"> <span translate="">Pending payment</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="credits"><span translate="">Credits:</span></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="number" min="1" step="1" class="form-control" id="credits" placeholder="Enter credits (set 1 if not required)" ng-model="level.credits" ng-required="true">
                                <div class="input-group-addon"><span translate="">credits</span></div>
                            </div>
                            <p class="help-block" translate="">(access to this level is revoked for user if credits become 0)</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="extend_expiry_days"><span translate="">Extend Validity:</span></label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <input type="number" class="form-control" id="extend_expiry_days" placeholder="Enter Extend Validity"
                                       ng-model="level.extend_expiry_days" ng-required="true" min="1">
                                <div class="input-group-addon"><span translate="">days</span></div>
                            </div>

                            <p class="help-block" translate="">(number of days the user will be granted access to this level)</p>
                        </div>
                    </div>


                </div>

                <div class="box-footer with-border">
                    <button type="submit" class="btn btn-flat btn-primary pull-right">
                        <span translate ng-show="!level.product_level_id">Add</span>
                        <span translate ng-show="!!level.product_level_id">Update</span>
                        <span translate="">level</span>
                        <i class="fa fa-fw fa-angle-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </script>

    <script type="text/ng-template" id="/edit-coupon-popup.html">
        <div class="box">
            <div class="box-header with-border">
                <b class="pull-left"><span translate="">Edit coupon</span></b>
                <a class="pull-right close-button" href=""><i class="fa fa-times"></i></a>
            </div>

            <form class="form-horizontal" ng-submit="ctrl.saveCoupon(coupon)">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="code"><span translate="">Coupon code:</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="code" placeholder="Enter Coupon code" ng-model="coupon.code" ng-required="true">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="description"><span translate="">Description:</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="description" placeholder="Enter description (HTML allowed)" ng-model="coupon.comment" ng-required="false">
                            <p class="help-block text-sm" translate="">(Message shown to user after coupon is applied)</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="expires_at"><span translate="">Expires on:</span></label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="expires_at" placeholder="Enter Expires on" ng-model="coupon.expires_at" ng-required="true">
                            <p class="help-block" translate="">({{coupon.expires_at | timeAgo}})</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="setup_amount">
                            <span translate="">Override Setup:</span>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="setup_amount" placeholder="Amount OR %" ng-model="coupon.setup_amount" ng-required="false">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="setup_time" placeholder="time" ng-model="coupon.setup_time" pattern="^\d+[dmyDMY]$" ng-required="false">
                        </div>
                        <div class="col-sm-8 col-sm-offset-4">
                            <p class="help-block" translate="">(you can override setup amount and time)</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="rebill_amount">
                            <span translate="">Override Re-bill:</span>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="rebill_amount" placeholder="Amount OR %" ng-model="coupon.rebill_amount" ng-required="false">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="rebill_time" placeholder="time" ng-model="coupon.rebill_time" pattern="^\d+[dmyDMY]$" ng-required="false">
                        </div>
                        <div class="col-sm-8 col-sm-offset-4">
                            <p class="help-block" translate="">(you can override re-bill amount and time)</p>
                        </div>
                    </div>
                </div>

                <div class="box-footer with-border">
                    <button type="submit" class="btn btn-flat btn-primary pull-right">
                        <span translate ng-show="!coupon.product_coupon_id">Add</span>
                        <span translate ng-show="!!coupon.product_coupon_id">Update</span>
                        <span translate="">coupon</span>
                        <i class="fa fa-fw fa-angle-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </script>

    <script type="text/ng-template" id="/purchase-popup.html">
        <div class="box">
            <div class="box-header with-border">
                <b class="pull-left"><span translate="">Purchase links</span></b>
                <a class="pull-right close-button" href=""><i class="fa fa-times"></i></a>
            </div>

            <div class="box-body">
                <div class="list-group-item list-group-item-bar" ng-repeat="provider in ctrl.data.providers" ng-init="link = session.site.host  + '/purchase/' + provider.name + '/' + product.product_id">
                    <h4 class="list-group-item-heading">{{provider.name}} <span translate="">Purchase link</span></h4>

                    <p class="list-group-item-text">
                        <input type="text" class="form-control input-sm" value="{{link}}" readonly title="Link" onclick="this.select()" />
                    </p>

                    <p class="list-group-item-text hidden-xs info-box-button">
                        <a class="btn btn-default btn-flat btn-xs" clip-copy="link" clip-click="ctrl.copied()">
                            <span translate=""><i class="fa fa-files-o"></i> Copy to clipboard..</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </script>
</div>
