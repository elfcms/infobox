{{-- <div class="infobox-nav-up-ib">
    <a href="{{ route('admin.infobox.nav',['infobox'=>$infobox]) }}">{{ $infobox->title }}</a>
</div>
@if ($category)
<div class="infobox-nav-up-cat">
    <a href="{{ route('admin.infobox.nav',['infobox'=>$infobox,'category'=>$category]) }}">{{ $category->title }}</a>
</div>
@endif --}}
@if ($category || $infobox->title)
<h4 class="infobox-nav-title">
    {{ $category->title ?? $infobox->title ?? __('infobox::default.infoboxes') }}
</h4>
<div class="infobox-nav-content">
    @if ($category)
        @if ($category->parent)
            <a class="infobox-nav-up" href="{{ route('admin.infobox.nav',['infobox'=>$infobox,'category'=>$category->parent]) }}">..</a>
        @else
            <a class="infobox-nav-up" href="{{ route('admin.infobox.nav',['infobox'=>$infobox]) }}">..</a>
        @endif
        @if ($category->categories)
        <div class="infobox-nav-categories infobox-nav-dnd-area infobox-nav-dnd-area-cat" data-container="category">
            @foreach ($category->categories as $cat)
                @include('elfcms::admin.infobox.nav.partials.category-line',['items'=>$cat->items])
            @endforeach
        </div>
        @endif
        @if ($category->items)
        <div class="infobox-nav-items infobox-nav-dnd-area infobox-nav-dnd-area-item" data-container="item">
            @foreach ($category->items()->position()->get() as $item)
                @include('elfcms::admin.infobox.nav.partials.item-line')
            @endforeach
        </div>
        @endif
    @else
        @if (!empty($infobox->topCategories))
        <div class="infobox-nav-categories infobox-nav-dnd-area infobox-nav-dnd-area-cat" data-container="category">
            @foreach ($infobox->topCategories as $cat)
                @include('elfcms::admin.infobox.nav.partials.category-line',['items'=>$cat->items])
            @endforeach
        </div>
        @endif
        @if (!empty($infobox->items))
        <div class="infobox-nav-items infobox-nav-dnd-area infobox-nav-dnd-area-item" data-container="item">
            @foreach ($infobox->topItems()->position()->get() as $item)
                @include('elfcms::admin.infobox.nav.partials.item-line')
            @endforeach
        </div>
        @endif
    @endif
</div>
@else
<div class="infobox-nav-content">
    <div class="infobox-nav-dnd-area infobox-nav-dnd-area-ib">
    @forelse ($infoboxes as $ib)
        @include('elfcms::admin.infobox.nav.partials.infobox')
    @empty
        {{ __('elfcms::default.nothing_was_found') }}
    @endforelse
    </div>
</div>
@endif
<script>
const checkForms = document.querySelectorAll('form[data-submit="check"]')
function setConfirmDelete(forms) {
    if (forms) {
        forms.forEach(form => {
            form.addEventListener('submit',function(e){
                e.preventDefault();
                let categoryId = this.querySelector('[name="id"]').value,
                    categoryName = this.querySelector('[name="name"]').value,
                    header = this.dataset.header,
                    message = this.dataset.message,
                    self = this
                popup({
                    title: header,
                    content:'<p>' + message + '</p>',
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
/*
const rows = document.querySelectorAll('.infobox-nav-category summary, .infobox-nav-item');
if (rows) {
    rows.forEach(row => {
        row.addEventListener('contextmenu',function(e){
            const box = row.querySelector('.contextmenu-content-box')
            if (box) {
                e.preventDefault()
                let content = box.cloneNode(true)
                let forms  = content.querySelectorAll('form[data-submit="check"]')
                setConfirmDelete(forms)
                contextPopup(content,{'left':e.x,'top':e.y})
            }
        })
    })
}
window.onload = (event) => {
  console.log("page is fully loaded");
};*/
</script>
@section('footerscript')
<script src="{{ asset('elfcms/admin/modules/infobox/js/navlineorder.js') }}"></script>
@endsection
