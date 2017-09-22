@section('menu')
    <li @if(Request::segment(1) === 'hotesse')class="active" @endif><a href="{{route("hotesse")}}"><span class="fa fa-users"></span> <span class="xn-text">Hotesse</span></a></li>
    <li @if(Request::segment(1) === 'code')class="active" @endif><a href="{{route("code")}}"><span class="fa fa-list-ol"></span> <span class="xn-text">Code</span></a></li>
    <li @if(Request::segment(1) === 'client')class="active" @endif><a href="{{route("client")}}"><span class="fa fa-user-circle"></span> <span class="xn-text">Client</span></a></li>

    @isset(Auth::user()->role)
        <li @if(Request::segment(1) === 'api')class="active" @endif><a href="{{route("api")}}"><span class="fa fa-server"></span> <span class="xn-text">API</span></a></li>
        @if(Auth::user()->role == "superAdmin")
            <li @if(Request::segment(1) === 'admin')class="active" @endif><a href="{{route("admin")}}"><span class="fa fa-user-plus"></span> <span class="xn-text">Admin</span></a></li>
        @endif
    @endisset
@endsection
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <!-- META SECTION -->
    <title>Joli Admin - Responsive Bootstrap Admin Template</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->
    <link rel="stylesheet" type="text/css" id="theme" href="{{url(elixir('css/theme-blue.css'))}}"/>
    <!-- EOF CSS INCLUDE -->
