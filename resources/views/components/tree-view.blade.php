@props(['messages'])

<!-- 1st Level Collapse -->
<div id="hs-cco-tree-collapse-one"
    class="hs-accordion-content w-full @if (!count($messages)) hidden @endif overflow-hidden transition-[height] duration-300"
    role="group" aria-labelledby="hs-cco-tree-heading-one">
    @foreach ($messages as $message)
        <!-- 2nd Level Accordion Group -->
        <div
            class="ps-7 relative before:absolute before:top-0 before:start-3 before:w-0.5 before:-ms-px before:h-full before:bg-gray-100 dark:before:bg-neutral-700">
            <!-- 2nd Level Nested Accordion -->
            <div class="hs-accordion @if (count($messages)) active @endif" role="treeitem"
                aria-expanded=" @if (count($messages)) true @else false @endif"
                id="hs-cco-tree-sub-heading-one"
                data-hs-tree-view-item='{"value": "{{ $message->name }}", "isDir": true}'>
                <!-- 2nd Level Accordion Heading -->
                <div
                    class="hs-accordion-heading py-0.5 rounded-md flex items-center gap-x-0.5 w-full hs-tree-view-selected:bg-gray-100 dark:hs-tree-view-selected:bg-neutral-700">
                    @if ($message->hidden_message)
                        <flux:icon.eye-slash variant="solid" class="text-red-500 dark:text-red-300" />
                    @else
                        @if (count($message->childrenMessages))
                        <flux:icon.folder-open />
                        @else
                        <flux:icon.document-text />
                        @endif
                    @endif
                    

                    <div
                        class="grow hs-tree-view-selected:bg-gray-100 dark:hs-tree-view-selected:bg-neutral-700 px-1.5 rounded-md cursor-pointer">
                        <div class="flex items-center gap-x-3"
                            wire:click="$dispatch('add-or-edit-message-modal', {whatsappMessageBoot: '{{ $message->id }}'})"
                            title="{{ trans('wa-gateway.button.edit-message') }}">
                            <div class="grow">
                                <span class="text-sm text-gray-800 dark:text-neutral-200 flex gap-2">
                                    <flux:button.group>
                                        <flux:button  size="sm"> {{ $message->name }} </flux:button>
                                   
                                        <flux:button wire:click="$dispatch('show-edit-boot-message-modal',{whatsappBootMessage: '{{ $message['id'] }}'})" icon="pencil" size="sm"/>
                                        <flux:button icon="trash" size="sm" disabled/>
                                    </flux:button.group>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- End 2nd Level Accordion Heading -->

                <!-- 2nd Level Collapse -->
                @if (count($message->childrenMessages))
                    <x-tree-view :messages="$message->childrenMessages" />
                @endif

                <!-- End 2nd Level Collapse -->

            </div>
            <!-- End 2nd Level Nested Accordion -->

        </div>
        <!-- 2nd Level Accordion Group -->
    @endforeach
</div>
