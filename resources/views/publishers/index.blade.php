@extends('layouts.app', ['activePage' => 'publisher-management', 'titlePage' => __('Editoras'), 'showSearch' => true, 'model' => 'publisher'])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">{{ __('Editoras') }}</h4>
                        <p class="card-category"> {{ __('Cadastro de editoras') }}</p>
                    </div>
                    <div class="card-body">
                        @include('layouts.status')
                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ route('publisher.create') }}" class="btn btn-sm btn-primary">
                                    <i class="material-icons" style="color: white">post_add</i> {{ __('Adicionar') }}</a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                    <th>
                                        {{ __('Nome') }}
                                    </th>
                                    <th class="text-right">
                                        {{ __('Ações') }}
                                    </th>
                                </thead>
                                <tbody>
                                    @include('publishers.grid', ['publishers' => $publishers])
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
    search('search', "{{ URL::to('publisher/search/return/view/') }}", 'tbody');
</script>
@endpush
