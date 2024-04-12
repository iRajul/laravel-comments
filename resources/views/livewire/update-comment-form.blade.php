<div x-data="{showMsg: false}">
    <div wire:ignore>
        <div id="{{$editorId}}" class="min-h-32 rounded rounded-t-none"></div>
        <div id="{{$toolbarId}}" class="w-full"></div>
    </div>
    <div class="min-h-6">
        @if($errors->has('text'))
            <span class="text-red-500 align-top text-xs sm:text-sm">
                {{__($errors->first('text'))}}
            </span>
        @endif
    </div>

    <div
            x-show="!showMsg"
            x-transition
            @comment-updated.window = "(e) => {
                let key = @js($comment->getKey());
                if(e.detail.commentId === key && $wire.approvalRequired) {
                    showMsg = true;
                }
            }"
            class="space-x-4">
        <x-comments::button wire:click="save" size="sm" dirtyTarget="text" loadingTarget="save">Save
        </x-comments::button>
        <x-comments::button wire:click="discard" size="sm" severity="info" type="button" loadingTarget="discard">
            Discard
        </x-comments::button>
    </div>

    <div x-show="showMsg" x-transition>
        <span
                x-transition
                class="text-green-500 text-xs sm:text-sm align-top"
        >
            {{__('Comment updated and will be displayed once approved !')}}
        </span>
    </div>
</div>

@script
<script>
    let editorConfig = @js(config('comments.editor_config'));
    const quill = new Quill(`#${$wire.editorId}`, editorConfig);

    const editorElm = document.querySelector(`#${$wire.editorId} .ql-editor`);
    const toolbarParentElm = document.querySelector(`#${$wire.toolbarId}`);

    const toolbars = Array.from(document.querySelector('.ql-toolbar'));

    toolbarParentElm.append(toolbars.slice(-1));

    editorElm.innerHTML = $wire.text;

    quill.on('text-change', (delta, oldDelta, source) => {
        let html = editorElm.innerHTML;
        if (html === '<p><br></p>') {
            $wire.text = '';
            return
        }
        $wire.text = html;
    });


    $wire.on('comment-update-discarded', function () {
        editorElm.innerHTML = @js($comment->text);
    });

    Alpine.data('successMsg', () => ({
        show: false,
        timeout: 2000,

        set(show, event) {
            if (event.detail.id !== $wire.editorId) {
                return;
            }
            this.show = show;
            setTimeout(() => {
                this.show = false;
            }, this.timeout)
        }
    }));
</script>
@endscript