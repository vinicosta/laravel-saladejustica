@extends('layouts.app', ['activePage' => 'comics-management', 'titlePage' => __('Quadrinhos'), 'showSearch' => false, 'model' => 'issue'])

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
                        <h4 class="card-title ">{{ __($issue->issue_number != '' ? $issue->name . ' #' . $issue->issue_number : $issue->name) }}</h4>
                        <p class="card-category">{{ $issue->subtitle }}</p>
                        <p class="card-category">{{ periodicsDetails($issue->publisher_name, 0, $issue->date_publication, true) }}</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 text-left">
                                {{-- Edit issue --}}
                                <a href="{{ URL::to('issue/comics/' . $issue->id . '/edit') }}" class="btn btn-sm btn-primary" title="Editar">
                                    <i class="material-icons" style="color: white">edit</i>
                                </a>

                                {{-- Delete issue --}}
                                <a href="{{ URL::to('issue/comics/' . $issue->id . '/delete') }}" class="btn btn-sm btn-danger" title="Excluir">
                                    <i class="material-icons" style="color: white">delete</i>
                                </a>

                                {{-- Include issue in collection --}}
                                <a href="#" class="btn btn-sm btn-primary" style="display:{{ !$issue->collection ? '' : 'none' }}" id="btn-collection-{{ $issue->id }}" onclick="collection({{ $issue->id }})" title="Incluir na coleção">
                                    <i class="material-icons" style="color: white">add_circle</i>
                                </a>

                                {{-- Exclude issue from collection --}}
                                <a href="#" class="btn btn-sm btn-primary" style="display:{{ $issue->collection ? '' : 'none' }}" id="btn-uncollection-{{ $issue->id }}" onclick="uncollection({{ $issue->id }})" title="Excluir da coleção">
                                    <i class="material-icons" style="color: white">remove_circle</i>
                                </a>

                                {{-- Mark issue as readed --}}
                                <a href="#" class="btn btn-sm btn-primary" style="display:{{ !$issue->readed ? '' : 'none' }}" id="btn-readed-{{ $issue->id }}" onclick="readed({{ $issue->id }}, {{ $issue->title_id }})" title="Marcar como lido">
                                    <i class="material-icons" style="color: white">visibility</i>
                                </a>

                                {{-- Uncheck issue as readed --}}
                                <a href="#" class="btn btn-sm btn-primary" style="display:{{ $issue->readed ? '' : 'none' }}" id="btn-unreaded-{{ $issue->id }}" onclick="unreaded({{ $issue->id }})" title="Marcar como não lido">
                                    <i class="material-icons" style="color: white">visibility_off</i>
                                </a>
                            </div>
                            <div class="col-lg-6 col-sm-12 text-right">
                                {{-- Show first issue --}}
                                @if($issue->first_issue)
                                <a href="{{ URL::to('issue/comics/' . $issue->first_issue) }}" class="btn btn-sm btn-warning">
                                    <i class="material-icons" style="color: white" title="Primeiro">first_page</i>
                                </a>
                                @endif

                                {{-- Show previous issue --}}
                                @if($issue->previous_issue)
                                <a href="{{ URL::to('issue/comics/' . $issue->previous_issue) }}" class="btn btn-sm btn-warning">
                                    <i class="material-icons" style="color: white" title="Anterior">chevron_left</i>
                                </a>
                                @endif

                                {{-- Show all issues of title --}}
                                <a href="{{ URL::to('issue/comics/title/' . $issue->title_id) }}" class="btn btn-sm btn-warning" title="Ver todos">
                                    <i class="material-icons" style="color: white">grid_on</i>
                                </a>

                                {{-- Show next issue --}}
                                @if($issue->next_issue)
                                <a href="{{ URL::to('issue/comics/' . $issue->next_issue) }}" class="btn btn-sm btn-warning">
                                    <i class="material-icons" style="color: white" title="Próximo">chevron_right</i>
                                </a>
                                @endif

                                {{-- Show last issue --}}
                                @if($issue->last_issue)
                                <a href="{{ URL::to('issue/comics/' . $issue->last_issue) }}" class="btn btn-sm btn-warning">
                                    <i class="material-icons" style="color: white" title="Último">last_page</i>
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="row"><div>&nbsp;</div></div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12" style="text-align: center">
                                <img src="{{ $issue->image != '' ? url("storage/covers/{$issue->image}") : url('storage/covers/blank.png') }}"
                                    style="max-width: 400px; height: 400px;">
                            </div>
                            <div class="col-lg-8 col-sm-12">
                                <p>{{ $issue->synopsis != '' ? $issue->synopsis : '(Não há sinopse cadastrada.)' }}</p>
                                <table>
                                    <tr>
                                        <td><strong>Subgênero:</strong></td>
                                        <td>{{ $issue->subgenre_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tamanho:</strong></td>
                                        <td>{{ $issue->size_name }}</td>
                                    </tr>
                                </table>
                                <p></p>
                                <p>
                                    {{ $issue->collection ? 'Adicionado na coleção em ' . strftime('%d/%m/%Y', strtotime($issue->added_date)) . '.' : '' }}<br>
                                    {{ $issue->readed ? 'Marcado como lido em ' . strftime('%d/%m/%Y', strtotime($issue->readed_date)) . '.' : '' }}
                                </p>
                            </div>
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
    $('#search').on('keyup', function(){
        $value = $(this).val();
        $.ajax({
            type: 'get',
            url: "{{ URL::to('issue/search/return/view/') }}",
            data: { 'term':$value },
            success: function(data){
                $('tbody').html(data);
            }
        });
    });

    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
</script>
@endpush
