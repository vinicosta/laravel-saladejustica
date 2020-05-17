<div class="sidebar">{{-- data-color="orange" data-background-color="base02"
data-image="{{ asset('material') }}/img/sidebar-1.jpg" --}}
    <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
    <div class="logo">
        <a href="{{ route('home') }}" class="simple-text logo-normal">
            <i><img style="width:25px; border: 1px solid #93a1a1" src="{{ asset('material') }}/img/saladejustica.png"></i>
            {{ __(config('app.name')) }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="material-icons">dashboard</i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            @php
                $basicsActive = ($activePage == 'user-management' || $activePage == 'genre-management' || $activePage == 'subgenre-management' || $activePage == 'author-management' || $activePage == 'publisher-management' || $activePage == 'size-management') ? true : false
            @endphp
            <li class="nav-item {{ $basicsActive ? 'active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#basics" aria-expanded="{{ $basicsActive ? 'true' : 'false' }}">
                    <i class="material-icons">table_chart</i>
                    <p>{{ __('Cadastros') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse {{ $basicsActive ? 'show' : '' }}" id="basics">
                    <ul class="nav">
                        <li class="nav-item {{ $basicsActive ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('user.index') }}">
                                <i class="material-icons">person</i>
                                <span class="sidebar-normal"> {{ __('Usuários') }} </span>
                            </a>
                        </li>
                        <li class="nav-item {{ $basicsActive ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('genre.index') }}">
                                <i class="material-icons">style</i>
                                <span class="sidebar-normal"> {{ __('Gêneros') }} </span>
                            </a>
                        </li>
                        <li class="nav-item {{ $basicsActive ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('subgenre.index') }}">
                                <i class="material-icons">account_tree</i>
                                <span class="sidebar-normal"> {{ __('Subgêneros') }} </span>
                            </a>
                        </li>
                        <li class="nav-item {{ $basicsActive ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('author.index') }}">
                                <i class="material-icons">recent_actors</i>
                                <span class="sidebar-normal"> {{ __('Autores') }} </span>
                            </a>
                        </li>
                        <li class="nav-item {{ $basicsActive ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('publisher.index') }}">
                                <i class="material-icons">business</i>
                                <span class="sidebar-normal"> {{ __('Editoras') }} </span>
                            </a>
                        </li>
                        <li class="nav-item {{ $basicsActive ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('size.index') }}">
                                <i class="material-icons">format_size</i>
                                <span class="sidebar-normal"> {{ __('Tamanhos') }} </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @php
            $collectionActive = ($activePage == 'comics-management' || $activePage == 'books-management' || $activePage == 'magazines-management') ? true : false
            @endphp
            <li class="nav-item {{ $collectionActive ? 'active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#collections" aria-expanded="{{ $collectionActive ? 'true' : 'false' }}">
                    <i class="material-icons">library_books</i>
                    <p>{{ __('Acervo') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse {{ $collectionActive ? 'show' : '' }}" id="collections">
                    <ul class="nav">
                        <li class="nav-item {{ $collectionActive ? 'active' : '' }}">
                            <a class="nav-link" href="{{ URL::to('issue/comics') }}">
                                <i class="material-icons">photo_album</i>
                                <span class="sidebar-normal"> {{ __('Quadrinhos') }} </span>
                            </a>
                        </li>
                        <li class="nav-item {{ $collectionActive ? 'active' : '' }}">
                            <a class="nav-link" href="{{ URL::to('issue/books') }}">
                                <i class="material-icons">book</i>
                                <span class="sidebar-normal"> {{ __('Livros') }} </span>
                            </a>
                        </li>
                        <li class="nav-item {{ $collectionActive ? 'active' : '' }}">
                            <a class="nav-link" href="{{ URL::to('issue/magazines') }}">
                                <i class="material-icons">menu_book</i>
                                <span class="sidebar-normal"> {{ __('Revistas') }} </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
