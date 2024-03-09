@extends('elfcms::admin.layouts.infobox')

@section('infoboxpage-content')

    <div class="table-search-box">
        <div class="table-search-result-title">
            @if (!empty($search))
                {{ __('elfcms::default.search_result_for') }} "{{ $search }}" <a href="{{ route('admin.infobox.categories') }}" title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
            @endif
        </div>
        <form action="{{ route('admin.infobox.categories') }}" method="get">
            <div class="input-box">
                <label for="search">
                    {{ __('elfcms::default.search') }}
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
        <table class="grid-table table-cols-9" style="--first-col:65px; --last-col:140px; --minw:800px">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.infobox.categories',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.name') }}
                        <a href="{{ route('admin.infobox.categories',UrlParams::addArr(['order'=>'name','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['name'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.slug') }}
                        <a href="{{ route('admin.infobox.categories',UrlParams::addArr(['order'=>'slug','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['slug'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    {{-- <th>{{ __('elfcms::default.preview') }}</th>
                    <th>{{ __('elfcms::default.image') }}</th>
                    <th>{{ __('elfcms::default.description') }}</th> --}}
                    <th>
                        {{ __('elfcms::default.created') }}
                        <a href="{{ route('admin.infobox.categories',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.updated') }}
                        <a href="{{ route('admin.infobox.categories',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.public_time') }}
                        <a href="{{ route('admin.infobox.categories',UrlParams::addArr(['order'=>'public_time','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['public_time'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.end_time') }}
                        <a href="{{ route('admin.infobox.categories',UrlParams::addArr(['order'=>'end_time','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['end_time'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.active') }}
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
                    <td>{{ $category->created_at }}</td>
                    <td>{{ $category->updated_at }}</td>
                    <td>{{-- {{ $category->public_time }} --}}</td>
                    <td>{{-- {{ $category->end_time }} --}}</td>
                    <td>
                    @if ($category->active)
                        {{ __('elfcms::default.active') }}
                    @else
                        {{ __('elfcms::default.not_active') }}
                    @endif
                    </td>
                    <td class="button-column non-text-buttons">
                        <form action="{{ route('admin.infobox.items.create') }}" method="GET">
                            <input type="hidden" name="category_id" value="{{ $category->id }}">
                            <button type="submit" class="default-btn submit-button create-button" title="{{ __('infobox::default.add_item') }}"></button>
                        </form>
                        <a href="{{ route('admin.infobox.categories.edit',$category) }}" class="default-btn edit-button" title="{{ __('elfcms::default.edit') }}"></a>
                        <form action="{{ route('admin.infobox.categories.update',$category) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="id" value="{{ $category->id }}">
                            <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$category->active }}">
                            <input type="hidden" name="notedit" value="1">
                            <button type="submit" @if ($category->active == 1) class="default-btn deactivate-button" title="{{__('elfcms::default.deactivate') }}" @else class="default-btn activate-button" title="{{ __('elfcms::default.activate') }}" @endif>
                            </button>
                        </form>
                        <form action="{{ route('admin.infobox.categories.destroy',$category) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $category->id }}">
                            <input type="hidden" name="name" value="{{ $category->name }}">
                            <button type="submit" class="default-btn delete-button" title="{{ __('elfcms::default.delete') }}"></button>
                        </form>
                        <div class="contextmenu-content-box">
                            <a href="{{ route('admin.infobox.items',UrlParams::addArr(['category'=>$category->id])) }}" class="contextmenu-item">
                                {{ __('infobox::default.show_items') }}
                            </a>
                            <form action="{{ route('admin.infobox.items.create') }}" method="GET">
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <button type="submit" class="contextmenu-item">{{ __('infobox::default.add_item') }}</button>
                            </form>
                            <a href="{{ route('admin.infobox.categories.edit',$category) }}" class="contextmenu-item">{{ __('elfcms::default.edit') }}</a>
                            <form action="{{ route('admin.infobox.categories.update',$category) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" id="id" value="{{ $category->id }}">
                                <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$category->active }}">
                                <input type="hidden" name="notedit" value="1">
                                <button type="submit" class="contextmenu-item">
                                @if ($category->active == 1)
                                    {{ __('elfcms::default.deactivate') }}
                                @else
                                    {{ __('elfcms::default.activate') }}
                                @endif
                                </button>
                            </form>
                            <form action="{{ route('admin.infobox.categories.destroy',$category) }}" method="POST" data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $category->id }}">
                                <input type="hidden" name="name" value="{{ $category->name }}">
                                <button type="submit" class="contextmenu-item">{{ __('elfcms::default.delete') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (empty(count($categories)))
            <div class="no-results-box">
                {{ __('elfcms::default.nothing_was_found') }}
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
                        title:'{{ __('elfcms::default.deleting_of_element') }}' + categoryId,
                        content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_category') }} "' + categoryName + '" (ID ' + categoryId + ')?</p>',
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
                            title:'{{ __('elfcms::default.deleting_of_element') }}' + categoryId,
                            content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_category') }} "' + categoryName + '" (ID ' + categoryId + ')?</p>',
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
