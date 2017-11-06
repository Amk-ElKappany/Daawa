@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{ trans('butterfly-effect/news::news/'.$language['admin'].'/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('bootstrap-daterange', 'bootstrap/css/daterangepicker-bs3.css', 'style') }}

{{ Asset::queue('moment', 'moment/js/moment.js', 'jquery') }}
{{ Asset::queue('data-grid', 'cartalyst/js/data-grid.js', 'jquery') }}
{{ Asset::queue('underscore', 'underscore/js/underscore.js', 'jquery') }}
{{ Asset::queue('index', 'butterfly-effect/news::news/js/index.js', 'platform') }}
{{ Asset::queue('bootstrap-daterange', 'bootstrap/js/daterangepicker.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
@stop

{{-- Inline styles --}}
@section('styles')
@parent
@stop

{{-- Page content --}}
@section('page')

{{-- Grid --}}
<section class="panel panel-default panel-grid">

	{{-- Grid: Header --}}
	<header class="panel-heading">

		<nav class="navbar navbar-default navbar-actions">

			<div class="container-fluid">

				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#actions">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>

					<span class="navbar-brand">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/common.title') }}</span>

				</div>

				{{-- Grid: Actions --}}
				<div class="collapse navbar-collapse" id="actions">

					<ul class="nav navbar-nav navbar-left">

						<li class="disabled">
							<a class="disabled" data-grid-bulk-action="disable" data-toggle="tooltip" data-original-title="{{ trans($language['admin'].'/action.bulk.disable') }}">
								<i class="fa fa-eye-slash"></i> <span class="visible-xs-inline">{{ trans($language['admin'].'/action.bulk.disable') }}</span>
							</a>
						</li>

						<li class="disabled">
							<a data-grid-bulk-action="enable" data-toggle="tooltip" data-original-title="{{ trans($language['admin'].'/action.bulk.enable') }}">
								<i class="fa fa-eye"></i> <span class="visible-xs-inline">{{ trans($language['admin'].'/action.bulk.enable') }}</span>
							</a>
						</li>

						<li class="danger disabled">
							<a data-grid-bulk-action="delete" data-toggle="tooltip" data-target="modal-confirm" data-original-title="{{ trans($language['admin'].'/action.bulk.delete') }}">
								<i class="fa fa-trash-o"></i> <span class="visible-xs-inline">{{ trans($language['admin'].'/action.bulk.delete') }}</span>
							</a>
						</li>

						<li class="dropdown">
							<a href="#" class="dropdown-toggle tip" data-toggle="dropdown" role="button" aria-expanded="false" data-original-title="{{ trans($language['admin'].'/action.export') }}">
								<i class="fa fa-download"></i> <span class="visible-xs-inline">{{ trans($language['admin'].'/action.export') }}</span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li><a data-download="json"><i class="fa fa-file-code-o"></i> JSON</a></li>
								<li><a data-download="csv"><i class="fa fa-file-excel-o"></i> CSV</a></li>
								<li><a data-download="pdf"><i class="fa fa-file-pdf-o"></i> PDF</a></li>
							</ul>
						</li>

						<li class="primary">
							<a href="{{ route('admin.butterfly-effect.news.news.create') }}" data-toggle="tooltip" data-original-title="{{ trans($language['admin'].'/action.create') }}">
								<i class="fa fa-plus"></i> <span class="visible-xs-inline">{{ trans($language['admin'].'/action.create') }}</span>
							</a>
						</li>

					</ul>

					{{-- Grid: Filters --}}
					<form class="navbar-form navbar-right" method="post" accept-charset="utf-8" data-search data-grid="news" role="form">

						<div class="input-group">

							<span class="input-group-btn">

								<button class="btn btn-default" type="button" disabled>
									{{ trans($language['admin'].'/common.filters') }}
								</button>

								<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>

								<ul class="dropdown-menu" role="menu">

									<li>
										<a data-grid="news" data-filter="enabled:1" data-label="enabled::{{ trans($language['admin'].'/common.all_enabled') }}" data-reset>
											<i class="fa fa-eye"></i> {{ trans($language['admin'].'/common.show_enabled') }}
										</a>
									</li>

									<li>
										<a data-toggle="tooltip" data-placement="top" data-original-title="" data-grid="news" data-filter="enabled:0" data-label="enabled::{{ trans($language['admin'].'/common.all_disabled') }}" data-reset>
											<i class="fa fa-eye-slash"></i> {{ trans($language['admin'].'/common.show_disabled') }}
										</a>
									</li>

									<li class="divider"></li>

									<li>
										<a data-grid-calendar-preset="day">
											<i class="fa fa-calendar"></i> {{ trans($language['admin'].'/date.day') }}
										</a>
									</li>

									<li>
										<a data-grid-calendar-preset="week">
											<i class="fa fa-calendar"></i> {{ trans($language['admin'].'/date.week') }}
										</a>
									</li>

									<li>
										<a data-grid-calendar-preset="month">
											<i class="fa fa-calendar"></i> {{ trans($language['admin'].'/date.month') }}
										</a>
									</li>

								</ul>

								<button class="btn btn-default hidden-xs" type="button" data-grid-calendar data-range-filter="created_at">
									<i class="fa fa-calendar"></i>
								</button>

							</span>

							<input class="form-control" name="filter" type="text" placeholder="{{ trans($language['admin'].'/common.search') }}">

							<span class="input-group-btn">

								<button class="btn btn-default" type="submit">
									<span class="fa fa-search"></span>
								</button>

								<button class="btn btn-default" data-grid="news" data-reset>
									<i class="fa fa-refresh fa-sm"></i>
								</button>

							</span>

						</div>

					</form>

				</div>

			</div>

		</nav>

	</header>

	<div class="panel-body">

		{{-- Grid: Applied Filters --}}
		<div class="btn-toolbar" role="toolbar" aria-label="data-grid-applied-filters">

			<div id="data-grid_applied" class="btn-group" data-grid="news"></div>

		</div>

	</div>

	{{-- Grid: Table --}}
	<div class="table-responsive">

		<table id="data-grid" class="table table-hover" data-source="{{ route('admin.butterfly-effect.news.news.grid') }}" data-grid="news">
			<thead>
				<tr>
					<th><input data-grid-checkbox="all" type="checkbox"></th>
					<th class="sortable" data-sort="image">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.image') }}</th>
					<th class="sortable" data-sort="newscategory_id">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.newscategory_id') }}</th>
					<th class="sortable" data-sort="title_en">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.title_en') }}</th>
					@foreach ($system_languages as $system_language)
						<th class="sortable" data-sort="title_{{$system_language}}">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.title_'.$system_language) }}</th>
					@endforeach
					<th class="sortable" data-sort="date">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.date') }}</th>
					<th class="sortable" data-sort="views">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.views') }}</th>
					<th class="sortable" data-sort="has_video">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.has_video') }}</th>
					<th class="sortable" data-sort="has_sound">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.has_sound') }}</th>
					<th class="sortable" data-sort="active">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.active') }}</th>
					<th class="sortable" data-sort="home">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.home') }}</th>
					<th class="sortable" data-sort="created_by">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.created_by') }}</th>
					<th class="sortable" data-sort="updated_by">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.updated_by') }}</th>
					<th class="sortable" data-sort="created_at">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.created_at') }}</th>
					<th>{{ trans($language['admin'].'/model.edit') }}</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>

	</div>

	<footer class="panel-footer clearfix">

		{{-- Grid: Pagination --}}
		<div id="data-grid_pagination" data-grid="news"></div>

	</footer>

	{{-- Grid: templates --}}
	@include('butterfly-effect/news::news/grid/index/results')
	@include('butterfly-effect/news::news/grid/index/pagination')
	@include('butterfly-effect/news::news/grid/index/filters')
	@include('butterfly-effect/news::news/grid/index/no_results')

</section>

@if (config('platform.app.help'))
	@include('butterfly-effect/news::news/help')
@endif

@stop
