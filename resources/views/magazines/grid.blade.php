<div class="container">
    <div class="row">
    @if($issues)
        @foreach($issues as $issue)
            <div class="col-lg-3 col-md-3 col-sm-12">
                @include('magazines.issue', ['issue' => $issue, 'result' => $result])
            </div>
        @endforeach
    @endif
    </div>
</div>
