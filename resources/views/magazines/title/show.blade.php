@extends('layouts.app', ['activePage' => 'magazines-management', 'titlePage' => __('Revistas'), 'showSearch' => false, 'model' => 'title'])

@section('content')
@php
    setlocale(LC_ALL, 'pt_BR');
@endphp
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">
                            {{ $title->name }}
                            {{ $title->publisher_name != '' ? " - $title->publisher_name" : '' }}
                            - {{ $title->issues_count }} edições
                        </h4>
                        <p class="card-category">
                            Periodicidade: {{ $title->periodicity_name }}
                            {{ $title->subgenre_name != '' ? ' | subgênero: ' . $title->subgenre_name : '' }}
                        </p>
                    </div>
                    <div class="card-body">
                        @include('layouts.status')
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 text-lg-left text-sm-center">
                                {{-- Create issue --}}
                                <a href="{{ URL::to('title/magazines/create/' . $title->id) }}" class="btn btn-sm btn-primary" title="Criar edição">
                                    <i class="material-icons" style="color: white">note_add</i>
                                </a>

                                {{-- Edit title --}}
                                <a href="{{ URL::to('title/magazines/' . $title->id . '/edit') }}" class="btn btn-sm btn-primary" title="Editar título">
                                    <i class="material-icons" style="color: white">edit</i>
                                </a>

                                {{-- Delete title --}}
                                <a href="{{ URL::to('title/magazines/' . $title->id . '/delete') }}" class="btn btn-sm btn-danger" title="Excluir título">
                                    <i class="material-icons" style="color: white">delete</i>
                                </a>

                                {{-- Mark title in reading --}}
                                <a href="#" class="btn btn-sm btn-primary" style="display:{{ !$title->reading ? '' : 'none' }}"
                                    id="btn-reading-{{ $title->id }}" onclick="reading({{ $title->id }})" title="Incluir na lista de leitura">
                                    <i class="material-icons" style="color: white">playlist_add</i>
                                </a>

                                {{-- Uncheck title from reading --}}
                                <a href="#" class="btn btn-sm btn-primary" style="display:{{ $title->reading ? '' : 'none' }}"
                                    id="btn-unreading-{{ $title->id }}" onclick="unreading({{ $title->id }})" title="Excluir da lista de leitura">
                                    <i class="material-icons" style="color: white">delete_sweep</i>
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <div class="container">
                                <div class="row">
                                    @foreach($issues as $issue)
                                    <div class="col-lg-3 col-md-3 col-sm-12">
                                        <div>
                                            <div class="container">
                                                <div class="row">
                                                    {{-- Include issue in collection --}}
                                                    <div class="col-6 text-center" style="display:{{ !$issue->collection ? 'block' : 'none' }}"
                                                        id="btn-collection-{{ $issue->id }}">
                                                        <button class="btn btn-primary btn-fab btn-fab-mini btn-link" title="Adicionar à minha coleção"
                                                            onclick="collection({{ $issue->id }})">
                                                            <i class="material-icons">add_circle</i>
                                                        </button>
                                                    </div>

                                                    {{-- Exclude issue from collection --}}
                                                    <div class="col-6 text-center" style="display:{{ $issue->collection ? 'block' : 'none' }}"
                                                        id="btn-uncollection-{{ $issue->id }}">
                                                        <button class="btn btn-primary btn-fab btn-fab-mini btn-link" title="Remover da minha coleção"
                                                            onclick="uncollection({{ $issue->id }})">
                                                            <i class="material-icons">remove_circle</i>
                                                        </button>
                                                    </div>

                                                    {{-- Mark issue as "readed" --}}
                                                    <div class="col-6 text-center" style="display:{{ !$issue->readed ? 'block' : 'none' }}"
                                                        id="btn-readed-{{ $issue->id }}">
                                                        <button class="btn btn-primary btn-fab btn-fab-mini btn-link" title="Marcar como lido"
                                                            onclick="readed({{ $issue->id }}, {{ $issue->title_id }})">
                                                            <i class="material-icons">visibility</i>
                                                        </button>
                                                    </div>

                                                    {{-- Uncheck issue as "readed" --}}
                                                    <div class="col-6 text-center" style="display:{{ $issue->readed ? 'block' : 'none' }}"
                                                        id="btn-unreaded-{{ $issue->id }}">
                                                        <button class="btn btn-primary btn-fab btn-fab-mini btn-link" title="Mascar como não lido"
                                                            onclick="unreaded({{ $issue->id }})">
                                                            <i class="material-icons">visibility_off</i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <a
                                                href="{{ URL::to('issue/magazines/' . $issue->id) }}">
                                                <img src="{{ $issue->image != '' ? url("storage/covers/{$issue->image}") : url('storage/covers/blank.png') }}"
                                                    style="max-width: 200px; height: 200px;">
                                            </a>
                                        </div>
                                        <div class="text-center" style="font-weight: bold">
                                            {{ periodicsTitle($issue->name, $issue->issue_number) }}
                                        </div>
                                        <div class="text-center">
                                            {{ $issue->date_publication != '' ? strftime('%B de %Y', strtotime($issue->date_publication)) : '' }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
