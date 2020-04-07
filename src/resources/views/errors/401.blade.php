@extends('adminlte::page')

@section('title', 'لا تملك صلاحيات للدخول الى هذه الصفحة')

@section('css') @endsection

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{route('ErrorsManagement::dashboard')}}"><i class="fas fa-tachometer-alt"></i> {{__('ErrorsManagement::trans.dashboard')}}</a>
        </li>
        <li class="breadcrumb-item">
            <a href="" class="no-link">@yield('title')</a>
        </li>
    </ol>
@stop

@section('content')

    <div class="alert alert-danger">
        <h1 class="text-center">
            <i class="fas fa-2x fa-exclamation-triangle"></i>
        </h1>
        <br>
        <h3 class="text-center">
            <b>@yield('title')</b>
        </h3>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="text-center">
                <a href="{{route('ErrorsManagement::dashboard')}}" class="btn btn-lg btn-outline-info">
                    <i class="fas fa-fas fa-fw fa-tachometer-alt"></i>
                    {{__('ErrorsManagement::trans.dashboard')}}
                </a>
                <a href="{{url()->previous()}}" class="btn btn-lg btn-outline-secondary">
                    {{__('ErrorsManagement::trans.back')}}
                    <i class="fas fa-chevron-circle-left"></i>
                </a>
            </div>
        </div>
    </div>


@stop

@section('js')

@endsection
