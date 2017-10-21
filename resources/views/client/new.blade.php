@extends('layouts.base')
@section('title')hotesse @endsection

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
    <li class="active">Ajout d'un client</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ajout d'un client</h3>
                    <ul class="panel-controls">
                        <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
                        <li><a href="#" class="panel-refresh"><span class="fa fa-refresh"></span></a></li>
                        <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    @isset($client->id)
                        {{ Form::model($client, array('route' => array('postUpdateClient', $client->id)))}}
                    @else
                        {{ Form::open(array('route' => 'postNewClient')) }}
                    @endisset
                <!-- name -->
                            <div class="form-group">
                                {{ Form::label('code', 'Code', array('class' => 'control-label')) }}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-sort-numeric-desc"></i></span>
                                    {!! Form::number('code', $client->code, array('class' => 'form-control',"readonly"=>"readonly")) !!}
                                </div>
                                {!! $errors->first('code', '<small class="help-block">:message</small>') !!}
                            </div>

                    <div class="form-group">
                        {{ Form::label('remarque', 'Remarque', array('class' => 'control-label')) }}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-align-justify"></i></span>
                            {!! Form::textArea('remarque', $client->remarque, array('class' => 'form-control',"rows"=>"8","style"=>"resize: none;")) !!}
                        </div>
                        {!! $errors->first('remarque', '<small class="help-block">:message</small>') !!}
                    </div>

                    {!! Form::submit('Valider', ['class' => 'btn btn-primary pull-right']) !!}

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <div class="message-box message-box-danger animated fadeIn" data-sound="alert" id="message-box-delete">
        <div class="mb-container">
            <div class="mb-middle">
                <div class="mb-title"><span class="fa fa-trash-o"></span> <strong>Supprimer</strong> ?</div>
                <div class="mb-content">
                    <p>êtes-vous sûr de vouloir supprimer le client</p>
                    <p>Appuyez sur Non si vous souhaitez continuer votre travail. Appuyez sur Oui pour supprimer le client.</p>
                </div>
                <div class="mb-footer">
                    <div class="pull-right">
                        <a href="{{route('deleteHotesse',['id'=> $client->id])}}" class="btn btn-success btn-lg">Yes</a>
                        <button class="btn btn-default btn-lg mb-control-close">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection