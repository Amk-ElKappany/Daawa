@extends('layouts/default')

{{-- Page title --}}
@section('title')
@parent
{{ trans("action.{$mode}") }} {{ trans('butterfly-effect/news::news/'.$language['admin'].'/common.title') }}
@stop

{{-- Queue assets --}}
{{ Asset::queue('selectize', 'selectize/css/selectize.bootstrap3.css', 'styles') }}
{{ Asset::queue('redactor', 'redactor/css/redactor.css', 'styles') }}

{{ Asset::queue('slugify', 'platform/js/slugify.js', 'jquery') }}
{{ Asset::queue('validate', 'platform/js/validate.js', 'jquery') }}
{{ Asset::queue('selectize', 'selectize/js/selectize.js', 'jquery') }}
{{ Asset::queue('redactor', 'redactor/js/redactor.min.js', 'jquery') }}
{{ Asset::queue('form', 'platform/users::js/form.js', 'platform') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
@include('butterfly-effect/news::news.script')
<script type="text/javascript">
	$(document).on('ready', function() {
		CKEDITOR.replace('description_en');
		@foreach($system_languages as $system_language)
            CKEDITOR.replace("description_{{$system_language}}");
		@endforeach
		CKEDITOR.replace('video_description_en');
		@foreach($system_languages as $system_language)
            CKEDITOR.replace("video_description_{{$system_language}}");
		@endforeach
		CKEDITOR.replace('sound_description_en');
		@foreach($system_languages as $system_language)
            CKEDITOR.replace("sound_description_{{$system_language}}");
		@endforeach
	});
</script>
<script>
	$('.date-picker').datepicker({
		format: 'dd/mm/yyyy',
		startDate: '-3d'
	})
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
		@foreach($system_languages as $i => $system_language)
			<input type="hidden" value="{{$system_language}}" name="system_language[{{$i}}]" class="system_languages">
		@endforeach

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

						<a class="btn btn-navbar-cancel navbar-btn pull-left tip" href="{{ route('admin.butterfly-effect.news.news.all') }}" data-toggle="tooltip" data-original-title="{{ trans($language['admin'].'/action.cancel') }}">
							<i class="fa fa-reply"></i> <span class="visible-xs-inline">{{ trans($language['admin'].'/action.cancel') }}</span>
						</a>

						<?php $input_name = 'title_'.$language['admin']; ?>
						<span class="navbar-brand">{{ trans("action.{$mode}") }} <small>{{ $news->exists ? $news->$input_name : null }}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($news->exists)
							<li>
								<a href="{{ route('admin.butterfly-effect.news.news.delete', $news->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{ trans($language['admin'].'/action.delete') }}" type="delete">
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
					<li class="active" role="presentation"><a href="#general-tab" aria-controls="general-tab" role="tab" data-toggle="tab">{{ trans('butterfly-effect/news::news/'.$language['admin'].'/common.tabs.general') }}</a></li>
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
											<input type="checkbox" name="en" id="en" @if($news->en) checked @endif value="1"> {{ strtoupper('en') }}
										</label>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										@foreach($system_languages as $system_language)
											<label>
												<input type="hidden" name="{{$system_language}}" id="{{$system_language}}" value="0" checked>
												<input type="checkbox" name="{{$system_language}}" id="{{$system_language}}" @if($news->$system_language) checked @endif value="1"> {{ strtoupper($system_language) }}
											</label>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.title_en_help') }}"></i>
										{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.title_en') }}
									</label>

									<input type="text" class="form-control" name="title_en" id="title_en" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.title_en') }}" value="{{ input()->old('title_en', $news->title_en) }}">

									<span class="help-block">{{ Alert::onForm('title_en') }}</span>

								</div>
								
								@foreach($system_languages as $system_language)
									<?php $input_name = 'title_'.$system_language;
									$input_value = $news->$input_name ?>
									<div class="col-md-3 form-group{{ Alert::onForm('title_'.$system_language.'', ' has-error') }}">

										<label for="title_{{$system_language}}" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.title_'.$system_language.'_help') }}"></i>
											{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.title_'.$system_language) }}
										</label>

										<input type="text" class="form-control" name="title_{{$system_language}}" id="title_{{$system_language}}" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.title_'.$system_language) }}" value="{{ input()->old($input_name, $input_value) }}">

										<span class="help-block">{{ Alert::onForm("title_$system_language") }}</span>

									</div>
								@endforeach

								<div class="col-md-12">
									<br/>
								</div>
								
								<div class="col-md-2 form-group{{ Alert::onForm('newscategory_id', ' has-error') }}">

									<label for="newscategory_id" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.newscategory_id_help') }}"></i>
										{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.newscategory_id') }}
									</label>

									<?php $input_name = 'title_'.$language['admin']; ?>
									<select class="form-control" name="newscategory_id" id="newscategory_id" required>
										@foreach($categories as $category)
											<option value="{{$category->id}}" {{($category->id == $news->newscategory_id)? 'Selected' : ''}}>{{$category->$input_name}}</option>
										@endforeach
									</select>

									<span class="help-block">{{ Alert::onForm('newscategory_id') }}</span>

								</div>
								
								<div class="col-md-1 form-group{{ Alert::onForm('date', ' has-error') }}">

									<label for="date" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.date_help') }}"></i>
										{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.date') }}
									</label>

									<input type="text" class="date-picker form-control" name="date" id="date" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.date') }}" value="{{ input()->old('date', $news->date) }}">

									<span class="help-block">{{ Alert::onForm('date') }}</span>

								</div>

								<div class="col-md-1 form-group{{ Alert::onForm('views', ' has-error') }}">

									<label for="views" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.views_help') }}"></i>
										{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.views') }}
									</label>

									<input type="number" step="1" min="0" class="form-control" name="views" id="views" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.views') }}" value="{{ input()->old('views', $news->views) }}">

									<span class="help-block">{{ Alert::onForm('views') }}</span>

								</div>

								<div class="col-md-2 form-group{{ Alert::onForm('has_video', ' has-error') }}">

									<label for="has_video" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.has_video_help') }}"></i>
										{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.has_video') }}
									</label>

									<div class="checkbox">
										<label>
											<input type="hidden" name="has_video" value="0" checked>
											<input type="checkbox" name="has_video" id="has_video" @if($news->has_video) checked @endif value="1"> {{ ucfirst(trans($language['admin'].'/model.yes')) }}
										</label>
									</div>

									<span class="help-block">{{ Alert::onForm('has_video') }}</span>

								</div>

								<div class="col-md-2 form-group{{ Alert::onForm('has_sound', ' has-error') }}">

									<label for="has_sound" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.has_sound_help') }}"></i>
										{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.has_sound') }}
									</label>

									<div class="checkbox">
										<label>
											<input type="hidden" name="has_sound" value="0" checked>
											<input type="checkbox" name="has_sound" id="has_sound" @if($news->has_sound) checked @endif value="1"> {{ ucfirst(trans($language['admin'].'/model.yes')) }}
										</label>
									</div>

									<span class="help-block">{{ Alert::onForm('has_sound') }}</span>

								</div>

								<div class="col-md-2 form-group{{ Alert::onForm('active', ' has-error') }}">

									<label for="active" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.active_help') }}"></i>
										{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.active') }}
									</label>

									<div class="checkbox">
										<label>
											<input type="hidden" name="active" id="active" value="0" checked>
											<input type="checkbox" name="active" id="active" @if($news->active) checked @endif value="1"> {{ ucfirst(trans($language['admin'].'/model.yes')) }}
										</label>
									</div>

									<span class="help-block">{{ Alert::onForm('active') }}</span>

								</div>

								<div class="col-md-2 form-group{{ Alert::onForm('home', ' has-error') }}">

									<label for="home" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.home_help') }}"></i>
										{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.home') }}
									</label>

									<div class="checkbox">
										<label>
											<input type="hidden" name="home" id="home" value="0" checked>
											<input type="checkbox" name="home" id="home" @if($news->home) checked @endif value="1"> {{ ucfirst(trans($language['admin'].'/model.yes')) }}
										</label>
									</div>

									<span class="help-block">{{ Alert::onForm('home') }}</span>

								</div>

								<div class="col-md-12">
									<br/>
								</div>

								<div class="col-md-2 form-group{{ Alert::onForm('image', ' has-error') }}">

									<label for="image" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.image_help') }}"></i>
										{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.image') }}
									</label>

									<input type="file" onchange="readURL(this, 'newsImagePreview', 'newsImage');" name="image" id="image" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.image') }}" value="{{ input()->old('image', $news->image) }}">

									<span class="help-block">{{ Alert::onForm('image') }}</span>

								</div>

								<div class="col-md-2 newsImagePreview" style="display: none">

									<label class="control-label">
										{{ trans($language['admin'].'/model.uploaded_image_button') }}
									</label>
									<br/>
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#current_image"><i class="fa fa-search"></i></button>
								</div>

								@if($news->exists)
									<div class="col-md-1" style="display: {{($news->exists)? 'block' : 'none'}}">

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
												<img class="newsImage" src="{{ asset($news->image) }}" width="500"/>
											</div>
											<div class="modal-footer">
												<div>
													<button type="button" class="btn btn-default pull-right" data-dismiss="modal" id="cancel" style="display: block;">{{ trans($language['admin'].'/model.modal_cancel') }}</button>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="col-md-3 form-group{{ Alert::onForm('attachment', ' has-error') }}">

									<label for="attachment" class="control-label">
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.attachment_help') }}"></i>
										{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.attachment') }}
									</label>

									<input type="file" name="attachment" id="attachment" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.attachment') }}" value="{{ input()->old('attachment', $news->attachment) }}">

									<span class="help-block">{{ Alert::onForm('attachment') }}</span>

								</div>

								@if($news->exists && trim($news->attachment) != '')
									<div class="col-md-2">

										<label class="control-label">
											{{ trans($language['admin'].'/model.current_attachment') }}
										</label>
										<br/>

										<?php $input_name = 'title_'.$system_language; ?>
										<a href="{{asset($news->attachment)}}" download="{{$news->$input_name}}" class="btn btn-primary"><i class="fa fa-download"></i></a>
									</div>
								@endif

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
										<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.description_en_help') }}"></i>
										{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.description_en') }}
									</label>

									<textarea class="form-control" name="description_en" id="description_en" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.description_en') }}">{{ input()->old('description_en', $news->description_en) }}</textarea>

									<span class="help-block">{{ Alert::onForm('description_en') }}</span>

								</div>

								@foreach($system_languages as $system_language)
									<?php $input_name = 'description_'.$system_language;
									$input_value = $news->$input_name ?>
									<div class="col-md-6 form-group{{ Alert::onForm('description_'.$system_language.'', ' has-error') }}">

										<label for="description_{{$system_language}}" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.description_en_help') }}"></i>
											{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.description_'.$system_language) }}
										</label>

										<textarea class="form-control" name="description_{{$system_language}}" id="description_{{$system_language}}" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.description_'.$system_language) }}">{{ input()->old($input_name, $input_value) }}</textarea>

										<span class="help-block">{{ Alert::onForm("description_$system_language") }}</span>

									</div>
								@endforeach

								<div class="col-md-12">
									<hr/>
								</div>

								<div class="col-sm-12">
									<div class="col-sm-12" style="background: #aaaaaa;margin-right: 50px;"><h3 style="color: #000000"><b>{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.news_paragraphs_header') }}</b></h3></div>
									<div class="col-sm-12 table-responsive">
										<div class="alert alert-success" style="display: none;" id="paragraphSuccess"><div id="paragraphMessage">{{ trans($language['admin'].'/model.delete_success') }}</div> </div>
										<table id="addParagraphTable" class="table-responsive table well">
											<thead>
											<tr>
												<th><b>{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_title_en') }}</b></th>
												@foreach($system_languages as $system_language)
													<th><b>{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_title_'.$system_language) }}</b></th>
												@endforeach
												<th><b>{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_description_en') }}</b></th>
											@foreach($system_languages as $system_language)
													<th><b>{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_description_'.$system_language) }}</b></th>
												@endforeach
												<th><b>{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_image') }}</b></th>
												<th><b>{{ trans($language['admin'].'/model.current_image_button') }}</b></th>
												<th><b>{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_active') }}</b></th>
												<th><b>{{ trans($language['admin'].'/model.language') }}</b></th>
												<th><b>{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.created_by') }}</b></th>
												<th><b>{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.updated_by') }}</b></th>
												<th><b>{{ trans($language['admin'].'/action.delete') }}</b></th>
												@if ($news->exists)
													<th><button type="button" class="btn btn-xs btn-success addParagraph" value="{{$news->id}}"><i class="fa fa-plus"></i> </button></th>
												@else
													<th><button type="button" class="btn btn-xs btn-success addNewParagraph"><i class="fa fa-plus"></i> </button></th>
												@endif
												<th><input type="hidden" value="{{ input()->old('counter', 0) }}" name="counter" id="counter"></th>
											</tr>
											</thead>
											<tbody>
											@foreach($news->paragraphs as $item)
												<tr class="{{$item->id}}">
														<td class="title_en{{$item->id}}"><input type="text" class="form-control title" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_title_en') }}" value="{{$item->title_en}}" row_id="{{$item->id}}" attribute="title_en"></td>
													@foreach($system_languages as $system_language)
														<?php $input_name = 'title_'.$system_language; ?>
														<td class="title_{{$system_language}}{{$item->id}}"><input type="text" class="form-control title" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_title_'.$system_language) }}" value="{{$item->$input_name}}" row_id="{{$item->id}}" attribute="title_{{$system_language}}"></td>
													@endforeach
													<td class="description_en{{$item->id}}"><textarea rows="7"  class="form-control description" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_description_en') }}" row_id="{{$item->id}}" attribute="description_en">{{$item->description_en}}</textarea></td>
													@foreach($system_languages as $system_language)
														<?php $input_name = 'description_'.$system_language; ?>
															<td class="description_{{$system_language}}{{$item->id}}"><textarea rows="7" cols="7" class="form-control description" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_description_'.$system_language) }}" row_id="{{$item->id}}" attribute="description_{{$system_language}}">{{$item->$input_name}}</textarea></td>
													@endforeach
													<td class="image{{$item->id}}"><input type="file" onchange="readURL(this, 'newsParagraphImagePreview', 'newsParagraphImage');" class="image" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_image') }}" value="{{$item->image}}" row_id="{{$item->id}}" attribute="image"></td>
													<td><button type="button" class="btn btn-primary newsParagraphImagePreview" data-toggle="modal" data-target="#paragraph_current_image{{$item->id}}"><i class="fa fa-search"></i></button></td>
													<td class="active{{$item->id}}">
														<div class="checkbox">
															<label>
																<input type="checkbox" class="activeness" attribute="active" row_id="{{$item->id}}" @if($item->active) checked @endif value="1"> {{ ucfirst(trans($language['admin'].'/model.yes')) }}
															</label>
														</div>
													</td>
													<td class="language{{$item->id}}">
														<div class="checkbox">
															<label>
																<input type="checkbox" class="language" attribute="en" row_id="{{$item->id}}" @if($item->en) checked @endif value="1"> {{ strtoupper('en') }}
															</label>
															&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
															@foreach($system_languages as $system_language)
																<label>
																	<input type="checkbox" class="language"  attribute="{{$system_language}}" row_id="{{$item->id}}" @if($item->$system_language) checked @endif value="1"> {{ strtoupper($system_language) }}
																</label>
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
															@endforeach
														</div>
													</td>
													<?php $user = \Platform\Users\Models\User::find($item->created_by); ?>
													<td class="created_by{{$item->id}}"><b>{{$user->first_name}} {{$user->last_name}}</b></td>
													<?php $user = \Platform\Users\Models\User::find($item->updated_by); ?>
													<td  class="updated_by{{$item->id}}"><b>{{($user != null)? $user->first_name.' '.$user->last_name : ''}}</b></td>
													<td><button type="button" class="btn btn-xs btn-danger deleteParagraph" value="{{$item->id}}"><i class="fa fa-remove"></i> </button></td>
													<td></td>
												</tr>
											@endforeach
											</tbody>
										</table>
										@foreach($news->paragraphs as $item)
											<div id="paragraph_current_image{{$item->id}}" class="modal fade" role="dialog">
												<div class="modal-dialog">
													<!-- Modal content-->
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal">&times;</button>
															<h4 class="modal-title"><b>{{ trans($language['admin'].'/model.current_image_header') }}</b></h4>
														</div>
														<div class="modal-body text-center" id="data" style="display: block;">
															<img class="newsParagraphImage" src="{{ asset($item->image) }}" width="500"/>
														</div>
														<div class="modal-footer">
															<div>
																<button type="button" class="btn btn-default pull-right" data-dismiss="modal" id="cancel" style="display: block;">{{ trans($language['admin'].'/model.modal_cancel') }}</button>
															</div>
														</div>
													</div>
												</div>
											</div>
										@endforeach
										<div id="paragraphsImagesPopUp"></div>
									</div>
								</div>
								<div id="video-section" style="display: {{($news->has_video)? 'block' : 'none'}}">
									<div class="col-md-12">
										<hr/>
										<div class="col-sm-12" style="background: #aaaaaa;margin-right: 50px;">
											<h3 style="color: #000000"><b>{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_header') }}</b></h3>
										</div>
										<div class="col-sm-12">
											<br/>
										</div>
									</div>

									<div class="col-md-3 form-group{{ Alert::onForm('video_link', ' has-error') }}">

										<label for="video_link" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_link_help') }}"></i>
											{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_link') }}
										</label>

										<input type="text" class="form-control" name="video_link" id="video_link" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_link') }}" value="{{ input()->old('video_link', $news->video_link) }}">

										<span class="help-block">{{ Alert::onForm('video_link') }}</span>

									</div>

									@if ($news->exists && $news->has_video && trim($news->video_link) != '')
										<div class="col-md-3">

											<label for="current_image" class="control-label">
												{{ trans($language['admin'].'/model.current_video') }}
											</label>
											<br/>
											<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#current_video_link"><i class="fa fa-search"></i></button>
										</div>
									@endif

									@if ($news->exists && $news->has_video && trim($news->video_link) != '')
										<div id="current_video_link" class="modal fade" role="dialog">
											<div class="modal-dialog modal-lg">
												<!-- Modal content-->
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title"><b>{{ trans($language['admin'].'/model.current_video_header') }}</b></h4>
													</div>
													<div class="modal-body text-center" id="data" style="display: block;">
														<?php $path=explode('watch?v=',$news->video_link)[1] ;?>
														<object width="800" height="600"
																data="https://www.youtube.com/embed/{{$path}}">
														</object>
													</div>
													<div class="modal-footer">
														<div>
															<button type="button" class="btn btn-default pull-right" data-dismiss="modal" id="cancel" style="display: block;">{{ trans($language['admin'].'/model.modal_cancel') }}</button>
														</div>
													</div>
												</div>
											</div>
										</div>
									@endif

									<div class="col-sm-12">
										<br/>
									</div>

									<div class="col-md-3 form-group{{ Alert::onForm('video_title_en', ' has-error') }}">

										<label for="video_title_en" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_title_en_help') }}"></i>
											{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_title_en') }}
										</label>

										<input type="text" class="form-control" name="video_title_en" id="video_title_en" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_title_en') }}" value="{{ input()->old('video_title_en', $news->video_title_en) }}">

										<span class="help-block">{{ Alert::onForm('video_title_en') }}</span>

									</div>

									@foreach($system_languages as $system_language)
										<?php $input_name = 'video_title_'.$system_language;
										$input_value = $news->$input_name ?>
										<div class="col-md-3 form-group{{ Alert::onForm('video_title_'.$system_language.'', ' has-error') }}">

											<label for="video_title_{{$system_language}}" class="control-label">
												<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_title_'.$system_language.'_help') }}"></i>
												{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_title_'.$system_language) }}
											</label>

											<input type="text" class="form-control" name="video_title_{{$system_language}}" id="video_title_{{$system_language}}" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_title_'.$system_language) }}" value="{{ input()->old($input_name, $input_value) }}">

											<span class="help-block">{{ Alert::onForm("video_title_$system_language") }}</span>

										</div>
									@endforeach

									<div class="col-sm-12">
										<br/>
									</div>

									<div class="col-md-6 form-group{{ Alert::onForm('video_description_en', ' has-error') }}">

										<label for="video_description_en" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_description_en_help') }}"></i>
											{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_description_en') }}
										</label>

										<textarea class="form-control" name="video_description_en" id="video_description_en" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_description_en') }}">{{ input()->old('video_description_en', $news->video_description_en) }}</textarea>

										<span class="help-block">{{ Alert::onForm('video_description_en') }}</span>

									</div>

									@foreach($system_languages as $system_language)
										<?php $input_name = 'video_description_'.$system_language;
										$input_value = $news->$input_name ?>
										<div class="col-md-6 form-group{{ Alert::onForm('video_description_'.$system_language.'', ' has-error') }}">

											<label for="video_description_{{$system_language}}" class="control-label">
												<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_description_en_help') }}"></i>
												{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_description_'.$system_language) }}
											</label>

											<textarea class="form-control" name="video_description_{{$system_language}}" id="video_description_{{$system_language}}" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.video_description_'.$system_language) }}">{{ input()->old($input_name, $input_value) }}</textarea>

											<span class="help-block">{{ Alert::onForm("video_description_$system_language") }}</span>

										</div>
									@endforeach
								</div>
								<div id="sound-section" style="display: {{($news->has_sound)? 'block' : 'none'}}">
									<div class="col-md-12">
										<hr/>
										<div class="col-sm-12" style="background: #aaaaaa;margin-right: 50px;">
											<h3 style="color: #000000"><b>{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_header') }}</b></h3>
										</div>
										<div class="col-sm-12">
											<br/>
										</div>
									</div>


									<div class="col-md-3 form-group{{ Alert::onForm('sound_link', ' has-error') }}">

										<label for="sound_link" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_link_help') }}"></i>
											{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_link') }}
										</label>

										<input type="text" class="form-control" name="sound_link" id="sound_link" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_link') }}" value="{{ input()->old('sound_link', $news->sound_link) }}">

										<span class="help-block">{{ Alert::onForm('sound_link') }}</span>

									</div>

									@if ($news->exists && $news->has_sound && trim($news->sound_link) != '')
										<div class="col-md-3">

											<label for="current_image" class="control-label">
												{{ trans($language['admin'].'/model.current_sound') }}
											</label>
											<br/>
											<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#current_sound_link"><i class="fa fa-search"></i></button>
										</div>
									@endif

									@if ($news->exists && $news->has_sound && trim($news->sound_link) != '')
										<div id="current_sound_link" class="modal fade" role="dialog">
											<div class="modal-dialog modal-lg">
												<!-- Modal content-->
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<h4 class="modal-title"><b>{{ trans($language['admin'].'/model.current_sound_header') }}</b></h4>
													</div>
													<div class="modal-body text-center" id="data" style="display: block;">
														<?php $path=explode('.com/',$news->sound_link)[1] ;?>
														<iframe width="850" src="https://w.soundcloud.com/player/?url={{$news->sound_link}}&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false"></iframe>
													</div>
													<div class="modal-footer">
														<div>
															<button type="button" class="btn btn-default pull-right" data-dismiss="modal" id="cancel" style="display: block;">{{ trans($language['admin'].'/model.modal_cancel') }}</button>
														</div>
													</div>
												</div>
											</div>
										</div>
									@endif

									<div class="col-sm-12">
										<br/>
									</div>
									
									<div class="col-md-3 form-group{{ Alert::onForm('sound_title_en', ' has-error') }}">

										<label for="sound_title_en" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_title_en_help') }}"></i>
											{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_title_en') }}
										</label>

										<input type="text" class="form-control" name="sound_title_en" id="sound_title_en" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_title_en') }}" value="{{ input()->old('sound_title_en', $news->sound_title_en) }}">

										<span class="help-block">{{ Alert::onForm('sound_title_en') }}</span>

									</div>

									@foreach($system_languages as $system_language)
										<?php $input_name = 'sound_title_'.$system_language;
										$input_value = $news->$input_name ?>
										<div class="col-md-3 form-group{{ Alert::onForm('sound_title_'.$system_language.'', ' has-error') }}">

											<label for="sound_title_{{$system_language}}" class="control-label">
												<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_title_'.$system_language.'_help') }}"></i>
												{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_title_'.$system_language) }}
											</label>

											<input type="text" class="form-control" name="sound_title_{{$system_language}}" id="sound_title_{{$system_language}}" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_title_'.$system_language) }}" value="{{ input()->old($input_name, $input_value) }}">

											<span class="help-block">{{ Alert::onForm("sound_title_$system_language") }}</span>

										</div>
									@endforeach

									<div class="col-sm-12">
										<br/>
									</div>

									<div class="col-md-6 form-group{{ Alert::onForm('sound_description_en', ' has-error') }}">

										<label for="sound_description_en" class="control-label">
											<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_description_en_help') }}"></i>
											{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_description_en') }}
										</label>

										<textarea class="form-control" name="sound_description_en" id="sound_description_en" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_description_en') }}">{{ input()->old('sound_description_en', $news->sound_description_en) }}</textarea>

										<span class="help-block">{{ Alert::onForm('sound_description_en') }}</span>

									</div>

									@foreach($system_languages as $system_language)
										<?php $input_name = 'sound_description_'.$system_language;
										$input_value = $news->$input_name ?>
										<div class="col-md-6 form-group{{ Alert::onForm('sound_description_'.$system_language.'', ' has-error') }}">

											<label for="sound_description_{{$system_language}}" class="control-label">
												<i class="fa fa-info-circle" data-toggle="popover" data-content="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_description_en_help') }}"></i>
												{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_description_'.$system_language) }}
											</label>

											<textarea class="form-control" name="sound_description_{{$system_language}}" id="sound_description_{{$system_language}}" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.sound_description_'.$system_language) }}">{{ input()->old($input_name, $input_value) }}</textarea>

											<span class="help-block">{{ Alert::onForm("sound_description_$system_language") }}</span>

										</div>
									@endforeach
								</div>
							</div>

						</fieldset>

					</div>

				</div>

			</div>

		</div>

	</form>

</section>
@stop
