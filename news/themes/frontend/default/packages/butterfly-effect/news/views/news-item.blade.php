@extends('layouts.butterfly-effect-'.$language['frontend'])
@section('slider')
    <div class="contacts" style="background:#ccc url({{asset($frontendConfiguration->news_cover)}}) center -170px fixed no-repeat; background-size: cover;"> </div>
@endsection
@section('questionnaire')
    @include('partials._questionnaire')
@endsection
@section('page')
<div class="container">
    <div class="row">
        <div class="col-sm-8 help">
            <div class="newsbox">
                <?php $news_title = 'title_'.$language['frontend'] ?>
                <div class="headtop">
                    <h2>{{$news->$news_title}}</h2>
                </div>
                <div class="ne-img"><img src="{{asset($news->image)}}"></div>
                <small class="deit"> <i class="fa fa-calendar" aria-hidden="true"></i> ({{ date(($language['frontend'] == 'en')? 'd/m/Y' :'Y/m/d', strtotime($news->date))}}) </small>
                <small class="deit"> <i class="fa fa-eye" aria-hidden="true"></i> ({{$news->views}}) </small>
                <div class="share42init"></div>
                <script type="text/javascript" src="{{asset($language['frontend'].'/js/share42.js')}}"></script>
                <?php $news_description = 'description_'.$language['frontend'] ?>
                <div class="ne-text nonmerg"> <?php echo $news->$news_description ?> </div>
                @foreach($news->active_paragraphs() as $key => $paragraph)
                    <div class="newsbox">
                        <div class="headtop">
                            <?php $paragraph_title = 'title_'.$language['frontend'] ?>
                            <h2>{{$paragraph->$paragraph_title}}</h2>
                        </div>
                        <div class="col-sm-12 help">
                            @if($paragraph->image != null && trim($paragraph->image) != '')
                                <div class="col-sm-8 img-new">
                                    <?php $paragraph_description = 'description_'.$language['frontend'] ?>
                                    <div class="ne-text nonmerg"> <?php echo $paragraph->$paragraph_description ?> </div>
                                </div>
                                <div class="col-sm-4 img-new">
                                    <img src="{{asset($paragraph->image)}}">
                                </div>
                            @else
                                <div class="col-sm-12 img-new">
                                    <?php $paragraph_description = 'description_'.$language['frontend'] ?>
                                    <div class="ne-text nonmerg"> <?php echo $paragraph->$paragraph_description ?> </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                <hr/>
                @if($news->has_video)
                    <div class="newsbox">
                        <div class="headtop">
                            <?php $video_title = 'video_title_'.$language['frontend'] ?>
                            <h2>{{$news->$video_title}}</h2>
                        </div>
                        <div class="col-sm-12 help">
                            <div class="col-sm-12">
                                <?php $path=explode('watch?v=',$news->video_link)[1] ;?>
                                <object width="700" height="400"
                                        data="https://www.youtube.com/embed/{{$path}}">
                                </object>
                            </div>
                            <br/>
                            <div class="img-new">
                                <?php $video_description = 'video_description_'.$language['frontend'] ?>
                                    <div class="ne-text nonmerg"> <?php echo $news->$video_description ?> </div>
                            </div>
                        </div>
                    </div>
                @endif
                <hr/>
                @if($news->has_sound)
                    <div class="newsbox">
                        <div class="headtop">
                            <?php $sound_title = 'sound_title_'.$language['frontend'] ?>
                            <h2>{{$news->$sound_title}}</h2>
                        </div>
                        <div class="col-sm-12 help">
                            <div class="col-sm-12">
                                <?php $path=explode('.com/',$news->sound_link)[1] ;?>
                                <iframe width="700" src="https://w.soundcloud.com/player/?url={{$news->sound_link}}&amp;color=ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false"></iframe>
                            </div>
                            <div class="img-new">
                                <?php $sound_description = 'sound_description_'.$language['frontend'] ?>
                                    <div class="ne-text nonmerg"> <?php echo $news->$sound_description ?> </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <!--//.newsbox-->

        </div>
        <!--//.help-->

        <div class="col-sm-4 help">
            <div class="newsbox">
                <div class="headtop">
                    <h2>{{ trans('butterfly-effect/news::news/'.$language['frontend'].'/model.general.related_news') }}</h2>
                </div>
                @foreach($related_news as $key => $item)
                    <?php $item_title = 'title_'.$language['frontend'] ?>
                    <div class="other-ne">
                        <div class="img-new">
                            <a href="{{route('butterfly-effect.frontend.news.news-item', [$category_id, $category_title, $item->id, $item->$item_title])}}">
                                <img src="{{asset($item->image)}}">
                            </a>
                        </div>
                        <a href="{{route('butterfly-effect.frontend.news.news-item', [$category_id, $category_title, $item->id, $item->$item_title])}}" class="titllin">
                            {{$item->$item_title}}
                        </a> <br/>
                        <small class="deit"> <i class="fa fa-calendar" aria-hidden="true"></i> ({{ date(($language['frontend'] == 'en')? 'd/m/Y' :'Y/m/d', strtotime($item->date))}}) </small>
                        <small class="deit"> <i class="fa fa-eye" aria-hidden="true"></i> ({{$item->views}}) </small>
                    </div>
                    <!--//.other-ne-->
                @endforeach

            </div>
            <!--//.newsbox-->

        </div>
        <!--//.help-->

    </div>
    <!--//.row-->
</div>
<!--//.conatiner-->
@endsection