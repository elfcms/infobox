@extends('elfcms::admin.layouts.infobox')

@section('infoboxpage-content')

    @if (Session::has('sbitemdeleted'))
    <div class="alert alert-alternate">{{ Session::get('sbitemdeleted') }}</div>
    @endif
    @if (Session::has('sbitemedited'))
    <div class="alert alert-alternate">{{ Session::get('sbitemedited') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="widetable-wrapper">
        @if (!empty($item))
            <div class="alert alert-alternate">
                {{ __('elfcms::default.showing_results_for_item') }} <strong>#{{ $item->id }} {{ $item->name }}</strong>
            </div>
        @endif
        <table class="grid-table sbitemtable">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.infobox.items',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.code') }}
                        <a href="{{ route('admin.infobox.items',UrlParams::addArr(['order'=>'code','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['code'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.title') }}
                        <a href="{{ route('admin.infobox.items',UrlParams::addArr(['order'=>'title','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['title'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.created') }}
                        <a href="{{ route('admin.infobox.items',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.updated') }}
                        <a href="{{ route('admin.infobox.items',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($items as $item)
            @php
                //dd($item);
            @endphp
                <tr data-id="{{ $item->id }}" class="">
                    <td>{{ $item->id }}</td>
                    <td>
                        <a href="{{ route('admin.infobox.items',['item'=>$item->id]) }}">
                            {{ $item->code }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.infobox.items',['item'=>$item->id]) }}">
                            {{ $item->title }}
                        </a>
                    </td>
                    <td>{{ $item->created_at }}</td>
                    <td>{{ $item->updated_at }}</td>
                    <td class="button-column">
                        <a href="{{ route('admin.infobox.items.edit',$item->id) }}" class="default-btn edit-button">{{ __('elfcms::default.edit') }}</a>
                        <form action="{{ route('admin.infobox.items.destroy',$item->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <input type="hidden" name="name" value="{{ $item->name }}">
                            <button type="submit" class="default-btn delete-button">{{ __('elfcms::default.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{$items->links('elfcms::admin.layouts.pagination')}}

    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')

        if (checkForms) {
            checkForms.forEach(form => {
                form.addEventListener('submit',function(e){
                    e.preventDefault();
                    let itemId = this.querySelector('[name="id"]').value,
                        itemName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title:'{{ __('elfcms::default.deleting_of_element') }}' + itemId,
                        content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_item') }} "' + itemName + '" (ID ' + itemId + ')?</p>',
                        buttons:[
                            {
                                title:'{{ __('elfcms::default.delete') }}',
                                class:'default-btn delete-button',
                                callback: function(){
                                    self.submit()
                                }
                            },
                            {
                                title:'{{ __('elfcms::default.cancel') }}',
                                class:'default-btn cancel-button',
                                callback:'close'
                            }
                        ],
                        class:'danger'
                    })
                })
            })
        }
    </script>

@endsection
