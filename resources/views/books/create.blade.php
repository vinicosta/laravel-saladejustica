@extends('layouts.app', ['activePage' => 'books-management', 'titlePage' => __('Livros'), 'showSearch' => false])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('issue.store') }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="title_id" value="0">
                    <input type="hidden" name="type_id" value="{{ Config::get('constants.types.books') }}">
                    <input type="hidden" name="author_id" value="">

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Adicionar edição de livro') }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ URL::to('issue/books') }}" class="btn btn-sm btn-primary">
                                        <i class="material-icons" style="color: white">arrow_back</i> {{ __('Voltar') }}</a>
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
                                <label class="col-sm-2 col-form-label" for="input-name">{{ __('Subtítulo') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('subtitle') ? ' is-invalid' : '' }}" name="subtitle" id="input-subtitle"
                                            type="text" placeholder="{{ __('Informe o título da edição') }}" value="{{ old('subtitle', $issue->subtitle) }}" />
                                        @if ($errors->has('subtitle'))
                                        <span id="subtitle-error" class="error text-danger" for="input-subtitle">{{ $errors->first('subtitle') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Issue number --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-issue_number">{{ __('Volume') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('issue_number') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('issue_number') ? ' is-invalid' : '' }}" name="issue_number"
                                            id="input-issue_number" type="text" placeholder="{{ __('Informe o número do volume da edição') }}"
                                            value="{{ old('issue_number', $issue->issue_number) }}" />
                                        @if ($errors->has('issue_number'))
                                        <span id="issue_number-error" class="error text-danger"
                                            for="input-issue_number">{{ $errors->first('issue_number') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Authors --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-authors_id">{{ __('Autores') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('authors_id') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('authors_id') ? ' is-invalid' : '' }}" name="authors_name"
                                            id="input-authors_name" placeholder="{{ __('Informe os autores') }}"
                                            value="{{ old('authors_name', $issue->id ? $issue->author->name : '') }}" />
                                        @if ($errors->has('authors_id'))
                                        <span id="authors_id-error" class="error text-danger"
                                            for="input-authors_id">{{ $errors->first('authors_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="authors_id" id="input-authors_id" value="{{ old('authors_id', $issue->authors_id) }}">
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

                            {{-- Genre --}}
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-genre_id">{{ __('Gênero') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('genre_id') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('genre_id') ? ' is-invalid' : '' }}" name="authors_name"
                                            id="input-genre_name" placeholder="{{ __('Informe o gênero') }}"
                                            value="{{ old('genre_name', $issue->id ? $issue->genre->name : '') }}" />
                                        @if ($errors->has('genre_id'))
                                        <span id="genre_id-error" class="error text-danger"
                                            for="input-genre_id">{{ $errors->first('genre_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="genre_id" id="input-genre_id"
                                    value="{{ old('genre_id', $issue->genre_id) }}">
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
    // Autocomplete multiple authors
    autocompleteMultiple('input-authors_name', 'input-authors_id', "{{ URL::to('author/search/return/json/') }}");

    // Autocomplete publishers
    autocomplete('input-publisher_name', 'input-publisher_id', "{{ URL::to('publisher/search/return/json/') }}");

    // Autocomplete periodicities
    autocomplete('input-periodicity_name', 'input-periodicity_id', "{{ URL::to('periodicity/search/return/json/') }}");

    // Autocomplete genres
    autocomplete('input-genre_name', 'input-genre_id', "{{ URL::to('genre/search/return/json/') }}");

    // Autocomplete subgenres
    autocomplete('input-subgenre_name', 'input-subgenre_id', "{{ URL::to('subgenre/search/return/json') }}/" + $('#input-genre_id').val());
    $("#input-genre_name").on("keyup", function (event) {
        autocomplete('input-subgenre_name', 'input-subgenre_id', "{{ URL::to('subgenre/search/return/json') }}/" + $('#input-genre_id').val());
    });
</script>
@endpush

