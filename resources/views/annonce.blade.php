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
    <li><a href="{{route('home')}}">Hotesse</a></li>
    <li class="active">Annonce</li>
@endsection
@section('content')
    <div class="row">
        @foreach ($annonces as $annonce)
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-body">
                        {{ Form::model($annonce, array('route' => array('postUpdateAnnonce', $annonce->id)))}}
                        <div class="form-group">
                            {{ Form::label('name', 'name', array('class' => 'control-label')) }}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                {{ Form::text('name',$annonce->name, array('class' => 'form-control','placeholder'=>'name')) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('code', 'Code', array('class' => 'control-label')) }}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                {!!Form::select('code[]',$codes,$annonce->code,array('class' => 'form-control select',"multiple"=>"true")) !!}
                            </div>
                        </div>
                        {!! Form::submit('Valider', ['class' => 'btn btn-primary pull-right']) !!}
                        <button type="button" class="btn btn-danger mb-control pull-right" data-box="#message-box-delete-{{$annonce->id}}">Supprimer</button>
                        {{Form::close()}}
                    </div>
                    <div class="panel-footer">
                        <a href="#" class="btn btn-primary btn-rounded pull-right" role="button" id="btn-{{$annonce->id}}" onclick="play({{$annonce->id}})"><span class="fa fa-play"></span></a></p>
                    </div>
                </div>
                <audio id="audio-{{$annonce->id}}" src="{{url(elixir("audio/annonce/".$annonce->file.".mp3"))}}" onended="stop('{{$annonce->id}}')"></audio>
            </div>
            <div class="message-box message-box-danger animated fadeIn" data-sound="alert" id="message-box-delete-{{$annonce->id}}">
                <div class="mb-container">
                    <div class="mb-middle">
                        <div class="mb-title"><span class="fa fa-trash-o"></span> <strong>Supprimer</strong> ?</div>
                        <div class="mb-content">
                            <p>êtes-vous sûr de vouloir supprimer l'annonce ?</p>
                            <p>Appuyez sur Non si vous souhaitez continuer votre travail. Appuyez sur Oui pour supprimer l'annonce.</p>
                        </div>
                        <div class="mb-footer">
                            <div class="pull-right">
                                <a href="{{route('deleteAnnonce',['id'=> $annonce->id])}}" class="btn btn-success btn-lg">Oui</a>
                                <button class="btn btn-default btn-lg mb-control-close">Non</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
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