@extends('layouts.app', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard'), 'showSearch' => false])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 col-md-12 col-sm-12">
                <div class="card card-stats">
                    <div class="card-header card-header-warning card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">photo_album</i>
                        </div>
                        <p class="card-category">Quadrinhos na coleção</p>
                        <h3 class="card-title">
                            <span style="font-weight: bold; color: #93a1a1">{{ number_format($issues[0]->count_issues, 0, ',', '.') }}</span>
                        </h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <a href="{{ URL('issue/comics') }}">Quadrinhos</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12">
                <div class="card card-stats">
                    <div class="card-header card-header-success card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">book</i>
                        </div>
                        <p class="card-category">Livros na coleção</p>
                        <h3 class="card-title">
                            <span style="font-weight: bold; color: #93a1a1">{{ number_format($issues[1]->count_issues, 0, ',', '.') }}</span>
                        </h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <a href="{{ URL('issue/books') }}">Livros</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12">
                <div class="card card-stats">
                    <div class="card-header card-header-danger card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">menu_book</i>
                        </div>
                        <p class="card-category">Revistas na coleção</p>
                        <h3 class="card-title">
                            <span style="font-weight: bold; color: #93a1a1">{{ number_format($issues[2]->count_issues, 0, ',', '.') }}</span>
                        </h3>
                    </div>
                    <div class="card-footer">
                        <div class="stats">
                            <a href="{{ URL('issue/magazines') }}">Revistas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-chart">
                    <div class="card-header card-header-success">
                        <div class="ct-chart" id="dailyReadedsChart"></div>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">
                            <span style="color: #93a1a1">Páginas lidas por dia</span>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-chart">
                    <div class="card-header card-header-warning">
                        <div class="ct-chart" id="monthlyReadedsChart"></div>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">
                            <span style="color: #93a1a1">Páginas lidas por mês</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-header card-header-tabs card-header-primary">
                        <div class="nav-tabs-navigation">
                            <div class="nav-tabs-wrapper">
                                <span class="nav-tabs-title">Lista de leitura - quadrinhos</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="profile">
                                <table class="table">
                                    <tbody>
                                        @foreach($reading_list as $issue)
                                        <tr>
                                            <td style="width: 50px" class="text-center">
                                                <a href="{{ URL::to('issue/comics/' . $issue->id) }}">
                                                    <img src="{{ $issue->image != '' ? url("storage/covers/{$issue->image}") : url('storage/covers/blank.png') }}"
                                                        style="max-width: 50px; height: 50px;">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ URL::to('issue/comics/' . $issue->id) }}" style="font-weight: bold">{{ periodicsTitle($issue->name, $issue->issue_number) }}</a><br>
                                                {{ periodicsDetails($issue->publisher_name, $issue->issue_count, $issue->date_publication) }}
                                            </td>
                                            <td>
                                                Publicado há {{ showDateInterval($issue->date_publication, date('Y-m-d')) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header card-header-tabs card-header-primary">
                        <div class="nav-tabs-navigation">
                            <div class="nav-tabs-wrapper">
                                <span class="nav-tabs-title">Adicionados na coleção</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="profile">
                                <table class="table">
                                    <tbody>
                                        @foreach($collection as $issue)
                                        <tr>
                                            <td style="width: 50px" class="text-center">
                                                <a href="{{ URL::to('issue/' . typeName($issue->type_id) . '/' . $issue->id) }}">
                                                    <img src="{{ $issue->image != '' ? url("storage/covers/{$issue->image}") : url('storage/covers/blank.png') }}"
                                                        style="max-width: 50px; height: 50px;">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ URL::to('issue/' . typeName($issue->type_id) . '/' . $issue->id) }}"
                                                    style="font-weight: bold">
                                                    @if($issue->type_id == 2)
                                                    {{ $issue->name }}
                                                    @else
                                                    {{ periodicsTitle($issue->name, $issue->issue_number) }}
                                                    @endif
                                                </a><br>
                                                {{ periodicsDetails($issue->publisher_name, $issue->issue_count, $issue->date_publication) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
<script>
    $(document).ready(function() {
        dataDailyReadedsChart = {
            labels: [
                @foreach($pages_daily as $pages)'{{ $pages->readed_date }}', @endforeach
            ],
            series: [
                [@foreach($pages_daily as $pages){{ $pages->pages_sum }}, @endforeach]
            ]
        };

        dataMonthlyReadedsChart = {
            labels: [
                @foreach($pages_monthly as $pages)'{{ $pages->readed_date }}', @endforeach
            ],
            series: [
                [@foreach($pages_monthly as $pages){{ $pages->pages_sum }}, @endforeach]
            ]
        };

        md.initDashboardPageCharts(dataDailyReadedsChart, 1000, dataMonthlyReadedsChart, 5000);
    });
</script>
@endpush
