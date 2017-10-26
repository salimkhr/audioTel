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
    <li><a href="{{route("client")}}">Client</a></li>
    <li class="active">{{$client->code}}</li>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                {{ Form::model($client, array('route' => array('postUpdateClient', $client->id)))}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-body profile bg-info">
                                <div class="profile-data">
                                    <div class="profile-data-name">Client {{$client->code}}</div>

                                </div>
                            </div>
                            <div class="panel-body list-group">
                                <span class="list-group-item"><span class="fa fa-phone"></span> {{$client->tel}}</span>
                                <span class="list-group-item"><span class="fa fa-sort-numeric-desc"></span> {{$client->code}}</span>
                                <span class="list-group-item"><span class="fa fa-calendar"></span>{{date_format(date_create($client->created_at), 'd/m/Y H:i:s')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('remarque', 'Remarque', array('class' => 'control-label')) }}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-align-justify"></i></span>
                                {!! Form::textArea('remarque', $client->remarque, array('class' => 'form-control',"rows"=>"8","style"=>"resize: none;")) !!}
                            </div>
                            {!! $errors->first('remarque', '<small class="help-block">:message</small>') !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="pull-right">
                            <a href="{{route("activeClient",["id"=>$client->id])}}" role="button" class="btn btn-warning"> {{$client->active?"Désaciver":"Activer"}}</a>
                            <a href="#" class="btn btn-danger mb-control" data-box="#message-box-delete-{{$client->id}}">Supprimer</a>
                            {!! Form::submit('Valider', ['class' => 'btn btn-primary ']) !!}
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <!-- START TIMELINE -->
            <div class="timeline">
                <div class="timeline-item timeline-main">
                    <div class="timeline-date">{{$client->solde}} min</div>
                </div>
            @foreach($listEvent as $event)
                <!-- START TIMELINE ITEM -->
                    @if($event instanceof \App\Credit)
                        <div class="timeline-item timeline-item-right">
                            <div class="timeline-item-info">{{date_format(date_create($event->created_at), 'd/m/Y H:i:s')}}</div>
                            <div class="timeline-item-icon"><span class="fa fa-money"></span></div>
                            <div class="timeline-item-content">
                                <div class="timeline-heading">
                                   <strong>{{$event->temps/60.%60}} min </strong> a été rajouté

                                </div>
                            </div>
                        </div>
                    @else
                        <div class="timeline-item">
                            <div class="timeline-item-info">{{date_format(date_create($event->debut), 'd/m/Y H:i:s')}}</div>
                            <div class="timeline-item-icon"><span class="fa fa-phone"></span></div>
                            <div class="timeline-item-content">
                                <div class="timeline-heading">
                                    <p><strong>Durée de l'appel : </strong> {{date_diff(date_create($event->debut),date_create($event->fin))->format('%I:%S')}}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                <!-- END TIMELINE ITEM -->
                @endforeach
            </div>
            <!-- END TIMELINE -->
        </div>
    </div>
    <div class="message-box message-box-danger animated fadeIn" data-sound="alert" id="message-box-delete-{{$client->id}}">
        <div class="mb-container">
            <div class="mb-middle">
                <div class="mb-title"><span class="fa fa-trash-o"></span> <strong>Supprimer</strong> ?</div>
                <div class="mb-content">
                    <p>êtes-vous sûr de vouloir supprimer le client ?</p>
                    <p>Appuyez sur Non si vous souhaitez continuer votre travail. Appuyez sur Oui pour supprimer le client.</p>
                </div>
                <div class="mb-footer">
                    <div class="pull-right">
                        <a href="{{route('deleteClient',['id'=> $client->id])}}" class="btn btn-success btn-lg">Oui</a>
                        <button class="btn btn-default btn-lg mb-control-close">Non</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection