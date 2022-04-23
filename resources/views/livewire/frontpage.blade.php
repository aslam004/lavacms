<div class="divide-y divide-sky-800" x-data="{show:false}">
    <nav class="flex items-center px-3 py-2 shadow-lg bg-sky-900">
        <div>
            <button @click="show =! show" 
            class="items-center block h-8 mr-3 text-gray-200 hover:text-gray-50 focus:text-gray-50 focus:outline-none sm:hidden">
                <svg class="w-8 fill-current" viewBox="0 0 24 24">                            
                    <path x-show="!show" fill-rule="evenodd" d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"/>
                    <path x-show="show" fill-rule="evenodd" d="M18.278 16.864a1 1 0 0 1-1.414 1.414l-4.829-4.828-4.828 4.828a1 1 0 0 1-1.414-1.414l4.828-4.829-4.828-4.828a1 1 0 0 1 1.414-1.414l4.829 4.828 4.828-4.828a1 1 0 1 1 1.414 1.414l-4.828 4.829 4.828 4.828z"/>
                </svg>
            </button>
        </div>
        <div class="flex items-center w-full h-12">
            <a href="{{ url('/') }}" class="w-full">
            <img class="h-8" src="https://source.unsplash.com/600x100?white"/>
            </a>
        </div>
        <div class="flex justify-end sm:w-8/12">
            {{-- Top Nav --}}
            <ul class="hidden text-xs text-gray-200 sm:block">
                <a href="{{ url('/login') }}"><li class="px-4 py-2 cursor-pointer hover:underline">Login</li></a>
            </ul>
        </div>
    </nav>
    <div class="sm:flex sm:min-h-screen">
        <aside class="text-gray-700 divide-y bg-sky-900 sm:w-4/12 divide-sky-700 divide-dashed md:w-3/12">
            {{-- desktop web view --}}
            <ul class="hidden text-xs text-gray-200 sm:text-left sm:block">
                @foreach ($sidebarlinks as $item)
                    <a href="{{ url('/'.$item->slug) }}"><li class="px-4 py-2 cursor-pointer hover:underline">{{ $item->label }}</li></a>
                @endforeach
            </ul>

            {{-- mobile web view --}}
            <div :class="show ? 'block' : 'hidden' " class="block pb-3 divide-y divide-Sky-800 sm:hidden">
                <ul class="text-xs text-gray-200">
                    @foreach ($topbarlink as $item)
                        <a href="{{ url('/'.$item->slug) }}"><li class="px-4 py-2 cursor-pointer hover:underline">{{ $item->label }}</li></a>
                    @endforeach
                </ul>
                {{-- Top Nav in Mobile --}}
                <ul class="text-xs text-gray-200">
                    <a href="{{ url('/home') }}"><li class="px-4 py-2 cursor-pointer hover:underline">Login</li></a>
                </ul>
            </div>
        </aside>
        <main class="min-h-screen bg-sky-100 p-14 sm:w-8/12 md:w-10/12">
            <section >
                <div x-text="show == true?'True':'false'"></div>
                <h1>{{ $title }}</h1>
                <article>
                    {!! $content !!}
                </article>
            </section>
        </main>
    </div> 
</div>

