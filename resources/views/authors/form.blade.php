@extends('layouts.app', ['activePage' => 'author-management', 'titlePage' => __('Gestāo de autores'), 'showSearch' => false])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="{{ $author->id ? route('author.update', $author) : route('author.store') }}" autocomplete="off" class="form-horizontal">
                    @csrf

                    @if($author->id)
                        @method('put')
                    @else
                        @method('post')
                    @endif

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ $author->id ? __('Editar autor') : __('Adicionar autor') }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ route('author.index') }}"
                                        class="btn btn-sm btn-primary">{{ __('Voltar') }}</a>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-name">{{ __('Nome') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            name="name" id="input-name" type="text" placeholder="{{ __('Nome') }}"
                                            value="{{ old('name', $author->name) }}" required="true" aria-required="true" />
                                        @if ($errors->has('name'))
                                        <span id="name-error" class="error text-danger"
                                            for="input-name">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
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
    $('#input-name').focus();
</script>
@endpush
