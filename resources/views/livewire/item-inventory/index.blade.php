@if($addInventory)
    @include('livewire.item-inventory.create')
@endif
@if($updateInventory)
    @include('livewire.item-inventory.update')
@endif
<div class="flex flex-wrap -mx-3">

    {{-- @if(session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{ session()->get('success') }}
    </div>
    @endif
    @if(session()->has('error'))
        <div class="alert alert-danger" role="alert">
            {{ session()->get('error') }}
        </div>
    @endif --}}

    <div class="flex-none w-full max-w-full px-3">
        @if (Session::has('status'))

        <div id="alert"
            class="relative p-4 pr-12 mb-4 text-white border border-solid rounded-lg bg-gradient-dark-gray border-slate-100">
            {{ Session::get('status') }}
            <button type="button" onclick="alertClose()"
                class="box-content absolute top-0 right-0 p-2 text-white bg-transparent border-0 rounded w-4-em h-4-em text-size-sm z-2">
                <span aria-hidden="true" class="text-center cursor-pointer">&#10005;</span>
            </button>
        </div>
        @endif

        <div
            class="flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <h6>All Items Inventory Details...</h6>
                <p>Here you can manage items inventory.</p>
            </div>

            <div class="my-auto ml-auto pr-6">                
                <button {{-- onclick="openModal(true)" --}} 
                        wire:click="addInventory()"                       
                        type="button" 
                        class="inline-block px-8 py-2 m-0 text-xs font-bold text-center text-white uppercase align-middle transition-all border-0 rounded-lg cursor-pointer ease-soft-in leading-pro tracking-tight-soft bg-gradient-fuchsia shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85">+&nbsp;
                        Add Item Inventory
                </button>
                
            </div>
            <div class="flex-auto px-0 pt-0 pb-2">
                <div class="p-0 overflow-x-auto">

                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom">
                            <tr>
                                <th
                                    class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    ID</th>
                                <th
                                    class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Name</th>    
                                
                                <th
                                    class="px-3 py-3 font-bold text-center uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Location</th>                            
                                <th
                                    class="px-3 py-3 font-bold text-center uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Qty.</th> 


                                <th
                                    class="px-3 py-3 font-bold text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Description</th>                                

                                <th
                                class="px-3 py-3 font-bold text-center uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                Total Qty.</th>                                    
                               
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items_inventory as $itemInventory)
                                @php
                                    $item = $itemInventory->item;
                                    $location = $itemInventory->location;
                                @endphp
                            <tr>
                                <td
                                    class="pl-6 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ $itemInventory->item_id }}
                                    </p>
                                </td>

                                <td
                                    class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ $item->name }}
                                    </p>
                                </td>

                                <td
                                    class="p-2 align-middle text-center bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ $location->name }}                                        
                                    </p>                                    
                                </td>
                               
                                <td
                                    class="p-2 align-middle text-center bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ $itemInventory->quantity }}                                        
                                    </p>                                    
                                </td>
                                
                                <td
                                    class="p-2 text-left bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ Str::limit($itemInventory->description, 50) }}
                                    </p>
                                </td>

                                <td x-data="{ reOrder: @entangle('reorder_limit') }"
                                    class="p-2 text-center bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    {{-- <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ $item->quantity }}
                                    </p> --}}
                                    <p :class="({{ $item->quantity }} <=reOrder) ? 'mt-2 bg-pink-100' : 'mt-2 bg-green-100'">
                                        {{ $item->quantity }}
                                    </p>
                                </td>                                

                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-base">
                                        {{-- <a href="#" wire:click='editItem({{$item->id}})'><i class="fas fa-edit" aria-hidden="true"></i></a> --}}
                                        <a href="#" onclick="javascript:del('{{$itemInventory->id}}')" ><i class="cursor-pointer fas fa-trash" aria-hidden="true" ></i></a>
                                        
                                    </p>
                                </td>
                            </tr> 
                            @empty
                                <tr colspan="4">
                                    <td>No item found</td>
                                </tr>
                            @endforelse                         
                           
                        </tbody>
                    </table>
                </div>
                <div class="p-1">
                    {{ $items_inventory->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script type="text/javascript">

    function del(itemId) {
        
        if (window.confirm("Do you really want to delete this record?")) {
            //Livewire.emit('delItem', itemId);
            alert('Deletion of inventory is unavailable right now.')
        }

    }
        window.addEventListener('close-modal', event => { 
            console.log('Please close the modal');           
        });       


    function alertClose() {
            document.getElementById("alert").style.display = "none";
    }
</script>
@endsection
