{{-- @include('livewire.category.category-modal-form') --}}

{{-- <div wire:ignore.self id="popup-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white" data-modal-hide="popup-modal">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Close modal</span>
            </button>
            <form wire:submit.prevent="destroyCategory">
            <div class="p-6 text-center">
                <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to delete this product?</h3>
                <button data-modal-hide="popup-modal" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                    Yes, I'm sure
                </button>
                <button data-modal-hide="popup-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">No, cancel</button>
            </div>
            </form>
        </div>
    </div>
</div> --}}

@if($addItem)
    @include('livewire.item.create')
@endif
@if($updateItem)
    @include('livewire.item.update')
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
                <h6>All Items...</h6>
                <p>Here you can manage items.</p>
            </div>
            <div id="reader" width="600px" class="ml-2"></div>

            <div class="my-auto ml-auto pr-6">
                {{-- <a href="#"> --}}
                <button {{-- onclick="openModal(true)" --}} 
                        wire:click="addItem()"                       
                        type="button" 
                        class="inline-block px-8 py-2 m-0 text-xs font-bold text-center text-white uppercase align-middle transition-all border-0 rounded-lg cursor-pointer ease-soft-in leading-pro tracking-tight-soft bg-gradient-fuchsia shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85">+&nbsp;
                        Add Item
                </button>
                {{-- </a> --}}
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
                                    class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Item Code</th>
                                <th
                                    class="px-3 py-3 font-bold text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Description</th> 

                                <th
                                    class="px-3 py-3 font-bold text-center uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Cost</th>
                                <th
                                class="px-3 py-3 font-bold text-center uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                Price</th>

                                <th
                                class="px-3 py-3 font-bold text-center uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                Qty.</th>
                                    
                                <th
                                class="text-center px-3 py-3 font-bold text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                Status</th> 
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                            <tr>
                                <td
                                    class="pl-6 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ $item->id }}
                                    </p>
                                </td>

                                <td
                                    class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ $item->name }}
                                    </p>
                                </td>

                                <td
                                    class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{-- {!! DNS2D::getBarcodeHTML(($item->item_code)?$item->item_code:'1020304050', 'QRCODE'); !!} --}}
                                        {{-- {!! DNS2D::getBarcodeHTML(($item->item_code)?$item->item_code:'1020304050', 'DATAMATRIX'); !!} --}}
                                        {{-- {!! DNS1D::getBarcodeHTML(($item->item_code)?$item->item_code:'1020304050', 'PHARMA'); !!} --}}
                                        {{-- p - {{ ($item->item_code)?$item->item_code:'1020304050' }} --}}
                                        {!! '<img src="data:image/png;base64,' . DNS2D::getBarcodePNG(($item->item_code)?$item->item_code:'1020304050', 'QRCODE') . '" alt="barcode"   />' !!}
                                        {{-- {!! DNS1D::getBarcodeSVG(($item->item_code)?$item->item_code:'1020304050', 'C39'); !!} --}}
                                        {{-- {!! DNS2D::getBarcodePNGPath(($item->item_code)?$item->item_code:'1020304050', 'PDF417') !!} --}}
                                        {{-- {!! DNS1D::getBarcodeSVG(($item->item_code)?$item->item_code:'1020304050', 'PHARMA2T', 3,33,'green') !!} --}}
                                        {{-- {!! DNS1D::getBarcodeHTML(($item->item_code)?$item->item_code:'1020304050', 'PHARMA2T', 3,33,'green') !!} --}}
                                        {{-- {!! DNS1D::getBarcodePNGPath(($item->item_code)?$item->item_code:'1020304050', 'PHARMA2T', 3,33, [101,155,191]) !!} --}}
                                        {{-- {!! '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG(($item->item_code)?$item->item_code:'1020304050', 'PHARMA2T',3,33, [101,155,191]) . '" alt="barcode"   />' !!} --}}
                                        {{-- p - {{ ($item->item_code)?$item->item_code:'1020304050' }} --}}
                                        {{-- {!! DNS1D::getBarcodeHTML(($item->item_code)?$item->item_code:'1020304050', 'EAN13') !!}                                        --}}
                                        {{-- p - {{ ($item->item_code)?$item->item_code:'1020304050' }} --}}
                                        {{-- {!! DNS1D::getBarcodeHTML(($item->item_code)?$item->item_code:'1020304050', 'I25') !!}                                        --}}
                                        {{-- p - {{ ($item->item_code)?$item->item_code:'1020304050' }} --}}
                                    </p>
                                </td>
                               
                                <td
                                    class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ Str::limit($item->description, 50) }}
                                    </p>
                                </td>
                                
                                <td
                                    class="p-2 text-center bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ $item->original_price }}
                                    </p>
                                </td>

                                <td
                                    class="p-2 text-center bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ $item->selling_price }}
                                    </p>
                                </td>

                                <td
                                    class="p-2 text-center bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ $item->quantity }}
                                    </p>
                                </td>
                                
                                <td
                                    class="p-2 text-center bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ ($item->status)?'Active':'DeActive'; }}
                                    </p>
                                </td>

                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-base">
                                        <a href="#" wire:click='editItem({{$item->id}})'><i class="fas fa-edit" aria-hidden="true"></i></a>
                                        <a href="#" onclick="javascript:del('{{$item->id}}')" ><i class="cursor-pointer fas fa-trash" aria-hidden="true" ></i></a>
                                        
                                        {{-- <button type="button" wire:click="deleteCategory({{$category->id}})" data-modal-target="popup-modal" data-modal-toggle="popup-modal">
                                            <i class="cursor-pointer fas fa-trash" aria-hidden="true"></i>
                                        </button> --}}
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
                    {{ $items->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script type="text/javascript">

    function del(itemId) {
        
        if (window.confirm("Do you really want to delete this record?")) {
            Livewire.emit('delItem', itemId);
        }

    }
        window.addEventListener('close-modal', event => { 
            console.log('Please close the modal');
            //$('#defaultModal').hide();
            //$('#defaultModal').modal('hide');
            //$('#updateBrandModal').modal('hide');
            //$('#deleteBrandModal').modal('hide');
        });
       


    function alertClose() {
            document.getElementById("alert").style.display = "none";
    }

    // livewire:load hook is called when the Livewire component is initially loaded or re-rendered
    document.addEventListener('livewire:load', function () {
        // Call your JavaScript function here
        startScanner();
        
    });

    // livewire:update hook is called after each Livewire update/render cycle
    document.addEventListener('livewire:update', function (event) {
        // Call your JavaScript function here
        //console.log(event)
        startScanner();        
        //console.log('updating livewire...');
    });

    // qr-bar code scanner /////////////////////////////////////////////////
    //console.log(Html5QrcodeScanner);

    
    function onScanSuccess(decodedText, decodedResult) 
    {
    // handle the scanned code as you like, for example:
        console.log(`Code matched = ${decodedText}`, decodedResult);
        // alert(`Code matched = ${decodedText}`);
        //alert(decodedText);
        Livewire.emit('loadForEdit', decodedText);

    }

    function onScanFailure(error) 
    {
    // handle scan failure, usually better to ignore and keep scanning.
    // for example:
    console.warn(`Code scan error = ${error}`);
    }

    // window.addEventListener('startScanner', (event) => {
    //         console.log(event.detail.hideScanner);
    // });

    

    function startScanner() 
    {
        var divElement = document.getElementById('reader');
        if (divElement !== null) {
            let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 250, height: 250} },
            /* verbose= */ false);
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }
    }


    
</script>
@endsection
