<?php
$route = Route::current();
$name = $route->getName();
$actionName = $route->getActionName();

?>

<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>
            <!-- END SIDEBAR TOGGLER BUTTON -->
            <li class="nav-item start @if ($name == 'admin.dashboard.index') active @endif open">
                <a href="{{ URL::route('admin.dashboard.index') }}" class="nav-link ">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item start @if ($name == 'admin.user.index') active @endif open">
                <a href="{{ URL::route('admin.user.index') }}" class="nav-link ">
                    <i class="fa fa-users"></i>
                    <span class="title">Manage Users</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item start @if (($name == 'admin.manageMembership.index') || ($name == 'admin.manageMembership.update')) active @endif open">
                <a href="{{ URL::route('admin.manageMembership.index') }}" class="nav-link ">
                    <i class="fa fa-list"></i>
                    <span class="title">Membership</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item start @if (($name == 'admin.product.index') || ($name == 'admin.product.update')) active @endif open">
                <a href="{{ URL::route('admin.product.index') }}" class="nav-link ">
                    <i class="fa fa-list"></i>
                    <span class="title">Manage Products</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item start @if (($name == 'admin.coupon.index') || ($name == 'admin.coupon.add') || ($name == 'admin.coupon.update')) active @endif open">
                <a href="{{ URL::route('admin.coupon.index') }}" class="nav-link ">
                    <i class="fa fa-list"></i>
                    <span class="title">Manage Coupons</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item start @if (($name == 'admin.faq.index') || ($name == 'admin.faq.add') || ($name == 'admin.faq.update')) active @endif open">
                <a href="{{ URL::route('admin.faq.index') }}" class="nav-link ">
                    <i class="fa fa-list"></i>
                    <span class="title">Manage Faqs</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item start @if (($name == 'admin.cms.index') || ($name == 'admin.cms.add') || ($name == 'admin.cms.update')) active @endif open">
                <a href="{{ URL::route('admin.cms.index') }}" class="nav-link ">
                    <i class="fa fa-list"></i>
                    <span class="title">Manage Cms</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item start @if (($name == 'admin.siteSettings.index')) active @endif open">
                <a href="{{ URL::route('admin.siteSettings.index') }}" class="nav-link ">
                    <i class="fa fa-gear"></i>
                    <span class="title">Site Settings</span>
                    <span class="selected"></span>
                </a>
            </li>
            <!--            <li class="heading">
                            <h3 class="uppercase">Features</h3>
                        </li>
                        <li class="nav-item  ">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="icon-diamond"></i>
                                <span class="title">UI Features</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item  ">
                                    <a href="ui_metronic_grid.html" class="nav-link ">
                                        <span class="title">Metronic Grid System</span>
                                    </a>
                                </li>
                            </ul>
                        </li>-->
        </ul>
        <!-- END SIDEBAR MENU -->
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>