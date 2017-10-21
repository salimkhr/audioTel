@extends('layouts.base')
@section('title')

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
    <li class="active">Message</li>
@endsection
@section('content')
    <div class="row">
        <!-- START CONTENT FRAME BODY -->
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="{{route("newMessage")}}" class="btn btn-primary pull-right">Envoyer un message</a>
                </div>
                <div class="panel-body mail">
                    @foreach($messages as $message)
                        <div class="mail-item mail-info">
                            <div class="mail-date">{{date_format(date_create($message->created_at), 'd/m/Y H:i:s')}}</div>
                            <div class="mail-user">@if(Auth::user() instanceof \App\Hotesse){{substr($message->tel,0,4).'******'}}@else{{$message->tel}}@endif</div>
                            <a href="#" data-box="#message-box-default-{{$message->id}}" class="mail-text btn mb-control">{{substr($message->contenu,0,20)." ".(strlen($message->contenu)>40?"...":"")}}</a>
                        </div>
                    @endforeach
                </div>
                <div class="panel-footer">
                    <ul class="pagination pagination-sm pull-right">
                        <li @if($page == 1)  class="disabled" @endif><a href="{{route(Route::currentRouteName(),$page-1)}}">«</a></li>
                        @for ($i = 1; $i <=$nbPages; $i++)
                            <li @if($i == $page)class="active"@endif><a href="{{route(Route::currentRouteName(),["page"=>$i])}}">{{$i}}</a></li>
                        @endfor
                        <li @if($page == $nbPages)  class="disabled" @endif><a href="{{route(Route::currentRouteName(),$page+1)}}">»</a></li>
                    </ul>
                </div>
            </div>
            @foreach($messages as $message)
                <div class="message-box animated fadeIn" id="message-box-default-{{$message->id}}">
                    <div class="mb-container">
                        <div class="mb-middle">
                            <div class="mb-title"><span class="fa "></span>@if(Auth::user() instanceof \App\Hotesse){{substr($message->tel,0,5).'*****'}}@else{{$message->tel}}@endif</div>
                            <div class="mb-content">
                                @if($message->photo != null)
                                    <div class="col-md-4">
                                        <img src="{{url(elixir("images/catalog/".$message->photo->file))}}" style="width: 100%">
                                    </div>
                                @endif
                                @if($message->annonce != null)
                                    <div class="col-md-4">
                                        <a href="#" class="btn btn-primary btn-rounded pull-right" role="button" id="btn-{{$message->annonce->id}}" onclick="play({{$message->annonce->id}})"><i class="fa fa-play"></i></a>
                                        <audio id="audio-{{$message->annonce->id}}" src="{{url(elixir("audio/annonce/".$message->annonce->file.".mp3"))}}" onended="stop('{{$message->annonce->id}}')"></audio>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <p>{{$message->contenu}}</p>
                                </div>
                            </div>
                            <div class="mb-footer">
                                <button class="btn btn-default btn-lg pull-right mb-control-close">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <!-- END CONTENT FRAME BODY -->
    </div>
@endsection