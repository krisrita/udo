<?php /* Smarty version Smarty-3.1.21, created on 2015-12-23 15:28:33
         compiled from "C:\wamp\www\udo\application\views\banner\banneredit.phtml" */ ?>
<?php /*%%SmartyHeaderCode:31387567a4d213bb0f9-17930935%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '88463574e173cf8fdc15ea00485f29d06e2e8d52' => 
    array (
      0 => 'C:\\wamp\\www\\udo\\application\\views\\banner\\banneredit.phtml',
      1 => 1446727317,
      2 => 'file',
    ),
    '21ee24d61c68629a7630ce2ce830d34a36a40c3f' => 
    array (
      0 => 'C:\\wamp\\www\\udo\\application\\views\\adminLayout.phtml',
      1 => 1450771454,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '31387567a4d213bb0f9-17930935',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21',
  'unifunc' => 'content_567a4d2182ee55_28257724',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_567a4d2182ee55_28257724')) {function content_567a4d2182ee55_28257724($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>好度运营管理后台-
活动编辑
</title>

    <!-- Bootstrap core CSS -->

    <link href="public/admin/css/bootstrap.min.css" rel="stylesheet">

    <link href="public/admin/fonts/css/font-awesome.min.css" rel="stylesheet">
    <link href="public/admin/css/animate.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="public/admin/css/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="public/admin/css/maps/jquery-jvectormap-2.0.1.css" />
    <link href="public/admin/css/icheck/flat/green.css" rel="stylesheet" />
    <link href="public/admin/css/floatexamples.css" rel="stylesheet" type="text/css" />

    <?php echo '<script'; ?>
 src="public/admin/js/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="public/admin/js/nprogress.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
        NProgress.start();
    <?php echo '</script'; ?>
>

    <!--[if lt IE 9]>
    <?php echo '<script'; ?>
 src="../assets/js/ie8-responsive-file-warning.js"><?php echo '</script'; ?>
>
    <![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <?php echo '<script'; ?>
 src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"><?php echo '</script'; ?>
>
    <![endif]-->

    <!-- 额外添加的css或script在这里 -->
     
     

</head>


<body class="nav-md">

<div class="container body">


    <div class="main_container">

        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">

                <div class="navbar nav_title" style="border: 0;">
                    <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>好度运营管理后台</span></a>
                </div>
                <div class="clearfix"></div>

                <!-- menu prile quick info -->
                <div class="profile">
                    <div class="profile_pic">
                        <img src="public/admin/images/img.jpg" alt="..." class="img-circle profile_img">
                    </div>
                    <div class="profile_info">
                        <span>Welcome,</span>
                        <h2>Anthony Fernando</h2>
                    </div>
                </div>
                <!-- /menu prile quick info -->

                <br />

                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                    <div class="menu_section">
                        <h3>General</h3>
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="index.html">Dashboard</a>
                                    </li>
                                    <li><a href="index2.html">Dashboard2</a>
                                    </li>
                                    <li><a href="index3.html">Dashboard3</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-edit"></i> 活动管理 <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="/banner/bannerEdit">活动编辑</a>
                                    </li>
                                    <li><a href="form_advanced.html">活动管理</a>
                                    </li>
                                    <li><a href="form_validation.html">活动统计</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-user"></i> 用户查询 <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="/Account/getOrder">订单查询</a>
                                    </li>
                                    <li><a href="form_advanced.html">账户查询</a>
                                    </li>
                                    <li><a href="form_validation.html">活动统计</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-desktop"></i> UI Elements <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="general_elements.html">General Elements</a>
                                    </li>
                                    <li><a href="media_gallery.html">Media Gallery</a>
                                    </li>
                                    <li><a href="typography.html">Typography</a>
                                    </li>
                                    <li><a href="icons.html">Icons</a>
                                    </li>
                                    <li><a href="glyphicons.html">Glyphicons</a>
                                    </li>
                                    <li><a href="widgets.html">Widgets</a>
                                    </li>
                                    <li><a href="invoice.html">Invoice</a>
                                    </li>
                                    <li><a href="inbox.html">Inbox</a>
                                    </li>
                                    <li><a href="calender.html">Calender</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-table"></i> Tables <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="tables.html">Tables</a>
                                    </li>
                                    <li><a href="tables_dynamic.html">Table Dynamic</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-bar-chart-o"></i> Data Presentation <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="chartjs.html">Chart JS</a>
                                    </li>
                                    <li><a href="chartjs2.html">Chart JS2</a>
                                    </li>
                                    <li><a href="morisjs.html">Moris JS</a>
                                    </li>
                                    <li><a href="echarts.html">ECharts </a>
                                    </li>
                                    <li><a href="other_charts.html">Other Charts </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="menu_section">
                        <h3>Live On</h3>
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-bug"></i> Additional Pages <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="e_commerce.html">E-commerce</a>
                                    </li>
                                    <li><a href="projects.html">Projects</a>
                                    </li>
                                    <li><a href="project_detail.html">Project Detail</a>
                                    </li>
                                    <li><a href="contacts.html">Contacts</a>
                                    </li>
                                    <li><a href="profile.html">Profile</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a><i class="fa fa-windows"></i> Extras <span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu" style="display: none">
                                    <li><a href="page_404.html">404 Error</a>
                                    </li>
                                    <li><a href="page_500.html">500 Error</a>
                                    </li>
                                    <li><a href="plain_page.html">Plain Page</a>
                                    </li>
                                    <li><a href="login.html">Login Page</a>
                                    </li>
                                    <li><a href="pricing_tables.html">Pricing Tables</a>
                                    </li>

                                </ul>
                            </li>
                            <li><a><i class="fa fa-laptop"></i> Landing Page <span class="label label-success pull-right">Coming Soon</span></a>
                            </li>
                        </ul>
                    </div>

                </div>
                <!-- /sidebar menu -->

                <!-- /menu footer buttons -->
                <div class="sidebar-footer hidden-small">
                    <a data-toggle="tooltip" data-placement="top" title="Settings">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Lock">
                        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                    </a>
                    <a data-toggle="tooltip" data-placement="top" title="Logout">
                        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                    </a>
                </div>
                <!-- /menu footer buttons -->
            </div>
        </div>


        <!-- top navigation -->
        <div class="top_nav">

            <div class="nav_menu">
                <nav class="" role="navigation">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="">
                            <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <img src="public/admin/images/img.jpg" alt="">John Doe
                                <span class=" fa fa-angle-down"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                                <li><a href="javascript:;">  Profile</a>
                                </li>
                                <li>
                                    <a href="javascript:;">
                                        <span class="badge bg-red pull-right">50%</span>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;">Help</a>
                                </li>
                                <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                                </li>
                            </ul>
                        </li>

                        <li role="presentation" class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-envelope-o"></i>
                                <span class="badge bg-green">6</span>
                            </a>
                            <ul id="menu1" class="dropdown-menu list-unstyled msg_list animated fadeInDown" role="menu">
                                <li>
                                    <a>
                                            <span class="image">
                                        <img src="images/img.jpg" alt="Profile Image" />
                                    </span>
                                            <span>
                                        <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                            </span>
                                            <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                            <span class="image">
                                        <img src="public/admin/images/img.jpg" alt="Profile Image" />
                                    </span>
                                            <span>
                                        <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                            </span>
                                            <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                            <span class="image">
                                        <img src="public/admin/images/img.jpg" alt="Profile Image" />
                                    </span>
                                            <span>
                                        <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                            </span>
                                            <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                            <span class="image">
                                        <img src="public/admin/images/img.jpg" alt="Profile Image" />
                                    </span>
                                            <span>
                                        <span>John Smith</span>
                                            <span class="time">3 mins ago</span>
                                            </span>
                                            <span class="message">
                                        Film festivals used to be do-or-die moments for movie makers. They were where...
                                    </span>
                                    </a>
                                </li>
                                <li>
                                    <div class="text-center">
                                        <a>
                                            <strong><a href="inbox.html">See All Alerts</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>

        </div>
        <!-- /top navigation -->


        <!-- page content -->
        <div class="right_col" role="main">

            
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>运营活动编辑-请选择活动类型</h3>
        </div>
    </div>
                    <div class="clearfix"></div>

                    <div class="">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">

                                <div class="x_content">


                                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                            <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">webview </a>
                                            </li>
                                            <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab"  aria-expanded="false">频道推荐</a>
                                            </li>
                                        </ul>
                                        <div id="myTabContent" class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <div class="x_panel">
                                                            <div class="x_content">

                                                                <form class="form-horizontal form-label-left" novalidate>
                                                                    <span class="section">请填写webview banner的相关信息</span>

                                                                    <div class="item form-group">
                                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="website">Webview URL <span class="required">*</span>
                                                                        </label>
                                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                                            <input type="url" id="website" name="website" required="required" placeholder="www.website.com" class="form-control col-md-7 col-xs-12">
                                                                        </div>
                                                                    </div>
                                                                    <div class="item form-group">
                                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="occupation">是否立即发布 <span class="required">*</span>
                                                                        </label>
                                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                                                是:
                                                                                <input type="radio" class="flat" name="gender" id="isPublish" value="Y"  required /> 否:
                                                                                <input type="radio" class="flat" name="gender" id="notPublish" checked="" value="N" />

                                                                        </div>
                                                                    </div>

                                                                    <div class="item form-group">
                                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="textarea">备注
                                                                        </label>
                                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                                            <textarea id="textarea" required="required" name="textarea" class="form-control col-md-7 col-xs-12"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="item form-group">
                                                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="textarea">上传logo
                                                                        </label>
                                                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                                                            <div class="x_panel">
                                                                                <div class="x_content">

                                                                                    <p>将文件拖拽至该区域上传</p>
                                                                                    <form action="choices/form_upload.html" class="dropzone" style="border: 1px solid #e5e5e5; height: 300px; "></form>
                                                                                    <br />
                                                                                    <br />
                                                                                    <br />
                                                                                    <br />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ln_solid"></div>
                                                                    <div class="form-group">
                                                                        <div class="col-md-6 col-md-offset-3">
                                                                            <button type="submit" class="btn btn-primary">Cancel</button>
                                                                            <button id="send" type="submit" class="btn btn-success">Submit</button>
                                                                        </div>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                                                <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="clearfix"></div>

                    </div>
                    <div class="clearfix"></div>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="x_panel">

                            <div class="x_content">

                                <!-- modals -->
                                <!-- Large modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Large modal</button>

                                <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                                            </div>
                                            <div class="modal-body">
                                                <h4>Text in a modal</h4>
                                                <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                                                <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- Small modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-sm">Small modal</button>

                                <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                                </button>
                                                <h4 class="modal-title" id="myModalLabel2">Modal title</h4>
                                            </div>
                                            <div class="modal-body">
                                                <h4>Text in a modal</h4>
                                                <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.</p>
                                                <p>Aenean lacinia bibendum nulla sed consectetur. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Donec ullamcorper nulla non metus auctor fringilla.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- /modals -->
                            </div>
                        </div>
                    </div>



                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2><i class="fa fa-bell"></i> Notifications <small>Styled & Custom notifications</small></h2>
                                <ul class="nav navbar-right panel_toolbox">
                                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="#">Settings 1</a>
                                            </li>
                                            <li><a href="#">Settings 2</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">

                                <p>Regular notifications</p>
                                <button class="btn btn-default source" onclick="new PNotify({
                                title: 'Regular Success',
                                text: 'That thing that you were trying to do worked!',
                                type: 'success'
                            });">Success</button>

                                <button class="btn btn-default source" onclick="new PNotify({
                                title: 'New Thing',
                                text: 'Just to let you know, something happened.',
                                type: 'info'
                            });">Info</button>

                                <button class="btn btn-default source" onclick="new PNotify({
                                title: 'Regular Notice',
                                text: 'Check me out! I\'m a notice.'
                            });">Notice</button>

                                <button class="btn btn-default source" onclick="new PNotify({
                                title: 'Oh No!',
                                text: 'Something terrible happened.',
                                type: 'error'
                            });">Error</button>

                                <button class="btn btn-default source" onclick="new PNotify({
                                title: 'Oh No!',
                                text: 'Something terrible happened.',
                                type: 'dark'
                            });">Dark</button>

                                <hr />

                                <p>Sticky notifications.. these wont close unless user closes them.</p>
                                <button class="btn btn-default source" onclick="new PNotify({
                                title: 'Sticky Success',
                                text: 'Sticky success... I\'m not even gonna make a joke.',
                                type: 'success',
                                hide: false
                            });">Success</button>


                                <button class="btn btn-default source" onclick="new PNotify({
                                title: 'Sticky Info',
                                text: 'Sticky Info... I\'m not even gonna make a joke.',
                                type: 'info',
                                hide: false
                            });">Info</button>


                                <button class="btn btn-default source" onclick="new PNotify({
                                title: 'Sticky Warning',
                                text: 'Sticky Warning... I\'m not even gonna make a joke.',
                                hide: false
                            });">Warning</button>


                                <button class="btn btn-default source" onclick="new PNotify({
                                title: 'Sticky Danger',
                                text: 'Sticky Danger... I\'m not even gonna make a joke.',
                                type: 'error',
                                hide: false
                            });">Error</button>


                                <button class="btn btn-default source" onclick="new PNotify({
                                title: 'Sticky Success',
                                text: 'Sticky success... I\'m not even gonna make a joke.',
                                type: 'dark',
                                hide: false
                            });">Dark</button>

                                <hr/>

                                <button class="btn btn-default source" onclick="new PNotify({
                                title: 'Non-Blocking Notice',
                                type: 'dark',
                                text: 'When you hover over me I\'ll fade to show the elements underneath. Feel free to click any of them just like I wasn\'t even here.',
                                nonblock: {
                                    nonblock: true,
                                    nonblock_opacity: .2
                                }
                            });">Non-Blocking Notice</button>


                                <hr />

                                <div id="antoox">
                                    <div>
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>

                                </div>

                                <button class="btn btn-default source" onclick="new TabbedNotification({
                                title: 'Tabbed Notificat',
                                text: 'Sticky success... Raw denim you probably haven\'t heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. ',
                                type: 'success',
                                sound: false
                            })">Success</button>

                                <button class="btn btn-default source" onclick="new TabbedNotification({
                                title: 'Tabbed Notificat',
                                text: 'Custom Info... Raw denim you probably haven\'t heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica.',
                                type: 'info',
                                sound: false
                            })">Info</button>

                                <button class="btn btn-default source" onclick="new TabbedNotification({
                                title: 'Tabbed Notificat',
                                text: 'Custom Warning... Raw denim you probably haven\'t heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. ',
                                type: 'warning',
                                sound: false
                            })">Warning</button>

                                <button class="btn btn-default source" onclick="new TabbedNotification({
                                title: 'Custom Notification error',
                                text: 'Custom Error... Raw denim you probably haven\'t heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. ',
                                type: 'error',
                                sound: false
                            })">Error</button>

                                <button class="btn btn-default source" onclick="new TabbedNotification({
                                title: 'Tabbed notification dark',
                                text: 'Custom Dark... Raw denim you probably haven\'t heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. ',
                                type: 'dark',
                                sound: false
                            })">Dark</button>


                            </div>
                        </div>
                    </div>



                </div>
                <div class="clearfix"></div>


            <!-- footer content -->

            



    <!-- PNotify -->
    <?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/notify/pnotify.core.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/notify/pnotify.buttons.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/notify/pnotify.nonblock.js"><?php echo '</script'; ?>
>

    <?php echo '<script'; ?>
>
        $(function () {
            var cnt = 10; //$("#custom_notifications ul.notifications li").length + 1;
            TabbedNotification = function (options) {
                var message = "<div id='ntf" + cnt + "' class='text alert-" + options.type + "' style='display:none'><h2><i class='fa fa-bell'></i> " + options.title + "</h2><div class='close'><a href='javascript:;' class='notification_close'><i class='fa fa-close'></i></a></div><p>" + options.text + "</p></div>";

                if (document.getElementById('custom_notifications') == null) {
                    alert('doesnt exists');
                } else {
                    $('#custom_notifications ul.notifications').append("<li><a id='ntlink" + cnt + "' class='alert-" + options.type + "' href='#ntf" + cnt + "'><i class='fa fa-bell animated shake'></i></a></li>");
                    $('#custom_notifications #notif-group').append(message);
                    cnt++;
                    CustomTabs(options);
                }
            }

            CustomTabs = function (options) {
                $('.tabbed_notifications > div').hide();
                $('.tabbed_notifications > div:first-of-type').show();
                $('#custom_notifications').removeClass('dsp_none');
                $('.notifications a').click(function (e) {
                    e.preventDefault();
                    var $this = $(this),
                        tabbed_notifications = '#' + $this.parents('.notifications').data('tabbed_notifications'),
                        others = $this.closest('li').siblings().children('a'),
                        target = $this.attr('href');
                    others.removeClass('active');
                    $this.addClass('active');
                    $(tabbed_notifications).children('div').hide();
                    $(target).show();
                });
            }

            CustomTabs();

            var tabid = idname = '';
            $(document).on('click', '.notification_close', function (e) {
                idname = $(this).parent().parent().attr("id");
                tabid = idname.substr(-2);
                $('#ntf' + tabid).remove();
                $('#ntlink' + tabid).parent().remove();
                $('.notifications a').first().addClass('active');
                $('#notif-group div').first().css('display','block');
            });
        })
    <?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript">
        var permanotice, tooltip, _alert;
        $(function () {
            new PNotify({
                title: "PNotify",
                type: "dark",
                text: "Welcome. Try hovering over me. You can click things behind me, because I'm non-blocking.",
                nonblock: {
                    nonblock: true
                },
                before_close: function (PNotify) {
                    // You can access the notice's options with this. It is read only.
                    //PNotify.options.text;

                    // You can change the notice's options after the timer like this:
                    PNotify.update({
                        title: PNotify.options.title + " - Enjoy your Stay",
                        before_close: null
                    });
                    PNotify.queueRemove();
                    return false;
                }
            });

        });
    <?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
        $(document).ready(function () {
            $('.progress .bar').progressbar(); // bootstrap 2
            $('.progress .progress-bar').progressbar(); // bootstrap 3
        });
    <?php echo '</script'; ?>
>


<!-- form validation -->
<?php echo '<script'; ?>
 src="public/admin/js/validator/validator.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    // initialize the validator function
    validator.message['date'] = 'not a real date';

    // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
    $('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);

    $('.multi.required')
        .on('keyup blur', 'input', function () {
            validator.checkField.apply($(this).siblings().last()[0]);
        });

    // bind the validation to the form submit event
    //$('#send').click('submit');//.prop('disabled', true);

    $('form').submit(function (e) {
        e.preventDefault();
        var submit = true;
        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
            submit = false;
        }

        if (submit)
            this.submit();
        return false;
    });

    /* FOR DEMO ONLY */
    $('#vfields').change(function () {
        $('form').toggleClass('mode2');
    }).prop('checked', false);

    $('#alerts').change(function () {
        validator.defaults.alerts = (this.checked) ? false : true;
        if (this.checked)
            $('form .alert').remove();
    }).prop('checked', false);
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="public/admin/js/dropzone/dropzone.js"><?php echo '</script'; ?>
>

            <footer>
                <div class="">
                    <p class="pull-right">Gentelella Alela! a Bootstrap 3 template by <a>Kimlabs</a>. |
                        <span class="lead"> <i class="fa fa-paw"></i> Gentelella Alela!</span>
                    </p>
                </div>
                <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->
        </div>
        <!-- /page content -->

    </div>