</head>
<body>
<!-- START PAGE CONTAINER -->
<div class="page-container">

    <!-- START PAGE SIDEBAR -->
    <div class="page-sidebar">
        <!-- START X-NAVIGATION -->
        <ul class="x-navigation">
            <li class="xn-logo">
                <a href="index.html">Opium love</a>
                <a href="#" class="x-navigation-control"></a>
            </li>
            <li class="xn-profile">
                <a href="#" class="profile-mini">
                    <img src="{{url(elixir("assets/images/users/avatar.jpg"))}}" alt="John Doe"/>
                </a>
                <div class="profile">
                    <div class="profile-image">
                        <img src="{{url(elixir("assets/images/users/avatar.jpg"))}}" alt="John Doe"/>
                    </div>
                    <div class="profile-data">
                        <div class="profile-data-name">@isset(Auth::user()->name){{Auth::user()->name}} @endisset</div>
                        <div class="profile-data-title"></div>
                    </div>
                    <div class="profile-controls">
                        <a href="pages-profile.html" class="profile-control-left"><span class="fa fa-info"></span></a>
                        <a href="pages-messages.html" class="profile-control-right"><span class="fa fa-envelope"></span></a>
                    </div>
                </div>
            </li>
            <li class="xn-title">Navigation</li>
            @yield('menu')

        </ul>
        <!-- END X-NAVIGATION -->
    </div>
    <!-- END PAGE SIDEBAR -->

    <!-- PAGE CONTENT -->
    <div class="page-content">
        <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
            <!-- TOGGLE NAVIGATION -->
            <li class="xn-icon-button">
                <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
            </li>
            <!-- END TOGGLE NAVIGATION -->
            <!-- SEARCH -->
            <li class="xn-search">
                <form role="form">
                    <input type="text" name="search" placeholder="Search...">
                </form>
            </li>
            <!-- END SEARCH -->
            <!-- SIGN OUT -->
            <li class="xn-icon-button pull-right">
                <a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span></a>
            </li>
            <!-- END SIGN OUT -->
            <!-- MESSAGES -->
            <li class="xn-icon-button pull-right">
                <a href="#"><span class="fa fa-comments"></span></a>
                <div class="informer informer-danger">4</div>
                <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging ui-draggable">
                    <div class="panel-heading ui-draggable-handle">
                        <h3 class="panel-title"><span class="fa fa-comments"></span> Messages</h3>
                        <div class="pull-right">
                            <span class="label label-danger">4 new</span>
                        </div>
                    </div>
                    <div class="panel-body list-group list-group-contacts scroll mCustomScrollbar _mCS_2 mCS-autoHide mCS_no_scrollbar" style="height: 200px;"><div id="mCSB_2" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0"><div id="mCSB_2_container" class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y" style="position:relative; top:0; left:0;" dir="ltr">
                                <a href="#" class="list-group-item">
                                    <div class="list-group-status status-online"></div>
                                    <img src="assets/images/users/user2.jpg" class="pull-left" alt="John Doe">
                                    <span class="contacts-title">John Doe</span>
                                    <p>Praesent placerat tellus id augue condimentum</p>
                                </a>
                                <a href="#" class="list-group-item">
                                    <div class="list-group-status status-away"></div>
                                    <img src="assets/images/users/user.jpg" class="pull-left" alt="Dmitry Ivaniuk">
                                    <span class="contacts-title">Dmitry Ivaniuk</span>
                                    <p>Donec risus sapien, sagittis et magna quis</p>
                                </a>
                                <a href="#" class="list-group-item">
                                    <div class="list-group-status status-away"></div>
                                    <img src="assets/images/users/user3.jpg" class="pull-left" alt="Nadia Ali">
                                    <span class="contacts-title">Nadia Ali</span>
                                    <p>Mauris vel eros ut nunc rhoncus cursus sed</p>
                                </a>
                                <a href="#" class="list-group-item">
                                    <div class="list-group-status status-offline"></div>
                                    <img src="assets/images/users/user6.jpg" class="pull-left" alt="Darth Vader">
                                    <span class="contacts-title">Darth Vader</span>
                                    <p>I want my money back!</p>
                                </a>
                            </div><div id="mCSB_2_scrollbar_vertical" class="mCSB_scrollTools mCSB_2_scrollbar mCS-light mCSB_scrollTools_vertical" style="display: none;"><div class="mCSB_draggerContainer"><div id="mCSB_2_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 30px; top: 0px;" oncontextmenu="return false;"><div class="mCSB_dragger_bar" style="line-height: 30px;"></div></div><div class="mCSB_draggerRail"></div></div></div></div></div>
                    <div class="panel-footer text-center">
                        <a href="pages-messages.html">Show all messages</a>
                    </div>
                </div>
            </li>
            <!-- END MESSAGES -->
            <!-- TASKS -->
            <li class="xn-icon-button pull-right">
                <a href="#"><span class="fa fa-tasks"></span></a>
                <div class="informer informer-warning">3</div>
                <div class="panel panel-primary animated zoomIn xn-drop-left xn-panel-dragging ui-draggable">
                    <div class="panel-heading ui-draggable-handle">
                        <h3 class="panel-title"><span class="fa fa-tasks"></span> Tasks</h3>
                        <div class="pull-right">
                            <span class="label label-warning">3 active</span>
                        </div>
                    </div>
                    <div class="panel-body list-group scroll mCustomScrollbar _mCS_3 mCS-autoHide mCS_no_scrollbar" style="height: 200px;"><div id="mCSB_3" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0"><div id="mCSB_3_container" class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y" style="position:relative; top:0; left:0;" dir="ltr">
                                <a class="list-group-item" href="#">
                                    <strong>Phasellus augue arcu, elementum</strong>
                                    <div class="progress progress-small progress-striped active">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%;">50%</div>
                                    </div>
                                    <small class="text-muted">John Doe, 25 Sep 2014 / 50%</small>
                                </a>
                                <a class="list-group-item" href="#">
                                    <strong>Aenean ac cursus</strong>
                                    <div class="progress progress-small progress-striped active">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;">80%</div>
                                    </div>
                                    <small class="text-muted">Dmitry Ivaniuk, 24 Sep 2014 / 80%</small>
                                </a>
                                <a class="list-group-item" href="#">
                                    <strong>Lorem ipsum dolor</strong>
                                    <div class="progress progress-small progress-striped active">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100" style="width: 95%;">95%</div>
                                    </div>
                                    <small class="text-muted">John Doe, 23 Sep 2014 / 95%</small>
                                </a>
                                <a class="list-group-item" href="#">
                                    <strong>Cras suscipit ac quam at tincidunt.</strong>
                                    <div class="progress progress-small">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%</div>
                                    </div>
                                    <small class="text-muted">John Doe, 21 Sep 2014 /</small><small class="text-success"> Done</small>
                                </a>
                            </div><div id="mCSB_3_scrollbar_vertical" class="mCSB_scrollTools mCSB_3_scrollbar mCS-light mCSB_scrollTools_vertical" style="display: none;"><div class="mCSB_draggerContainer"><div id="mCSB_3_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 30px; top: 0px;" oncontextmenu="return false;"><div class="mCSB_dragger_bar" style="line-height: 30px;"></div></div><div class="mCSB_draggerRail"></div></div></div></div></div>
                    <div class="panel-footer text-center">
                        <a href="pages-tasks.html">Show all tasks</a>
                    </div>
                </div>
            </li>
            <!-- END TASKS -->
        </ul>
        <!-- START BREADCRUMB -->
        <ul class="breadcrumb">
            @yield('breadcrumb')
        </ul>
        <!-- END BREADCRUMB -->

        <!-- PAGE CONTENT WRAPPER -->
        <div class="page-content-wrap">
            @yield('content')
        </div>
        <!-- END PAGE CONTENT -->
    </div>
    <!-- END PAGE CONTAINER -->

    <!-- MESSAGE BOX-->
    <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
        <div class="mb-container">
            <div class="mb-middle">
                <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
                <div class="mb-content">
                    <p>Are you sure you want to log out?</p>
                    <p>Press No if youwant to continue work. Press Yes to logout current user.</p>
                </div>
                <div class="mb-footer">
                    <div class="pull-right">
                        <a href="{{url('/logout')}}" class="btn btn-success btn-lg">Yes</a>
                        <button class="btn btn-default btn-lg mb-control-close">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END MESSAGE BOX-->
