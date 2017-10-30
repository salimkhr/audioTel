@extends('layouts.base')
@section('title')

@endsection

@section('breadcrumb')
    <li>
        <a href="{{route('home')}}">
            @if(Auth::user() instanceof \App\Admin)
                Admin
            @else
                Hotesse
            @endif
        </a>
    </li>
@endsection
@section('content')
    @if(Auth::user() instanceof \App\Hotesse && \App\Hotesse::where("co","=","1")->count() < 3)
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <p class="text-center">Il y a <strong>{{\App\Hotesse::where("co","=","1")->count()}}</strong> hotesse de connecté</p>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <!-- START WIDGET MESSAGES -->
            <a href="{{route("tchat")}}">
                <div class="widget widget-default widget-item-icon" onclick="location.href='#';">
                    <div class="widget-item-left">
                        <span class="fa fa-envelope"></span>
                    </div>
                    <div class="widget-data">
                        <div class="widget-int num-count wiget-message" >{{$nbmsg}}</div>
                        <div class="widget-title">Nouveaux messages</div>
                        <div class="widget-subtitle">dans la tchat room</div>
                    </div>
                </div>
            </a>
            <!-- END WIDGET MESSAGES -->
        </div>
        <div class="col-md-3 col-sm-6">

            <!-- START WIDGET REGISTRED -->
            <div class="widget @if($nbHotesseCo >2) widget-default  @else widget-danger @endif widget-item-icon" onclick="location.href='#';"><!---->
                <div class="widget-item-left">
                    <span class="fa fa-user"></span>
                </div>
                <div class="widget-data">
                    <div class="widget-int num-count">{{$nbHotesseCo}}</div>
                    <div class="widget-title">Hôtesse</div>
                    <div class="widget-subtitle">connectées en ce moment</div>
                </div>
            </div>
            <!-- END WIDGET REGISTRED -->

        </div>
        <div class="col-md-3 col-sm-6">
            <div class="widget widget-default widget-carousel">
                <div class="owl-carousel" id="owl-example">
                    <div>
                        <div class="widget-title">Nombre de minutes</div>
                        <div class="widget-subtitle">Aujourd'hui</div>
                        <div class="widget-int">{{$dureeAppel}}</div>
                    </div>
                    <div>
                        <div class="widget-title">Nombre d'appels</div>
                        <div class="widget-subtitle">Aujourd'hui</div>
                        <div class="widget-int">{{$nbAppel}}</div>
                    </div>
                    <div>
                        <div class="widget-title">Moyenne minutes</div>
                        <div class="widget-subtitle"> </div>
                        <div class="widget-int">@if($dureeAppel != 0) {{number_format($nbAppel/$dureeAppel,2)}} @else 0 @endif</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">

            <!-- START WIDGET CLOCK -->
            <div class="widget widget-info">
                <div class="widget-big-int plugin-clock">00:00</div>
                <div class="widget-subtitle plugin-date">Loading...</div>
            </div>
            <!-- END WIDGET CLOCK -->

        </div>
    </div>
    <div class="row">
        <div class="col-md-{{$hotesses != null ? "9":"12"}}">
            <div class="panel panel-default">
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
                                <td>@isset($appel->code)<a href="{{route("getUpdateCode",["id"=>$appel->getcode])}}">{{$appel->getcode->pseudo}} ({{$appel->code}})</a>@endisset</td>
                                @if(Auth::user() instanceof \App\Admin)<td>@isset($appel->getClient)<a href="{{route("getClient",["id"=>$appel->getClient->id])}}">{{$appel->getClient->code}}</a>@endisset</td>@endif
                                @if(Auth::guard('web_admin')->check())<td>@isset($appel->hotesse)<a href="{{route("hotesseAdmin",["id"=>$appel->hotesse->id])}}">{{$appel->hotesse->name}}</a>@endisset</td>@endif
                                <td><button class="btn btn-primary btn-rounded" @if($appel->file == "NULL") disabled @else id="btn-{{$appel->file}}" @endif><i class="fa fa-play" onclick="play('{{$appel->file}}')"></i></button></td>
                                <td>@isset($appel->tarif->prixMinute){{(date_diff(date_create($appel->debut),date_create($appel->fin))->format('%i')*$appel->tarif->prixMinute)." €"}}@else NC @endisset</td>
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
                        <h3 class="panel-title">Hôtesses connectées</h3>
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
                    @if($appel->file != "NULL" && $appel->file != null )
                        <audio id="audio-{{$appel->file}}" src="{{url(elixir("audio/log_appel/".$appel->file.".mp3"))}}" onended="stop('{{$appel->file}}')"></audio>
                    @endif
                @endforeach
            </div>
    </div>
@endsection
@section("script")
    <script>
        $('.datatable').DataTable({
            "order": [[ 0, 'desc' ]]
        });
    </script>
@endsection