</div>

<div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
</div>

<?php echo '<script'; ?>
 src="public/admin/js/bootstrap.min.js"><?php echo '</script'; ?>
>

<!-- gauge js -->
<?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/gauge/gauge.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/gauge/gauge_demo.js"><?php echo '</script'; ?>
>
<!-- chart js -->
<?php echo '<script'; ?>
 src="public/admin/js/chartjs/chart.min.js"><?php echo '</script'; ?>
>
<!-- bootstrap progress js -->
<?php echo '<script'; ?>
 src="public/admin/js/progressbar/bootstrap-progressbar.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="public/admin/js/nicescroll/jquery.nicescroll.min.js"><?php echo '</script'; ?>
>
<!-- icheck -->
<?php echo '<script'; ?>
 src="public/admin/js/icheck/icheck.min.js"><?php echo '</script'; ?>
>
<!-- daterangepicker -->
<?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/moment.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/datepicker/daterangepicker.js"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 src="public/admin/js/custom.js"><?php echo '</script'; ?>
>

<!-- flot js -->
<!--[if lte IE 8]><?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/excanvas.min.js"><?php echo '</script'; ?>
><![endif]-->
<?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/flot/jquery.flot.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/flot/jquery.flot.pie.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/flot/jquery.flot.orderBars.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/flot/jquery.flot.time.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/flot/date.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/flot/jquery.flot.spline.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/flot/jquery.flot.stack.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/flot/curvedLines.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="public/admin/js/flot/jquery.flot.resize.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    $(document).ready(function () {
        // [17, 74, 6, 39, 20, 85, 7]
        //[82, 23, 66, 9, 99, 6, 2]
        var data1 = [[gd(2012, 1, 1), 17], [gd(2012, 1, 2), 74], [gd(2012, 1, 3), 6], [gd(2012, 1, 4), 39], [gd(2012, 1, 5), 20], [gd(2012, 1, 6), 85], [gd(2012, 1, 7), 7]];

        var data2 = [[gd(2012, 1, 1), 82], [gd(2012, 1, 2), 23], [gd(2012, 1, 3), 66], [gd(2012, 1, 4), 9], [gd(2012, 1, 5), 119], [gd(2012, 1, 6), 6], [gd(2012, 1, 7), 9]];
        $("#canvas_dahs").length && $.plot($("#canvas_dahs"), [
            data1, data2
        ], {
            series: {
                lines: {
                    show: false,
                    fill: true
                },
                splines: {
                    show: true,
                    tension: 0.4,
                    lineWidth: 1,
                    fill: 0.4
                },
                points: {
                    radius: 0,
                    show: true
                },
                shadowSize: 2
            },
            grid: {
                verticalLines: true,
                hoverable: true,
                clickable: true,
                tickColor: "#d5d5d5",
                borderWidth: 1,
                color: '#fff'
            },
            colors: ["rgba(38, 185, 154, 0.38)", "rgba(3, 88, 106, 0.38)"],
            xaxis: {
                tickColor: "rgba(51, 51, 51, 0.06)",
                mode: "time",
                tickSize: [1, "day"],
                //tickLength: 10,
                axisLabel: "Date",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 10
                //mode: "time", timeformat: "%m/%d/%y", minTickSize: [1, "day"]
            },
            yaxis: {
                ticks: 8,
                tickColor: "rgba(51, 51, 51, 0.06)",
            },
            tooltip: false
        });

        function gd(year, month, day) {
            return new Date(year, month - 1, day).getTime();
        }
    });
