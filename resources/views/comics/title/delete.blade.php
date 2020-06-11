@extends('layouts.app', ['activePage' => 'comics-management', 'titlePage' => __('Quadrinhos'), 'showSearch' => false])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ url("/title/$title->id") }}" method="post">
                    @csrf
                    @method('delete')

                    <div class="card ">
                        <div class="card-header card-header-danger">
                            <h4 class="card-title" style="color: white">{{ __('Excluir título de quadrinhos') }}</h4>
                            <p class="card-category">Você tem certeza de que deseja excluir?</p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ URL::to('title/comics/' . $title->id) }}" class="btn btn-sm btn-primary">
                                        <i class="material-icons" style="color: white">arrow_back</i> {{ __('Voltar') }}</a>
                                </div>
                            </div>
                            <div class="row">
                                <div>&nbsp;</div>
                            </div>
                            <div class="row">
                                @if($title->image != '')
                                <div class="col-sm-12 col-lg-4" style="text-align: center">
                                    <img src="{{ asset("storage/covers/{$title->image}") }}" style="max-width: 200px; height: 200px;">
                                </div>
                                @endif
                                <div class="col-sm-12 col-lg-8">
                                    <p><strong>{{ $title->name }}</strong></p>
                                    <p>{{ $title->publisher_name }}</p>
                                    <p>{{ $title->issues_count }} edições</p>
                                    <p></p>
                                    <p>
                                        @if($title->readings)
                                        Incluído na lista de leitura de {{ $title->readings > 1 ? "$title->readings usuários" : "$title->readings usuário" }}<br>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-danger">
                                <i class="material-icons" style="color: white">delete</i> {{ __('Excluir') }}</button>
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

</script>
@endpush

