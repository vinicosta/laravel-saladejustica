<div class="container">
    <div class="row">
    @if($issues)
        @foreach($issues as $issue)
            <div class="col-lg-6 col-md-4 col-sm-2">
                <div class="row">
                    @switch($issue->result)
                        @case('titles')
                            {{-- Mark title as "reading" --}}
                            <div style="display:{{ !$issue->reading ? 'block' : 'none' }}" id="btn-reading-{{ $issue->id }}">
                                <button type="button" class="btn btn-danger btn-link" href="#" data-original-title="" title="Adicionar à minha lista de leitura" onclick="reading({{ $issue->id }})">
                                    <i class="material-icons">playlist_add</i>
                                    <div class="ripple-container"></div>
                                </button>
                            </div>

                            {{-- Uncheck title as "reading" --}}
                            <div style="display:{{ $issue->reading ? 'block' : 'none' }}" id="btn-unreading-{{ $issue->id }}">
                                <a rel="tooltip" class="btn btn-success btn-link" href="#" data-original-title=""
                                    title="Excluir da minha lista de leitura" onclick="unreading({{ $issue->id }})">
                                    <i class="material-icons">delete_sweep</i>
                                    <div class="ripple-container"></div>
                                </a>
                            </div>

                            @break
                        @case('issues')
                            {{-- Include issue in collection --}}
                            <div style="display:{{ !$issue->collection ? 'block' : 'none' }}" id="btn-collection-{{ $issue->id }}">
                                <button type="button" class="btn btn-danger btn-link" href="#" data-original-title=""
                                    title="Adicionar à minha coleção" onclick="collection({{ $issue->id }})">
                                    <i class="material-icons">add_circle</i>
                                    <div class="ripple-container"></div>
                                </button>
                            </div>

                            {{-- Exclude issue from collection --}}
                            <div style="display:{{ $issue->collection ? 'block' : 'none' }}" id="btn-uncollection-{{ $issue->id }}">
                                <button type="button" class="btn btn-danger btn-link" href="#" data-original-title=""
                                    title="Remover da minha coleção" onclick="uncollection({{ $issue->id }})">
                                    <i class="material-icons">remove_circle</i>
                                    <div class="ripple-container"></div>
                                </button>
                            </div>

                            {{-- Mark issue as "readed" --}}
                            <div style="display:{{ !$issue->readed ? 'block' : 'none' }}" id="btn-readed-{{ $issue->id }}">
                                <button type="button" class="btn btn-danger btn-link" href="#" data-original-title=""
                                    title="Marcar como lido" onclick="readed({{ $issue->id }}, {{ $issue->title_id }})">
                                    <i class="material-icons">visibility</i>
                                    <div class="ripple-container"></div>
                                </button>
                            </div>
                            
                            {{-- Uncheck issue as "readed" --}}
                            <div style="display:{{ $issue->readed ? 'block' : 'none' }}" id="btn-unreaded-{{ $issue->id }}">
                                <button type="button" class="btn btn-danger btn-link" href="#" data-original-title=""
                                    title="Mascar como não lido" onclick="unreaded({{ $issue->id }})">
                                    <i class="material-icons">visibility_off</i>
                                    <div class="ripple-container"></div>
                                </button>
                            </div>

                            @break
                    @endswitch

                </div>
                <div>
                    <a href="{{ $issue->result == 'titles' ? URL::to('issue/comics/title/' . $issue->id) : URL::to('issue/comics/' . $issue->id) }}">
                        <img src="{{ $issue->image != '' ? url("storage/covers/{$issue->image}") : url('storage/covers/blank.png') }}" style="max-width: 200px; height: 200px;">
                    </a>
                </div>
                <div style="font-weight: bold">
                    {{ periodicsTitle($issue->name, $issue->issue_number) }}
                </div>
                <div>
                    {{ periodicsDetails($issue->publisher_name, $issue->issue_count, $issue->date_publication) }}
                </div>
            </div>
        @endforeach
    @endif
    </div>
</div>
