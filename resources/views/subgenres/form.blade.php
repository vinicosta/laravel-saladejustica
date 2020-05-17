@extends('layouts.app', ['activePage' => 'subgenre-management', 'titlePage' => __('Gestāo de subgêneros literários'), 'showSearch' => false])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="{{ $subgenre->id ? route('subgenre.update', $subgenre) : route('subgenre.store') }}" autocomplete="off" class="form-horizontal">
                    @csrf

                    @if($subgenre->id)
                        @method('put')
                    @else
                        @method('post')
                    @endif

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ $subgenre->id ? __('Editar subgênero') : __('Adicionar subgênero') }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ route('subgenre.index') }}"
                                        class="btn btn-sm btn-primary">{{ __('Voltar') }}</a>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-name">{{ __('Nome') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            name="name" id="input-name" type="text" placeholder="{{ __('Informe o nome do subgênero literário') }}"
                                            value="{{ old('name', $subgenre->name) }}" required="true" aria-required="true" />
                                        @if ($errors->has('name'))
                                        <span id="name-error" class="error text-danger"
                                            for="input-name">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-genre_id">{{ __('Gênero') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('genre_id') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('genre_id') ? ' is-invalid' : '' }}" name="genre_name"
                                            id="input-genre_name" placeholder="{{ __('Informe o gênero literário') }}" value="{{ old('genre_name', $subgenre->id ? $subgenre->genre->name : '') }}"
                                            required="true" aria-required="true" />
                                        @if ($errors->has('genre_id'))
                                        <span id="genre_id-error" class="error text-danger" for="input-genre_id">{{ $errors->first('genre_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="genre_id" id="input-genre_id" value="{{ old('genre_id', $subgenre->genre_id) }}">
                            </div>
                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary">{{ __('Salvar') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script type="text/javascript">
    autocomplete('input-genre_name', 'input-genre_id', "{{ URL::to('genre/search/return/json/') }}");
</script>
@endpush

