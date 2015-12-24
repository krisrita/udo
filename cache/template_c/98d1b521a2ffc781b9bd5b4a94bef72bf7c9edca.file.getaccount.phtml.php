<?php /* Smarty version Smarty-3.1.21, created on 2015-12-23 21:51:29
         compiled from "C:\wamp\www\udo\application\views\account\getaccount.phtml" */ ?>
<?php /*%%SmartyHeaderCode:5388567a51863e9648-90783344%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '98d1b521a2ffc781b9bd5b4a94bef72bf7c9edca' => 
    array (
      0 => 'C:\\wamp\\www\\udo\\application\\views\\account\\getaccount.phtml',
      1 => 1450878676,
      2 => 'file',
    ),
    '21ee24d61c68629a7630ce2ce830d34a36a40c3f' => 
    array (
      0 => 'C:\\wamp\\www\\udo\\application\\views\\adminLayout.phtml',
      1 => 1450856524,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5388567a51863e9648-90783344',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.21',
  'unifunc' => 'content_567a5186690ca6_03282860',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_567a5186690ca6_03282860')) {function content_567a5186690ca6_03282860($_smarty_tpl) {?><!DOCTYPE html>
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
                                    <li><a href="/Account/getAccount">账户查询</a>
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

            
<!-- page content -->
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>
                账户查询
            </h3>
        </div>

        <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                                    <span class="input-group-btn">
                            <button class="btn btn-default" type="button">Go!</button>
                        </span>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">

                    <div class="well" style="overflow: auto">

                        <!-- 列表日期筛选 -->
                        <label class="control-label col-md-1">选择日期</label>
                        <div class="col-md-4">
                            <div id="reportrange_right" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
                            </div>
                        </div>
                        <!--日期筛选-->

                        <br><br><br>

                        <!-- 用户名筛选 -->
                        <!--<div class="form-group">-->
                            <label class="control-label col-md-1 col-sm-1 col-xs-12">用户名</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" class="form-control" placeholder="输入用户名筛选">
                            </div>
                        <!--</div>-->
                        <!-- 用户名筛选-->

                        <!-- 用户id筛选 -->
                        <!--<div class="form-group">-->
                            <label class="control-label col-md-1 col-sm-1 col-xs-12">用户id</label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" class="form-control" placeholder="输入用户id筛选">
                            </div>
                        <!--</div>-->
                        <!-- 用户id筛选-->

                        <br><br><br>
                        <div class="ln_solid"></div>
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                            <button type="submit" class="btn btn-primary">清空条件</button>
                            <button type="submit" class="btn btn-success">确认筛选</button>
                        </div>
                    </div>

                    <!-- 表格2 U币日志-->
    </div>
</div>


    <div class="" role="tabpanel" data-example-id="togglable-tabs">
        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
            <li role="presentation" class="active"><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">账户余额</a>
            </li>
            <li role="presentation" class=""><a href="#tab_content2" role="tab" id="profile-tab" data-toggle="tab"  aria-expanded="false">U币日志</a>
            </li>
            <li role="presentation" class=""><a href="#tab_content3" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">学分日志</a>
            </li>
            <li role="presentation" class=""><a href="#tab_content4" role="tab" id="profile-tab2" data-toggle="tab" aria-expanded="false">购买记录</a>
            </li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                <!-- 表格1 账户信息-->
                <table id="example1" class="table table-striped responsive-utilities jambo_table">
                    <!--表头-->
                    <thead>
                    <tr class="headings">
                        <!--<th>
                            <input type="checkbox" class="tableflat">
                        </th>-->
                        <th>用户id</th>
                        <th>用户名</th>
                        <th>用户姓名</th>
                        <th>U币余额</th>
                        <th>学分余额</th>
                    </tr>
                    </thead>
                    <!--表头-->
                    <tbody>
                    <?php  $_smarty_tpl->tpl_vars['account'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['account']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['accountList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['account']->key => $_smarty_tpl->tpl_vars['account']->value) {
$_smarty_tpl->tpl_vars['account']->_loop = true;
?>
                    <tr class="even pointer">
                        <!--<td class="a-center ">
                            <input type="checkbox" class="tableflat">
                        </td>-->
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['account']->value['sso_id'];?>
</td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['account']->value['mobile'];?>
 </td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['account']->value['user_name'];?>
 </td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['account']->value['amt'];?>

                        </td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['account']->value['score'];?>
</td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <!-- 表格1 账户信息-->
            </div>

            <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
                <!-- 表格2 U币日志-->
                <table id="example2" class="table table-striped responsive-utilities jambo_table">
                    <!--表头-->
                    <thead>
                    <tr class="headings">
                        <!--<th>
                            <input type="checkbox" class="tableflat">
                        </th>-->
                        <th>用户id</th>
                        <th>用户名</th>
                        <th>用户姓名</th>
                        <th>信息</th>
                        <th>数值</th>
                        <th>时间</th>
                    </tr>
                    </thead>
                    <!--表头-->
                    <tbody>
                    <?php  $_smarty_tpl->tpl_vars['coinLog'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['coinLog']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['coinLogList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['coinLog']->key => $_smarty_tpl->tpl_vars['coinLog']->value) {
$_smarty_tpl->tpl_vars['coinLog']->_loop = true;
?>
                    <tr class="even pointer">
                        <!--<td class="a-center ">
                            <input type="checkbox" class="tableflat">
                        </td>-->
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['coinLog']->value['userId'];?>
</td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['coinLog']->value['mobile'];?>
 </td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['coinLog']->value['name'];?>
 </td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['coinLog']->value['info'];?>
</td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['coinLog']->value['amt'];?>
</td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['coinLog']->value['convertedTime'];?>
</td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <!-- 表格2 U币日志-->
            </div>


            <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
                <!-- 表格3 学分日志-->
                <table id="example3" class="table table-striped responsive-utilities jambo_table">
                    <!--表头-->
                    <thead>
                    <tr class="headings">
                        <!--<th>
                            <input type="checkbox" class="tableflat">
                        </th>-->
                        <th>用户id</th>
                        <th>用户名</th>
                        <th>用户姓名</th>
                        <th>信息</th>
                        <th>数值</th>
                        <th>时间</th>
                    </tr>
                    </thead>
                    <!--表头-->
                    <tbody>
                    <?php  $_smarty_tpl->tpl_vars['creditLog'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['creditLog']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['creditLogList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['creditLog']->key => $_smarty_tpl->tpl_vars['creditLog']->value) {
$_smarty_tpl->tpl_vars['creditLog']->_loop = true;
?>
                    <tr class="even pointer">
                        <!--<td class="a-center ">
                            <input type="checkbox" class="tableflat">
                        </td>-->
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['creditLog']->value['userId'];?>
</td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['creditLog']->value['mobile'];?>
 </td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['creditLog']->value['name'];?>
 </td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['creditLog']->value['info'];?>
</td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['creditLog']->value['amt'];?>
</td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['creditLog']->value['convertedTime'];?>
</td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <!-- 表格3 学分日志-->
            </div>

            <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
                <!-- 表格4 购买记录-->
                <table id="example4" class="table table-striped responsive-utilities jambo_table">
                    <!--表头-->
                    <thead>
                    <tr class="headings">
                        <!--<th>
                            <input type="checkbox" class="tableflat">
                        </th>-->
                        <th>用户id</th>
                        <th>用户名</th>
                        <th>用户姓名</th>
                        <th>课程名称</th>
                        <th>频道名称</th>
                        <th>订单号</th>
                        <th>购买时间</th>
                    </tr>
                    </thead>
                    <!--表头-->
                    <tbody>
                    <?php  $_smarty_tpl->tpl_vars['bought'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['bought']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['boughtList']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['bought']->key => $_smarty_tpl->tpl_vars['bought']->value) {
$_smarty_tpl->tpl_vars['bought']->_loop = true;
?>
                    <tr class="even pointer">
                        <!--<td class="a-center ">
                            <input type="checkbox" class="tableflat">
                        </td>-->
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['bought']->value['userId'];?>
</td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['bought']->value['mobile'];?>
 </td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['bought']->value['userName'];?>
 </td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['bought']->value['name'];?>
</td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['bought']->value['schoolName'];?>
</td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['bought']->value['orderId'];?>
</td>
                        <td class=" "><?php echo $_smarty_tpl->tpl_vars['bought']->value['createTime'];?>
</td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <!-- 表格4 购买记录-->
            </div>

        </div>
    </div>

</div>

            <!-- footer content -->

            

<!-- Datatables -->
<?php echo '<script'; ?>
 src="public/admin/js/datatables/js/jquery.dataTables.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="js/datatables/tools/js/dataTables.tableTools.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    $(document).ready(function () {
        $('input.tableflat').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });
    });

    var asInitVals = new Array();
    $(document).ready(function () {
        for(var i=1;i<5;i++){
            var oTable = $('#example'+i).dataTable({
                "oLanguage": {
                    "sSearch": "Search all columns:"
                },
                "aoColumnDefs": [
                    {
                        'bSortable': false,
                        'aTargets': [0]
                    } //disables sorting for column one
                ],
                'iDisplayLength': 12,
                "sPaginationType": "full_numbers",
                "dom": 'T<"clear">lfrtip',
                "tableTools": {
                    "sSwfPath": "<?php echo '<?php'; ?>
 echo base_url('assets2/js/Datatables/tools/swf/copy_csv_xls_pdf.swf'); <?php echo '?>'; ?>
"
                }
            });
        }

        $("tfoot input").keyup(function () {
            /* Filter on the column based on the index of this element's parent <th> */
            oTable.fnFilter(this.value, $("tfoot th").index($(this).parent()));
        });
        $("tfoot input").each(function (i) {
            asInitVals[i] = this.value;
        });
        $("tfoot input").focus(function () {
            if (this.className == "search_init") {
                this.className = "";
                this.value = "";
            }
        });
        $("tfoot input").blur(function (i) {
            if (this.value == "") {
                this.className = "search_init";
                this.value = asInitVals[$("tfoot input").index(this)];
            }
        });
    });
