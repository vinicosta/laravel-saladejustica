@extends('layouts.app', ['activePage' => 'books-management', 'titlePage' => __('Livros'), 'showSearch' => false])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ $issue->id ? route('issue.update', $issue) : route('issue.store') }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data">
                    @csrf

                    @if($issue->id)
                        @method('put')
                    @else
                        @method('post')
                    @endif

                    <input type="hidden" name="title_id" value="{{ $issue->title_id }}">
                    <input type="hidden" name="type_id" value="{{ Config::get('constants.types.books') }}">
                    <input type="hidden" name="genre_id" value="{{ Config::get('constants.genres.books') }}">

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ $issue->id ? 'Editar edição de livro' : 'Adicionar edição de livro' }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ URL::to('title/books/' . $issue->title_id) }}" class="btn btn-sm btn-primary">
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
                                        <input class="form-control{{ $errors->has('subtitle') ? ' is-invalid' : '' }}" name="subtitle"
                                            id="input-subtitle" type="text" placeholder="{{ __('Informe o subtítulo da edição') }}"
                                            value="{{ old('subtitle', $issue->subtitle) }}" />
                                        @if ($errors->has('subtitle'))
                                        <span id="subtitle-error" class="error text-danger"
                                            for="input-subtitle">{{ $errors->first('subtitle') }}</span>
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
                                            id="input-issue_number" type="text" placeholder="{{ __('Informe o número do volume') }}"
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
                                            value="{{ old('authors_name', listAuthors($authors)) }}" />
                                        @if ($errors->has('authors_id'))
                                        <span id="authors_id-error" class="error text-danger"
                                            for="input-authors_id">{{ $errors->first('authors_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="authors_id" id="input-authors_id" value="{{ old('authors_id', listAuthors($authors, true)) }}">
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
                                    <input type="hidden" name="image" value="{{ old('image', $issue->image) }}">
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
</script>
@endpush