<?php echo '</script'; ?>
>

<!-- worldmap -->
<?php echo '<script'; ?>
 type="text/javascript" src="js/maps/jquery-jvectormap-2.0.1.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="js/maps/gdp-data.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="js/maps/jquery-jvectormap-world-mill-en.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="js/maps/jquery-jvectormap-us-aea-en.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    $(function () {
        $('#world-map-gdp').vectorMap({
            map: 'world_mill_en',
            backgroundColor: 'transparent',
            zoomOnScroll: false,
            series: {
                regions: [{
                    values: gdpData,
                    scale: ['#E6F2F0', '#149B7E'],
                    normalizeFunction: 'polynomial'
                }]
            },
            onRegionTipShow: function (e, el, code) {
                el.html(el.html() + ' (GDP - ' + gdpData[code] + ')');
            }
        });
    });
<?php echo '</script'; ?>
>
<!-- skycons -->
<?php echo '<script'; ?>
 src="js/skycons/skycons.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    var icons = new Skycons({
            "color": "#73879C"
        }),
        list = [
            "clear-day", "clear-night", "partly-cloudy-day",
            "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
            "fog"
        ],
        i;

    for (i = list.length; i--;)
        icons.set(list[i], list[i]);

    icons.play();
<?php echo '</script'; ?>
>

