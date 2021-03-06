@extends('layouts.base')
@section('title')

@endsection

@section('breadcrumb')
    <li>
        <a href="{{route('home')}}">
            @if(Auth::user() instanceof \App\Admin)
                Admin
            @else
                Hôtesse
            @endif
        </a>
    </li>
@endsection
@section('content')
    @if(Auth::user() instanceof \App\Hotesse)
        <?php $var = \App\Code::where("hotesse_id","=",Auth::id())->where("dispo","=","1")->count(); ?>
        @if($var < 3)
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <p class="text-center">Il y a <strong>{{$var}}</strong> code hôtesse de connecté</p>
                    </div>
                </div>
            </div>
        @endif
    @endif
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <!-- START WIDGET MESSAGES -->
            <div class="widget widget-default widget-item-icon" onclick="location.href='#';">
                <div class="widget-item-left">
                    <span class="fa fa-hourglass-end"></span>
                </div>
                <div class="widget-data">
                    <div class="widget-int num-count">{{$dureeAppel}} min</div>
                    <div class="widget-title">durée d'appel</div>
                </div>
            </div>
            <!-- END WIDGET MESSAGES -->
        </div>
        <div class="col-md-3 col-sm-6">
            <!-- START WIDGET MESSAGES -->
            <div class="widget widget-default widget-item-icon" onclick="location.href='#';">
                <div class="widget-item-left">
                    <span class="fa fa-phone"></span>
                </div>
                <div class="widget-data">
                    <div class="widget-int num-count">{{$nbAppel}}</div>
                    <div class="widget-title">Nombre d'appels</div>
                </div>
            </div>
            <!-- END WIDGET MESSAGES -->
        </div>
        <div class="col-md-3 col-sm-6">
            <!-- START WIDGET MESSAGES -->
            <div class="widget widget-default widget-item-icon" onclick="location.href='#';">
                <div class="widget-item-left">
                    <span class="fa fa-hourglass-half"></span>
                </div>
                <div class="widget-data">
                    <div class="widget-int num-count">@if($nbAppel!=0){{number_format($dureeAppel/$nbAppel,2)}} @else 0 @endif min</div>
                    <div class="widget-title">Durée moyenne d'un appel</div>
                </div>
            </div>
            <!-- END WIDGET MESSAGES -->
        </div>
        <div class="col-md-3 col-sm-6">
            <!-- START WIDGET MESSAGES -->
            <div class="widget widget-default widget-item-icon" onclick="location.href='#';">
                <div class="widget-item-left">
                    <span class="fa fa-money"></span>
                </div>
                <div class="widget-data">
                    <div class="widget-int num-count">{{$ca}} €</div>
                    <div class="widget-title">Chiffre d'affaire</div>
                </div>
            </div>
            <!-- END WIDGET MESSAGES -->
        </div>
    </div>
    <!-- END WIDGETS -->
    <div class="row">
        <div class="col-md-{{$hotesses != null ? "9":"12"}}">
            <div class="panel panel-default">
                <div class="panel-heading">

                    <div class="col-md-5"><input type="text" id="debut" class="form-control datepicker" value="{{date_format(date_create($debut), 'Y-m-d')}}"></div>
                    <div class="col-md-5"><input type="text" id="fin" class="form-control datepicker" value="{{date_format(date_create($fin), 'Y-m-d')}}"></div>

                    <div class="col-md-2">
                        <button class="btn btn-primary" id="periode">valider</button>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table datatable" data-page-length="25" data-order="[[2, 'asc' ]]">
                        <thead>
                        <tr>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Durée</th>
                            <th>Appelant</th>
                            <th>Code</th>
                            @if(Auth::guard('web_admin')->check())<th>Client</th>@endif
                            @if(Auth::guard('web_admin')->check())<th>Hôtesse</th>@endif
                            <th>Enregistrement</th>
                            @if(Auth::user() instanceof \App\Hotesse)<th>Envoyer un message</th>@endif
                            <th>CA</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($appels as $appel)
                            <tr>
                                <td>{{date_format(date_create($appel->debut), 'd/m/Y H:i:s')}}</td>
                                <td>{{date_format(date_create($appel->fin), 'd/m/Y H:i:s')}}</td>
                                <td>{{date_diff(date_create($appel->debut),date_create($appel->fin))->format('%I:%S')}}</td>
                                <td>@if(Auth::guard('web')->check() && $appel->appellant != "ANONYME"){{substr($appel->appellant,0,5).'*****'}}@else{{$appel->appellant}}@endif </td>
                                <td>@isset($appel->getcode)<a href="{{route("getUpdateCode",["id"=>$appel->getcode])}}">{{$appel->getcode->pseudo}} ({{$appel->code}})</a>@endisset</td>
                                @if(Auth::user() instanceof \App\Admin)<td>@isset($appel->getClient)<a href="{{route("getClient",["id"=>$appel->getClient->id])}}">{{$appel->getClient->code}}</a>@endisset</td>@endif
                                @if(Auth::guard('web_admin')->check())<td>@isset($appel->hotesse)<a href="{{route("hotesseAdmin",["id"=>$appel->hotesse->id])}}">{{$appel->hotesse->name}}</a>@endisset</td>@endif
                                <td><button class="btn btn-primary btn-rounded" @if($appel->file == "NULL") disabled @else id="btn-{{$appel->file}}" @endif><i class="fa fa-play" onclick="play('{{$appel->file}}')"></i></button></td>
                                @if(Auth::user() instanceof \App\Hotesse)<td><a href="{{route("newMessage",["appel"=>$appel->id])}}" role="button" class="btn btn-primary btn-rounded @if($appel->appellant == "ANONYME") disabled @endif" ><i class="fa fa-comment"></i></a></td>@endif
                                <td>{{$appel->ca}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END DEFAULT DATATABLE -->
        </div>
        @if($hotesses != null)
            <div class="col-md-3">
                <div class="panel panel-default"  style="max-height: 46vh; overflow: auto;">
                    <div class="panel-heading ui-draggable-handle">
                        <h3 class="panel-title">@if(isset($hotesses[0]) && $hotesses[0] instanceof \App\Code) Codes @else Hôtesses @endif connectés</h3>
                    </div>
                    <div class="panel-body list-group list-group-contacts">
                        @foreach ($hotesses as $hotesse)
                            @if($hotesse instanceof \App\Hotesse)
                                <a href="{{route("getUpdateHotesse",["id"=>$hotesse->id])}}" class="list-group-item">
                                    <div class="list-group-status  status-online"></div>
                                    <img src="@isset($hotesse->photo->file){{url(elixir("images/catalog/".$hotesse->photo->file))}} @endisset" class="pull-left" alt="{{$hotesse->name}}" title="{{$hotesse->name}}">
                                    <span class="contacts-title">{{$hotesse->name}}</span>
                                    <p></p><br/>
                                </a>
                            @else
                                <a href="{{route("getUpdateCode",["id"=>$hotesse->code])}}" class="list-group-item">
                                    <div class="list-group-status  status-online"></div>
                                    <img src="@isset($hotesse->getPhoto->file){{url(elixir("images/catalog/".$hotesse->getPhoto->file))}} @endisset" class="pull-left" alt="{{$hotesse->name}}" title="{{$hotesse->name}}">
                                    <span class="contacts-title">{{$hotesse->pseudo}}</span>
                                    <p></p><br/>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                @foreach ($appels as $appel)
                    @if($appel->file != "NULL" && $appel->file != null)
                        <audio id="audio-{{$appel->file}}" src="{{url(elixir("audio/log_appel/".$appel->file.".mp3"))}}" onended="stop('{{$appel->file}}')"></audio>
                    @endif
                @endforeach
            </div>
    </div>
@endsection
@section("script")
    <script>
        $("#periode").click(function () {
            d =new Date($("#debut").val());
            f =new Date($("#fin").val());
            @if($debut == null)
                window.location.href+="/"+formatDate(d)+"/"+formatDate(f)
                    @else
            var href = window.location.href.split("/");
            newHref="";
            for(i=0;i<href.length-2;i++)
                newHref+=href[i]+"/"
            window.location.href=newHref+formatDate(d)+"/"+formatDate(f)
            @endif
        })

        function formatDate(d) {
            return d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate();
        }


        $('.datatable').DataTable({
            "order": [[ 0, 'desc' ]]
        });
    </script>
@endsection
