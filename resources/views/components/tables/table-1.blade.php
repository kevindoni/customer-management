   <div class="align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg">
       <!-- <div class="align-middle min-w-full shadow sm:rounded-lg ">-->
       <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200'])->only('class') }}>
           <thead class="bg-gray-100 dark:bg-gray-900 ">
               @if (!empty($header))
                   {{ $header }}
               @endif
           </thead>
           <tbody class="bg-white dark:bg-gray-400 divide-y divide-gray-200">
               {{ $body ?? '' }}
           </tbody>
           @if (!empty($footer))
               <tfoot class="table-foot bg-gray-100 dark:bg-gray-900">
                   {{ $footer }}
               </tfoot>
           @endif
       </table>
   </div>
