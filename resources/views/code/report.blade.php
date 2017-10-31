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
        </a>
    </li>
    <li class="active">
        {{$code->pseudo}}
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
                    <div class="widget-int num-count">@if($nbAppel!=0){{number_format($dureeAppel/$nbAppel,2)}} @else 0 @endif</div>
                    <div class="widget-title">durée moyenne d'un appel</div>
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
        <div class="col-md-3">

            <div class="panel panel-default">
                <div class="panel-body profile">
                    <div class="profile-image">
                        <a href="@isset($code->getPhoto){{url(elixir("images/catalog/".$code->getPhoto->file))}}@else{{url(elixir("images/catalog/noImage.jpg"))}}@endisset"> <img src="@isset($code->getPhoto){{url(elixir("images/catalog/".$code->getPhoto->file))}}@else{{url(elixir("images/catalog/noImage.jpg"))}}@endisset" alt="{{$code->pseudo}}"> </a>
                    </div>
                    <div class="profile-data">
                        <div class="profile-data-name">{{$code->code}}</div>
                        <div class="profile-data-title">{{$code->pseudo}}</div>
                    </div>
                    <div class="profile-controls">
                        <a href="#" class="profile-control-right @if($code->annonce == null) disabled @endif mb-control" @if($code->annonce != null) id="btn-{{$code->annonce->id}}" onclick="play({{$code->annonce->id}})"@endif ><i class="fa fa-play"></i></a>
                        @if($code->annonce != null)
                            <audio id="audio-{{$code->annonce->id}}" src="{{url(elixir("audio/annonce/".$code->annonce->file.".mp3"))}}" onended="stop({{$code->annonce->id}})"></audio>
                        @endif
                    </div>
                </div>
                <div class="panel-body border-bottom">
                    <strong>Age</strong>
                    <p>{{$code->age}}</p>
                    <strong>Description</strong>
                    <p>{{$code->description}}</p>
                </div>
                <div class="panel-body list-group border-bottom">
                    <a href="{{route('getUpdateCode',["id"=>$code->code])}}" class="list-group-item"><span class="fa fa-pencil"></span> @if(Auth::user() instanceof \App\Admin)Modifier @else Modifier l'annonce @endif</a>
                    @if(Auth::user() instanceof \App\Admin)<a href="{{route('activeCode',["id"=>$code->code])}}" class="list-group-item"><span class="fa fa-ban"></span> {{(($code->dispo === 0)?"Déconnecté":"").(($code->dispo === 1)?"Connecté":"").(($code->dispo === 2)?"En ligne":"")}}</a>
                    <a href="{{route('bockCode',["id"=>$code->code])}}" class="list-group-item"><span class="fa fa-lock"></span> {{($code->active)?"Activé":"Désactivé"}}</a>
                    <a href="#" class="list-group-item mb-control" data-box="#message-box-delete"><span class="fa fa-trash-o"></span> Supprimer</a> @endif
                </div>

            </div>

        </div>
        <div class="col-md-9 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">

                    <div class="col-md-5"><input id="debut" class="form-control datepicker" value="{{date_format(date_create($debut), 'Y-m-d')}}"></div>
                    <div class="col-md-5"><input id="fin" class="form-control datepicker" value="{{date_format(date_create($fin), 'Y-m-d')}}"></div>

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
                            @if(Auth::guard('web_admin')->check())<th>Client</th>@endif
                            @if(Auth::guard('web_admin')->check())<th>Hôtesse</th>@endif
                            <th>Enregistrement</th>
                            <th>Envoyer un message</th>
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
                                @if(Auth::user() instanceof \App\Admin)<td>@isset($appel->client)<a href="{{route("getClient",["id"=>$appel->client->id])}}">{{$appel->client->code}}</a>@endisset</td>@endif
                                @if(Auth::guard('web_admin')->check())<td>@isset($appel->hotesse)<a href="{{route("hotesseAdmin",["id"=>$appel->hotesse->id])}}">{{$appel->hotesse->name}}</a>@endisset</td>@endif
                                <td><button class="btn btn-primary btn-rounded" @if($appel->file == "NULL") disabled @else id="btn-{{$appel->file}}" @endif><i class="fa fa-play" onclick="play('{{$appel->file}}')"></i></button></td>
                                <td><a href="" role="button" class="btn btn-primary btn-rounded" ><i class="fa fa-comment"></i></a></td>
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
                        <a href="{{route('deleteCode',['id'=> $code->code])}}" class="btn btn-success btn-lg">Yes</a>
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