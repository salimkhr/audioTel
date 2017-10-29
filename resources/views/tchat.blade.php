@extends('layouts.base')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if(Request::route()->getName() == "tchat.general")
                        Tchat général
                    @else
                        @if(Auth::user() instanceof \App\Admin)
                            Tchat hôtesse {{\App\Hotesse::find($id)->name}}
                        @else
                            Tchat admin {{Auth::user()->admin->name}}
                        @endif
                    @endif
                </div>
                <div class="panel-body">
                    <div class="messages messages-img">
                        @foreach($tchats as $tchat)
                            <div class="item {{$tchat->getClass()}} item-visible">
                                <div class="image">
                                    <img src="{{url(elixir("images/catalog/".$tchat->getAuteur()->photo->file))}}" alt="{{$tchat->getAuteur()->name}}">
                                </div>
                                <div class="text">
                                    <div class="heading">
                                        <a href="#">{{$tchat->getAuteur()->name }}</a>
                                        <span class="date">{{date_format(date_create($tchat->created_at), 'd/m/Y H:i:s')}}</span>
                                    </div>
                                    <span class="message">{{$tchat->message}}</span>
                                    @if(Request::route()->getName() == "tchat.general" && Auth::user() instanceof \App\Admin)<a href="{{route("tchat.delete",["id"=>$tchat->id])}}" class="pull-right btn btn-rounded btn-primary"><i class="fa fa-fw fa-trash"></i></a> @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="panel-body panel-body-search">
                    @if(Request::route()->getName() == "tchat.general")
                        {{ Form::open(array('route' => array('tchat.general'))) }}
                    @else
                        @if(Auth::user() instanceof \App\Admin)
                            {{ Form::open(array('route' => array('tchat.store',$id))) }}
                        @else
                            {{ Form::open(array('route' => 'tchat.store')) }}
                        @endif
                    @endif
                    <div class="input-group">
                        {{Form::text('subject',"",array("class"=>"form-control","placeholder"=>"Votre message..."))}}
                        <div class="input-group-btn">
                            <input type="submit" class="btn btn-default">Envoyer <i class="fa fa-fw fa-send-o"></i></input>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

            <div class="col-md-offset-7 col-md-3 fixed">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @if(Auth::user() instanceof \App\Admin)
                            Liste des hôtesses
                        @else
                            Administrateur
                        @endif
                    </div>
                    <div class="panel-body">
                        <div class="list-group list-group-contacts border-bottom push-down-10">
                            @if(Auth::user() instanceof \App\Admin)
                            @foreach(Auth::user()->hotesses as $hotesse)
                                <a href="{{route("tchat.show",["id"=>$hotesse->id])}}" class="list-group-item @if(isset($id) && $hotesse->id == $id) active @endif">
                                <div class="list-group-status status-online"></div>
                                <img src="{{url(elixir("images/catalog/".$hotesse->photo->file))}}" class="pull-left" alt="Dmitry Ivaniuk">
                                <span class="contacts-title">{{$hotesse->name}}</span>
                                <p>@isset($hotesse->messages[0]){{$hotesse->messages[count($hotesse->messages)-1]->message}}@else aucun message @endisset</p>
                            </a>
                            @endforeach
                            @else
                                <a href="{{route("tchat")}}" class="list-group-item">
                                    <div class="list-group-status status-online"></div>
                                    <img src="{{url(elixir("images/catalog/".Auth::user()->admin->photo->file))}}" class="pull-left" alt="Dmitry Ivaniuk">
                                    <span class="contacts-title">{{Auth::user()->admin->name}}</span>
                                    <p>@isset(Auth::user()->messages[0]){{Auth::user()->messages[count(Auth::user()->messages)-1]->message}}@else aucun message @endisset</p>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="panel-footer">
                        <a href="{{route("tchat.general")}}" class="btn btn-primary pull-right">tchat général</a>
                    </div>
                </div>
            </div>

    </div>
@endsection
@section("script")
<script>

    $(".message").each(function( index ){
        $(this).html(replaceEmoticons($(this).text()));
    });

    $('html, body').animate({
            scrollTop: $(document).height()-$(window).height()},
        1400,
        "easeOutQuint"
    );

    function replaceEmoticons(text) {
        var emoticons = {
                ';-)' : 'smile1.png',
                ':-(' : 'smile2.png',
                ':D'  : 'smile3.png',
                ':o'  : 'smile4.png',
                ':-|' : 'smile5.png'
            }, url = "{{URL::to('/')}}/images/smiley/", patterns = [],
            metachars = /[[\]{}()*+?.\\|^$\-,&#\s]/g;

        // build a regex pattern for each defined property
        for (var i in emoticons) {
            if (emoticons.hasOwnProperty(i)){ // escape metacharacters
                patterns.push('('+i.replace(metachars, "\\$&")+')');
            }
        }

        // build the regular expression and replace
        return text.replace(new RegExp(patterns.join('|'),'g'), function (match) {
            return typeof emoticons[match] != 'undefined' ?
                '<img src="'+url+emoticons[match]+'" class="smiley"/>' :
                match;
        });
    }

</script>
@endsection