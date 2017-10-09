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
    <li>
        <a href="{{route('home')}}">
            @if(Auth::user() instanceof \App\Admin)
                Admin
            @else
                Hotesse
            @endif
            {{dump(Auth::user())}}
        </a>
    </li>
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
                    <div class="widget-int num-count">@if($nbAppel!=0){{$dureeAppel/$nbAppel}} @else 0 @endif</div>
                    <div class="widget-title">durée moyenne d'un appel</div>
                    <div class="widget-subtitle">Aujourd'hui</div>
                </div>
            </div>
            <!-- END WIDGET MESSAGES -->
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
    <!-- END WIDGETS -->
    <div class="row">
        <div class="col-md-9 col-sm-12">
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
                                <td>@isset($appel->tarif->prixMinute){{date_diff(date_create($appel->debut),date_create($appel->fin))->format('%i')*$appel->tarif->prixMinute}}@endisset</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <a href="{{route('getUpdateHotesse',["id"=>$hotesse->id])}}" role="button" class="btn btn-primary pull-right">Modifier</a>
                </div>
            </div>
            <!-- END DEFAULT DATATABLE -->
        </div>
        <div class="col-md-3 col-sm-12">
            <!-- CONTACT ITEM -->
            <div class="panel panel-default">
                <div class="panel-body profile">
                    <div class="profile-image">
                        <img src="{{url(elixir("images/catalog/".$hotesse->photo->file))}}" alt="{{$hotesse->name}}" title="{{$hotesse->name}}">
                    </div>
                    <div class="profile-data">
                        <div class="profile-data-name">{{$hotesse->name}}</div>
                        <div class="profile-data-title">{{($hotesse->co)?"Connecté":"Déconnecté"}}</div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="contact-info">
                        <p><small>Mobile</small><br>{{$hotesse->tel}}</p>
                        <p><small>Dérniere connection</small><br>{{date_format(date_create($hotesse->derniere_connection), 'd/m/Y H:i:s')}}</p>
                        <p><small>Code</small><br><a href="{{route("codeHotesse",["id"=>$hotesse->id])}}">Liste des codes hôtesse</a></p>
                    </div>
                </div>
            </div>

            <!-- END CONTACT ITEM -->
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