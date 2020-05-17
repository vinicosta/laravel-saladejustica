@extends('layouts.app', ['activePage' => 'comics-management', 'titlePage' => __('Quadrinhos'), 'showSearch' => true, 'model' => 'issue'])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">{{ __($issue->issue_number != '' ? $issue->name . ' #' . $issue->issue_number : $issue->name) }}</h4>
                        <p class="card-category"> {{ __('Cadastro de edições de quadrinhos') }}</p>
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
                                <a href="comics/create" class="btn btn-sm btn-primary">{{ __('Adicionar') }}</a>
                            </div>
                        </div>
                        {{-- <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                    <th>
                                        {{ __('Nome') }}
                                    </th>
                                    <th>
                                        {{ __('Gênero') }}
                                    </th>
                                    <th class="text-right">
                                        {{ __('Ações') }}
                                    </th>
                                </thead>
                                <tbody>
                                    @include('issues.grid', ['issues' => $issues])
                                </tbody>
                            </table>
                        </div> --}}
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
            url: "{{ URL::to('issue/search/return/view/') }}",
            data: { 'term':$value },
            success: function(data){
                $('tbody').html(data);
            }
        });
    });

    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
</script>
@endpush
