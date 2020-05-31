@extends('layouts.app', ['activePage' => 'user-management', 'titlePage' => __('Usuários'), 'showSearch' => true, 'model' => 'user'])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">{{ __('Usuários') }}</h4>
                        <p class="card-category"> {{ __('Cadastro de usuários do sistema') }}</p>
                    </div>
                    <div class="card-body">
                        @include('layouts.status')
                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary">
                                    <i class="material-icons" style="color: white">post_add</i>{{ __('Adicionar') }}
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class=" text-primary">
                                    <th>
                                        {{ __('Nome') }}
                                    </th>
                                    <th>
                                        {{ __('E-mail') }}
                                    </th>
                                    <th>
                                        {{ __('Data de criação') }}
                                    </th>
                                    <th class="text-right">
                                        {{ __('Ações') }}
                                    </th>
                                </thead>
                                <tbody>
                                    @include('users.grid', ['users' => $users])
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
    search('search', "{{ URL::to('user/search/return/view/') }}", 'tbody');
</script>
@endpush
