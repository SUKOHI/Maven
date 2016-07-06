<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">
<head>
    <title>{{ trans('maven::manage.title') }}</title>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <style>

        .text-bold {

            font-weight:bold;

        }

        .line-height-2 {

            line-height: 2em !important;

        }

    </style>
</head>
<body>
<div class="container">

    @if(!empty($message))
    <br>
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {!! $message !!}
    </div>
    @else
    <br>
    @endif
    <div class="text-right">
        <a href="/maven" class="btn btn-default btn-sm">{{ trans('maven::manage.clear') }}</a>
        <button id="add_button" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-plus"></i> {{ trans('maven::manage.add') }}</button>
    </div>
    @if(Request::has('remove_id') || (!Request::has('_token') && !Request::has('id')))
        {!! Form::open(['id' => 'save_form', 'style' => 'display:none']) !!}
    @else
        {!! Form::open(['id' => 'save_form']) !!}
    @endif
    <br>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <div class="pull-right">
                <a href="/maven"><i class="glyphicon glyphicon-remove-sign" style="color:#fff;"></i></a>
            </div>
            <h3 class="panel-title text-bold"><i class="glyphicon glyphicon-question-sign"></i> {{ trans('maven::manage.faq_form') }}</h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <i class="glyphicon glyphicon-chevron-right"></i> {!! Form::label(trans('maven::manage.question')) !!}<br>
                {!! Form::text('question', Request::get('question'), ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                <i class="glyphicon glyphicon-chevron-right"></i> {!! Form::label(trans('maven::manage.answer')) !!}<br>
                {!! Form::textarea('answer', Request::get('answer'), ['rows' => 5, 'class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                <i class="glyphicon glyphicon-chevron-right"></i> {!! Form::label(trans('maven::manage.sort')) !!}<br>
                {!! Form::select('sort', $sort_values, Request::get('sort')) !!}
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <i class="glyphicon glyphicon-chevron-right"></i> {!! Form::label(trans('maven::manage.tags')) !!}<br>
                    {!! Form::text('tags', Request::get('tags'), ['id' => 'tags', 'class' => 'form-control']) !!}
                    <br><span class="text-muted">{{ trans('maven::manage.tag_e_g') }}</span>
                </div>
                <div class="form-group col-md-6">
                    <br>
                    <div>
                    @if(count($tag_values) > 0)
                        @foreach($tag_values as $tag_value)
                            <a href="#" class="label label-info tags">{{ $tag_value }}</a>
                        @endforeach
                    @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <i class="glyphicon glyphicon-chevron-right"></i> {!! Form::label(trans('maven::manage.locale')) !!}<br>
                {!! Form::select('locale', \Sukohi\Maven\MavenLocale::options(), Request::get('locale')) !!}
                </div>
            </div>
            <div class="clearfix form-group checkbox">
                <label>{!! Form::checkbox('draft_flag', '1', Request::get('draft_flag')) !!} {{ trans('maven::manage.save_as_draft') }}</label>
            </div>
            <div class="text-right">
                {!! link_to(URL::current() .'?locale='. Request::get('locale'), trans('maven::manage.cancel'), ['class' => 'btn btn-md btn-default']) !!}&nbsp;
                <button type="submit" class="btn btn-md btn-primary"><i class="glyphicon glyphicon-saved"></i> {{ trans('maven::manage.save') }}</button>
            </div>
        </div>
    </div>
    @if(Request::has('id'))
        {!! Form::hidden('id', Request::get('id')) !!}
    @endif
    @if(Request::has('search_locale'))
        {!! Form::hidden('search_locale', Request::get('search_locale')) !!}
    @endif
    <br>
    {!! Form::close() !!}
    @if(!empty($locales))
        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                {{ trans('maven::manage.locale') }}
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <li><a href="?search_locale=">{{ trans('maven::manage.all') }}</a></li>
                @foreach($locales as $locale)
                    <li><a href="?search_locale={{ $locale }}">{{ $locale }}</a></li>
                @endforeach
            </ul>
        </div>
        <br>
    @else
        <br>
        <br>
    @endif
    @if($faqs->count() > 0)
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th><nobr>{{ trans('maven::manage.q_and_a') }}</nobr></th>
                    <th><nobr>{{ trans('maven::manage.tags') }}</nobr></th>
                    <th><nobr>{{ trans('maven::manage.locale') }}</nobr></th>
                    <th><nobr>{{ trans('maven::manage.unique_key') }}</nobr></th>
                    <th class="text-center"><nobr>{{ trans('maven::manage.draft') }}</nobr></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($faqs as $index => $faq)
                <tr>
                    <td>{!! $faq->sort_number !!}</td>
                    <td>
                        <div class="text-bold">{!! $faq->question !!}</div>
                        <br>
                        {!! $faq->answer !!}
                    </td>
                    <td class="line-height-2">
                        @if(!empty($faq->tags))
                            @foreach($faq->tags as $tag)
                                @if(!empty($tag))
                                <a href="?locale={{ Request::get('locale') }}&search_key={{ urlencode($tag) }}" class="btn btn-default btn-xs">{{ $tag }}</a>
                                @endif
                            @endforeach
                        @endif
                    </td>
                    <td class="line-height-2">
                        @if(!empty($faq->locale))
                        <a class="btn btn-default btn-xs" href="?search_locale={{ $faq->locale }}">{!! $faq->locale !!}</a>
                        @endif
                    </td>
                    <td>{!! $faq->unique_key !!}</td>
                    <td class="text-center">{!! $faq->draft_flag_icon !!}</td>
                    <td class="text-right">
                        <nobr>
                        &nbsp;
                        &nbsp;
                        <a href="?id={{ $faq->id }}&search_locale={{ Request::get('locale') }}" class="btn btn-xs btn-default btn-warning">
                            <i class="glyphicon glyphicon-pencil"></i>
                        </a>
                        <button href="?id={{ $faq->id }}" class="btn btn-xs btn-default btn-danger remove-button" data-id="{{ $faq->id }}">
                            <i class="glyphicon glyphicon-remove"></i>
                        </button>
                        </nobr>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="text-center">
            {!! $faqs->render() !!}
        </div>
    @endif
    {!! Form::open(['id' => 'remove_form']) !!}
        {!! Form::hidden('remove_id', '', ['id' => 'remove_id']) !!}
    {!! Form::close() !!}
</div>
<script>
    $(document).ready(function(){

        $('#add_button').on('click', function(){

            $('#save_form').slideToggle('fast');
            $('textarea[name=question]').focus();

        });
        $('.remove-button').on('click', function(){

            if(confirm('Delete this record?')) {

                var id = $(this).data('id');
                $('#remove_id').val(id);
                $('#remove_form').submit();

            }

        });
        $('.tags').on('click', function(){

            var tag = $(this).html();
            var currentTagString = $('#tags').val();
            var currentTags = currentTagString.split(',');

            if($.inArray(tag, currentTags) == -1) {

                var newTagString = currentTagString;

                if(currentTagString != '') {

                    newTagString += ',';

                }

                newTagString += tag;
                $('#tags').val(newTagString)

            }

            return false;

        });

    });
</script>
</body>
</html>
