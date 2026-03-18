<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 space-y-3 sm:space-y-0">
        <div class="flex items-center space-x-2">
            <input type="text" wire:model.debounce.300ms="search" placeholder="Search agents..." class="border rounded px-3 py-2 w-64 focus:outline-none focus:ring" />
            <select wire:model="perPage" class="border rounded px-3 py-2">
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
        <div class="text-sm text-gray-600">Showing {{ $agents->firstItem() ?: 0 }} - {{ $agents->lastItem() ?: 0 }} of {{ $agents->total() }}</div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Email</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Company</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Phone</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($agents as $agent)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $agent->name }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $agent->email }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $agent->company ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">{{ $agent->phone_number ?? '-' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            @if($agent->status === 'active')
                                <span class="inline-flex px-2 py-1 rounded text-xs bg-green-100 text-green-800">Active</span>
                            @elseif($agent->status === 'inactive')
                                <span class="inline-flex px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">Inactive</span>
                            @else
                                <span class="inline-flex px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">Pending</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('admin.agents.show', $agent) }}" class="text-indigo-600 hover:underline">View</a>
                            <a href="{{ route('admin.agents.edit', $agent) }}" class="text-indigo-600 hover:underline">Edit</a>

                            @if($confirmingDeleteId === $agent->id)
                                <button wire:click="deleteAgent({{ $agent->id }})" class="text-red-600 hover:underline">Confirm Delete</button>
                                <button wire:click="cancelDelete" class="text-gray-600 hover:underline">Cancel</button>
                            @else
                                <button wire:click="confirmDelete({{ $agent->id }})" class="text-red-600 hover:underline">Delete</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-center" colspan="6">No agents found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $agents->links() }}
    </div>
</div>
