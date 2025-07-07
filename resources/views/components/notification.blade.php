<div x-data="{ show: true }" x-show="show" x-transition class="fixed top-6 right-6 z-50 max-w-xs w-full">
    @if(session('success'))
    <div class="bg-green-500 text-white px-6 py-4 rounded shadow flex items-center justify-between mb-2">
        <span>{{ session('success') }}</span>
        <button @click="show = false" class="ml-4 text-2xl leading-none font-bold">&times;</button>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-500 text-white px-6 py-4 rounded shadow flex items-center justify-between mb-2">
        <span>{{ session('error') }}</span>
        <button @click="show = false" class="ml-4 text-2xl leading-none font-bold">&times;</button>
    </div>
    @endif
    @if(session('status'))
    <div class="bg-blue-500 text-white px-6 py-4 rounded shadow flex items-center justify-between mb-2">
        <span>{{ session('status') }}</span>
        <button @click="show = false" class="ml-4 text-2xl leading-none font-bold">&times;</button>
    </div>
    @endif
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('show', () => ({
                show: true,
                init() {
                    setTimeout(() => this.show = false, 3000);
                }
            }))
        })
    </script>
</div>