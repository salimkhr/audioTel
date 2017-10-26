@extends('layouts.base')
@section('title')Admin @endsection

@section('breadcrumb')
    <li>
        <a href="{{route('home')}}">
            @if(Auth::guard("web_admin")->id())
                Admin
            @else
                Hotesse
            @endif
        </a>
    </li>
    <li class="active">Ajout d'une cle api</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    Log de l'API {{$api->id}}
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover datatable">
                        <thead>
                        <tr>
                            <th>Id de l’accès</th>
                            <th>Date</th>
                            <th>Ip capturée</th>
                            <th>Route</th>
                            <th>Méthode</th>
                            <th>Résultat</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($listAcces as $acces)
                            <tr>
                                <td>{{$acces->id}}</td>
                                <td>{{date_format(date_create($acces->created_at), 'd/m/Y H:i:s')}}</td>
                                <td>{{$acces->IP}}</td>
                                <td>{{parse_url($acces->url)["path"]}}</td>
                                <td>{{$acces->methode}}</td>
                                <td> @if($acces->methode == "POST") {{$acces->resultat===0?"failed":"success"}} @endif</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    information de l'API {{$api->id}}
                </div>
                {{ Form::model($api, array('route' => array('postUpdateAPI', $api->id)))}}
                <div class="panel-body list-group">
                    <span class="list-group-item"><span class="fa fa-unlock"></span>{{$api->active?"activé":"désactivé"}}</span>
                    <span class="list-group-item"><span class="fa fa-key"></span>{{$api->cle}}</span>
                    <span class="list-group-item">
                        <span class="row">
                            <span class="col-md-8">
                            {{ Form::text('name',$api->name, array('class' => 'form-control','placeholder'=>'name')) }}
                                {!! $errors->first('cle', '<small class="help-block">:message</small>') !!}
                            </span>
                            <span class="col-md-4">
                                 {!! Form::submit('Valider', ['class' => 'btn btn-primary']) !!}
                            </span>
                        </span>
                    </span>
                    <span class="list-group-item"><span class="fa fa-calendar"></span>{{date_format(date_create($api->created_at), 'd/m/Y H:i:s')}}</span>
                </div>
                <div class="panel-footer">
                    <div class="pull-right">
                        <a href="#" role="btn" class="btn btn-warning mb-control" data-box="#message-box-regenere">Régénérer la clé</a>
                        <a href="{{route('activeAPI',['id'=> $api->id])}}" type="button" class="btn btn-warning">@if($api->active)Desactiver @else Activer @endif</a>
                        <button type="button" class="btn btn-danger mb-control" data-box="#message-box-delete">Supprimer</button>

                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
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
                        <a href="{{route('deleteAPI',['id'=> $api->id])}}" class="btn btn-success btn-lg">Yes</a>
                        <button class="btn btn-default btn-lg mb-control-close">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="message-box message-box-warning animated fadeIn" data-sound="alert" id="message-box-regenere">
        <div class="mb-container">
            <div class="mb-middle">
                <div class="mb-title"><span class="fa fa-refresh"></span> <strong>régénérer</strong> ?</div>
                <div class="mb-content">
                    <p>êtes-vous sûr de vouloir régénérer la clé de l'API ? </p>
                    <p>Appuyez sur Non si vous souhaitez annuler. Appuyez sur Oui pour régénérer la clé.</p>
                </div>
                <div class="mb-footer">
                    <div class="pull-right">
                        <a href="{{route('regenereAPI',['id'=> $api->id])}}" class="btn btn-success btn-lg">Oui</a>
                        <button class="btn btn-default btn-lg mb-control-close">Non</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("script")
    <script>
        $('.datatable').DataTable({
            "order": [[ 1, 'desc' ]]
        });
    </script>
@endsection