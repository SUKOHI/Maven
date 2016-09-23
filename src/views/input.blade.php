@extends('maven.layout')

@section('content')

    @include('maven.header')
    @if($errors->count() > 0)

        <div class="alert alert-danger">
            <i class="glyphicon glyphicon-warning-sign"></i> @lang('maven.failed')
        </div>

    @endif
    <h1>{{ $page_title }}</h1>
    @if($mode == 'store')
    {!! Form::open(['route' => 'maven.store']) !!}
    @elseif($mode == 'update')
    {!! Form::open(['route' => ['maven.update', $maven_item->id], 'method' => 'put']) !!}
    @endif
        <ul class="nav nav-tabs">
        @foreach($locales as $locale => $locale_name)
            <li{!! ($locale == $current_locale) ? ' class="active"' : '' !!}><a data-toggle="tab" href="#form-{{ $locale }}">{{ $locale_name }}</a></li>
        @endforeach
        </ul>
        <div class="tab-content">
            @foreach($locales as $locale => $locale_name)
            <div id="form-{{ $locale }}" class="tab-pane fade{!! ($locale == $current_locale) ? ' in active' : '' !!}">
                <br>
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="pull-right text-muted"><span class="bg-danger">&nbsp;{{ $locale }}&nbsp;</span>&nbsp;</div>
                        {!! Form::label(trans('maven.question')) !!}<br>
                        {!! Form::textarea('questions['. $locale .']', (isset($faqs[$locale])) ? $faqs[$locale]->raw_question : '', ['class' => 'form-control', 'rows' => 7]) !!}
                        @if($errors->has('questions.'. $locale))
                           <div class="text-danger">{{ $errors->first('questions.'. $locale) }}</div>
                        @endif
                    </div>
                    <div class="form-group col-md-6">
                        <div class="pull-right text-muted"><span class="bg-danger">&nbsp;{{ $locale }}&nbsp;</span>&nbsp;</div>
                        {!! Form::label(trans('maven.answer')) !!}<br>
                        {!! Form::textarea('answers['. $locale .']', (isset($faqs[$locale])) ? $faqs[$locale]->raw_answer : '', ['class' => 'form-control', 'rows' => 7]) !!}
                        @if($errors->has('answers.'. $locale))
                            <div class="text-danger">{{ $errors->first('answers.'. $locale) }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="pull-right text-muted"><span class="bg-danger">&nbsp;{{ $locale }}&nbsp;</span>&nbsp;</div>
                        {!! Form::label(trans('maven.tags')) !!} <br>
                        {!! Form::text('tags['. $locale .']', (isset($faqs[$locale])) ? $faqs[$locale]->tag_string : '', ['class' => 'form-control']) !!}
                        &nbsp;<small>{{ trans('maven.tag_e_g', [], 'messages', $locale) }}</small>
                    </div>
                    <div class="form-group col-md-6">
                        <div>
                            @if(count($tag_values) > 0)
                                @foreach($tag_values as $tag_value)
                                    <a href="#" class="label label-info tags">{{ $tag_value }}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <hr>
            <div class="row">
                <div class="form-group col-md-6">
                    {!! Form::label(trans('maven.sort')) !!}<br>
                    {!! Form::select('sort', $sort_options, $maven_item->sort_id) !!}
                    @if($errors->has('sort'))
                        <div class="text-danger">{{ $errors->first('sort') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label>
                        {!! Form::checkbox('draft_flag', '1', $maven_item->draft_flag) !!} {{ trans('maven.save_as_draft') }}
                    </label>
                </div>
            </div>
        </div>
        @if(!empty($maven_item->unique_key))
            <div class="text-center">
                <small>{{ trans('maven.unique_key') }}: {{ $maven_item->unique_key }}</small>
            </div>
        @endif
        <div class="text-right" style="margin-top:10px;">
            <a href="{{ route('maven.index') }}" class="btn icon-btn btn-default" type="button" data-dismiss="modal">{{ trans('maven.cancel') }}</a>
            <button class="btn icon-btn btn-primary" type="submit">{{ trans('maven.save') }}</button>
        </div>
    {!! Form::close() !!}

@endsection

@section('style')

    <link href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet">

@endsection

@section('script')

    <script src="//cdn.jsdelivr.net/bootstrap.tagsinput/0.8.0/bootstrap-tagsinput.js"></script>
    <script src="//cdn.jsdelivr.net/jquery.autosize/3.0.17/autosize.min.js"></script>
    <script>

        $('input[name^=tags]').tagsinput({
            trimValue: true
        });
        autosize($('textarea'));

    </script>

@endsection