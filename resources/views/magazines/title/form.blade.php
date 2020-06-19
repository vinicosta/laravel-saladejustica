@extends('layouts.app', ['activePage' => 'magazines-management', 'titlePage' => __('Revistas'), 'showSearch' => false])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('title.update', $title) }}" autocomplete="off" class="form-horizontal">
                    @csrf
                    @method('put')

                    <input type="hidden" name="type_id" value="{{ Config::get('constants.types.magazines') }}">
                    <input type="hidden" name="genre_id" value="{{ Config::get('constants.genres.magazines') }}">

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ 'Alterar título de revista' }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ URL::to('title/magazines/' . $title->id) }}" class="btn btn-sm btn-primary">
                                        <i class="material-icons" style="color: white">arrow_back</i> {{ 'Voltar' }}</a>
                                </div>
                            </div>

                            {{-- Name --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-name">{{ __('Nome') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            name="name" id="input-name" type="text" placeholder="{{ __('Informe nome do título') }}"
                                            value="{{ old('name', $title->name) }}" required="true" aria-required="true" />
                                        @if ($errors->has('name'))
                                        <span id="name-error" class="error text-danger"
                                            for="input-name">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Publisher --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-publisher_id">{{ __('Editora') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('publisher_id') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('publisher_id') ? ' is-invalid' : '' }}" name="publisher_name"
                                            id="input-publisher_name" placeholder="{{ __('Informe a editora') }}"
                                            value="{{ old('publisher_name', $title->publisher ? $title->publisher->name : '') }}" />
                                        @if ($errors->has('publisher_id'))
                                        <span id="publisher_id-error" class="error text-danger"
                                            for="input-publisher_id">{{ $errors->first('publisher_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="publisher_id" id="input-publisher_id" value="{{ old('publisher_id', $title->publisher_id) }}">
                            </div>

                            {{-- Periodicity --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-periodicity_id">{{ __('Periodicidade') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('periodicity_id') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('periodicity_id') ? ' is-invalid' : '' }}" name="periodicity_name"
                                            id="input-periodicity_name" placeholder="{{ __('Informe a periodicidade') }}"
                                            value="{{ old('periodicity_name', $title->periodicity ? $title->periodicity->name : '') }}" />
                                        @if ($errors->has('periodicity_id'))
                                        <span id="periodicity_id-error" class="error text-danger"
                                            for="input-periodicity_id">{{ $errors->first('periodicity_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="periodicity_id" id="input-periodicity_id"
                                    value="{{ old('periodicity_id', $title->periodicity_id) }}">
                            </div>

                            {{-- Subgenre --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-subgenre_id">{{ __('Subgênero') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('subgenre_id') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('subgenre_id') ? ' is-invalid' : '' }}" name="subgenre_name"
                                            id="input-subgenre_name" placeholder="{{ __('Informe o subgênero') }}"
                                            value="{{ old('subgenre_name', $title->subgenre ? $title->subgenre->name : '') }}" />
                                        @if ($errors->has('subgenre_id'))
                                        <span id="subgenre_id-error" class="error text-danger"
                                            for="input-subgenre_id">{{ $errors->first('subgenre_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="subgenre_id" id="input-subgenre_id"
                                    value="{{ old('subgenre_id', $title->subgenre_id) }}">
                            </div>

                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-success">
                                <i class="material-icons" style="color: white">save</i> {{ __('Salvar') }}</button>
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
    // Autocomplete publishers
    autocomplete('input-publisher_name', 'input-publisher_id', "{{ URL::to('publisher/search/return/json/') }}");

    // Autocomplete periodicities
    autocomplete('input-periodicity_name', 'input-periodicity_id', "{{ URL::to('periodicity/search/return/json/') }}");

    // Autocomplete subgenres
    autocomplete('input-subgenre_name', 'input-subgenre_id', "{{ URL::to('subgenre/search/return/json/' . Config::get('constants.genres.magazines')) }}");
</script>
@endpush

