<?php
    $route           = str_replace('.', '_', Route::currentRouteName());
    $meta_parameters = isset($meta_parameters) ? $meta_parameters : [];
    $title       = trans("seo.{$route}.title", $meta_parameters);
    $description = trans("seo.{$route}.description", $meta_parameters);
    $keywords    = trans("seo.{$route}.keywords", $meta_parameters);

    if (App::environment(['production', 'staging'])) {
        if ($title === "seo.{$route}.title") {
            $title = trans("seo.default.title");
        }

        if ($description === "seo.{$route}.description") {
            $description = trans("seo.default.description");
        }

        if ($keywords === "seo.{$route}.keywords") {
            $keywords = trans("seo.default.keywords");
        }
    }
    
?>
<title>{{ $meta_title or $title }}</title>
<meta name="description" content="{{ $meta_description or $description }}">
<meta name="keywords" content="{{ $meta_keywords or $keywords }}">
@if(isset($meta_parameters['noindex']) && $meta_parameters['noindex'] == true) <meta name="robots" content="noindex"> @endif

@if (isset($canonical) && ! empty($canonical)) 
    <link rel="canonical" href="{{ $canonical }}">
@endif
@if (isset($results) && $results->meta->nextPage)
    <link rel="next" href="{{ $results->meta->nextPage }}">
@endif
@if (isset($results) && $results->meta->prevPage)
    <link rel="prev" href="{{ $results->meta->prevPage }}">
@endif
