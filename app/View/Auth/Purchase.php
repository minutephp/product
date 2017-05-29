<div class="container ng-cloak" ng-app="authApp" ng-controller="authController" ng-init="init()" ng-cloak="">
    <div class="header">
        <h3 ng-if="!!session.site.logo.light"><img src="" ng-src="{{session.site.logo.light}}" class="site-logo"></h3>
        <h3 ng-if="!session.site.logo.light">{{session.site.site_name}}</h3>
    </div>

    <div class="well" style="background: white;">

        <h3><i class="fa fa-exclamation-circle"></i> <span translate="">Complete your purchase..</span></h3>

        <p><span translate="">Please link your purchase with your {{session.site.site_name}} account.</span></p>

        <hr>

        <p><b>Select one:</b></p>

        <p align="left">
            <i class="fa fa-caret-right"></i>
            <button type="button" class="btn btn-flat btn-primary" ng-click="session.signup(true)">
                <i class="fa fa-user"></i> <span translate="">I'm a new member</span>
            </button>
        </p>

        <p align="left">
            <i class="fa fa-caret-right"></i>
            <button type="button" class="btn btn-flat btn-warning" ng-click="session.login(true)">
                <i class="fa fa-sign-in"></i> <span translate="">I already have a {{session.site.site_name}} account</span>
            </button>
        </p>
    </div>
</div>
