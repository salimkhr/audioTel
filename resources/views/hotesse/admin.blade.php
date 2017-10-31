@extends('layouts.base')

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
    <li class="active">
        {{$hotesse->name}}
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
        <div class="col-md-3">

            <div class="panel panel-default">
                <div class="panel-body profile">
                    <div class="profile-image">
                        <img src="{{url(elixir("images/catalog/".$hotesse->photo->file))}}" alt="{{$hotesse->name}}">
                    </div>
                    <div class="profile-data">
                        <div class="profile-data-name">{{$hotesse->name}}</div>
                        <div class="profile-data-title">{{($hotesse->co)?"Connecté":"Déconnecté"}}</div>
                    </div>
                </div>
                <div class="panel-body list-group border-bottom">
                    <a href="{{route('getUpdateHotesse',["id"=>$hotesse->id])}}" class="list-group-item"><span class="fa fa-pencil"></span> Modifier</a>
                    <a href="{{route('activeHotesse',["id"=>$hotesse->id])}}" class="list-group-item"><span class="fa fa-ban"></span> {{$hotesse->active?"Désactiver":"Activer"}}</a>
                    <a href="#" class="list-group-item mb-control" data-box="#message-box-delete"><span class="fa fa-trash-o"></span> Supprimer</a>
                </div>
                <div class="panel-body">
                    <h4 class="text-title">Code</h4>
                    <div class="row">
                        @foreach($hotesse->code as $code)
                        @if($loop->iteration %3==1)
                        <div class="row">

                        </div>
                            @endif
                        <div class="col-md-4 col-xs-4">
                            <a href="{{route("reportingCode",["id"=>$code->code])}}" class="friend">
                                <img src="@if($code->getPhoto != null){{url(elixir("images/catalog/".$code->getPhoto->file))}}@else {{url(elixir("images/catalog/noImage.jpg"))}} @endif">
                                <span>{{$code->pseudo." (".$code->code.")"}}</span>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-9 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">

                    <div class="col-md-5"><input type="text" id="debut" class="form-control datepicker" value="{{date_format(date_create($debut), 'Y-m-d')}}"></div>
                    <div class="col-md-5"><input type="text" id="fin" class="form-control datepicker" value="{{date_format(date_create($fin), 'Y-m-d')}}"></div>

                    <div class="col-md-2">
                        <button class="btn btn-primary" id="periode">valider</button>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Durée</th>
                            <th>Appelant</th>
                            <th>Code</th>
                            @if(Auth::guard('web_admin')->check())<th>Client</th>@endif
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
                                <td>@isset($appel->code)<a href="{{route("reportingCode",["id"=>$appel->getcode])}}">{{$appel->getcode->pseudo}} ({{$appel->code}})</a>@endisset</td>
                                @if(Auth::user() instanceof \App\Admin)<td>@isset($appel->client)<a href="{{route("getClient",["id"=>$appel->client->id])}}">{{$appel->client->code}}</a>@endisset</td>@endif
                                <td><button class="btn btn-primary btn-rounded" @if($appel->file == "NULL") disabled @else id="btn-{{$appel->file}}" @endif><i class="fa fa-play" onclick="play('{{$appel->file}}')"></i></button></td>
                                <td>{{$appel->ca}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END DEFAULT DATATABLE -->
        </div>
    </div>
    @foreach ($appels as $appel)
        @if($appel->file != "NULL" && $appel->file!=null)
            <audio id="audio-{{$appel->file}}" src="{{url(elixir("audio/log_appel/".$appel->file.".mp3"))}}" onended="stop('{{$appel->file}}')"></audio>
        @endif
    @endforeach
    <div class="message-box message-box-danger animated fadeIn" data-sound="alert" id="message-box-delete">
        <div class="mb-container">
            <div class="mb-middle">
                <div class="mb-title"><span class="fa fa-trash-o"></span> <strong>Supprimer</strong> ?</div>
                <div class="mb-content">
                    <p>êtes-vous sûr de vouloir supprimer l'hôtesse</p>
                    <p>Appuyez sur Non si vous souhaitez continuer votre travail. Appuyez sur Oui pour supprimer l'hôtesse.</p>
                </div>
                <div class="mb-footer">
                    <div class="pull-right">
                        <a href="{{route('deleteHotesse',['id'=> $hotesse->id])}}" class="btn btn-success btn-lg">Yes</a>
                        <button class="btn btn-default btn-lg mb-control-close">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! $errors->first('err','<div class="message-box message-box-danger animated fadeIn open" data-sound="fail" id="message-box-delete-err">
       <div class="mb-container">
           <div class="mb-middle">
               <div class="mb-title"><span class="fa fa-times"></span> ERREUR!</div>
               <div class="mb-content">
                   <p>:message</p>
               </div>
               <div class="mb-footer">
                   <button class="btn btn-default btn-lg pull-right mb-control-close">Close</button>
               </div>
           </div>
       </div>
   </div>')!!}
@endsection
@section('script')
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