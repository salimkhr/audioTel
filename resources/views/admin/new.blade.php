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
    <li class="active">{{$admin->name}}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        @if(isset($admin->id))
                            {{ Form::model($admin, array('route' => array('postUpdateAdmin', $admin->id)))}}
                        @else
                            {{ Form::open(array('route' => 'postNewAdmin')) }}
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <!-- name -->
                                <div class="form-group">
                                    <label class="control-label">Pseudo</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        {{ Form::text('name',null, array('class' => 'form-control','placeholder'=>'name','readonly'=>!isset(Auth::user()->role))) }}
                                        {!! $errors->first('name', '<small class="help-block">:message</small>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- name -->
                                <div class="form-group">
                                    <label class="control-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-at"></i></span>
                                        {{ Form::email('email',null, array('class' => 'form-control','placeholder'=>'name','readonly'=>!isset(Auth::user()->role))) }}
                                        {!! $errors->first('name', '<small class="help-block">:message</small>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($admin->id == null)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                        {{ Form::password('password', array('class' => 'form-control','placeholder'=>'Mot de passe')) }}
                                        {!! $errors->first('password', '<small class="help-block">:message</small>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- name -->
                                <div class="form-group">
                                    <label class="control-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                        {{ Form::password('passwordConf', array('class' => 'form-control','placeholder'=>'Confirmation')) }}
                                        {!! $errors->first('passwordConf', '<small class="help-block">:message</small>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="gallery" id="links">
                                    @foreach($admin->photos as $photo)
                                        <div class="gallery-item" data-gallery="" style="width:auto;">
                                            <div class="image" style="max-height:150px; max-width: 150px">
                                                <img src="{{url(elixir('images/catalog/hotesse/'.$photo->file))}}" alt="{{$photo->file}}" class="img-responsive">
                                                <ul class="gallery-item-controls">
                                                    <li>{!!Form::radio("photo_id",$photo->id,$photo->id==$admin->photo->id,array('class' => 'icheckbox',"style"=>"position: absolute; opacity: 0;"))!!}</li>
                                                    @if($photo->id!=$admin->photo->id)<li><a href="{{route("deletePhotoAdmin",["id"=>$photo->id])}}"><i class="fa fa-times"></i></a></li>@endif
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {!! Form::submit('Valider', ['class' => 'btn btn-primary btn btn-primary pull-right']) !!}
                        </div>
                        {{ Form::close() }}
                    </div>
                    <hr/>
                    <div class="row">
                        {{Form::open(array('route' => 'postNewPhotoAdmin','files'=> true))}}
                        {!! Form::file('image',["class"=>"","accept"=>"image/*","id"=>'image'])!!}
                        {!! Form::submit('Envoyer', ['class' => 'btn btn-primary pull-right']) !!}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection