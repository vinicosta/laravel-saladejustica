@foreach($users as $user)
<tr>
    <td>
        {{ $user->name }}
    </td>
    <td>
        {{ $user->email }}
    </td>
    <td>
        {{ date('d/m/Y', strtotime($user->created_at)) }}
    </td>
    <td class="td-actions text-right">
		@if ($user->id != Auth::id())
        <form action="{{ url("/user/$user->id") }}" method="post">
            @csrf
            @method('delete')

            <a rel="tooltip" class="btn btn-success btn-link" href="{{ url("/user/$user->id/edit") }}"
                data-original-title="" title="">
                <i class="material-icons">edit</i>
                <div class="ripple-container"></div>
            </a>
            <button type="button" class="btn btn-danger btn-link" data-original-title="" title=""
                onclick="confirm('{{ __("Tem certeza de que deseja excluir o usuário?") }}') ? this.parentElement.submit() : ''">
                <i class="material-icons">close</i>
                <div class="ripple-container"></div>
            </button>
        </form>
        @else
        <a rel="tooltip" class="btn btn-success btn-link" href="{{ url("/user/$user->id/edit") }}" data-original-title=""
            title="">
            <i class="material-icons">edit</i>
            <div class="ripple-container"></div>
        </a>
        @endif
    </td>
</tr>
@endforeach
