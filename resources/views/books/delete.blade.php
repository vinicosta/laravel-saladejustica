@extends('layouts.app', ['activePage' => 'books-management', 'titlePage' => __('Livros'), 'showSearch' => false])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ url("/issue/$issue->id") }}" method="post">
                    @csrf
                    @method('delete')

                    <div class="card ">
                        <div class="card-header card-header-danger">
                            <h4 class="card-title" style="color: white">{{ __('Excluir livro') }}</h4>
                            <p class="card-category">Você tem certeza de que deseja excluir?</p>
                        </div>
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <a href="{{ URL::to('issue/books/' . $issue->id) }}" class="btn btn-sm btn-primary">
                                        <i class="material-icons" style="color: white">arrow_back</i> {{ __('Voltar') }}</a>
                                </div>
                            </div>
                            <div class="row">
                                <div>&nbsp;</div>
                            </div>
                            <div class="row">
                                @if($issue->image != '')
                                <div class="col-sm-12 col-lg-4" style="text-align: center">
                                    <img src="{{ asset("storage/covers/{$issue->image}") }}" style="max-width: 200px; height: 200px;">
                                </div>
                                @endif
                                <div class="col-sm-12 col-lg-8">
                                    <p><strong>{{ $issue->name }}</strong></p>
                                    {{ $issue->subtitle != '' ? "<p>$issue->subtitle</p" : '' }}
                                    <p>{{ $issue->publisher_name }}</p>
                                    <p></p>
                                    <p>
                                        @if($issue->readings)
                                        Incluído na lista de leitura de {{ $issue->readings > 1 ? "$issue->readings usuários" : "$issue->readings usuário" }}<br>
                                        @endif

                                        @if($issue->collections)
                                        Incluído na coleção de {{ $issue->collections > 1 ? "$issue->collections usuários" : "$issue->collections usuário" }}<br>
                                        @endif

                                        @if($issue->readeds)
                                        Marcado como lido por {{ $issue->readeds > 1 ? "$issue->readeds usuários" : "$issue->readeds usuário" }}<br>
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