<!-- dashbord linegraph -->
<?php echo '<script'; ?>
>
    var doughnutData = [
        {
            value: 30,
            color: "#455C73"
        },
        {
            value: 30,
            color: "#9B59B6"
        },
        {
            value: 60,
            color: "#BDC3C7"
        },
        {
            value: 100,
            color: "#26B99A"
        },
        {
            value: 120,
            color: "#3498DB"
        }
    ];
    var myDoughnut = new Chart(document.getElementById("canvas1").getContext("2d")).Doughnut(doughnutData);
<?php echo '</script'; ?>
>
<!-- /dashbord linegraph -->
<!-- datepicker -->
<?php echo '<script'; ?>
 type="text/javascript">
    $(document).ready(function () {

        var cb = function (start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
        }

        var optionSet1 = {
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            minDate: '01/01/2012',
            maxDate: '12/31/2015',
            dateLimit: {
                days: 60
            },
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens: 'left',
            buttonClasses: ['btn btn-default'],
            applyClass: 'btn-small btn-primary',
            cancelClass: 'btn-small',
            format: 'MM/DD/YYYY',
            separator: ' to ',
            locale: {
                applyLabel: 'Submit',
                cancelLabel: 'Clear',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            }
        };
        $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));
        $('#reportrange').daterangepicker(optionSet1, cb);
        $('#reportrange').on('show.daterangepicker', function () {
            console.log("show event fired");
        });
        $('#reportrange').on('hide.daterangepicker', function () {
            console.log("hide event fired");
        });
        $('#reportrange').on('apply.daterangepicker', function (ev, picker) {
            console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
        });
        $('#reportrange').on('cancel.daterangepicker', function (ev, picker) {
            console.log("cancel event fired");
        });
        $('#options1').click(function () {
            $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
        });
        $('#options2').click(function () {
            $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
        });
        $('#destroy').click(function () {
            $('#reportrange').data('daterangepicker').remove();
        });
    });
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    NProgress.done();
<?php echo '</script'; ?>
>
<!-- /datepicker -->
<!-- /footer content -->
</body>

</html>
<?php }} ?>
