
<label class="items-center cursor-pointer">
    <input type="checkbox" value="" class="sr-only peer" wire:model.change="hasDisable" <?php if($disableButton):?> disabled <?php endif;?>>
    <div wire:loading wire:target="hasDisable">
        <x-icon.loading class="inline w-4 h-4 me-3 text-blue-500 " />
    </div>
    <div wire:loading.remove wire:target="hasDisable"
        class="relative w-9 h-5  bg-gray-400 dark:bg-gray-800 dark:border-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 rounded-full peer  peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all @if($disableButton) peer-checked:bg-gray-400 @else peer-checked:bg-green-600 @endif">
    </div>
</label>
