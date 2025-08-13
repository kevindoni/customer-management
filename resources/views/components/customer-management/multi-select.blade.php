@props(['options' => [], 'placeholderValue' => '', 'model', 'valueKey' => 'id', 'displayName', 'keys'])

@php
$uniqId = 'select' . uniqid();
@endphp
<div wire:ignore x-data x-init="$nextTick(() => {
    const choices = new Choices($refs.{{ $uniqId }}, {
        removeItems: true,
        removeItemButton: true,
        searchResultLimit: 5,
        maxItemCount: -1,
        placeholderValue: '{{ $placeholderValue }}',

    })
//console.log('load');
    // This is to handle 'Send to Group' checkbox
    $wire.on('selectGroup', (selectedOptions) => {
        if (selectedOptions[0].length > 0) {
            //Deselect the previously selected options
            choices.removeActiveItems();
           // Select the provided options
            selectedOptions[0].map((option) => {
                choices.setChoiceByValue(`${option}`, true);
            });
        } else {
            // Deselect all options
            choices.removeActiveItems();
        }
    })
})">
    <select x-ref="{{ $uniqId }}"
        wire:change="$set('{{ $model }}', [...$event.target.options].filter(option => option.selected).map(option => option.value))"
        {{ $attributes }} multiple>
        @foreach ($options as $option)
        <option @if (in_array($option[$valueKey], $keys)) selected @endif value="{{$option[$valueKey]}}">
            {{$option[$displayName]}}
        </option>
        @endforeach
    </select>
</div>
