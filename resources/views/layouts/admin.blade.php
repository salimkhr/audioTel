@extends('layouts.base')
@section('title')Admin @endsection

@section('breadcrumb')
    <li><a href="{{route('admin')}}">Admin</a></li>
@endsection

@section('menu')
    <li @if(Request::segment(2) === 'hotesse')class="active" @endif><a href="{{route("hotesseAdmin")}}"><span class="fa fa-users"></span> <span class="xn-text">Hotesse</span></a></li>
    <li @if(Request::segment(2) === 'code')class="active" @endif><a href="{{route("codeAdmin")}}"><span class="fa fa-list-ol"></span> <span class="xn-text">Code</span></a></li>
    <li @if(Request::segment(2) === 'client')class="active" @endif><a href="{{route("clientAdmin")}}"><span class="fa fa-user-circle"></span> <span class="xn-text">Client</span></a></li>
    <li @if(Request::segment(2) === 'admin')class="active" @endif><a href="{{route("adminAdmin")}}"><span class="fa fa-user-plus"></span> <span class="-user-plus">Admin</span></a></li>
@endsection