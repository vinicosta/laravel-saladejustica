@extends('layouts.app', ['activePage' => 'magazines-management', 'titlePage' => __('Revistas'), 'showSearch' => true, 'model' => 'issue/magazines'])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">{{ __('Revistas') }}</h4>
                        <p class="card-category">
                            Lista de leitura - {{ count($issues) }} revista{{ count($issues) != 1 ? 's' : '' }}
                        </p>
                    </div>
                    <div class="card-body">
                        @include('layouts.status')
                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ URL::to('issue/magazines/create') }}" class="btn btn-sm btn-primary">
                                    <i class="material-icons" style="color: white">note_add</i> {{ __('Adicionar') }}</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            @include('magazines.grid', ['issues' => $issues])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script type="text/javascript">
    search('search', "{{ URL::to('issue/magazines/search/return/view') }}", '.table-responsive');

    $.ajaxSetup({
        headers: {
            'csrftoken': '{{ csrf_token() }}'
        }
    });
</script>
@endpush