<?php echo '</script'; ?>
>

<!-- datepicker -->
<?php echo '<script'; ?>
 type="text/javascript">
    $(document).ready(function () {

        var cb = function (start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
            $('#reportrange_right span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
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
                '今天': [moment(), moment()],
                '昨天': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '过去7天': [moment().subtract(6, 'days'), moment()],
                '过去30天': [moment().subtract(29, 'days'), moment()],
                '本月': [moment().startOf('month'), moment().endOf('month')],
                '上月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens: 'right',
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
                customRangeLabel: '常规',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            }
        };

        $('#reportrange_right span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

        $('#reportrange_right').daterangepicker(optionSet1, cb);

        $('#reportrange_right').on('show.daterangepicker', function () {
            console.log("show event fired");
        });
        $('#reportrange_right').on('hide.daterangepicker', function () {
            console.log("hide event fired");
        });
        $('#reportrange_right').on('apply.daterangepicker', function (ev, picker) {
            console.log("apply event fired, start/end dates are " + picker.startDate.format('MMMM D, YYYY') + " to " + picker.endDate.format('MMMM D, YYYY'));
        });
        $('#reportrange_right').on('cancel.daterangepicker', function (ev, picker) {
            console.log("cancel event fired");
        });

        $('#options1').click(function () {
            $('#reportrange_right').data('daterangepicker').setOptions(optionSet1, cb);
        });

        $('#options2').click(function () {
            $('#reportrange_right').data('daterangepicker').setOptions(optionSet2, cb);
        });

        $('#destroy').click(function () {
            $('#reportrange_right').data('daterangepicker').remove();
        });

    });
<?php echo '</script'; ?>
>
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
                '今天': [moment(), moment()],
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
<!-- /datepicker -->
<?php echo '<script'; ?>
 type="text/javascript">
    $(document).ready(function () {
        $('#single_cal1').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_1"
        }, function (start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
        $('#single_cal2').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_2"
        }, function (start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
        $('#single_cal3').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_3"
        }, function (start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
        $('#single_cal4').daterangepicker({
            singleDatePicker: true,
            calender_style: "picker_4"
        }, function (start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
    $(document).ready(function () {
        $('#reservation').daterangepicker(null, function (start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
        });
    });
<?php echo '</script'; ?>
>
<!-- /datepicker -->

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
