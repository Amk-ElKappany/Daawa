@extends('layouts.butterfly-effect-'.$language['frontend'])
@section('slider')
    <div class="contacts" style="background:#ccc url({{asset($frontendConfiguration->news_cover)}}) center -170px fixed no-repeat; background-size: cover;"> </div>
@endsection
@section('questionnaire')
    @include('partials._questionnaire')
@endsection
@section('page')
<div class="container">
    <h1 class="title"> {{ trans('butterfly-effect/news::news/'.$language['frontend'].'/common.title') }} </h1>
    <div class="row">
        @foreach($news as $key => $item)
            <div class="col-sm-6 help">
                <div class="row">
                    <?php $item_title = 'title_'.$language['frontend'] ?>
                    <div class="col-sm-4 img-new">
                        <a href="{{route('butterfly-effect.frontend.news.news-item', [$category_id, $category_title, $item->id, $item->$item_title])}}">
                            <img src="{{asset($item->image)}}">
                        </a>
                    </div>
                    <div class="col-sm-8 img-new">
                        <a href="{{route('butterfly-effect.frontend.news.news-item', [$category_id, $category_title, $item->id, $item->$item_title])}}" class="titllin">{{$item->$item_title}}</a>
                        <small class="deit"> <i class="fa fa-calendar" aria-hidden="true"></i> ({{ date(($language['frontend'] == 'en')? 'd/m/Y' :'Y/m/d', strtotime($item->date))}}) </small>
                        <small class="deit"> <i class="fa fa-eye" aria-hidden="true"></i> ({{$item->views}}) </small>
                        <?php $item_description = 'description_'.$language['frontend'] ?>
                        <div class="text" style="height: 120px !important">
                            <?php echo mb_substr($item->$item_description, 0, 180).'...' ?> <a href="{{route('butterfly-effect.frontend.news.news-item', [$category_id, $category_title, $item->id, $item->$item_title])}}">{{trans($language['frontend'].'/model.see_more')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--//.help-->
        @endforeach
    </div>
    <!--//.row-->

    <div class="pager">
        <?php echo $news->render(); ?>
    </div>
    <!--//.pager-->
</div>
<!--//.container-->
@endsection