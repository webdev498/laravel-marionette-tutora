@extends('_.layouts.default', [
    'page_class' => 'page--blog page--admin'
])
@section('body')
<header class="page-head">
        <div class="wrapper">
            @include('_.partials.site-nav.authed.admin')
        </div>
    </header>

    <div class="[ band band--ruled band--dark @if($article->deleted_at) band--danger @endif ]">
        <div class="wrapper">
            <div class="layout"><!--
                --><div class="layout__item">
                    <div class="tal u-mt- ib">
                        <h4 class="delta inline">
                            @if(! $article->id)
                                Create Article
                            @else
                                Edit Article <span class="article_id">{{$article->id}}</span>
                            @endif
                        </h4>
                    </div>
                    <div class="tar r inline">
                        @if( $article->published_at && ! $article->deleted_at)
                            <a href="{{ route('articles.show', ['slug' => $article->slug])}}" class="btn">View article</a>
                        @endif
                        @if($article->deleted_at) <span>This article has been deleted</span> @endif
                        @if($article->id)
                            @if(! $article->deleted_at)<a  href="{{ relroute('admin.blog.article.delete', ['id' => $article->id ]) }}" class="btn btn--error">Delete Article</a>@endif
                        @endif
                    </div>
                </div><!--
            --></div>
        </div>
    </div>
    <div class="wrapper">
            <div class="[ layout ]"><!--
                --><div class="[ layout__item layout__item--narrow ] post">
                    
                    <p class="large">Title:</p>
                    <h2 class="article_title u-mb+"> {{$article->title}}</h2>
                    <p class="large">Preview (text only):</p>
                    <textarea
                                    name="body"
                                    placeholder="Start Typing..."
                                    class="[ input input--squared input--full ] article_preview"
                                    style="min-height: 120px;"
                                >{{ $article->preview }}</textarea>
                    <p class="large">Content:</p>
                    <div class="article_body u-mb+"> {!! $article->body !!} </div>
                    <hr class="u-mb+">
                </div>

                <div class="[ layout__item layout__item--narrow ] ">
                    <div class="radios">
                        <div class="radios__item @if($article->published == 1) radios__item--checked @endif ">
                            Published
                            <input type="radio" name="published" value="1" class="radios__input reason" @if($article->published == 1) checked @endif>
                        </div>
                        <div class="radios__item @if($article->published == 0) radios__item--checked @endif ">
                            Not Published
                            <input type="radio" name="published" value="0" class="radios__input reason " @if($article->published == 0) checked @endif>
                        </div>
                    
                    </div>
               
                    <input type="date" name="published_at" class="[ input input--full input--squared input--bordered ] field__input article_published_at" value="@if($article->published_at){{$article->published_at->computer }}@endif">
                    <div>
                        <button class="btn btn--small btn--full btn--success u-mt">Save Article</button>
                    
                    </div>
                </div>
        
        </div>
    </div>

@stop
@section('scripts')
<script>


$('.btn--success').on('click',function(){
    var article_id = $('.article_id').text();
    var article_published_at = $('.article_published_at').val();
    var article_title = $('.article_title').text();
    var article_preview = $('.article_preview').val();
    console.log(article_preview);
    var allContents = editor.serialize();
    var article_body = allContents["element-0"].value;


    var article_status = $('input[name="published"]:checked').val();

    $.ajax({
        type: 'POST',
        dataType: 'json',
        data: {
            article_id: article_id,
            article_published_at: article_published_at,
            article_title: article_title,
            article_preview: article_preview,
            article_body: article_body,
            article_status: article_status,
            deleting_images: deleting_images,
        },
        url:'/admin/blog/articles',
        success: function(data){
        var id = data;
          location.replace('/admin/blog/'+id+'/article');
        }
    })
})

