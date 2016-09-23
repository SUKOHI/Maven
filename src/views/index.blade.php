@extends('maven.layout')

@section('content')

    @include('maven.header')
    <h1>{{ $page_title }}</h1>
    @if($maven_items->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th><nobr>@lang('maven.question_and_answer')</nobr></th>
                    <th class="text-right">
                        <div class="dropdown">
                            <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <i class="glyphicon glyphicon-sort"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                @foreach($neatness->all_urls as $key => $urls)
                                    @foreach($urls as $direction => $url)
                                        <li>
                                            <a href="{{ $url }}">
                                                @if($direction == 'asc')
                                                    <i class="glyphicon glyphicon-sort-by-attributes"></i>
                                                @else
                                                    <i class="glyphicon glyphicon-sort-by-attributes-alt"></i>
                                                @endif
                                                @lang('maven.'. $key)
                                            </a>
                                        </li>
                                    @endforeach
                                    @if($key != 'updated')
                                    <li role="separator" class="divider"></li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </th>
                </tr>
            </thead>
        @foreach($maven_items as $maven_item)
            <tr>
                <td><label>{{ $maven_item->sort_id }}</label></td>
                <td style="line-height: 1.8em;">
                @if(!is_null($maven_item->faq))
                    <span class="label label-danger">Q</span> <span style="text-decoration: underline;background:#eee;">{{ str_limit($maven_item->faq->raw_question, 100) }}</span><br>
                    <span class="label label-default">A</span> {{ str_limit($maven_item->faq->raw_answer, 140) }}
                    <div class="clearfix"></div>
                    <div class="pull-right">
                        @if($maven_item->draft_flag)
                        <span class="badge">@lang('maven.draft')</span>
                        @endif
                    </div>
                    @if($maven_item->faq->tags->count() > 0)
                        <div>
                            @foreach($maven_item->faq->tags as $maven_tag)
                                <a href="?tag={{ urlencode($maven_tag->tag) }}" class="btn btn-xs btn-default">{{ $maven_tag->tag }}</a>
                            @endforeach
                        </div>
                    @endif
                @endif
                </td>
                <td class="text-right">
                    <a href="{{ route('maven.edit', $maven_item->id) }}" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-edit"></i></a>
                    <button class="btn btn-default btn-sm remove-button" data-id="{{ $maven_item->id }}"><i class="glyphicon glyphicon-remove"></i></button>
                </td>
            </tr>
        @endforeach
        </table>
        <div class="text-center">
            {{ $maven_items
                ->appends($smoothness->appends)
                ->appends($neatness->appends)
                ->links() }}
        </div>
        {!! Form::open(['method' => 'delete', 'id' => 'delete-form']) !!}
        {!! Form::close() !!}
    @else
        @lang('maven.item_not_found')
    @endif

@endsection

@section('script')

    <script>

        $('.remove-button').on('click', function(){

            if(!confirm('{{ trans('maven.delete_confirm') }}')) {

                return false;

            }

            var id = $(this).data('id');
            var url = '{{ route('maven.destroy', '*') }}'.replace('*', id);
            $('#delete-form').prop('action', url).submit();

        });


    </script>

@endsection