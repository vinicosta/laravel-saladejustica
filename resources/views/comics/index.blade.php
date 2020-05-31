@extends('layouts.app', ['activePage' => 'comics-management', 'titlePage' => __('Quadrinhos'), 'showSearch' => true, 'model' => 'issue/comics'])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">{{ __('Quadrinhos') }}</h4>
                        <p class="card-category"> {{ __('Cadastro de edições de quadrinhos') }}</p>
                    </div>
                    <div class="card-body">
                        @include('layouts.status')
                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ URL::to('issue/comics/create') }}" class="btn btn-sm btn-primary">
                                    <i class="material-icons" style="color: white">post_add</i> {{ __('Adicionar') }}</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            @include('comics.grid', ['issues' => $issues])
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
    search('search', "{{ URL::to('issue/comics/search/return/view') }}", '.table-responsive');

    $.ajaxSetup({
        headers: {
            'csrftoken': '{{ csrf_token() }}'
        }
    });
</script>
@endpush
