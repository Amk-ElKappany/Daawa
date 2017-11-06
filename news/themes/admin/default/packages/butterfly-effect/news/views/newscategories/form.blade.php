@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{ trans("action.{$mode}") }} {{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
<script type="text/javascript">
	$(document).on('ready', function() {
		CKEDITOR.replace('description_en');
		@foreach($system_languages as $system_language)
            CKEDITOR.replace("description_{{$system_language}}");
		@endforeach
	});
</script>
@stop

{{-- Inline styles --}}
@section('styles')
@parent
@stop

{{-- Page content --}}
@section('page')

<section class="panel panel-default panel-tabs">

	{{-- Form --}}
	<form id="news-form" action="{{ request()->fullUrl() }}" role="form" method="post" data-parsley-validate enctype="multipart/form-data">

		{{-- Form: CSRF Token --}}
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

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

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.butterfly-effect.news.newscategories.all') }}" data-toggle="tooltip" data-original-title="{{ trans($language['admin'].'/action.cancel') }}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{ trans($language['admin'].'/action.cancel') }}</span>
						</a>

						<?php $input_name = 'title_'.$language['admin']; ?>
						<span class="navbar-brand">{{ trans("action.{$mode}") }} <small>{{ $newscategory->exists ? $newscategory->$input_name : null }}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($newscategory->exists)
							<li>
								<a href="{{ route('admin.butterfly-effect.news.newscategories.delete', $newscategory->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{ trans($language['admin'].'/action.delete') }}" type="delete">
									<i class="fa fa-trash-o"></i> <span class="visible-xs-inline">{{ trans($language['admin'].'/action.delete') }}</span>
								</a>
							</li>
							@endif

							<li>
								<button class="btn btn-primary navbar-btn" data-toggle="tooltip" data-original-title="{{ trans($language['admin'].'/action.save') }}">
									<i class="fa fa-save"></i> <span class="visible-xs-inline">{{ trans($language['admin'].'/action.save') }}</span>
								</button>
							</li>

						</ul>

					</div>

				</div>

			</nav>

		</header>

		<div class="panel-body">

			<div role="tabpanel">

				{{-- Form: Tabs --}}
				<ul class="nav nav-tabs" role="tablist">
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/common.tabs.general') }}</a></li>
					@if ($newscategory->exists && !empty($newscategory->news) )
						<li role="presentation"><a href="#news_tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/common.tabs.news') }}</a></li>
					@endif
				</ul>

				<div class="tab-content">

					{{-- Tab: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general-tab">

						<fieldset>

							<div class="row">

								<div class="col-sm-12">
									<hr/>
									<div class="col-sm-12" style="background: #aaaaaa;margin-right: 50px;">
										<h3 style="color: #000000"><b>{{ trans($language['admin'].'/model.languages') }}</b></h3>
									</div>
									<div class="col-sm-12">
										<br/>
									</div>
								</div>

								<div class="col-md-3 form-group{{ Alert::onForm('en', ' has-error') }}">

									<label for="en" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans($language['admin'].'/model.language_help') }}"></i>
										{{ trans($language['admin'].'/model.language') }}
									</label>

									<div class="checkbox">
										<label>
											<input type="hidden" name="en" id="en" value="0" checked>
											<input type="checkbox" name="en" id="en" @if($newscategory->en) checked @endif value="1"> {{ strtoupper('en') }}
										</label>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										@foreach($system_languages as $system_language)
											<label>
												<input type="hidden" name="{{$system_language}}" id="{{$system_language}}" value="0" checked>
												<input type="checkbox" name="{{$system_language}}" id="{{$system_language}}" @if($newscategory->$system_language) checked @endif value="1"> {{ strtoupper($system_language) }}
											</label>
										@endforeach
									</div>
									<span class="help-block">{{ Alert::onForm('en') }}</span>

								</div>

								<div class="col-sm-12">
									<hr/>
									<div class="col-sm-12" style="background: #aaaaaa;margin-right: 50px;">
										<h3 style="color: #000000"><b>{{ trans($language['admin'].'/model.information') }}</b></h3>
									</div>
									<div class="col-sm-12">
										<br/>
									</div>
								</div>

								<div class="col-md-3 form-group{{ Alert::onForm('title_en', ' has-error') }}">

									<label for="title_en" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.title_en_help') }}"></i>
										{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.title_en') }}
									</label>

									<input type="text" class="form-control" name="title_en" id="title_en" placeholder="{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.title_en') }}" value="{{ input()->old('title_en', $newscategory->title_en) }}">

									<span class="help-block">{{ Alert::onForm('title_en') }}</span>

								</div>

								@foreach($system_languages as $system_language)
									<?php $input_name = 'title_'.$system_language;
									$input_value = $newscategory->$input_name ?>
									<div class="col-md-3 form-group{{ Alert::onForm('title_'.$system_language.'', ' has-error') }}">

										<label for="title_{{$system_language}}" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.title_'.$system_language.'_help') }}"></i>
											{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.title_'.$system_language) }}
										</label>

										<input type="text" class="form-control" name="title_{{$system_language}}" id="title_{{$system_language}}" placeholder="{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.title_'.$system_language) }}" value="{{ input()->old($input_name, $input_value) }}">

										<span class="help-block">{{ Alert::onForm("title_$system_language") }}</span>

									</div>
								@endforeach

								<div class="col-md-12">
									<br/>
								</div>
								
								<div class="col-md-1 form-group{{ Alert::onForm('views', ' has-error') }}">

									<label for="views" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.views_help') }}"></i>
										{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.views') }}
									</label>

									<input type="number" step="1" min="0" class="form-control" name="views" id="views" placeholder="{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.views') }}" value="{{ input()->old('views', $newscategory->views) }}">

									<span class="help-block">{{ Alert::onForm('views') }}</span>

								</div>

								<div class="col-md-1 form-group{{ Alert::onForm('active', ' has-error') }}">

									<label for="active" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.active_help') }}"></i>
										{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.active') }}
									</label>

									<div class="checkbox">
										<label>
											<input type="hidden" name="active" id="active" value="0" checked>
											<input type="checkbox" name="active" id="active" @if($newscategory->active) checked @endif value="1"> {{ ucfirst(trans($language['admin'].'/model.yes')) }}
										</label>
									</div>

									<span class="help-block">{{ Alert::onForm('active') }}</span>

								</div>

								<div class="col-md-2 form-group{{ Alert::onForm('home', ' has-error') }}">

									<label for="home" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.home_help') }}"></i>
										{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.home') }}
									</label>

									<div class="checkbox">
										<label>
											<input type="hidden" name="home" id="home" value="0" checked>
											<input type="checkbox" name="home" id="home" @if($newscategory->home) checked @endif value="1"> {{ ucfirst(trans($language['admin'].'/model.yes')) }}
										</label>
									</div>

									<span class="help-block">{{ Alert::onForm('home') }}</span>

								</div>

								<div class="col-md-3 form-group{{ Alert::onForm('image', ' has-error') }}">

									<label for="image" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.image_help') }}"></i>
										{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.image') }}
									</label>

									<input type="file" onchange="readURL(this, 'newsImagePreview', 'newsImage');"  name="image" id="image" placeholder="{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.image') }}" value="{{ input()->old('image', $newscategory->image) }}">

									<span class="help-block">{{ Alert::onForm('image') }}</span>

								</div>

								<div class="col-md-2 newsImagePreview" style="display: none">

									<label class="control-label">
										{{ trans($language['admin'].'/model.uploaded_image_button') }}
									</label>
									<br/>
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#current_image"><i class="fa fa-search"></i></button>
								</div>

								@if($newscategory->exists)
									<div class="col-md-1">

										<label class="control-label">
											{{ trans($language['admin'].'/model.current_image_button') }}
										</label>
										<br/>
										<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#current_image"><i class="fa fa-search"></i></button>
									</div>
								@endif

								<div id="current_image" class="modal fade" role="dialog">
									<div class="modal-dialog">
										<!-- Modal content-->
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title"><b>{{ trans($language['admin'].'/model.current_image_header') }}</b></h4>
											</div>
											<div class="modal-body text-center" id="data" style="display: block;">
												<img class="newsImage" src="{{ asset($newscategory->image) }}" width="500"/>
											</div>
											<div class="modal-footer">
												<div>
													<button type="button" class="btn btn-default pull-right" data-dismiss="modal" id="cancel" style="display: block;">{{ trans($language['admin'].'/model.modal_cancel') }}</button>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-sm-12">
									<hr/>
									<div class="col-sm-12" style="background: #aaaaaa;margin-right: 50px;">
										<h3 style="color: #000000"><b>{{ trans($language['admin'].'/model.description') }}</b></h3>
									</div>
									<div class="col-sm-12">
										<br/>
									</div>
								</div>
								
								<div class="col-md-6 form-group{{ Alert::onForm('description_en', ' has-error') }}">

									<label for="description_en" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.description_en_help') }}"></i>
										{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.description_en') }}
									</label>

									<textarea class="form-control" name="description_en" id="description_en" placeholder="{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.description_en') }}">{{ input()->old('description_en', $newscategory->description_en) }}</textarea>

									<span class="help-block">{{ Alert::onForm('description_en') }}</span>

								</div>

								@foreach($system_languages as $system_language)
									<?php $input_name = 'description_'.$system_language;
									$input_value = $newscategory->$input_name ?>
									<div class="col-md-6 form-group{{ Alert::onForm('description_'.$system_language.'', ' has-error') }}">

										<label for="description_{{$system_language}}" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.description_en_help') }}"></i>
											{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.description_'.$system_language) }}
										</label>

										<textarea class="form-control" name="description_{{$system_language}}" id="description_{{$system_language}}" placeholder="{{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/model.general.description_'.$system_language) }}">{{ input()->old($input_name, $input_value) }}</textarea>

										<span class="help-block">{{ Alert::onForm("description_$system_language") }}</span>

									</div>
								@endforeach
							</div>

						</fieldset>

					</div>
					
					{{-- Tab: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="news_tab">
						@if ($newscategory->exists && !empty($newscategory->news) )
							<div class="col-sm-12">
								<div class="col-sm-12 well"><h4 style="color: black"><b> {{ trans('butterfly-effect/news::newscategories/'.$language['admin'].'/common.tabs.news') }} </b></h4></div>
								<div class="col-sm-7 table-responsive text-center">
									<table class="table-bordered table table-hover table-striped well text-center">
										<thead>
										<th style="text-align: center; vertical-align: middle;">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.image') }}</th>
										<th style="text-align: center; vertical-align: middle;">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.title_en') }}</th>
										@foreach($system_languages as $system_language)
											<th style="text-align: center; vertical-align: middle;">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.title_'.$system_language) }}</th>
										@endforeach
										<th style="text-align: center; vertical-align: middle;">{{trans($language['admin'].'/model.edit') }}</th>
										</thead>
										<tbody>
										@foreach($newscategory->news as $news)
											<tr class="{{$news->id}}">
												<td style="text-align: center; vertical-align: middle;"><img src="{{asset($news->image)}}" width="100" height="100"></td>
												<td style="text-align: center; vertical-align: middle;"><b>{{$news->title_en}}</b></td>
												@foreach($system_languages as $system_language)
													<?php $input_name = 'title_'.$system_language; ?>
													<td style="text-align: center; vertical-align: middle;"><b>{{$news->$input_name}}</b></td>
												@endforeach
												<td style="text-align: center; vertical-align: middle;"><a class="btn btn-xs btn-success fa fa-pencil" href="{{route('admin.butterfly-effect.news.news.edit', $news->id)}}"></a></td>
											</tr>
										@endforeach
										</tbody>
									</table>
								</div>
							</div>
						@endif
					</div>

				</div>
				
			</div>

		</div>

	</form>

</section>
@stop
