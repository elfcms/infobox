<div class="popup-wrapper {{ $message['type'] ?? '' }}" id="popup_{{time()}}" style="position: fixed;inset: 0px;z-index: 10000;">
    <div class="popup-box">
        <div class="popup-close" onclick="messageDestroy(this)"></div>
        <div class="popup-header">
            <div class="popup-title">{!! $message['header'] ?? '&nbsp;' !!}</div>
        </div>
        <div class="popup-content">
            <p>{!! $message['text'] ?? '' !!}</p>
        </div>
        <div class="popup-button-box">
            <button class="button color-text-button" onclick="messageDestroy(this)">OK</button>
        </div>
    </div>
</div>
<script>
function messageDestroy(th) {
    console.log(th);
    const wrapper = th.closest('.popup-wrapper');
console.log(wrapper);
    if (wrapper) {
        wrapper.remove();
    }
}
</script>
