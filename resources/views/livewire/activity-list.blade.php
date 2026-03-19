<?php /**
 * Livewire view for ActivityList
 * Provides filters, table listing and a modal for metadata.
 */ ?>

<div>
    <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">User ID</label>
                <input type="text" wire:model.debounce.500ms="user_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Filter by user id">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Action</label>
                <input type="text" wire:model.debounce.500ms="action" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="e.g. project.created">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">From</label>
                <input type="date" wire:model="date_from" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">To</label>
                <input type="date" wire:model="date_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-600">Per page</label>
                <select wire:model="perPage" class="rounded-md border-gray-300 text-sm">
                    <option>10</option>
                    <option>20</option>
                    <option>50</option>
                </select>
            </div>

            <div>
                <button wire:click="resetPage" type="button" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700">
                    Refresh
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">When</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metadata</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <div class="font-medium text-gray-900">{{ $activity->created_at->toDayDateTimeString() }}</div>
                                <div class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                @if($activity->user)
                                    <div class="font-medium">{{ $activity->user->name ?? 'User #' . $activity->user->id }}</div>
                                    <div class="text-xs text-gray-400">#{{ $activity->user->id }}</div>
                                @else
                                    <div class="text-sm text-gray-500">(guest)</div>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600">{{ $activity->action }}</td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                @if($activity->metadata)
                                    <div class="text-sm text-gray-600 truncate max-w-xs">{{ json_encode($activity->metadata) }}</div>
                                @else
                                    <div class="text-sm text-gray-400">—</div>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $activity->ip ?? '—' }}</td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="showMetadata({{ $activity->id }})" class="text-indigo-600 hover:text-indigo-900">View</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No activity found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $activities->links() }}
        </div>

        {{-- Modal: metadata viewer --}}
        <div x-data x-cloak x-show="$wire.showModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="fixed inset-0 bg-black opacity-50" @click="$wire.closeModal()"></div>

            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-2xl sm:w-full z-50">
                <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Activity Metadata</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" @click="$wire.closeModal()">✕</button>
                </div>

                <div class="p-4">
                    @if($modalMetadata)
                        <pre class="text-sm text-gray-800 bg-gray-100 p-3 rounded overflow-auto" style="max-height:40vh">{{ $modalMetadata }}</pre>
                    @else
                        <div class="text-sm text-gray-500">No metadata available for this activity.</div>
                    @endif
                </div>

                <div class="px-4 py-3 bg-gray-50 text-right">
                    <button type="button" wire:click="closeModal()" class="inline-flex justify-center rounded-md border border-transparent px-4 py-2 bg-indigo-600 text-white text-sm">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
