@foreach($subgenres as $subgenre)
<tr>
    <td>
        {{ $subgenre->name }}
    </td>
    <td>
        {{ $subgenre->genre->name }}
    </td>
    <td class="td-actions text-right">
        <form action="{{ url("/subgenre/$subgenre->id") }}" method="post">
            @csrf
            @method('delete')

            <a rel="tooltip" class="btn btn-success btn-link" href="{{ url("/subgenre/$subgenre->id/edit") }}"
                data-original-title="" title="Editar">
                <i class="material-icons">edit</i>
                <div class="ripple-container"></div>
            </a>
            <button type="button" class="btn btn-danger btn-link" data-original-title="" title="Excluir"
                onclick="confirm('{{ __("Tem certeza de que deseja excluir o subgênero?") }}') ? this.parentElement.submit() : ''">
                <i class="material-icons">close</i>
                <div class="ripple-container"></div>
            </button>
        </form>
    </td>
</tr>
@endforeach
