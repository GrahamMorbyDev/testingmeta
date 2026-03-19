<div class="max-w-4xl mx-auto p-6 bg-white shadow-sm rounded-md">
    <h2 class="text-2xl font-semibold mb-4">One-Click README Generator</h2>

    <form wire:submit.prevent="generate" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Project Name (optional)</label>
            <input type="text" wire:model.defer="project_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. SuperApp">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Short Description</label>
            <textarea wire:model.defer="description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Describe what your project does, who it's for, and notable tech or goals."></textarea>
            @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500" wire:loading.attr="disabled" wire:target="generate">
                <span wire:loading.remove wire:target="generate">Generate README</span>
                <span wire:loading wire:target="generate">Generating...</span>
            </button>

            <button type="button" class="inline-flex items-center px-3 py-2 border rounded-md text-sm bg-white hover:bg-gray-50" onclick="document.querySelector('[wire\\:model=\"description\"]').value = '' ; Livewire.hook('message.processed',()=>{})">
                Clear
            </button>

            @if($status === 'pending')
                <p class="text-sm text-gray-500">Working on it — this may take a few seconds.</p>
            @elseif($status === 'failed')
                <p class="text-sm text-red-600">{{ $error ?? 'Failed to generate README' }}</p>
            @endif
        </div>
    </form>

    <div class="mt-6" x-data="{
            copied: false,
            copy(text) {
                navigator.clipboard.writeText(text).then(()=>{ this.copied = true; setTimeout(()=> this.copied = false, 2000); });
            },
            download(filename, text) {
                const blob = new Blob([text], { type: 'text/markdown' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(url);
            }
        }">

        @if($generated_readme)
            <div class="flex justify-between items-start">
                <h3 class="text-lg font-medium">Generated README</h3>
                <div class="flex items-center gap-2">
                    <button type="button" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white rounded-md text-sm" @click="copy(@js($generated_readme))">
                        <span x-show="!copied">Copy</span>
                        <span x-show="copied" x-cloak>Copied</span>
                    </button>

                    <button type="button" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-md text-sm" @click="download('README.md', @js($generated_readme))">
                        Download
                    </button>
                </div>
            </div>

            <div class="mt-3 border rounded-md overflow-hidden">
                <pre class="whitespace-pre-wrap p-4 text-sm bg-gray-50 text-gray-900"><code>{{ $generated_readme }}</code></pre>
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium text-gray-700">Edit before copy</label>
                <textarea rows="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" x-ref="editor" x-init="$el.value = @js($generated_readme)" x-on:input="$dispatch('input', $el.value)" x-bind:value="@js($generated_readme)"></textarea>
                <div class="mt-2 flex gap-2">
                    <button type="button" class="px-3 py-1.5 bg-indigo-600 text-white rounded-md text-sm" @click="copy($refs.editor.value)">Copy Edited</button>
                    <button type="button" class="px-3 py-1.5 bg-blue-600 text-white rounded-md text-sm" @click="download('README.md', $refs.editor.value)">Download Edited</button>
                </div>
            </div>
        @else
            <div class="mt-6 p-4 rounded-md bg-gray-50 text-sm text-gray-600">
                Enter a short project description and click "Generate README" to create a polished README tailored to your description.
            </div>
        @endif
    </div>
</div>
