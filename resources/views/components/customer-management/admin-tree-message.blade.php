<div id="tree-view" class="bg-white rounded p-4 dark:bg-neutral-900" role="tree" aria-orientation="vertical"
    data-hs-tree-view="">

    @foreach ($treeMessages as $message)
        <!-- 1st Level Accordion -->
        <div class="hs-accordion  @if (count($message->childrenMessages)) active @endif" role="treeitem"
            aria-expanded="  @if (count($message->childrenMessages)) true @else false @endif" id="hs-cco-tree-heading-one"
            data-hs-tree-view-item='{"value": "{{ $message->name }}","isDir": true}'>
            <!-- 1st Level Accordion Heading -->
            <div
                class="hs-accordion-heading py-0.5 rounded-md flex items-center gap-x-0.5 w-full hs-tree-view-selected:bg-gray-100 dark:hs-tree-view-selected:bg-neutral-700">
                <button
                    class="hs-accordion-toggle size-6 flex justify-center items-center hover:bg-gray-100 rounded-md focus:outline-none focus:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                    aria-expanded="true" aria-controls="hs-cco-tree-collapse-one">
                    @if ($message->hidden_message)
                        <flux:icon.eye-slash variant="solid" class="text-red-500 dark:text-red-300" />
                    @else
                        @if (count($message->childrenMessages))
                        <flux:icon.folder-open />
                        @else
                        <flux:icon.document-text />
                        @endif
                    @endif
                </button>

                <div
                    class="grow hs-tree-view-selected:bg-gray-100 dark:hs-tree-view-selected:bg-neutral-700 px-1.5 rounded-md cursor-pointer">
                    <div wire:click="$dispatch('add-or-edit-message-modal', {whatsappMessageBoot: '{{ $message->id }}'})"
                        title="{{ trans('wa-gateway.button.edit-message') }}" class="flex items-center gap-x-3">

                        <div class="grow">
                            <span class="text-sm text-gray-800 dark:text-neutral-200">
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
            <!-- End 1st Level Accordion Heading -->
            @if (count($message->childrenMessages))
                <x-tree-view :messages="$message->childrenMessages" />
            @endif
        </div>
    @endforeach

   

</div>
