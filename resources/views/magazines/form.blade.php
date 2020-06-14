@extends('layouts.app', ['activePage' => 'magazines-management', 'titlePage' => __('Revistas'), 'showSearch' => false])

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
                    <input type="hidden" name="type_id" value="{{ Config::get('constants.types.magazines') }}">
                    <input type="hidden" name="genre_id" value="{{ Config::get('constants.genres.magazines') }}">
                    <input type="hidden" name="periodicity_id" value="{{ $issue->periodicity_id }}">

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ $issue->id ? 'Editar edição de revista' : 'Adicionar edição de revista' }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ URL::to('issue/magazines/' . $issue->id) }}" class="btn btn-sm btn-primary">
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
                            <input type="hidden" name="date_publication" value="{{ $issue->date_publication }}">
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
                                                    <option value="1" {{ date('m', strtotime($issue->date_publication)) == '01' ? 'selected' : '' }}>Janeiro</option>
                                                    <option value="2" {{ date('m', strtotime($issue->date_publication)) == '02' ? 'selected' : '' }}>Fevereiro</option>
                                                    <option value="3" {{ date('m', strtotime($issue->date_publication)) == '03' ? 'selected' : '' }}>Março</option>
                                                    <option value="4" {{ date('m', strtotime($issue->date_publication)) == '04' ? 'selected' : '' }}>Abril</option>
                                                    <option value="5" {{ date('m', strtotime($issue->date_publication)) == '05' ? 'selected' : '' }}>Maio</option>
                                                    <option value="6" {{ date('m', strtotime($issue->date_publication)) == '06' ? 'selected' : '' }}>Junho</option>
                                                    <option value="7" {{ date('m', strtotime($issue->date_publication)) == '07' ? 'selected' : '' }}>Julho</option>
                                                    <option value="8" {{ date('m', strtotime($issue->date_publication)) == '08' ? 'selected' : '' }}>Agosto</option>
                                                    <option value="9" {{ date('m', strtotime($issue->date_publication)) == '09' ? 'selected' : '' }}>Setembro</option>
                                                    <option value="10" {{ date('m', strtotime($issue->date_publication)) == '10' ? 'selected' : '' }}>Outubro</option>
                                                    <option value="11" {{ date('m', strtotime($issue->date_publication)) == '11' ? 'selected' : '' }}>Novembro</option>
                                                    <option value="12" {{ date('m', strtotime($issue->date_publication)) == '12' ? 'selected' : '' }}>Dezembro</option>
                                                </select>
                                                @if ($errors->has('month_publication'))
                                                <span id="month_publication-error" class="error text-danger" for="input-month_publication">{{ $errors->first('month_publication') }}</span>
                                                @endif
                                            </div>

                                            {{-- Year --}}
                                            <div class="col">
                                                <input class="form-control{{ $errors->has('year_publication') ? ' is-invalid' : '' }}" name="year_publication"
                                                    id="input-year_publication" type="number" placeholder="{{ __('Ano') }}"
                                                    value="{{ old('year_publication', date('Y', strtotime($issue->date_publication))) }}" />
                                                @if ($errors->has('year_publication'))
                                                <span id="year_publication-error" class="error text-danger"
                                                    for="input-year_publication">{{ $errors->first('year_publication') }}</span>
                                                @endif
                                            </div>

                                        </div>

                                    </div>
                                </div>
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
    // Autocomplete publishers
    autocomplete('input-publisher_name', 'input-publisher_id', "{{ URL::to('publisher/search/return/json/') }}");

    // Autocomplete periodicities
    autocomplete('input-periodicity_name', 'input-periodicity_id', "{{ URL::to('periodicity/search/return/json/') }}");

    // Autocomplete subgenres
    autocomplete('input-subgenre_name', 'input-subgenre_id', "{{ URL::to('subgenre/search/return/json/' . Config::get('constants.genres.magazines')) }}");
</script>
@endpush

