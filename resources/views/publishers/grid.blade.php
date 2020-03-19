@foreach($publishers as $publisher)
<tr>
    <td>
        {{ $publisher->name }}
    </td>
    <td class="td-actions text-right">
        <form action="{{ url("/publisher/$publisher->id") }}" method="post">
            @csrf
            @method('delete')

            <a rel="tooltip" class="btn btn-success btn-link" href="{{ url("/publisher/$publisher->id/edit") }}"
                data-original-title="" title="">
                <i class="material-icons">edit</i>
                <div class="ripple-container"></div>
            </a>
            <button type="button" class="btn btn-danger btn-link" data-original-title="" title=""
                onclick="confirm('{{ __("Tem certeza de que deseja excluir a editora?") }}') ? this.parentElement.submit() : ''">
                <i class="material-icons">close</i>
                <div class="ripple-container"></div>
            </button>
        </form>
    </td>
</tr>
@endforeach
