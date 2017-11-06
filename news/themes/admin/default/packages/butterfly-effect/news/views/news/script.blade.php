<script type="text/javascript">

    $.ajaxSetup({
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    });

    $(document).on('ready', function() {
        if($('#counter').val() > 0)
        {
            var counter = $('#counter').val();
            for(iterator=1; iterator<=counter; iterator++)
            {
                $('.deleteParagraphRow').hide();
                $('#addParagraphTable tr:last').after(drawNewParagraphRaw(iterator));
                $('#paragraphsImagesPopUp').append(drawNewParagraphRawPopUP(iterator));
            }
        }
    });

    $('#has_video').bind('click', function () {
        if($(this).prop('checked') == true)
        {
            $('#video-section').show()
        }
        else
        {
            $('#video-section').hide()
        }
    });

    $('#has_sound').bind('click', function () {
        if($(this).prop('checked') == true)
        {
            $('#sound-section').show()
        }
        else
        {
            $('#sound-section').hide()
        }
    });

    function drawNewParagraphRaw(counter) {
        var tr = '<tr class="'+counter+'">';
        tr += '<td><input type="text" class="form-control" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_title_en') }}" value="{{ input()->old('paragraph_title_en'.\Illuminate\Support\Facades\Config::get('paragraph_counter')) }}" required name="paragraph_title_en'+counter+'"></td>';
        $('input.system_languages').each(function() {
            <?php $iterator = 0; ?>
            tr += '<td><input type="text" class="form-control" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_title_en') }}" value="{{ input()->old('paragraph_title_'.\Illuminate\Support\Facades\Config::get('system_languages')[$iterator++].\Illuminate\Support\Facades\Config::get('paragraph_counter')) }}" required name="paragraph_title_'+$(this).val()+counter+'"></td>';
        });
        tr += '<td><textarea class="form-control" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_description_en') }}" value="{{ input()->old('paragraph_description_en'.\Illuminate\Support\Facades\Config::get('paragraph_counter')) }}" required name="paragraph_description_en'+counter+'"></textarea></td>';
        $('input.system_languages').each(function() {
            <?php $iterator = 0; ?>
            tr += '<td><textarea class="form-control" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_description_en') }}" value="{{ input()->old('paragraph_description_'.\Illuminate\Support\Facades\Config::get('system_languages')[$iterator++].\Illuminate\Support\Facades\Config::get('paragraph_counter')) }}" required name="paragraph_description_'+$(this).val()+counter+'"></textarea></td>';
        });
        tr += '<td><input type="file"  onchange="readURL(this, \'newsParagraphImagePreviewRow'+counter+'\', \'newsParagraphImageRow'+counter+'\');" value="{{ input()->old('paragraph_image'.\Illuminate\Support\Facades\Config::get('paragraph_counter')) }}" name="paragraph_image'+counter+'"></td>';
        tr += '<td><button style="display: none" type="button" class="btn btn-primary newsParagraphImagePreviewRow'+counter+'" data-toggle="modal" data-target="#paragraph_uploaded_image'+counter+'"><i class="fa fa-search"></i></button></td>';
        tr += '<td><input type="hidden" name="paragraph_active'+counter+'" value="0" checked><input type="checkbox" name="paragraph_active'+counter+'" value="1">{{ trans($language['admin'].'/model.yes') }}</td>';
        tr += '<td>' +
                '<input type="hidden" name="paragraph_en'+counter+'" value="0" checked><input type="checkbox" name="paragraph_en'+counter+'" value="1">' + 'en'.toUpperCase();
        $('input.system_languages').each(function() {
            tr += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="hidden" name="paragraph_'+$(this).val()+counter+'" value="0" checked>' +
                    '<input type="checkbox" name="paragraph_'+$(this).val()+counter+'" value="1">'+ $(this).val().toUpperCase() ;
        });
        tr += '</td>';
        tr += '<td></td><td></td>';
        tr += '<td><button type="button" class="btn btn-xs btn-danger deleteParagraphRow deleteParagraphRow'+counter+'" value="'+counter+'"><i class="fa fa-remove"></i> </button></td>';
        tr += '</tr>';
        return tr;
    }
    function drawNewParagraphRawPopUP(counter) {
        var popup = '<div id="paragraph_uploaded_image'+counter+'" class="modal fade" role="dialog"> ' +
                '<div class="modal-dialog">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal">&times;</button>' +
                '<h4 class="modal-title"><b>{{ trans($language['admin'].'/model.uploaded_image') }}</b></h4></div>' +
                '<div class="modal-body text-center" id="data" style="display: block;">' +
                '<img class="newsParagraphImageRow'+counter+'" src="" width="500"/></div>' +
                '<div class="modal-footer"><div>' +
                '<button type="button" class="btn btn-default pull-right" data-dismiss="modal" id="cancel" style="display: block;">{{ trans($language['admin'].'/model.modal_cancel') }}</button>' +
                '</div></div></div></div></div>';
        return popup;
    }

    $(".addNewParagraph").bind('click',function()
    {
        $('.deleteParagraphRow').hide();
        var counter = parseInt($('#counter').val()) + 1;
        $('#addParagraphTable tr:last').after(drawNewParagraphRaw(counter));
        $('#paragraphsImagesPopUp').append(drawNewParagraphRawPopUP(counter));
        $('#counter').val(counter);
    });

    $(".addParagraph").bind('click',function()
    {
        var id = $(this).val();
        var addParagraphURL = '<?php echo route('butterfly-effect.admin.ajax.news.news.add-paragraph') ?>';
        $.ajax({
            type: "POST",
            url: addParagraphURL,
            dataType: 'JSON',
            data: {id : id},
            success: function(data)
            {
                var tr = '<tr class="'+data[0]+'">';
                tr += '<td class="title_en'+data[0]+'"><input row_id="'+data[0]+'" attribute="title_en" type="text" class="form-control title" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_title_en') }}"></td>';
                $('input.system_languages').each(function() {
                    tr += '<td class="title_'+$(this).val()+''+data[0]+'"><input  row_id="'+data[0]+'" attribute="title_'+$(this).val()+'" type="text" class="form-control title" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_title_en') }}"></td>';
                });
                tr += '<td class="description_en'+data[0]+'"><textarea row_id="'+data[0]+'" attribute="description_en" class="form-control description" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_description_en') }}"></textarea></td>';
                $('input.system_languages').each(function() {
                    tr += '<td class="description_'+$(this).val()+''+data[0]+'"><textarea row_id="'+data[0]+'" attribute="description_'+$(this).val()+'" class="form-control description" placeholder="{{ trans('butterfly-effect/news::news/'.$language['admin'].'/model.general.paragraph_description_en') }}"></textarea></td>';
                });
                tr += '<td class="image'+data[0]+'"><input row_id="'+data[0]+'" attribute="image" type="file"  onchange="readURL(this, \'newsParagraphImagePreviewRow'+data[0]+'\', \'newsParagraphImageRow'+data[0]+'\');" ></td>';
                tr += '<td><button style="display: none" type="button" class="btn btn-primary newsParagraphImagePreviewRow'+data[0]+'" data[0]-toggle="modal" data[0]-target="#paragraph_uploaded_image'+data[0]+'"><i class="fa fa-search"></i></button></td>';
                tr += '<td class="active'+data[0]+'"><input row_id="'+data[0]+'" attribute="active" class="activeness" type="checkbox" value="1"></td>';
                tr += '<td class="language'+data[0]+'">' +
                        '<input row_id="'+data[0]+'" attribute="en" class="language" type="checkbox" value="1">' + 'en'.toUpperCase();
                $('input.system_languages').each(function() {
                    tr += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
                            '<input row_id="'+data[0]+'" attribute="'+$(this).val()+'" class="language" type="checkbox" value="1">'+ $(this).val().toUpperCase() ;
                });
                tr += '</td>';
                tr += '<td class="created_by'+data[0]+'"><b>'+data[1]+'</b></td>' +
                '<td class="updated_by'+data[0]+'"></td>';
                tr += '<td><button type="button" class="btn btn-xs btn-danger deleteParagraph" value="'+data[0]+'"><i class="fa fa-remove"></i> </button></td>';
                tr += '</tr>';
                $('#addParagraphTable tr:last').after(tr);
                var div = '<div id="paragraph_uploaded_image'+data[0]+'" class="modal fade" role="dialog"> ' +
                        '<div class="modal-dialog">' +
                        '<div class="modal-content">' +
                        '<div class="modal-header">' +
                        '<button type="button" class="close" data[0]-dismiss="modal">&times;</button>' +
                        '<h4 class="modal-title"><b>{{ trans($language['admin'].'/model.uploaded_image') }}</b></h4></div>' +
                        '<div class="modal-body text-center" id="data[0]" style="display: block;">' +
                        '<img class="newsParagraphImageRow'+data[0]+'" src="" width="500"/></div>' +
                        '<div class="modal-footer"><div>' +
                        '<button type="button" class="btn btn-default pull-right" data[0]-dismiss="modal" id="cancel" style="display: block;">{{ trans($language['admin'].'/model.modal_cancel') }}</button>' +
                        '</div></div></div></div></div>';
                $('#paragraphsImagesPopUp').append(div);
                $(this).closest('tr').addClass('alert alert-success');
            }
        });
    });

    $(document).on('blur', '.title', function(){
        var id = $(this).attr('row_id');
        var attribute = $(this).attr('attribute');
        var editTitleUrl = '<?php echo route('butterfly-effect.admin.ajax.news.news.edit-paragraph-title') ?>';
        $.ajax({
            type: "POST",
            url: editTitleUrl,
            dataType: 'JSON',
            data: {id : id, value: $(this).val(), name: $(this).attr('attribute')},
            success: function(data)
            {
                $('.'+data[0]).addClass('alert alert-success');
                $('.updated_by'+data[3]).html('<b>'+data[1]+'</b>');

            }
        });
    });

    $(document).on('blur', '.description', function(){
        var id = $(this).attr('row_id');
        var editDescriptionUrl = '<?php echo route('butterfly-effect.admin.ajax.news.news.edit-paragraph-description') ?>';
        $.ajax({
            type: "POST",
            url: editDescriptionUrl,
            dataType: 'JSON',
            data: {id : id, value: $(this).val(), name: $(this).attr('attribute')},
            success: function(data)
            {
                $('.'+data[0]).addClass('alert alert-success');
                $('.updated_by'+data[3]).html('<b>'+data[1]+'</b>');
            }
        });
    });

    $(document).on('click', '.activeness', function(){
        var id = $(this).attr('row_id');
        var editActiveUrl = '<?php echo route('butterfly-effect.admin.ajax.news.news.edit-paragraph-active') ?>';
        $.ajax({
            type: "POST",
            url: editActiveUrl,
            dataType: 'JSON',
            data: {id : id, value: $(this).prop('checked')},
            success: function(data)
            {
                $('.'+data[0]).addClass('alert alert-success');
                $('.updated_by'+data[3]).html('<b>'+data[1]+'</b>');
            }
        });
    });

    $(document).on('click', '.language', function(){
        var id = $(this).attr('row_id');
        var editActiveUrl = '<?php echo route('butterfly-effect.admin.ajax.news.news.edit-paragraph-languages') ?>';
        $.ajax({
            type: "POST",
            url: editActiveUrl,
            dataType: 'JSON',
            data: {id : id, value: $(this).prop('checked'), name: $(this).attr('attribute')},
            success: function(data)
            {
                $('.'+data[0]).addClass('alert alert-success');
                $('.updated_by'+data[3]).html('<b>'+data[1]+'</b>');
            }
        });
    });

    $(document).on('click', '.deleteParagraph', function(){
        var id = $(this).val();
        var deleteUrl = '<?php echo route('butterfly-effect.admin.ajax.news.news.delete-paragraph') ?>';
        $.ajax({
            type: "POST",
            url: deleteUrl,
            dataType: 'JSON',
            data: {id : id},
            success: function(data)
            {
                $('#paragraphSuccess').show();
                $('.'+id).remove();
            }
        });
    });

    $(document).on('click', '.deleteParagraphRow', function(){
        var id = $(this).val();
        $('.'+id).remove();
        $('#counter').val(parseInt($('#counter').val())-1);
        $('.deleteParagraphRow'+$('#counter').val()).show();
    });
</script>