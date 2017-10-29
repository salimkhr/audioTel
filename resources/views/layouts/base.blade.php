@section('menu')
    @if(Auth::user() instanceof \App\Admin)<li @if(Request::segment(1) === 'hotesse')class="active" @endif><a href="{{route("hotesse")}}"><span class="fa fa-users"></span> <span class="xn-text">Hôtesse</span></a></li>@endif
    <li @if(Request::segment(1) === 'code')class="active" @endif><a href="{{route("code")}}"><span class="fa fa-list-ol"></span> <span class="xn-text">Code</span></a></li>
    <li @if(Request::segment(1) === 'reporting')class="active" @endif><a href="{{route("home")}}"><span class="fa fa-table"></span> <span class="xn-text">Reporting Général</span></a></li>
    @if(Auth::user() instanceof \App\Admin)<li @if(Request::segment(1) === 'activite')class="active" @endif><a href="{{route("activite")}}"><span class="fa fa-table"></span> <span class="xn-text">Activité</span></a></li>@endif
    <li @if(Request::segment(1) === 'message')class="active" @endif><a href="{{route("message")}}"><span class="fa fa-commenting"></span> <span class="xn-text">Message</span></a></li>
    <li @if(Request::segment(1) === 'tchat')class="active" @endif><a href="{{route("tchat.general")}}"><span class="fa fa-comments"></span> <span class="xn-text">Tchat Room</span><span class="pull-right nbMessage"></span></a></li>
    @if(Auth::user() instanceof \App\Admin)<li @if(Request::segment(1) === 'client')class="active" @endif><a href="{{route("client")}}"><span class="fa fa-user-circle"></span> <span class="xn-text">Client</span></a></li>@endif

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
    <title>Opiumlove - Interface de gestion audiotel</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <!-- END META SECTION -->

    <!-- CSS INCLUDE -->
    <link rel="stylesheet" type="text/css" id="theme" href="{{url(elixir('css/theme-blue.css'))}}"/>
    <link rel="stylesheet" type="text/css" id="theme" href="{{url(elixir('css/style.css'))}}"/>
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
                <a href="{{route("home")}}">Opium love</a>
                <a href="#" class="x-navigation-control"></a>
            </li>
            <li class="xn-profile">
                <a href="@if(Auth::user() instanceof \App\Admin){{route("getUpdateAdmin",["id"=>Auth::id()])}} @else{{route("getUpdateHotesse",["id"=>Auth::id()])}}@endif" class="profile-mini">
                    <img src="{{url(elixir("images/catalog/".Auth::user()->photo->file))}}" alt="{{Auth::user()->name}}">
                </a>
                <div class="profile">
                    <div class="profile-image">
                        <a href="@if(Auth::user() instanceof \App\Admin){{route("getUpdateAdmin",["id"=>Auth::id()])}} @else{{route("getUpdateHotesse",["id"=>Auth::id()])}}@endif">
                            <img src="{{url(elixir("images/catalog/".Auth::user()->photo->file))}}" alt="{{Auth::user()->name}}">
                        </a>
                    </div>
                    <div class="profile-data">
                        <div class="profile-data-name">@isset(Auth::user()->name)<a href="@if(Auth::user() instanceof \App\Admin){{route("getUpdateAdmin",["id"=>Auth::id()])}} @else{{route("getUpdateHotesse",["id"=>Auth::id()])}}@endif">
                                <span style="color:white">{{Auth::user()->name}}</span> </a>@endisset</div>
                        <div class="profile-data-title"></div>
                    </div>
                    <div class="profile-controls">
                        <a href="@if(Auth::user() instanceof \App\Admin){{route("getUpdateAdmin",["id"=>Auth::id()])}} @else{{route("getUpdateHotesse",["id"=>Auth::id()])}}@endif" class="profile-control-left"><span class="fa fa-info"></span></a>
                        <a href="{{route("tchat.general")}}" class="profile-control-right"><span class="fa fa-envelope"></span></a>
                    </div>
                </div>
            </li>
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

            <!-- SIGN OUT -->
            <li class="xn-icon-button pull-right">
                <a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span></a>
            </li>
            <!-- END SIGN OUT -->
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
                <div class="mb-title"><span class="fa fa-sign-out"></span><strong>Déconnexion</strong> ?</div>
                <div class="mb-content">
                    <p>Êtes-vous sûr de vouloir vous déconnecter ?</p>
                    <p>Appuyez sur Non si vous souhaitez continuer à travailler. Appuyez sur Oui pour vous déconnecter.</p>
                </div>
                <div class="mb-footer">
                    <div class="pull-right">
                        <a href="{{url('/logout')}}" class="btn btn-success btn-lg">Oui</a>
                        <button class="btn btn-default btn-lg mb-control-close">Non</button>
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
<script type="text/javascript" src="{{url(elixir('js/plugins/intl-tel-input-master/data.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/intl-tel-input-master/intlTelInput.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/blueimp/jquery.blueimp-gallery.min.js'))}}"></script>


