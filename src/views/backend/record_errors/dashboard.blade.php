@extends('adminlte::page')

@section('title', __("backend.dashboard"))

@section('css')
@endsection

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/admin/error-management')}}"><i class="fas fa-tachometer-alt"></i> {{__('backend.home')}}</a></li>
    </ol>
@stop

@section('content')
    <div class="row">
        @foreach(list_of_error_codes() as $err)
            <?php $count = 0 ?>
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box mb-3">
                  <span class="info-box-icon {{isset($err['bg-color'])?$err['bg-color']:"bg-danger"}} elevation-1">
                      <a href="{{route('error-records',['code'=>$err['code']])}}">
                       <i class="{{$err['icon']}}"></i>
                    </a>
                  </span>
                    <div class="info-box-content">
                        <span class="info-box-text"><a href="{{route('error-records',['code'=>$err['code']])}}">{{$err['code']}} Error Records</a></span>
                        <span class="info-box-number"><i class="fas fa-fw fa-link"></i> {{Hayrullah\RecordErrors\Models\RecordError::Type($err['code'])->count()}}</span>
                        <span class="info-box-number"><i class="fas fa-fw fa-eye"></i> 0
{{--                            {{ Hayrullah\RecordErrors\Models\RecordError::showVisits($err['code'])  }}--}}
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
        @endforeach
    </div>

@stop

@section('js')
    <script src="{{asset('js/global.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

        });
    </script>
@endsection
