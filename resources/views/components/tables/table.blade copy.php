<div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
    <!-- <div class="align-middle min-w-full shadow sm:rounded-lg ">-->
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200'])->only('class') }}>
        @if (!empty($header))
            {{ $header }}
        @endif
        <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
            {{ $body ?? '' }}
        </tbody>
        @if (!empty($footer))
            <tfoot class="table-foot">
                {{ $footer }}
            </tfoot>
        @endif
    </table>
</div>
