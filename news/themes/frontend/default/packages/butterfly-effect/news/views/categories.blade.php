@extends('layouts.butterfly-effect-'.$language['frontend'])
@section('slider')
    <div class="contacts" style="background:#ccc url({{asset($frontendConfiguration->news_cover)}}) center -170px fixed no-repeat; background-size: cover;"> </div>
@endsection
@section('questionnaire')
    @include('partials._questionnaire')
@endsection
@section('page')
<div class="container">
    <h1 class="title"> {{ trans('butterfly-effect/news::newscategories/'.$language['frontend'].'/common.title') }} </h1>
    <div class="row" style="margin-left: -15px !important; margin-right: -15px !important;">
        @foreach($categories as $key => $category)
            <div class="col-sm-6 help">
                <div class="row">
                    <?php $category_title = 'title_'.$language['frontend'] ?>
                    <div class="col-sm-4 img-new">
                        <a href="{{route('butterfly-effect.frontend.news.news', [$category->id, $category->$category_title])}}">
                            <img src="{{asset($category->image)}}">
                        </a>
                    </div>
                    <div class="col-sm-8 img-new">
                        <a href="{{route('butterfly-effect.frontend.news.news', [$category->id, $category->$category_title])}}" class="titllin">{{$category->$category_title}}</a>
                        <small class="deit"> <i class="fa fa-eye" aria-hidden="true"></i> ({{$category->views}}) </small>
                            <?php $category_description = 'description_'.$language['frontend'] ?>
                            <div class="text" style="height: 120px !important">
                                <?php echo mb_substr($category->$category_description, 0, 180).'...' ?> <a href="{{route('butterfly-effect.frontend.news.news', [$category->id, $category->$category_title])}}">{{trans($language['frontend'].'/model.see_more')}}</a>
                            </div>
                    </div>
                </div>
            </div>
            <!--//.help-->
        @endforeach
    </div>
    <!--//.row-->

    <div class="pager">
        <?php echo $categories->render(); ?>
    </div>
    <!--//.pager-->
</div>
<!--//.container-->
@endsection