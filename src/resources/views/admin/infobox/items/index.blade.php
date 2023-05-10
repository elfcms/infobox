@extends('infobox::admin.layouts.infobox')

@section('infoboxpage-content')

    <div class="table-search-box">
        <div class="table-search-result-title">
            @if (!empty($search))
                {{ __('basic::elf.search_result_for') }} "{{ $search }}" <a href="{{ route('admin.infobox.items') }}" title="{{ __('basic::elf.reset_search') }}">&#215;</a>
            @endif
        </div>
        <form action="{{ route('admin.infobox.items') }}" method="get">
            <div class="input-box">
                <label for="search">
                    {{ __('basic::elf.search') }}
                </label>
                <div class="input-wrapper">
                    <input type="text" name="search" id="search" value="{{ $search ?? '' }}" placeholder="">
                </div>
                <div class="non-text-buttons">
                    <button type="submit" class="default-btn search-button"></button>
                </div>
            </div>
        </form>
    </div>
    @if (Session::has('itemresult'))
    <div class="alert alert-alternate">{{ Session::get('itemresult') }}</div>
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
        @if (!empty($category))
            <div class="alert alert-alternate">
                {{ __('basic::elf.showing_results_for_category') }} <strong>#{{ $category->id }} {{ $category->title }}</strong>
            </div>
        @endif
        <table class="grid-table infobox-itemtable">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.infobox.items',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.title') }}
                        <a href="{{ route('admin.infobox.items',UrlParams::addArr(['order'=>'title','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['title'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.slug') }}
                        <a href="{{ route('admin.infobox.items',UrlParams::addArr(['order'=>'slug','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['slug'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.category') }}
                        <a href="{{ route('admin.infobox.items',UrlParams::addArr(['order'=>'category_id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['category_id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                {{-- <th>{{ __('basic::elf.preview') }}</th>
                    <th>{{ __('basic::elf.image') }}</th> --}}
                    <th>
                        {{ __('basic::elf.created') }}
                        <a href="{{ route('admin.infobox.items',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.updated') }}
                        <a href="{{ route('admin.infobox.items',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.public_time') }}
                        <a href="{{ route('admin.infobox.items',UrlParams::addArr(['order'=>'public_time','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['public_time'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.end_time') }}
                        <a href="{{ route('admin.infobox.items',UrlParams::addArr(['order'=>'end_time','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['end_time'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.active') }}
                        <a href="{{ route('admin.infobox.items',UrlParams::addArr(['order'=>'active','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['active'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($items as $item)
                <tr data-id="{{ $item->id }}" class="@empty ($item->active) inactive @endempty">
                    <td>{{ $item->id }}</td>
                    <td>
                        <a href="{{ route('admin.infobox.items.edit',$item->id) }}">
                            {{ $item->title }}
                        </a>
                    </td>
                    <td>{{ $item->slug }}</td>
                    <td>
                        <a href="{{ route('admin.infobox.items',UrlParams::addArr(['category'=>$item->category->id])) }}">
                            #{{ $item->category->id }} {{ $item->category->title }}
                        </a>
                    </td>
                {{-- <td class="image-cell">
                        <img src="{{ asset($item->preview) }}" alt="">
                    </td>
                    <td class="image-cell">
                        <img src="{{ asset($item->image) }}" alt="">
                    </td> --}}
                    <td>{{ $item->created_at }}</td>
                    <td>{{ $item->updated_at }}</td>
                    <td>{{ $item->public_time }}</td>
                    <td>{{ $item->end_time }}</td>
                    <td>
                    @if ($item->active)
                        {{ __('basic::elf.active') }}
                    @else
                        {{ __('basic::elf.not_active') }}
                    @endif
                    </td>
                    <td class="button-column non-text-buttons">
                        <a href="{{ route('admin.infobox.items.edit',$item->id) }}" class="default-btn edit-button" title="{{ __('basic::elf.add_item') }}"></a>
                        <form action="{{ route('admin.infobox.items.update',$item->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="id" value="{{ $item->id }}">
                            <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$item->active }}">
                            <input type="hidden" name="notedit" value="1">
                            <button type="submit" @if ($item->active == 1) class="default-btn deactivate-button" title="{{__('basic::elf.deactivate') }}" @else class="default-btn activate-button" title="{{ __('basic::elf.activate') }}" @endif></button>
                        </form>
                        <form action="{{ route('admin.infobox.items.destroy',$item->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <input type="hidden" name="title" value="{{ $item->title }}">
                            <button type="submit" class="default-btn delete-button" title="{{ __('basic::elf.delete') }}"></button>
                        </form>
                        <div class="contextmenu-content-box">
                            <a href="{{ route('admin.infobox.items.edit',$item->id) }}" class="contextmenu-item">{{ __('basic::elf.edit') }}</a>
                            <form action="{{ route('admin.infobox.items.update',$item->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" id="id" value="{{ $item->id }}">
                                <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$item->active }}">
                                <input type="hidden" name="notedit" value="1">
                                <button type="submit" class="contextmenu-item">
                                @if ($item->active == 1)
                                    {{ __('basic::elf.deactivate') }}
                                @else
                                    {{ __('basic::elf.activate') }}
                                @endif
                                </button>
                            </form>
                            <form action="{{ route('admin.infobox.items.destroy',$item->id) }}" method="POST" data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <input type="hidden" name="title" value="{{ $item->title }}">
                                <button type="submit" class="contextmenu-item">{{ __('basic::elf.delete') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (empty(count($items)))
            <div class="no-results-box">
                {{ __('basic::elf.nothing_was_found') }}
            </div>
        @endif
    </div>
    {{$items->links('basic::admin.layouts.pagination')}}

    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')


        function setConfirmDelete(forms) {
            if (forms) {
                forms.forEach(form => {
                    form.addEventListener('submit',function(e){
                        e.preventDefault();
                        let itemId = this.querySelector('[name="id"]').value,
                            itemName = this.querySelector('[name="title"]').value,
                            self = this
                        popup({
                            title:'{{ __('basic::elf.deleting_of_element') }}' + itemId,
                            content:'<p>{{ __('basic::elf.are_you_sure_to_deleting_item') }} "' + itemName + '" (ID ' + itemId + ')?</p>',
                            buttons:[
                                {
                                    title:'{{ __('basic::elf.delete') }}',
                                    class:'default-btn delete-button',
                                    callback: function(){
                                        self.submit()
                                    }
                                },
                                {
                                    title:'{{ __('basic::elf.cancel') }}',
                                    class:'default-btn cancel-button',
                                    callback:'close'
                                }
                            ],
                            class:'danger'
                        })
                    })
                })
            }
        }
        setConfirmDelete(checkForms)

        const tablerow = document.querySelectorAll('.infobox-itemtable tbody tr');
        if (tablerow) {
            tablerow.forEach(row => {
                row.addEventListener('contextmenu',function(e){
                    e.preventDefault()
                    let content = row.querySelector('.contextmenu-content-box').cloneNode(true)
                    let forms  = content.querySelectorAll('form[data-submit="check"]')
                    setConfirmDelete(forms)
                    contextPopup(content,{'left':e.x,'top':e.y})
                })
            })
        }
    </script>

@endsection
