{{-- <div class="infobox-nav-up-ib">
    <a href="{{ route('admin.infobox.nav',['infobox'=>$infobox]) }}">{{ $infobox->title }}</a>
</div>
@if ($category)
<div class="infobox-nav-up-cat">
    <a href="{{ route('admin.infobox.nav',['infobox'=>$infobox,'category'=>$category]) }}">{{ $category->title }}</a>
</div>
@endif --}}
<h4 class="infobox-nav-title">
    {{ $category->title ?? $infobox->title }}
</h4>
<div class="infobox-nav-content">
    @if ($category)
        @if ($category->parent)
            <a class="infobox-nav-up" href="{{ route('admin.infobox.nav',['infobox'=>$infobox,'category'=>$category->parent]) }}">..</a>
        @else
            <a class="infobox-nav-up" href="{{ route('admin.infobox.nav',['infobox'=>$infobox]) }}">..</a>
        @endif
        @if ($category->categories)
            @foreach ($category->categories as $cat)
                @include('infobox::admin.infobox.nav.partials.category',['items'=>$cat->items])
            @endforeach
        @endif
        @if ($category->items)
            @foreach ($category->items as $item)
                @include('infobox::admin.infobox.nav.partials.item')
            @endforeach
        @endif
    @else
        @if ($infobox->topCategories)
            @foreach ($infobox->topCategories as $cat)
                @include('infobox::admin.infobox.nav.partials.category',['items'=>$cat->items])
            @endforeach
        @endif
        @if ($infobox->items)
            @foreach ($infobox->topItems as $item)
                @include('infobox::admin.infobox.nav.partials.item')
            @endforeach
        @endif
    @endif
</div>
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
};
</script>

<script>

</script>
