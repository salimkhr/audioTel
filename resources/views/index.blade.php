@extends('layouts.base')
@section('title')
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

@section('breadcrumb')
    <li><a href="{{route('home')}}">Admin</a></li>
@endsection
@section('content')
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
                    <div class="widget-subtitle">Aujourd'hui</div>
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
                    <div class="widget-title">nombre d'appel</div>
                    <div class="widget-subtitle">Aujourd'hui</div>
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
                    <div class="widget-int num-count">@if($nbAppel!=0){{$dureeAppel/$nbAppel}} @else 0 @endif min</div>
                    <div class="widget-title">durée moyenne d'un appel</div>
                    <div class="widget-subtitle">Aujourd'hui</div>
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
                    <div class="widget-subtitle">Aujourd'hui</div>
                </div>
            </div>
            <!-- END WIDGET MESSAGES -->
        </div>
    </div>
    <!-- END WIDGETS -->
    <div class="row">
        <div class="col-sm-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th>début</th>
                            <th>fin</th>
                            <th>Durée</th>
                            <th>appelant</th>
                            <th>appelé</th>
                            {{Auth::guard('web_admin')->check()}}
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
                                <td>@if(Auth::guard('web')->check() && $appel->appellant != "ANONYME"){{substr($appel->appellant,0,4).'******'}}@else{{$appel->appellant}}@endif </td>
                                <td>@isset($appel->hotesse){{$appel->hotesse->tel}}@endisset</td>
                                @if(Auth::guard('web_admin')->check())<td>@isset($appel->hotesse)<a href="{{route("getHotesse",["id"=>$appel->hotesse->id])}}">{{$appel->hotesse->name}}</a>@endisset</td>@endif
                                <td><button class="btn btn-success" @if($appel->file == "NULL") disabled @else id="btn-{{$appel->file}}" @endif><i class="fa fa-play" onclick="play('{{$appel->file}}')"></i></button></td>
                                <td>@isset($appel->tarif->prixMinute){{(date_diff(date_create($appel->debut),date_create($appel->fin))->format('%i')*$appel->tarif->prixMinute)." €"}}@else NC @endisset</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <a href="{{route('getUpdateHotesse',["id"=>Auth::id()])}}" role="button" class="btn btn-primary pull-right">Modifier</a>
                </div>
            </div>
            <!-- END DEFAULT DATATABLE -->
        </div>
        <div class="col-sm-3">
            <!-- START CONTENT FRAME RIGHT -->
            <div class="list-group list-group-contacts border-bottom push-down-10">
                @foreach ($hotesses as $hotesse)
                    <a href="#" class="list-group-item">
                        <div class="list-group-status
                        @switch($hotesse->co)
                        @case(1)
                                status-away
@break
                        @case(2)
                                status-online
@break
                        @case(3)
                                status-away
@break
                        @default
                                status-offline
@endswitch">
                        </div>
                        <img src="assets/images/users/user.jpg" class="pull-left" alt="{{$hotesse["name"]}}" title="{{$hotesse["name"]}}">
                        <span class="contacts-title">{{$hotesse["name"]}}</span>
                        <p> @foreach ($hotesse->code as $code){{$code->code}} @endforeach</p>
                    </a>
                @endforeach
            </div>

            <div class="block">
                <h4>Status</h4>
                <div class="list-group list-group-simple">
                    <a href="#" class="list-group-item"><span class="fa fa-circle text-success"></span> Online</a>
                    <a href="#" class="list-group-item"><span class="fa fa-circle text-warning"></span> Away</a>
                    <a href="#" class="list-group-item"><span class="fa fa-circle text-muted"></span> Offline</a>
                </div>
            </div>
            <!-- END CONTENT FRAME RIGHT -->
        </div>
    </div>
    @foreach ($appels as $appel)
        @if($appel->file != "NULL")
            <audio id="audio-{{$appel->file}}" src="{{url(elixir("audio/log_appel/".$appel->file.".mp3"))}}" onended="stop('{{$appel->file}}')"></audio>
        @endif
    @endforeach
@endsection
@section('script')
    <script>
        function play(file) {
            console.log($("#"+file));
            $("#btn-"+file).prop('disabled', true);
            $("#audio-"+file).get(0).play();
        }

        function stop(file) {
            console.log($("#"+file));
            $("#btn-"+file).prop('disabled', false);
        }
    </script>
@endsection