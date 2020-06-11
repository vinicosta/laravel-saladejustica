<div class="container">
    <div class="row">
    @if($issues)
        @foreach($issues as $issue)
            <div class="col-lg-3 col-md-3 col-sm-12">
                <div>
                    <div class="container">
                        <div class="row">
                        @switch($result)
                            @case('titles')
                                {{-- Mark title as "reading" --}}
                                <div class="col-6 text-center" style="display:{{ !$issue->reading ? 'block' : 'none' }}" id="btn-reading-{{ $issue->id }}">
                                    <button class="btn btn-primary btn-fab btn-fab-mini btn-link"title="Adicionar à minha lista de leitura" onclick="reading({{ $issue->id }})">
                                        <i class="material-icons">playlist_add</i>
                                    </button>
                                </div>

                                {{-- Uncheck title as "reading" --}}
                                <div class="col-6 text-center" style="display:{{ $issue->reading ? 'block' : 'none' }}" id="btn-unreading-{{ $issue->id }}">
                                    <button class="btn btn-primary btn-fab btn-fab-mini btn-link"title="Excluir da minha lista de leitura" onclick="unreading({{ $issue->id }})">
                                        <i class="material-icons">delete_sweep</i>
                                    </button>
                                </div>

                                @break
                            @case('issues')
                                {{-- Include issue in collection --}}
                                <div class="col-6 text-center" style="display:{{ !$issue->collection ? 'block' : 'none' }}" id="btn-collection-{{ $issue->id }}">
                                    <button class="btn btn-primary btn-fab btn-fab-mini btn-link" title="Adicionar à minha coleção" onclick="collection({{ $issue->id }})">
                                        <i class="material-icons">add_circle</i>
                                    </button>
                                </div>

                                {{-- Exclude issue from collection --}}
                                <div class="col-6 text-center" style="display:{{ $issue->collection ? 'block' : 'none' }}" id="btn-uncollection-{{ $issue->id }}">
                                    <button class="btn btn-primary btn-fab btn-fab-mini btn-link"title="Remover da minha coleção" onclick="uncollection({{ $issue->id }})">
                                        <i class="material-icons">remove_circle</i>
                                    </button>
                                </div>

                                {{-- Mark issue as "readed" --}}
                                <div class="col-6 text-center" style="display:{{ !$issue->readed ? 'block' : 'none' }}" id="btn-readed-{{ $issue->id }}">
                                    <button class="btn btn-primary btn-fab btn-fab-mini btn-link"title="Marcar como lido" onclick="readed({{ $issue->id }}, {{ $issue->title_id }})">
                                        <i class="material-icons">visibility</i>
                                    </button>
                                </div>

                                {{-- Uncheck issue as "readed" --}}
                                <div class="col-6 text-center" style="display:{{ $issue->readed ? 'block' : 'none' }}" id="btn-unreaded-{{ $issue->id }}">
                                    <button class="btn btn-primary btn-fab btn-fab-mini btn-link"title="Mascar como não lido" onclick="unreaded({{ $issue->id }})">
                                        <i class="material-icons">visibility_off</i>
                                    </button>
                                </div>

                                @break
                        @endswitch
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <a href="{{ $result == 'titles' ? URL::to('title/magazines/' . $issue->id) : URL::to('issue/magazines/' . $issue->id) }}">
                        <img src="{{ $issue->image != '' ? url("storage/covers/{$issue->image}") : url('storage/covers/blank.png') }}" style="max-width: 200px; height: 200px;">
                    </a>
                </div>
                <div class="text-center" style="font-weight: bold">
                    {{ periodicsTitle($issue->name, $issue->issue_number) }}
                </div>
                <div class="text-center">
                    {{ periodicsDetails($issue->publisher_name, $issue->issue_count, $issue->date_publication) }}
                </div>
            </div>
        @endforeach
    @endif
    </div>
</div>
