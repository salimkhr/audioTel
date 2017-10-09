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
        <!-- START CONTENT FRAME LEFT -->
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="list-group border-bottom">
                        @foreach($clients as $client=>$nbMessage)
                            <a href="#" class="list-group-item">@if(Auth::user() instanceof \App\Hotesse){{substr($client,0,4).'******'}}@else{{$client}}@endif <span class="badge badge-primary">{{$nbMessage}}</span></a>
                        @endforeach
                    </div>
                </div>
                <div class="panel-footer">
                    <a href="{{route("newMessage")}}">Nouveau message</a>
                </div>
            </div>
        </div>
        <!-- END CONTENT FRAME LEFT -->

        <!-- START CONTENT FRAME BODY -->
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-body mail">
                    @foreach($messages as $message)
                        <div class="mail-item mail-unread mail-info">
                            @if(Auth::user() instanceof \App\Admin)<div class="mail-user">{{$message->hotesse->name}}</div>@endif
                            <div class="mail-user">@if(Auth::user() instanceof \App\Hotesse){{substr($message->client->tel,0,4).'******'}}@else{{$message->client->tel}}@endif</div>
                            <a href="#" data-box="#message-box-default-{{$message->id}}" class="mail-text btn mb-control">{{substr($message->contenu,0,20)}}...</a>
                            <div class="mail-date">{{date_format(date_create($message->created_at), 'd/m/Y H:i:s')}}</div>
                        </div>
                    @endforeach
                </div>
                <div class="panel-footer">
                    <ul class="pagination pagination-sm pull-right">
                        <li class="disabled"><a href="#">«</a></li>
                        <li class="active"><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">»</a></li>
                    </ul>
                </div>
            </div>
            @foreach($messages as $message)
                <div class="message-box animated fadeIn" id="message-box-default-{{$message->id}}">
                    <div class="mb-container">
                        <div class="mb-middle">
                            <div class="mb-title"><span class="fa "></span>@if(Auth::user() instanceof \App\Hotesse){{substr($message->client->tel,0,4).'******'}}@else{{$message->client->tel}}@endif</div>
                            <div class="mb-content">
                                <p>{{$message->contenu}}</p>
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