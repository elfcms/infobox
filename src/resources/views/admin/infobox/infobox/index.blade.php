@extends('infobox::admin.layouts.infobox')

@section('infoboxpage-content')

    @if (Session::has('infoboxresult'))
    <div class="alert alert-alternate">{{ Session::get('infoboxresult') }}</div>
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
        @if (!empty($infobox))
            <div class="alert alert-alternate">
                {{ __('basic::elf.showing_results_for_item') }} <strong>#{{ $infobox->id }} {{ $infobox->name }}</strong>
            </div>
        @endif
        <table class="grid-table infoboxestable">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.infobox.infoboxes',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.slug') }}
                        <a href="{{ route('admin.infobox.infoboxes',UrlParams::addArr(['order'=>'slug','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['slug'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.title') }}
                        <a href="{{ route('admin.infobox.infoboxes',UrlParams::addArr(['order'=>'title','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['title'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('infobox::elf.categories_count') }}
                    </th>
                    <th>
                        {{ __('infobox::elf.items_count') }}
                    </th>
                    <th>
                        {{ __('basic::elf.created') }}
                        <a href="{{ route('admin.infobox.infoboxes',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.updated') }}
                        <a href="{{ route('admin.infobox.infoboxes',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($infoboxes as $infobox)
            @php
                //dd($infobox);
            @endphp
                <tr data-id="{{ $infobox->id }}" class="">
                    <td>{{ $infobox->id }}</td>
                    <td>
                        <a href="{{ route('admin.infobox.infoboxes.edit',$infobox) }}">
                            {{ $infobox->slug }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.infobox.infoboxes.show',$infobox) }}">
                            {{ $infobox->title }}
                        </a>
                    </td>
                    <td>{{ $infobox->items->count() }}</td>
                    <td>{{ $infobox->categories->count() }} ({{ $infobox->topcategories->count() }})</td>
                    <td>{{ $infobox->created_at }}</td>
                    <td>{{ $infobox->updated_at }}</td>
                    {{-- <td class="button-column">
                        <a href="{{ route('admin.infobox.infoboxes.edit',$infobox->id) }}" class="default-btn edit-button">{{ __('basic::elf.edit') }}</a>
                        <form action="{{ route('admin.infobox.infoboxes.destroy',$infobox->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $infobox->id }}">
                            <input type="hidden" name="name" value="{{ $infobox->name }}">
                            <button type="submit" class="default-btn delete-button">{{ __('basic::elf.delete') }}</button>
                        </form>
                    </td> --}}
                    <td class="button-column non-text-buttons">
                        {{-- <form action="{{ route('admin.infobox.infoboxes.create') }}" method="GET">
                            <input type="hidden" name="infobox_id" value="{{ $infobox->id }}">
                            <button type="submit" class="default-btn submit-button create-button" title="{{ __('basic::elf.add_post') }}"></button>
                        </form> --}}
                        <a href="{{ route('admin.infobox.infoboxes.edit',$infobox) }}" class="default-btn edit-button" title="{{ __('basic::elf.edit') }}"></a>
                        <form action="{{ route('admin.infobox.infoboxes.update',$infobox) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="id" value="{{ $infobox->id }}">
                            <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$infobox->active }}">
                            <input type="hidden" name="notedit" value="1">
                            <button type="submit" @if ($infobox->active == 1) class="default-btn deactivate-button" title="{{__('basic::elf.deactivate') }}" @else class="default-btn activate-button" title="{{ __('basic::elf.activate') }}" @endif>
                            </button>
                        </form>
                        <form action="{{ route('admin.infobox.infoboxes.destroy',$infobox) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $infobox->id }}">
                            <input type="hidden" name="name" value="{{ $infobox->title }}">
                            <button type="submit" class="default-btn delete-button" title="{{ __('basic::elf.delete') }}"></button>
                        </form>
                        <div class="contextmenu-content-box">
                            {{-- <a href="{{ route('admin.infobox.infoboxes',UrlParams::addArr(['infobox'=>$infobox->id])) }}" class="contextmenu-item">
                                {{ __('basic::elf.show_posts') }}
                            </a>
                            <form action="{{ route('admin.infobox.infoboxes.create') }}" method="GET">
                                <input type="hidden" name="infobox_id" value="{{ $infobox->id }}">
                                <button type="submit" class="contextmenu-item">{{ __('basic::elf.add_post') }}</button>
                            </form> --}}
                            <a href="{{ route('admin.infobox.infoboxes.edit',$infobox) }}" class="contextmenu-item">{{ __('basic::elf.edit') }}</a>
                            <form action="{{ route('admin.infobox.infoboxes.update',$infobox) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" id="id" value="{{ $infobox->id }}">
                                <input type="hidden" name="active" id="active" value="{{ (int)!(bool)$infobox->active }}">
                                <input type="hidden" name="notedit" value="1">
                                <button type="submit" class="contextmenu-item">
                                @if ($infobox->active == 1)
                                    {{ __('basic::elf.deactivate') }}
                                @else
                                    {{ __('basic::elf.activate') }}
                                @endif
                                </button>
                            </form>
                            <form action="{{ route('admin.infobox.infoboxes.destroy',$infobox) }}" method="POST" data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $infobox->id }}">
                                <input type="hidden" name="name" value="{{ $infobox->title }}">
                                <button type="submit" class="contextmenu-item">{{ __('basic::elf.delete') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{$infoboxes->links('basic::admin.layouts.pagination')}}

    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')

        function setConfirmDelete(forms) {
            if (forms) {
                forms.forEach(form => {
                    form.addEventListener('submit',function(e){
                    e.preventDefault();
                    let infoboxId = this.querySelector('[name="id"]').value,
                        infoboxName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title:'{{ __('infobox::elf.deleting_of_infobox',['infobox'=>'']) }} "' + infoboxName + '"',
                        content:'<p>{{ __('infobox::elf.are_you_sure_to_deleting_infobox') }} (ID ' + infoboxId + ')</p>',
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

        setConfirmDelete(checkForms);

        const tablerow = document.querySelectorAll('.infoboxestable tbody tr');
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
