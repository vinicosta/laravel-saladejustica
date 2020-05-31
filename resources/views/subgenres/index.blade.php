@extends('layouts.app', ['activePage' => 'subgenre-management', 'titlePage' => __('Subgêneros'), 'showSearch' => true, 'model' => 'subgenre'])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">{{ __('Subgêneros') }}</h4>
                        <p class="card-category"> {{ __('Cadastro de subgêneros literários') }}</p>
                    </div>
                    <div class="card-body">
                        @include('layouts.status')
                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ route('subgenre.create') }}" class="btn btn-sm btn-primary">
                                    <i class="material-icons" style="color: white">post_add</i> {{ __('Adicionar') }}</a>
                            </div>
                        </div>
                        <div class="table-responsive">
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
                                    @include('subgenres.grid', ['subgenres' => $subgenres])
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
    search('search', "{{ URL::to('subgenre/search/return/view/') }}", 'tbody');
</script>
@endpush
