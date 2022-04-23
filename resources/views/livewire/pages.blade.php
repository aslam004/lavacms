<div class="p-6">
    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-button class="mr-5" wire:click="dispatchEvent">
            {{ __('Dispatch Event') }}
        </x-jet-button>
        <x-jet-button wire:click="createShowModal">
            {{ __('Create') }}
        </x-jet-button>
    </div>
    {{-- Data Table --}}
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-x-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <table class="w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase bg-gray-50">Title</th>
                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase bg-gray-50">Link</th>
                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase bg-gray-50">Content</th>
                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase bg-gray-50"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if ($data->count())
                                @foreach ($data as $item)
                                    <tr>
                                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                                            {{ $item->title }}
                                            {!! $item->is_default_home?'<span class="text-xs font-bold text-green-400">[Default Home Page]</span>':'' !!}
                                            {!! $item->is_default_not_found?'<span class="text-xs font-bold text-red-400">[Default 404 Page]</span>':'' !!}
                                        </td>
                                        <td class="px-6 py-4 text-sm whitespace-nowrap">
                                            <a 
                                            class="text-indigo-600 hover:text-indigo-900"
                                            target="_blank"
                                            href="{{ URL::to('/'.$item->slug) }}"
                                            >
                                            {{ $item->slug }}
                                            </a>
                                
                                        </td>
                                        <td class="px-6 py-4 text-sm whitespace-nowrap">{!! \Illuminate\Support\Str::limit($item->content,50,'...') !!}</td>
                                        <td class="px-6 py-4 text-sm text-right">
                                            <x-jet-button wire:click="updateShowModal({{ $item->id }})">
                                                {{ __('Edit') }}
                                            </x-jet-button>
                                            <x-jet-danger-button wire:click="deleteShowModal({{ $item->id }})">
                                                {{ __('Delete') }}
                                            </x-jet-danger-button>
                                        </td>
                                    </tr>
                                @endforeach    
                            @else
                                    <tr>
                                        <td class="px-6 py-4 text-sm whitespace-nowrap" colspan="4">No Result</td>
                                    </tr> 
                            @endif
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br/>
        {{ $data->links() }}
    {{-- MODAL FORM --}}
    <x-jet-dialog-modal wire:model="modalVisible">
        <x-slot name="title">
            {{ __('Save Page') }}
        </x-slot>

        <x-slot name="content">
            FORM ELEMENT HERE
            <div class="mt-4">
                <x-jet-label for="title" value="{{ __('title') }}" />
                <x-jet-input id="title" class="block w-full mt-1" type="text" wire:model.debounce.800ms="title" />
                @error('title') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="slug" value="{{ __('slug') }}" />
                <div class="flex mt-1 rounded-md shadow-sm">
                    <span class="inline-flex items-center px-3 text-sm text-gray-500 border border-r-0 rounded-1-md border-gray-50 bg-gray-50">lavacms.test/</span>
                    <input wire:model="slug" class="flex-1 block w-full transition duration-150 ease-in-out rounded-none form-input rounded-r-md sm:text-sm sm:leading-5" placeholder="url-slug">
                </div>
                @error('slug') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4">
                <label>
                    <input class="form-checkbox" type="checkbox" value="{{ $isSetToDefaultHomePage }}" wire:model="isSetToDefaultHomePage">
                    <span class="ml-2 text-sm text-gray-600">Set as the Default Home Page</span>
                </label>
            </div>
            <div class="mt-4">
                <label>
                    <input class="form-checkbox" type="checkbox" value="{{ $isSetToDefaultNotFoundPage }}" wire:model="isSetToDefaultNotFoundPage">
                    <span class="ml-2 text-sm text-red-600">Set as the Default 404 error Page</span>
                </label>
            </div>
            <div class="mt-4">
                <x-jet-label for="content" value="{{ __('content') }}" />
                <div class="rounded-md shadow-sm">
                    <div class="mt-1 bg-white">
                        <div class="body-content" wire:ignore>
                            <trix-editor
                            class="trix-content"
                            x-ref="trix"
                            wire:model.debounce.10ms="content"
                            wire:key="trix-content-unique-key"
                            ></trix-editor>
                        </div>
                    </div>
                </div>
                @error('content') <span class="error">{{ $message }}</span> @enderror
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalVisible')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            @if ($modelId)
                <x-jet-button class="ml-3" wire:click="update" wire:loading.attr="disabled">
                    {{ __('Update')}}
                </x-jet-button>
            @else
                <x-jet-button class="ml-3" wire:click="create" wire:loading.attr="disabled">
                    {{ __('Create') }}
                </x-jet-button>
            @endif

        </x-slot>
    </x-jet-dialog-modal>
    {{-- Delete Modal --}}
    <x-jet-dialog-modal wire:model="modalConfirmDelete">
        <x-slot name="title">
            {{ __('Delete Page') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you want to delete this Page? Once your account is deleted, all of its resources and data will be permanently deleted.') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalConfirmDelete')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-3" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Delete Page') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
