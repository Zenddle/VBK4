@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition role="status"
         class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-400/25 bg-emerald-400/10 px-4 py-3 text-emerald-200">
        <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <p class="flex-1 text-sm font-medium">{{ session('success') }}</p>
        <button @click="show = false" class="text-xl leading-none text-emerald-300/70 transition hover:text-emerald-200" aria-label="Tutup">&times;</button>
    </div>
@endif

@if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-transition role="alert"
         class="mb-6 flex items-start gap-3 rounded-lg border border-rose-400/25 bg-rose-400/10 px-4 py-3 text-rose-200">
        <svg class="mt-0.5 h-5 w-5 shrink-0 text-rose-300" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <p class="flex-1 text-sm font-medium">{{ session('error') }}</p>
        <button @click="show = false" class="text-xl leading-none text-rose-300/70 transition hover:text-rose-200" aria-label="Tutup">&times;</button>
    </div>
@endif

@if ($errors->any())
    <div role="alert" class="mb-6 rounded-lg border border-rose-400/25 bg-rose-400/10 px-4 py-3 text-rose-200">
        <ul class="list-inside list-disc space-y-1 text-sm">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif
