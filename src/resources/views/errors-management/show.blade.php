@extends('adminlte::page')

@section('title', " {$code} error link visits ||  ({$row->link})")

@section('css')
@endsection

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/admin')}}"><i class="fas fa-tachometer-alt"></i> {{__('home')}}</a></li>
        <li class="breadcrumb-item"><a href="{{url("/errors-management/records/{$code}")}}" class="no-link"> @yield('title') </a></li>
    </ol>
@stop

@section('content')
    <div class="card">
        {{-- table header--}}
        <div class="card-header">
            <h3 class="card-title text-info">
                <i class="fas fa-fw fa-exclamation-circle"></i>
                <b> @yield('title')</b>
            </h3>
            {!! backButton() !!}
        </div>
        {{-- table body--}}
        <div class="card-body">
            <table id="_table" class="table table-bordered table-hover"
                   style="width:100%">
                <thead>
                <tr>
                    <th> {{ __('id') }}</th>
                    <th> {{ __('link') }}</th>
                    <th> {{ __('previous') }}</th>
                    <th> {{ __('visit_date') }}</th>
                    <th> {{ __('action') }}</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            <?php $TrashParam = has_trash_param() ? '?trash=true' : ''?>
            // init table
            var _url = "{{ "/errors-management/records/$code/{$row->id}{$TrashParam}"}}";
            var _columns = [
                {data: "id", name: "id", width: "60", className: 'align-middle text-center'},
                {data: "link", name: "link"},
                {data: "previous", name: "previous"},
                {data: "created_at", name: "created_at"},
                {data: "action", name: "action", width: "125", orderable: false, searchable: false}
            ];
            $('#_table').DataTable({
                "processing": true,
                "serverSide": true,
                "lengthMenu": [[5, 10, 25, 50, 75, 100, 200, 500, -1], [5, 10, 25, 50, 75, 100, 200, 500, "All"]],
                pageLength: 10,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/English.json"
                },
                "ajax": _url,
                columns: _columns,
                order: [0, 'desc'],
                "drawCallback": function () {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
            //=======================================================================

            _url = '{{url('/visits')}}';
            // delete
            $(document).on('click', '.delete', function () {
                let id = $(this).attr('id');
                deleteItem(_url, id);
            });
            // restore
            $(document).on('click', '.btn-restore', function () {
                let id = $(this).attr('id');
                restoreItem(_url, id);
            });
            // force delete
            $(document).on('click', '.btn-force-delete', function () {
                let id = $(this).attr('id');
                forceDeleteItem(_url, id);
            });
            //=======================================================================

        });
    </script>
@endsection
