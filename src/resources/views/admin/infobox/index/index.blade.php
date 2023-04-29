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
                        <a href="{{ route('admin.infobox.infobox.index',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.slug') }}
                        <a href="{{ route('admin.infobox.infobox.index',UrlParams::addArr(['order'=>'slug','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['slug'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.title') }}
                        <a href="{{ route('admin.infobox.infobox.index',UrlParams::addArr(['order'=>'title','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['title'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('infobox::elf.categories_count') }}
                    </th>
                    <th>
                        {{ __('infobox::elf.items_count') }}
                    </th>
                    <th>
                        {{ __('basic::elf.created') }}
                        <a href="{{ route('admin.infobox.infobox.index',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('basic::elf.updated') }}
                        <a href="{{ route('admin.infobox.infobox.index',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
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
                        <a href="{{ route('admin.infobox.infobox.index',['infobox'=>$infobox->id]) }}">
                            {{ $infobox->slug }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.infobox.infobox.index',['infobox'=>$infobox->id]) }}">
                            {{ $infobox->title }}
                        </a>
                    </td>
                    <td>{{ $infobox->items->count() }}</td>
                    <td>{{ $infobox->categories->count() }} ({{ $infobox->topcategories->count() }})</td>
                    <td>{{ $infobox->created_at }}</td>
                    <td>{{ $infobox->updated_at }}</td>
                    <td class="button-column">
                        <a href="{{ route('admin.infobox.infobox.edit',$infobox->id) }}" class="default-btn edit-button">{{ __('basic::elf.edit') }}</a>
                        <form action="{{ route('admin.infobox.infobox.destroy',$infobox->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $infobox->id }}">
                            <input type="hidden" name="name" value="{{ $infobox->name }}">
                            <button type="submit" class="default-btn delete-button">{{ __('basic::elf.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{$infoboxes->links('basic::admin.layouts.pagination')}}

    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')

        if (checkForms) {
            checkForms.forEach(form => {
                form.addEventListener('submit',function(e){
                    e.preventDefault();
                    let infoboxId = this.querySelector('[name="id"]').value,
                        infoboxName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title:'{{ __('basic::elf.deleting_of_element') }}' + infoboxId,
                        content:'<p>{{ __('basic::elf.are_you_sure_to_deleting_infobox') }} "' + infoboxName + '" (ID ' + infoboxId + ')?</p>',
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
    </script>

@endsection
