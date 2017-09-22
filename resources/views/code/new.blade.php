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
    <li class="active">Ajout d'un code hôtesse</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-body">
                @isset($code->code)
                    {{ Form::model($code, array('route' => array('postUpdateCode', $code->code)))}}
                    @else
                        {{ Form::open(array('route' => 'postNewCode')) }}
                        @endisset


                        <!-- name -->
                            <div class="form-group">
                                {{ Form::label('code', 'Code', array('class' => 'control-label')) }}
                                {{ Form::number('code',null, array('class' => 'form-control','placeholder'=>'code')) }}
                                {!! $errors->first('code', '<small class="help-block">:message</small>') !!}
                            </div>
                            <div class="form-group">
                                <!-- email -->
                                {{ Form::label('pseudo', 'Pseudo', array('class' => 'control-label')) }}
                                {!! Form::text('pseudo', null, array('class' => 'form-control', 'placeholder' => 'pseudo')) !!}
                                {!! $errors->first('pseudo', '<small class="help-block">:message</small>') !!}
                            </div>
                            <div class="form-group">
                                {{ Form::label('description', 'Description', array('class' => 'control-label')) }}
                                {!! Form::textArea('description', null, array('class' => 'form-control', 'placeholder' => 'description')) !!}
                            </div>

                            <div class="form-group">
                                {{ Form::label('hotesse_id', 'Hotesse', array('class' => 'control-label')) }}
                                {!!Form::select('hotesse_id', $hotesses,null,array('class' => 'form-control select')) !!}
                            </div>

                            <div class="form-group">
                                {{ Form::label('annonce_id', 'Annonce', array('class' => 'control-label')) }}
                                {!!Form::select('annonce_id', $annonces,null,array('class' => 'form-control select')) !!}
                            </div>
                            <div class="gallery" id="links">
                                @foreach ($photos as $photo)
                                    <a class="gallery-item" href="assets/images/gallery/nature-1.jpg" title="Nature Image 1" data-gallery="">
                                        <div class="image">
                                            <img src="{{url(elixir('assets/images/users/'.$photo->file.'.jpg'))}}" alt="{{$photo->file}}">
                                            <ul class="gallery-item-controls">
                                                <li><label class="check"><div class="icheckbox_minimal-grey" style="position: relative;"> {!!Form::checkbox("photo",null,array('class' => 'icheckbox', 'placeholder' => 'pseudo','style'=>"position: absolute; opacity: 0;"))!!}<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div></label></li>
                                                <li><span class="gallery-item-remove"><i class="fa fa-times"></i></span></li>
                                            </ul>
                                        </div>
                                        <div class="meta">
                                            <strong>{{$photo->file}}</strong>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            {!! Form::submit('Valider', ['class' => 'btn btn-primary pull-right']) !!}

                            {{ Form::close() }}
                </div>
                <div class="panel-footer">
                    <a href="{{route('activeCode',['id'=> $code->code])}}" role="button" class="btn btn-warning pull-right">@if($code->active)Desactiver @else Activer @endif</a>
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
                    <p>êtes-vous sûr de vouloir supprimer le code hôtesse</p>
                    <p>Appuyez sur Non si vous souhaitez continuer votre travail. Appuyez sur Oui pour supprimer le code hôtesse.</p>
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