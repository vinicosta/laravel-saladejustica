Teste
<pre>
{{-- @php
var_dump($subgenres)
@endphp --}}
@foreach($subgenres as $subgenre)
    {{ $subgenre->genre->name }}
@endforeach
