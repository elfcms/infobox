@extends('infobox::admin.layouts.infobox')

@section('infoboxpage-content')

    <div class="table-search-box">
        <div class="table-search-result-title">
            @if (!empty($search))
                {{ __('basic::elf.search_result_for') }} "{{ $search }}" <a href="{{ route('admin.infobox.categories') }}" title="{{ __('basic::elf.reset_search') }}">&#215;</a>
            @endif
        </div>
        <form action="{{ route('admin.infobox.categories') }}" method="get">
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
    @if (Session::has('categorydeleted'))
    <div class="alert alert-alternate">{{ Session::get('categorydeleted') }}</div>
    @endif
    @if (Session::has('categoryedited'))
    <div class="alert alert-alternate">{{ Session::get('categoryedited') }}</div>
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
        <table class="grid-table categorytable">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.infobox.categories',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.name') }}
                        <a href="{{ route('admin.infobox.categories',UrlParams::addArr(['order'=>'name','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['name'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.slug') }}
                        <a href="{{ route('admin.infobox.categories',UrlParams::addArr(['order'=>'slug','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['slug'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    {{-- <th>{{ __('basic::elf.preview') }}</th>
                    <th>{{ __('basic::elf.image') }}</th>
                    <th>{{ __('basic::elf.description') }}</th> --}}
                    <th>
                        {{ __('basic::elf.created') }}
                        <a href="{{ route('admin.infobox.categories',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.updated') }}
                        <a href="{{ route('admin.infobox.categories',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.public_time') }}
                        <a href="{{ route('admin.infobox.categories',UrlParams::addArr(['order'=>'public_time','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['public_time'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.end_time') }}
                        <a href="{{ route('admin.infobox.categories',UrlParams::addArr(['order'=>'end_time','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['end_time'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.active') }}
                        <a href="{{ route('admin.infobox.categories',UrlParams::addArr(['order'=>'active','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['active'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <tr data-id="{{ $category->id }}" class="level-{{ $category->level }}@empty ($category->active) inactive @endempty">
                    <td class="subline-{{ $category->level }}">{{ $category->id }}</td>
                    <td>
                        <a href="{{ route('admin.infobox.categories.edit',$category) }}">
                            {{ $category->title }}
                        </a>
                    </td>
                    <td>{{ $category->slug }}</td>
                    {{-- <td class="image-cell">
                        <img src="{{ asset($category->preview) }}" alt="">
                    </td>
                    <td class="image-cell">
                        <img src="{{ asset($category->image) }}" alt="">
                    </td>
                    <td>{{ $category->description }}</td> --}}
                    <td>{{ $category->created_at }}</td>
                    <td>{{ $category->updated_at }}</td>
                    <td>{{-- {{ $category->public_time }} --}}</td>
                    <td>{{-- {{ $category->end_time }} --}}</td>
                    <td>
                    @if ($category->active)
                        {{ __('basic::elf.active') }}
                    @else
                        {{ __('basic::elf.not_active') }}
                    @endif
                    </td>
                    <td class="button-column non-text-buttons">
                        <form action="{{ route('admin.infobox.items.create') }}" method="GET">
                            <input type="hidden" name="category_id" value="{{ $category->id }}">
                            <button type="submit" class="default-btn submit-button create-button" title="{{ __('infobox::elf.add_item') }}"></button>
                        </form>
                        <a href="{{ route('admin.infobox.categories.edit',$category) }}" class="default-btn edit-button" title="{{ __('basic::elf.edit') }}"></a>
                        <form action="{{ route('admin.infobox.categories.update',$category) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="id" value="{{ $category->id }}">
                            <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$category->active }}">
                            <input type="hidden" name="notedit" value="1">
                            <button type="submit" @if ($category->active == 1) class="default-btn deactivate-button" title="{{__('basic::elf.deactivate') }}" @else class="default-btn activate-button" title="{{ __('basic::elf.activate') }}" @endif>
                            </button>
                        </form>
                        <form action="{{ route('admin.infobox.categories.destroy',$category) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $category->id }}">
                            <input type="hidden" name="name" value="{{ $category->name }}">
                            <button type="submit" class="default-btn delete-button" title="{{ __('basic::elf.delete') }}"></button>
                        </form>
                        <div class="contextmenu-content-box">
                            <a href="{{ route('admin.infobox.items',UrlParams::addArr(['category'=>$category->id])) }}" class="contextmenu-item">
                                {{ __('infobox::elf.show_items') }}
                            </a>
                            <form action="{{ route('admin.infobox.items.create') }}" method="GET">
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <button type="submit" class="contextmenu-item">{{ __('infobox::elf.add_item') }}</button>
                            </form>
                            <a href="{{ route('admin.infobox.categories.edit',$category) }}" class="contextmenu-item">{{ __('basic::elf.edit') }}</a>
                            <form action="{{ route('admin.infobox.categories.update',$category) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" id="id" value="{{ $category->id }}">
                                <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$category->active }}">
                                <input type="hidden" name="notedit" value="1">
                                <button type="submit" class="contextmenu-item">
                                @if ($category->active == 1)
                                    {{ __('basic::elf.deactivate') }}
                                @else
                                    {{ __('basic::elf.activate') }}
                                @endif
                                </button>
                            </form>
                            <form action="{{ route('admin.infobox.categories.destroy',$category) }}" method="POST" data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $category->id }}">
                                <input type="hidden" name="name" value="{{ $category->name }}">
                                <button type="submit" class="contextmenu-item">{{ __('basic::elf.delete') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (empty(count($categories)))
            <div class="no-results-box">
                {{ __('basic::elf.nothing_was_found') }}
            </div>
        @endif
    </div>

    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')

        /* if (checkForms) {
            checkForms.forEach(form => {
                form.addEventListener('submit',function(e){
                    e.preventDefault();
                    let categoryId = this.querySelector('[name="id"]').value,
                        categoryName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title:'{{ __('basic::elf.deleting_of_element') }}' + categoryId,
                        content:'<p>{{ __('basic::elf.are_you_sure_to_deleting_category') }} "' + categoryName + '" (ID ' + categoryId + ')?</p>',
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
        } */

        function setConfirmDelete(forms) {
            if (forms) {
                forms.forEach(form => {
                    form.addEventListener('submit',function(e){
                        e.preventDefault();
                        let categoryId = this.querySelector('[name="id"]').value,
                            categoryName = this.querySelector('[name="name"]').value,
                            self = this
                        popup({
                            title:'{{ __('basic::elf.deleting_of_element') }}' + categoryId,
                            content:'<p>{{ __('basic::elf.are_you_sure_to_deleting_category') }} "' + categoryName + '" (ID ' + categoryId + ')?</p>',
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

        const tablerow = document.querySelectorAll('.categorytable tbody tr');
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
