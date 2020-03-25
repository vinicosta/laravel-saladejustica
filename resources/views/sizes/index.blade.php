@extends('layouts.app', ['activePage' => 'size-management', 'titlePage' => __('Tamanhos'), 'showSearch' => true])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">{{ __('Tamanhos') }}</h4>
                        <p class="card-category"> {{ __('Cadastro de tamanhos') }}</p>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert alert-success">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <i class="material-icons">close</i>
                                    </button>
                                    <span>{{ session('status') }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ route('size.create') }}"
                                    class="btn btn-sm btn-primary">{{ __('Adicionar') }}</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                    <th>
                                        {{ __('Nome') }}
                                    </th>
                                    <th>
                                        {{ __('Tipo') }}
                                    </th>
                                    <th class="text-right">
                                        {{ __('Ações') }}
                                    </th>
                                </thead>
                                <tbody>
                                    @include('sizes.grid', ['sizes' => $sizes])
                                </tbody>
                            </table>
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
    $('#search').on('keyup', function(){
        $value = $(this).val();
        $.ajax({
            type: 'get',
            url: "{{ URL::to('size/search/return/view/') }}",
            data: { 'term':$value },
            success: function(data){
                $('tbody').html(data);
            }
        });
    })

    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
</script>
@endpush
