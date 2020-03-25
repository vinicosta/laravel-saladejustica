@foreach($sizes as $size)
<tr>
    <td>
        {{ $size->name }}
    </td>
    <td>
        @switch($size->id_type)
            @case(1)
                Gibis
            @break

            @case(2)
                Livros
            @break

            @case(3)
                Revistas
            @break
        @endswitch
    </td>
    <td class="td-actions text-right">
        <form action="{{ url("/size/$size->id") }}" method="post">
            @csrf
            @method('delete')

            <a rel="tooltip" class="btn btn-success btn-link" href="{{ url("/size/$size->id/edit") }}"
                data-original-title="" title="">
                <i class="material-icons">edit</i>
                <div class="ripple-container"></div>
            </a>
            <button type="button" class="btn btn-danger btn-link" data-original-title="" title=""
                onclick="confirm('{{ __("Tem certeza de que deseja excluir o tamanho?") }}') ? this.parentElement.submit() : ''">
                <i class="material-icons">close</i>
                <div class="ripple-container"></div>
            </button>
        </form>
    </td>
</tr>
@endforeach
