@extends('layouts.base')
@section('title')hotesse @endsection

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
    <li class="active">{{$hotesse->name}}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        @if(isset($hotesse->id))
                            {{ Form::model($hotesse, array('route' => array('postUpdateHotesse', $hotesse->id)))}}
                        @else
                            {{ Form::open(array('route' => 'postNewHotesse')) }}
                        @endif
                        <div class="col-md-6">
                            <!-- name -->
                            <div class="form-group {!! $errors->first('name','has-error') !!}">
                                <label class="control-label">Pseudo</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    {{ Form::text('name',null, array('class' => 'form-control','placeholder'=>'name','readonly'=>!isset(Auth::user()->role))) }}
                                </div>
                                {!! $errors->first('name', '<strong class="help-block">pseudo déja utilisé</strong>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- tel -->
                            <div class="form-group {!! $errors->first('tel','has-error') !!}">
                                <label class="control-label">Téléphone</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-phone"></i> </span>
                                    {!! Form::tel('tel', null, array('class' => 'form-control', 'placeholder' => 'tel')) !!}
                                </div>
                                {!! $errors->first('tel', '<strong class="help-block">Les numéros de mobile ne sont pas autorisés</strong>') !!}
                            </div>
                        </div>
                        @if($hotesse->id == null)
                            <div class="col-md-6">
                                <div class="form-group {!! $errors->first('passwordConf','has-error') !!}">
                                    <label class="control-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock"></i> </span>
                                        {{ Form::password('password', array('class' => 'form-control','placeholder'=>'Mot de passe')) }}
                                    </div>
                                    {!! $errors->first('passwordConf', '<strong class="help-block">Les mot de passe ne conrespondent pas</strong>') !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- name -->
                                <div class="form-group {!! $errors->first('passwordConf','has-error')!!}">
                                    <label class="control-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock"></i> </span>
                                        {{ Form::password('passwordConf', array('class' => 'form-control','placeholder'=>'Confirmation')) }}
                                    </div>
                                    {!! $errors->first('passwordConf', '<strong class="help-block">Les mot de passe ne conrespondent pas</strong>') !!}
                                </div>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <div class="gallery" id="links">
                                @foreach($photos as $photo)
                                    <div class="gallery-item" data-gallery="" style="width:auto;">
                                        <div class="image" style="max-height:150px; max-width: 150px">
                                            <img src="{{url(elixir('images/catalog/hotesse/'.$photo->file))}}" alt="{{$photo->file}}" class="img-responsive">
                                            <ul class="gallery-item-controls">
                                                <li>{!!Form::radio("photo_id",$photo->id,$hotesse->photo != null && $photo->id==$hotesse->photo->id,array('class' => 'icheckbox',"style"=>"position: absolute; opacity: 0;"))!!}</li>
                                                @if(isset($hotesse->photo->id) && $photo->id!=$hotesse->photo->id)<li><a href="{{route("deletePhotoHotesse",["id"=>$photo->id])}}"><i class="fa fa-times"></i></a></li>@endif
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        {!! Form::submit('Valider', ['class' => 'btn btn-primary btn btn-primary pull-right']) !!}
                        {{ Form::close() }}
                    </div>
                    <hr/>
                    <div class="row">
                        {{Form::open(array('route' => 'postNewPhotoHotesse','files'=> true))}}
                        {!! Form::file('image',["class"=>"","accept"=>"image/*","id"=>'image'])!!}
                        {!! Form::submit('Envoyer', ['class' => 'btn btn-primary pull-right']) !!}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection