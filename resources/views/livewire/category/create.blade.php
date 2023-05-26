<div>
    <div class="flex flex-wrap mt-6 -mx-3">
        <div class="w-full px-3 mb-6 e">
            <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border">
                <div class="flex-auto p-4">

                    <h5 class="font-bold py-3">Category Information</h5>

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

                    <form wire:submit.prevent="storeCategory">
                        <div class="flex flex-wrap -mx-3">
                            <div class="max-w-full px-3 w-1/2 lg:flex-none">
                                <div class="flex flex-col h-full">
                                    <h6 class="font-bold leading-tight uppercase text-size-xs text-slate-500">Category Name
                                    </h6>
                                    <div class="mb-4">
                                        <input wire:model.defer="name" type="text"
                                            class="text-size-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:bg-white focus:text-gray-700 focus:outline-none focus:transition-shadow"
                                            placeholder="Name" id="user-name" required />
                                        @error('name') <p class="text-size-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div>


                                    {{-- <h6 class="font-bold leading-tight uppercase text-size-xs text-slate-500">Email</h6>

                                    <div class="mb-4">
                                        <input wire:model.defer="user.email" type="email"
                                            class="text-size-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:bg-white focus:text-gray-700 focus:outline-none focus:transition-shadow"
                                            placeholder="Email" id="user-email" required />
                                        @error('user.email') <p class="text-size-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                    </div> --}}

                                </div>
                            </div>
                            <div class="max-w-full px-3 w-1/2 lg:flex-none">
                                <div class="flex flex-col h-full">

                                    <h6 class="font-bold leading-tight uppercase text-size-xs text-slate-500">Status
                                    </h6>

                                    <div class="mb-4">
                                        <input id="terms"
                                        class="ml-2 py-2 px-2 w-4.92-em h-4.92-em ease-soft -ml-6.92-em rounded-1.4 checked:bg-gradient-dark-gray after:text-size-fa-check after:font-awesome after:duration-250 after:ease-soft-in-out duration-250 relative float-left mt-1 cursor-pointer appearance-none border border-solid border-slate-200 bg-white bg-contain bg-center bg-no-repeat align-top transition-all after:absolute after:flex after:h-full after:w-full after:items-center after:justify-center after:text-white after:opacity-0 after:transition-all after:content-['\f00c'] checked:border-0 checked:border-transparent checked:bg-transparent checked:after:opacity-100"
                                        type="checkbox" wire:model.defer="status" />
                                        @error('status') <p class="text-size-sm text-red-500">{{ $message }}</p>
                                        @enderror

                                    </div>

                                    {{-- <h6 class="font-bold leading-tight uppercase text-size-xs text-slate-500">Location
                                    </h6>

                                    <div class="mb-4">
                                        <input wire:model.defer="user.location" type="text"
                                            class="text-size-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:bg-white focus:text-gray-700 focus:outline-none focus:transition-shadow"
                                            placeholder="Location" id="user-location" required />
                                        @error('user.location') <p class="text-size-sm text-red-500">{{ $message }}</p>
                                        @enderror

                                    </div> --}}

                                </div>
                            </div>

                        </div>

                        <h6 class="font-bold leading-tight uppercase text-size-xs text-slate-500">Description</h6>

                        <div class="mb-4">
                            <textarea wire:model.defer="description" rows="4"
                                class="text-size-sm focus:shadow-soft-primary-outline leading-5.6 ease-soft block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 px-3 font-normal text-gray-700 transition-all focus:border-fuchsia-300 focus:bg-white focus:text-gray-700 focus:outline-none focus:transition-shadow"
                                placeholder="Say something about yourself" id="user-about">  </textarea>
                            @error('description') <p class="text-size-sm text-red-500">{{ $message }}</p> @enderror


                        </div>
                        <div class="flow-root">
                            <button type="button" wire:click='cancelCategory' 
                                class="ml-1 float-right inline-block px-6 py-3 mt-6 mb-2 font-bold text-center text-white uppercase align-middle transition-all bg-transparent border-0 rounded-lg cursor-pointer active:opacity-85 hover:scale-102 hover:shadow-soft-xs leading-pro text-size-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 bg-gradient-dark-gray hover:border-slate-700 hover:bg-slate-700 hover:text-white">
                                Cancel</button>

                            <button type="submit"
                                class="float-right inline-block px-6 py-3 mt-6 mb-2 font-bold text-center text-white uppercase align-middle transition-all bg-transparent border-0 rounded-lg cursor-pointer active:opacity-85 hover:scale-102 hover:shadow-soft-xs leading-pro text-size-xs ease-soft-in tracking-tight-soft shadow-soft-md bg-150 bg-x-25 bg-gradient-dark-gray hover:border-slate-700 hover:bg-slate-700 hover:text-white">
                                Save changes</button>

                        </div>

                </div>

                </form>

            </div>
        </div>
    </div>

    <script>
        // function alertClose() {
        //     document.getElementById("alert").style.display = "none";
        // }
    </script>

</div>