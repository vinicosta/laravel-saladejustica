@extends('layouts.app', ['activePage' => 'size-management', 'titlePage' => __('Gestāo de tamanhos'), 'showSearch' =>
false])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="{{ $size->id ? route('size.update', $size) : route('size.store') }}"
                    autocomplete="off" class="form-horizontal">
                    @csrf

                    @if($size->id)
                    @method('put')
                    @else
                    @method('post')
                    @endif

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ $size->id ? __('Editar tamanho') : __('Adicionar tamanho') }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ route('size.index') }}" class="btn btn-sm btn-primary">
                                        <i class="material-icons" style="color: white">arrow_back</i> {{ __('Voltar') }}</a>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-name">{{ __('Nome') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            name="name" id="input-name" type="text" placeholder="{{ __('Informe o nome do tamanho') }}"
                                            value="{{ old('name', $size->name) }}" required="true" aria-required="true" />
                                        @if ($errors->has('name'))
                                        <span id="name-error" class="error text-danger"
                                            for="input-name">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-input-type_id">{{ __('Tipo') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group {{ $errors->has('type_id') ? ' has-danger' : '' }}">
                                        <select class="form-control {{ $errors->has('type_id') ? ' is-invalid' : '' }}" data-style="btn btn-link" name="type_id" id="input-type_id" required="true" aria-required="true" placeholder="{{ __('Informe o tipo do tamanho') }}">
                                            <option></option>
                                            <option value="1" {{ $size->type_id == 1 ? 'selected' : '' }}>Gibis</option>
                                            <option value="2" {{ $size->type_id == 2 ? 'selected' : '' }}>Livros</option>
                                            <option value="3" {{ $size->type_id == 3 ? 'selected' : '' }}>Revistas</option>
                                        </select>
                                        @if ($errors->has('type_id'))
                                        <span id="type_id-error" class="error text-danger"
                                            for="input-type_id">{{ $errors->first('type_id') }}</span>
                                        @endif
                                    </div>
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
    $('#input-name').focus();
</script>
@endpush
