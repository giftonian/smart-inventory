
@if($addImport)
    @include('livewire.import-items.import-create')
@endif
@if($updateImport)
    @include('livewire.import-items.import-update')
@endif
<div class="flex flex-wrap -mx-3">

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
                <h6>All imports...</h6>
                <p>Here you can manage imports.</p>
            </div>

            <div class="my-auto ml-auto pr-6">
                {{-- <a href="#"> --}}
                <button {{-- onclick="openModal(true)" --}} 
                        wire:click="addImport()"                       
                        type="button" 
                        class="inline-block px-8 py-2 m-0 text-xs font-bold text-center text-white uppercase align-middle transition-all border-0 rounded-lg cursor-pointer ease-soft-in leading-pro tracking-tight-soft bg-gradient-fuchsia shadow-soft-md bg-150 bg-x-25 hover:scale-102 active:opacity-85">+&nbsp;
                        Import Items
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
                                    class="px-3 py-3 font-bold text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Description</th> 
                                    
                                <th
                                class="text-center px-3 py-3 font-bold text-left uppercase bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                Status</th> 
                                <th
                                    class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-gray-200 shadow-none text-size-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($imports as $category)
                            <tr>
                                <td
                                    class="pl-6 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ $category->id }}
                                    </p>
                                </td>

                                <td
                                    class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ $category->name }}
                                    </p>
                                </td>
                               
                                <td
                                    class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ Str::limit($category->description, 50) }}
                                    </p>
                                </td>  
                                
                                <td
                                    class="p-2 text-center bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-size-xs">
                                        {{ ($category->status)?'Active':'DeActive'; }}
                                    </p>
                                </td>

                                <td
                                    class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                    <p class="mb-0 font-semibold leading-tight text-base">
                                        <a href="#" wire:click='editCategory({{$category->id}})'><i class="fas fa-edit" aria-hidden="true"></i></a>
                                        <a href="#" onclick="javascript:del('{{$category->id}}')" ><i class="cursor-pointer fas fa-trash" aria-hidden="true" ></i></a>
                                        
                                        {{-- <button type="button" wire:click="deleteCategory({{$category->id}})" data-modal-target="popup-modal" data-modal-toggle="popup-modal">
                                            <i class="cursor-pointer fas fa-trash" aria-hidden="true"></i>
                                        </button> --}}
                                    </p>
                                </td>
                            </tr> 
                            @empty
                                <tr colspan="4">
                                    <td>No import found</td>
                                </tr>
                            @endforelse                         
                           
                        </tbody>
                    </table>
                </div>
                <div class="p-1">
                    {{-- {{ $imports->links() }} --}}
                </div>
            </div>

           
        </div>
    </div>
</div>
@section('scripts')
<script type="text/javascript">

    function del(catId) {
        
        if (window.confirm("Do you really want to delete this record?")) {
            Livewire.emit('delCat', catId);
        }

    }
        window.addEventListener('close-modal', event => { 
            console.log('Please close the modal');
            //$('#defaultModal').hide();
            //$('#defaultModal').modal('hide');
            //$('#updateBrandModal').modal('hide');
            //$('#deleteBrandModal').modal('hide');
        });

       
    //     document.addEventListener('alpine:init', () => {
    //     Alpine.data('toggle-modal', () => ({
    //         showModal: false,
    //         saveCategory() {
    //             this.$wire.saveCategory();
    //         },
    //     }));
    // });

    function alertClose() {
            document.getElementById("alert").style.display = "none";
    }
</script>
@endsection
