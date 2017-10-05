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
    <li class="active">Ajout d'un admin</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    @if(isset($hotesse->id))
                        {{ Form::model($hotesse, array('route' => array('postUpdateHotesse', $hotesse->id)))}}
                    @else
                        {{ Form::open(array('route' => 'postNewHotesse')) }}
                    @endif
                    <div class="col-md-6">
                        <!-- name -->
                        <div class="form-group">
                            {{ Form::text('name',null, array('class' => 'form-control','placeholder'=>'name','readonly'=>!isset(Auth::user()->role))) }}
                            {!! $errors->first('name', '<small class="help-block">:message</small>') !!}
                        </div>

                        <!-- tel -->
                        <div class="form-group">
                            {!! Form::tel('tel', null, array('class' => 'form-control', 'placeholder' => 'tel')) !!}
                            {!! $errors->first('tel', '<small class="help-block">:message</small>') !!}
                        </div>
                    @if($hotesse->id == null)
                        <!-- password -->
                            <div class="form-group">
                                {{ Form::password('password', array('class' => 'form-control','placeholder'=>'Mot de passe')) }}
                                {!! $errors->first('password', '<small class="help-block">:message</small>') !!}
                            </div>

                            <!-- name -->
                            <div class="form-group">
                                {{ Form::password('passwordConf', array('class' => 'form-control','placeholder'=>'Confirmation')) }}
                                {!! $errors->first('passwordConf', '<small class="help-block">:message</small>') !!}
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="gallery" id="links">
                            @foreach($hotesse->photos as $photo)
                                <a class="gallery-item" href="" title="Nature Image 1" data-gallery="">
                                    <div class="image">
                                        <img src="{{url(elixir('images/catalog/hotesse/'.$photo->file))}}" alt="{{$photo->file}}">
                                        <ul class="gallery-item-controls">
                                            <li>{!!Form::radio("photo_id",$photo->id,$photo->id==$hotesse->photo->id,array('class' => 'icheckbox',"style"=>"position: absolute; opacity: 0;"))!!}</li>
                                        </ul>
                                    </div>
                                </a>
                            @endforeach

                        </div>
                    </div>
                        {!! Form::submit('Valider', ['class' => 'btn btn-primary pull-right']) !!}
                        {{ Form::close() }}

                        {{Form::open(array('route' => 'postNewPhotoHotesse','files'=> true))}}
                        {!! Form::file('image',["class"=>"file","accept"=>"image/*","id"=>'filename'])!!}
                        {{ Form::close() }}
                </div>
                <div class="panel-footer">
                    <a href="{{route('activeHotesse',['id'=> $hotesse->id])}}" type="button" class="btn btn-warning pull-right">@if($hotesse->active)Desactiver @else Activer @endif</a>
                    <button type="button" class="btn btn-danger mb-control pull-right" data-box="#message-box-delete">Supprimer</button>
                </div>
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