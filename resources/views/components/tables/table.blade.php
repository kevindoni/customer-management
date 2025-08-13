<div class="shadow border-b border-zinc-200 border-b-zinc-300/80 dark:border-white/10 sm:rounded-lg relative overflow-x-auto">
    <!-- <div class="align-middle min-w-full shadow sm:rounded-lg ">-->
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200 dark:divide-none'])->only('class') }}>
        @if (!empty($header))
            {{ $header }}
        @endif
        <tbody class="bg-white dark:bg-white/10 divide-y divide-gray-200 dark:divide-gray-600">
            {{ $body ?? '' }}
        </tbody>
        @if (!empty($footer))
            <tfoot class="table-foot">
                {{ $footer }}
            </tfoot>
        @endif
    </table>
</div>
