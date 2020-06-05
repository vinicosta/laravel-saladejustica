@extends('layouts.app', ['activePage' => 'comics-management', 'titlePage' => __('Quadrinhos'), 'showSearch' => false])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('title.update', $title) }}" autocomplete="off" class="form-horizontal">
                    @csrf

                    <input type="hidden" name="type_id" value="1">
                    <input type="hidden" name="genre_id" value="{{ Config::get('constants.genres.comics') }}">

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ 'Alterar título de quadrinhos' }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ URL::to('title/comics/' . $title->id) }}" class="btn btn-sm btn-primary">
                                        <i class="material-icons" style="color: white">arrow_back</i> {{ 'Voltar' }}</a>
                                </div>
                            </div>
                            
                            {{-- Name --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-name">{{ __('Nome') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            name="name" id="input-name" type="text" placeholder="{{ __('Informe o título da edição') }}"
                                            value="{{ old('name', $issue->name) }}" required="true" aria-required="true" />
                                        @if ($errors->has('name'))
                                        <span id="name-error" class="error text-danger"
                                            for="input-name">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Subtitle --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-subtitle">{{ __('Subtítulo') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('subtitle') ? ' is-invalid' : '' }}" name="subtitle" id="input-subtitle"
                                            type="text" placeholder="{{ __('Informe o subtítulo da edição') }}" value="{{ old('subtitle', $issue->subtitle) }}" />
                                        @if ($errors->has('subtitle'))
                                        <span id="subtitle-error" class="error text-danger" for="input-subtitle">{{ $errors->first('subtitle') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Issue number --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-issue_number">{{ __('Nº da edição') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('issue_number') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('issue_number') ? ' is-invalid' : '' }}" name="issue_number"
                                            id="input-issue_number" type="text" placeholder="{{ __('Informe o número da edição') }}"
                                            value="{{ old('issue_number', $issue->issue_number) }}" />
                                        @if ($errors->has('issue_number'))
                                        <span id="issue_number-error" class="error text-danger"
                                            for="input-issue_number">{{ $errors->first('issue_number') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Month and year of publication --}}
                            <input type="hidden" name="date_publication" value="">
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-month_publication">{{ __('Data de publicação') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('month_publication') or $errors->has('year_publication') ? ' has-danger' : '' }}">

                                        <div class="row">

                                            {{-- Month --}}
                                            <div class="col">
                                                <select class="form-control {{ $errors->has('month_publication') ? ' is-invalid' : '' }}" data-style="btn btn-link" name="month_publication"
                                                    id="input-month_publication" placeholder="{{ __('Informe o tipo do tamanho') }}">
                                                    <option>Mês</option>
                                                    <option value="1" {{ old('month_publication') == '1' ? 'selected' : '' }}>Janeiro</option>
                                                    <option value="2" {{ old('month_publication') == '2' ? 'selected' : '' }}>Fevereiro</option>
                                                    <option value="3" {{ old('month_publication') == '3' ? 'selected' : '' }}>Março</option>
                                                    <option value="4" {{ old('month_publication') == '4' ? 'selected' : '' }}>Abril</option>
                                                    <option value="5" {{ old('month_publication') == '5' ? 'selected' : '' }}>Maio</option>
                                                    <option value="6" {{ old('month_publication') == '6' ? 'selected' : '' }}>Junho</option>
                                                    <option value="7" {{ old('month_publication') == '7' ? 'selected' : '' }}>Julho</option>
                                                    <option value="8" {{ old('month_publication') == '8' ? 'selected' : '' }}>Agosto</option>
                                                    <option value="9" {{ old('month_publication') == '9' ? 'selected' : '' }}>Setembro</option>
                                                    <option value="10" {{ old('month_publication') == '10' ? 'selected' : '' }}>Outubro</option>
                                                    <option value="11" {{ old('month_publication') == '11' ? 'selected' : '' }}>Novembro</option>
                                                    <option value="12" {{ old('month_publication') == '12' ? 'selected' : '' }}>Dezembro</option>
                                                </select>
                                                @if ($errors->has('month_publication'))
                                                <span id="month_publication-error" class="error text-danger" for="input-month_publication">{{ $errors->first('month_publication') }}</span>
                                                @endif
                                            </div>

                                            {{-- Year --}}
                                            <div class="col">
                                                <input class="form-control{{ $errors->has('year_publication') ? ' is-invalid' : '' }}" name="year_publication"
                                                    id="input-year_publication" type="number" placeholder="{{ __('Ano') }}"
                                                    value="{{ old('year_publication', $issue->year_publication) }}" />
                                                @if ($errors->has('year_publication'))
                                                <span id="year_publication-error" class="error text-danger"
                                                    for="input-year_publication">{{ $errors->first('year_publication') }}</span>
                                                @endif
                                            </div>

                                        </div>

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
                                            value="{{ old('publisher_name', $issue->id ? $issue->publisher->name : '') }}" />
                                        @if ($errors->has('publisher_id'))
                                        <span id="publisher_id-error" class="error text-danger"
                                            for="input-publisher_id">{{ $errors->first('publisher_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="publisher_id" id="input-publisher_id" value="{{ old('publisher_id', $issue->publisher_id) }}">
                            </div>

                            {{-- Periodicity --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-periodicity_id">{{ __('Periodicidade') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('periodicity_id') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('periodicity_id') ? ' is-invalid' : '' }}" name="periodicity_name"
                                            id="input-periodicity_name" placeholder="{{ __('Informe a periodicidade') }}"
                                            value="{{ old('periodicity_name', $issue->id ? $issue->periodicity->name : '') }}" />
                                        @if ($errors->has('periodicity_id'))
                                        <span id="periodicity_id-error" class="error text-danger"
                                            for="input-periodicity_id">{{ $errors->first('periodicity_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="periodicity_id" id="input-periodicity_id"
                                    value="{{ old('periodicity_id', $issue->periodicity_id) }}">
                            </div>

                            {{-- Size --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-size_id">{{ __('Tamanho') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('size_id') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('size_id') ? ' is-invalid' : '' }}" name="size_name"
                                            id="input-size_name" placeholder="{{ __('Informe o tamanho') }}"
                                            value="{{ old('size_name', $issue->id ? $issue->size->name : '') }}" />
                                        @if ($errors->has('size_id'))
                                        <span id="size_id-error" class="error text-danger"
                                            for="input-size_id">{{ $errors->first('size_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="size_id" id="input-size_id"
                                    value="{{ old('size_id', $issue->size_id) }}">
                            </div>

                            {{-- Subgenre --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-subgenre_id">{{ __('Subgênero') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('subgenre_id') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('subgenre_id') ? ' is-invalid' : '' }}" name="subgenre_name"
                                            id="input-subgenre_name" placeholder="{{ __('Informe o subgênero') }}"
                                            value="{{ old('subgenre_name', $issue->id ? $issue->subgenre->name : '') }}" />
                                        @if ($errors->has('subgenre_id'))
                                        <span id="subgenre_id-error" class="error text-danger"
                                            for="input-subgenre_id">{{ $errors->first('subgenre_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="subgenre_id" id="input-subgenre_id"
                                    value="{{ old('subgenre_id', $issue->subgenre_id) }}">
                            </div>

                            {{-- Number of pages --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-number_pages">{{ __('Nº de páginas') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('number_pages') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('number_pages') ? ' is-invalid' : '' }}" name="number_pages"
                                            id="input-number_pages" type="number" placeholder="{{ __('Informe o número de paginas da edição') }}"
                                            value="{{ old('number_pages', $issue->number_pages) }}" />
                                        @if ($errors->has('number_pages'))
                                        <span id="number_pages-error" class="error text-danger"
                                            for="input-number_pages">{{ $errors->first('number_pages') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- ISBN --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-isbn">{{ __('ISBN') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('isbn') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('isbn') ? ' is-invalid' : '' }}" name="isbn" id="input-isbn" type="text" placeholder="{{ __('Informe o ISBN da edição') }}" value="{{ old('isbn', $issue->isbn) }}" />
                                        @if ($errors->has('isbn'))
                                        <span id="isbn-error" class="error text-danger"
                                            for="input-isbn">{{ $errors->first('isbn') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Synopsis --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-synopsis">{{ __('Sinopse') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('synopsis') ? ' has-danger' : '' }}">
                                        <textarea class="form-control{{ $errors->has('synopsis') ? ' is-invalid' : '' }}" name="synopsis" id="input-synopsis" rows="6">{{ old('synopsis', $issue->synopsis) }}</textarea>
                                        @if ($errors->has('synopsis'))
                                        <span id="synopsis-error" class="error text-danger" for="input-synopsis">{{ $errors->first('synopsis') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Image --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-image">{{ __('Capa') }}</label>
                                <div class="col-sm-7">
                                    <input type="file" name="image_file">
                                    <input type="hidden" name="image" value="">
                                </div>
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

    // Autocomplete sizes
    autocomplete('input-size_name', 'input-size_id', "{{ URL::to('size/search/return/json/type/1') }}");

    // Autocomplete subgenres
    autocomplete('input-subgenre_name', 'input-subgenre_id', "{{ URL::to('subgenre/search/return/json/' . Config::get('constants.genres.comics')) }}");
</script>
@endpush

