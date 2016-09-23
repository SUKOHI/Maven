<div>
    @if(count(config('maven.locales')) > 0)
        @foreach(config('maven.locales') as $locale => $locale_name)
            @if($locale == $current_locale)
                <span class="text-muted">{{ $locale_name }}</span>
            @else
                <a href="{{ route('maven.locale', $locale) }}">{{ $locale_name }}</a>
            @endif
            &nbsp;
        @endforeach
    @endif
</div>
<div class="pull-right">
    &nbsp;<a href="{{ route('maven.create') }}" class="btn btn-success">@lang('maven.add')</a>
</div>
<form action="{{ route('maven.index') }}" method="get" role="search">
    <div class="input-group col-md-4 col-sm-6">
        <input type="text" class="form-control" placeholder="@lang('maven.search')" name="q" value="{{ (isset($smoothness)) ? $smoothness->values->get('q') : '' }}">
        <span class="input-group-btn">
            <a href="{{ route('maven.index') }}" class="btn btn-default" type="submit"><i class="glyphicon glyphicon-remove"></i></a>
            <button class="btn btn-primary" type="submit"><i class="glyphicon glyphicon-search"></i></button>
        </span>
    </div>
</form>
<br>