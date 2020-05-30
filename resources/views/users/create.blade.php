@extends('layouts.app', ['activePage' => 'user-management', 'titlePage' => __('Gestāo de Usuários'), 'showSearch' => false])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="{{ route('user.store') }}" autocomplete="off" class="form-horizontal">
                    @csrf
                    @method('post')

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Adicionar Usuário') }}</h4>
                            <p class="card-category"></p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary">
                                        <i class="material-icons" style="color: white">arrow_back</i> {{ __('Voltar') }}
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-name">{{ __('Nome') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            name="name" id="input-name" type="text" placeholder="{{ __('Informe o nome do usuário') }}"
                                            value="{{ old('name') }}" required="true" aria-required="true" />
                                        @if ($errors->has('name'))
                                        <span id="name-error" class="error text-danger"
                                            for="input-name">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label" for="input-email">{{ __('E-mail') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            name="email" id="input-email" type="email" placeholder="{{ __('Informe o e-mail do usuário') }}"
                                            value="{{ old('email') }}" required />
                                        @if ($errors->has('email'))
                                        <span id="email-error" class="error text-danger"
                                            for="input-email">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label"
                                    for="input-password">{{ __(' Senha') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                            input type="password" name="password" id="input-password"
                                            placeholder="{{ __('Informe a senha do usuário') }}" value="" required />
                                        @if ($errors->has('password'))
                                        <span id="name-error" class="error text-danger"
                                            for="input-name">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label"
                                    for="input-password-confirmation">{{ __('Confirme a senha') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <input class="form-control" name="password_confirmation"
                                            id="input-password-confirmation" type="password"
                                            placeholder="{{ __('Informe novamente a senha do usuário') }}" value="" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="material-icons" style="color: white">save</i> {{ __('Salvar') }}
                            </button>
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