var editor = new MediumEditor('.article_body', {
    toolbar: {
        buttons: ['bold', 'italic', 'underline', 'anchor', 'h2', 'h3', 'h4', 'h5', 'quote', 'orderedlist', 'unorderedlist', 'justifyLeft','justifyCenter','justifyRight', 'justifyFull']
    },

});
new MediumEditor('.article_date');
new MediumEditor('.article_title');
var deleting_images = [];
$('.article_body').mediumInsert({
    editor: editor,  // (MediumEditor) Instance of MediumEditor
    enabled: true, // (boolean) If the plugin is enabled
   
    addons: { // (object) Addons configuration
        images: { // (object) Image addon configuration
            label: '<span class="fa fa-camera"></span>', // (string) A label for an image addon
            preview: true, // (boolean) Show an image before it is uploaded (only in browsers that support this feature)
            captions: true, // (boolean) Enable captions
            captionPlaceholder: 'Type caption for image (optional)', // (string) Caption placeholder

            autoGrid: 3, // (integer) Min number of images that automatically form a grid

            fileUploadOptions: { // (object) File upload configuration. See https://github.com/blueimp/jQuery-File-Upload/wiki/Options
                url: '/admin/blog/article/upload', // (string) A relative path to an upload script
                acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
                done: function (e, data) {
                    console.log(e)
                }
            },

            styles: { // (object) Available image styles configuration
                wide: { // (object) Image style configuration. Key is used as a class name added to an image, when the style is selected (.medium-insert-images-wide)
                    label: '<span class="fa fa-align-justify"></span>', // (string) A label for a style
                    added: function ($el) {}, // (function) Callback function called after the style was selected. A parameter $el is a current active paragraph (.medium-insert-active)
                    removed: function ($el) {} // (function) Callback function called after a different style was selected and this one was removed. A parameter $el is a current active paragraph (.medium-insert-active)
                },
                left: {
                    label: '<span class="fa fa-align-left"></span>'
                },
                right: {
                    label: '<span class="fa fa-align-right"></span>'
                },
                grid: {
                    label: '<span class="fa fa-th"></span>'
                }
            },
            actions: { // (object) Actions for an optional second toolbar
                remove: { // (object) Remove action configuration
                    label: '<span class="fa fa-times"></span>', // (string) Label for an action
                    clicked: function ($el) { // (function) Callback function called when an action is selected
                        var path = $el[0].getAttribute('src');
                        var image_name = path.substring(path.lastIndexOf('/')+1,path.length);
                        $.ajax({
                            type: 'DELETE',
                            dataType: 'json',
                            data: {
                                image_name: image_name,
                            },
                            url:'/admin/blog/article/delete_image',
                            success: function(){
                                deleting_images.push(image_name) ;
                                $el.remove();
                            },
                            error: function () {
                              console.log('error');
                            }
                        })
                    }
                }
            },
            messages: {
                acceptFileTypesError: 'This file is not in a supported format: ',
                maxFileSizeError: 'This file is too big: '
            },
            uploadCompleted: function ($el, data) {
               console.log(data.result)
             } // (function) Callback function called when upload is completed
        },
        embeds: { // (object) Embeds addon configuration
            label: '<span class="fa fa-youtube-play"></span>', // (string) A label for an embeds addon
            placeholder: 'Paste a YouTube, Vimeo, Facebook, Twitter or Instagram link and press Enter', // (string) Placeholder displayed when entering URL to embed
            captions: true, // (boolean) Enable captions
            captionPlaceholder: 'Type caption (optional)', // (string) Caption placeholder
            oembedProxy: 'http://medium.iframe.ly/api/oembed?iframe=1', // (string/null) URL to oEmbed proxy endpoint, such as Iframely, Embedly or your own. You are welcome to use "http://medium.iframe.ly/api/oembed?iframe=1" for your dev and testing needs, courtesy of Iframely. *Null* will make the plugin use pre-defined set of embed rules without making server calls.
            styles: { // (object) Available embeds styles configuration
                wide: { // (object) Embed style configuration. Key is used as a class name added to an embed, when the style is selected (.medium-insert-embeds-wide)
                    label: '<span class="fa fa-align-justify"></span>', // (string) A label for a style
                    added: function ($el) {}, // (function) Callback function called after the style was selected. A parameter $el is a current active paragraph (.medium-insert-active)
                    removed: function ($el) {} // (function) Callback function called after a different style was selected and this one was removed. A parameter $el is a current active paragraph (.medium-insert-active)
                },
                left: {
                    label: '<span class="fa fa-align-left"></span>'
                },
                right: {
                    label: '<span class="fa fa-align-right"></span>'
                }
            },
            actions: { // (object) Actions for an optional second toolbar
                remove: { // (object) Remove action configuration
                    label: '<span class="fa fa-times"></span>', // (string) Label for an action
                    clicked: function ($el) { // (function) Callback function called when an action is selected
                        var $event = $.Event('keydown');

                        $event.which = 8;
                        $(document).trigger($event);
                    }
                }
            }
        }
    }
});

$('.article_body')
    .bind('fileupload', function (e, data) {/* ... */})

</script>
@stop


