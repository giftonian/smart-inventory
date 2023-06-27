<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    @if(Session::has('error'))
            <div id="alert"
            class="relative p-4 pr-12 mb-4 text-white text-bold border border-solid 
            rounded-lg bg-gradient-red border-slate-100">
            {{ Session::get('error') }}
            <button type="button" onclick="alertClose()"
                class="box-content absolute top-0 right-0 p-2 text-white 
                bg-transparent border-0 rounded w-4-em h-4-em text-size-sm z-2">
                <span aria-hidden="true" class="text-center cursor-pointer">&#10005;</span>
            </button>
            </div>
            @endif
            
    @if($imported)
        <div class="p-6 rounded border my-4 border-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="text-xl text-green-400 inline">{{__('Contacts imported')}}</span>
        </div>
    @else
        <div class="p-6 rounded border my-4 border-gray-100">
            <form class="form-horizontal" wire:submit.prevent="excelImport" method="POST" action=""
                  enctype="multipart/form-data" >
                <div class="flex gap-5">
                    <div class="control-label">{{__('Excel file to import')}}</div>
                    <div class="">
                        <input id="csv_file" wire:model="csv_file" type="file"
                               class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                               name="csv_file"
                               required>
                        @error('csv_file') <span class="text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="text-monospace p-2 border border-gray-100">Colums accepted:
                    <code class="text-xs text-red-400">
                        name,
                        small_description,
                        description,
                        original_price,
                        selling_price,
                        status                       
                    </code>
                </div>
                {{-- <div class="mt-4">
                    <label>
                        <input type="checkbox" wire:model="fileHasHeader" name="header" checked>
                        {{__('File contains header row?')}}
                    </label>
                </div> --}}


                <div class="mt-4">
                    <button type="submit"
                    wire:loading.attr="disabled"
                            class="bg-blue-400 px-4 py-2 rounded-md text-white font-semibold tracking-wide cursor-pointer">
                            <span wire:loading.remove>Submit</span>
                            <span wire:loading>
                                <i class="fa fa-spinner fa-spin"></i> Uploading...
                            </span>
                    </button>
                </div>
            </form>
        </div>
        <div class="p-6 rounded border my-4 border-gray-100 overflow-x-auto max-w-7xl">
            <form method="post" wire:submit.prevent="processImport">

                <div class="flex flex-col">
                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full ">
                            <div class="overflow-hidden shadow-md sm:rounded-lg">
                                <table class="min-w-full table-auto">

                                    @foreach ($csv_data as $i=> $row)
                                        <tr>
                                            @foreach ($row as $key => $value)
                                                <td class="py-3 border-r px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400 @if($i==0) font-bold text-yellow-400 @endif">{{ $value }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    <tr>
                                        @foreach ($csv_data[0]??[] as $key => $value)
                                            <td  class=" text-xs tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                                <select wire:model="match_fields.{{$key}}"
                                                        class="text-sm w-full border border-gray-400 rounded-sm"
                                                        name="fields[{{ $key }}]">
                                                    <option value="">--select--</option>
                                                    @foreach ($db_fields as $i=>$db_field)
                                                        <option
                                                                value="{{$db_field}}">{{ str_replace('_',' ',ucfirst($db_field)) }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        @endforeach
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!empty($csv_data))
                    <button type="submit"
                            class="mt-4 bg-yellow-400 px-4 py-2 rounded-md text-white font-semibold tracking-wide cursor-pointer">
                        {{__('Import Contacts')}}
                    </button>
                @endif
            </form>

            @if(!empty($faile))
                <div class="border-t border-gray-400 mt-4">
                    <div class="font-bold text-red-400 text-lg">Failed records</div>
                    @foreach($failed as $fail)
                        <div class="text-monospace text-sm py-2 border-b border-gray-400">{{$fail}}</div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>