<!--<script type="text/javascript" src="{{url(elixir('js/plugins/morris/raphael-min.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/morris/morris.min.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/rickshaw/d3.v3.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/rickshaw/rickshaw.min.js'))}}"></script>
<script type='text/javascript' src="{{url(elixir('js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'))}}"></script>
<script type='text/javascript' src="{{url(elixir('js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'))}}"></script>
<script type='text/javascript' src="{{url(elixir('js/plugins/bootstrap/bootstrap-datepicker.js'))}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>-->


<script type="text/javascript" src="{{url(elixir('js/plugins/moment.min.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/bootstrap/bootstrap-datepicker.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/rangeslider/jQAllRangeSliders-min.js'))}}"></script>

<script type="text/javascript" src="{{url(elixir('js/plugins/bootstrap/bootstrap-select.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/fileinput/fileinput.min.js'))}}"></script>

<!-- END THIS PAGE js/plugins-->

<!-- START TEMPLATE -->
<script type="text/javascript" src="{{url(elixir('js/plugins/noty/jquery.noty.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/noty/layouts/topRight.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins/noty/themes/default.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/plugins.js'))}}"></script>
<script type="text/javascript" src="{{url(elixir('js/actions.js'))}}"></script>

<script type="text/javascript" src="{{url(elixir('js/demo_dashboard.js'))}}"></script>

<!-- END TEMPLATE -->
<!-- END SCRIPTS -->

@if (Session::has('message')) {
<script>
    noty({text: '{{Session::get('message')}}', layout: 'topRight', type: 'success'});
</script>
@endif
<script>
    function play(file) {
        console.log($("#btn-"+file+" i"));
        console.log($("#audio-"+file).get(0));

        if($("#btn-"+file+" i").hasClass("fa-pause"))
        {
            $("#btn-"+file+" i").addClass("fa-play").removeClass("fa-pause");
            $("#audio-"+file).get(0).pause();
        }
        else{
            $("#btn-"+file+" i").removeClass("fa-play").addClass("fa-pause");
            $("#audio-"+file).get(0).play();
        }

    }

    function stop(file) {
        $("#btn-"+file+" i").addClass("fa-play").removeClass("fa-pause");
    }

    var ancienNbMessage =0;
    nbMessage();

    window.setInterval(function(){
        nbMessage();
    }, 1000);

    function nbMessage() {

        @if(Request::route()->getName() == "tchat" || Request::route()->getName() == "tchat.show")
        $.ajax({
            @if(Auth::user() instanceof \App\Hotesse)
                url: "{{route("tchat.updateMessage")}}",				// url du fichier php
            @else
                url: "{{route("tchat.updateMessage",["idHotesse"=>$id])}}",				// url du fichier php
            @endif

            type: "GET",			                // Type d'envoi des données (Soit GET ou POST)
            success: function(html, statut)			// html contient le résultat du script php
            {
                console.log(html);
                for(message in html )
                    $(".messages").append("<div class='item  item-visible'>" +
                        "   <div class='image'>" +
                        "       <img src='"+html[message].img+"' alt='"+html[message].name+"'>" +
                        "   </div>" +
                        "   <div class='text'>" +
                        "       <div class='heading'>" +
                        "       <a href='#'>"+html[message].name+"</a>" +
                        "       <span class='date'>"+html[message].date+"</span>" +
                        "   </div>" +
                        "   <span class='message'>"+replaceEmoticons(html[message].message)+"</span>" +
                        "   </div>" +
                        "</div>");
            }
        });
        @endif

        @if(Request::route()->getName() == "tchat.general")
        $.ajax({
            url: "{{route("tchat.updateMessageGeneral")}}",				// url du fichier php
            type: "GET",			                // Type d'envoi des données (Soit GET ou POST)
            success: function(html, statut)			// html contient le résultat du script php
            {
                console.log(html);
                for(message in html )
                    $(".messages").append("<div class='item item-visible'>" +
                        "   <div class='image'>" +
                        "       <img src='"+html[message].img+"' alt='"+html[message].name+"'>" +
                        "   </div>" +
                        "   <div class='text'>" +
                        "       <div class='heading'>" +
                        "       <a href='#'>"+html[message].name+"</a>" +
                        "       <span class='date'>"+html[message].date+"</span>" +
                        "   </div>" +
                        "   <span class='message'>"+replaceEmoticons(html[message].message)+"</span>" +
                            @if(Request::route()->getName() == "tchat.general" && Auth::user() instanceof \App\Admin)"<a href='{{route("tchat.delete",["id"=>$tchat->id])}}' class='pull-right btn btn-rounded btn-primary'><i class='fa fa-fw fa-trash'></i></a>"+@endif

                "   </div>" +
                        "</div>");
            }
        });
        @endif

        $.ajax({
            url: "{{route("tchat.nbmessage")}}",				// url du fichier php
            type: "GET",			                // Type d'envoi des données (Soit GET ou POST)
            success: function(html, statut)			// html contient le résultat du script php
            {
                console.log(html);
                if(html != 0)
                    $(".nbMessage").html('<span class="badge badge-danger">'+html+'</span>');
                else if(ancienNbMessage != html)
                {
                    ancienNbMessage = html;
                    $(".nbMessage").html('');
                }
                $(".wiget-message").text(html);
            }
        });
    }

</script>
@yield('script')

</body>
</html>






