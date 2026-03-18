@php
    $isEdit = isset($agent);
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.agents.update', $agent) : route('admin.agents.store') }}" class="space-y-6 bg-white p-6 rounded shadow">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div>
        <label class="block text-sm font-medium text-gray-700">Name</label>
        <input name="name" value="{{ old('name', $agent->name ?? '') }}" class="mt-1 block w-full border rounded px-3 py-2" />
        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input name="email" value="{{ old('email', $agent->email ?? '') }}" class="mt-1 block w-full border rounded px-3 py-2" />
        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Phone Number</label>
            <input name="phone_number" value="{{ old('phone_number', $agent->phone_number ?? '') }}" class="mt-1 block w-full border rounded px-3 py-2" />
            @error('phone_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Company</label>
            <input name="company" value="{{ old('company', $agent->company ?? '') }}" class="mt-1 block w-full border rounded px-3 py-2" />
            @error('company') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status" class="mt-1 block w-48 border rounded px-3 py-2">
            @foreach($statuses as $status)
                <option value="{{ $status }}" {{ old('status', $agent->status ?? '') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-center space-x-3">
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">{{ $isEdit ? 'Update Agent' : 'Create Agent' }}</button>
        <a href="{{ route('admin.agents.index') }}" class="text-gray-600 hover:underline">Cancel</a>
    </div>
</form>