</div>
<!-- START PRELOADS -->
<audio id="audio-alert" src="{{url(elixir("audio/alert.mp3"))}}" preload="auto"></audio>
<audio id="audio-fail" src="{{url(elixir("audio/fail.mp3"))}}" preload="auto"></audio>
<!-- END PRELOADS -->

<!-- START SCRIPTS -->
<!-- START js/plugins -->
<script type="text/javascript" src="{{url(elixir('js/plugins/jquery/jquery.min.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/jquery/jquery-ui.min.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/bootstrap/bootstrap.min.js'))}}"></script>
<!-- END js/plugins -->

<!-- START THIS PAGE js/plugins-->
<script type="text/javascript" src="{{url(elixir('js/plugins/owl/owl.carousel.min.js'))}}"></script>
<script type='text/javascript' src="{{url(elixir('js/plugins/icheck/icheck.min.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/scrolltotop/scrolltopcontrol.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/datatables/jquery.dataTables.min.js'))}}"></script>


<!--<script type="text/javascript" src="{{url(elixir('js/plugins/morris/raphael-min.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/morris/morris.min.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/rickshaw/d3.v3.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/rickshaw/rickshaw.min.js'))}}"></script>
<script type='text/javascript' src="{{url(elixir('js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'))}}"></script>
<script type='text/javascript' src="{{url(elixir('js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'))}}"></script>
<script type='text/javascript' src="{{url(elixir('js/plugins/bootstrap/bootstrap-datepicker.js'))}}"></script>-->


<script type="text/javascript" src="{{url(elixir('js/plugins/moment.min.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/daterangepicker/daterangepicker.js'))}}"></script>

<script type="text/javascript" src="{{url(elixir('js/plugins/bootstrap/bootstrap-select.js'))}}"></script>
<!-- END THIS PAGE js/plugins-->

<!-- START TEMPLATE -->
<script type="text/javascript" src="{{url(elixir('js/plugins.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/actions.js'))}}"></script>

<script type="text/javascript" src="{{url(elixir('js/demo_dashboard.js'))}}"></script>
<!-- END TEMPLATE -->
<!-- END SCRIPTS -->

@yield('script')

</body>
</html>






