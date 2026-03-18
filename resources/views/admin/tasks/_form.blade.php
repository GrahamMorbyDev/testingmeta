<div class="grid grid-cols-1 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Agent</label>
        <select name="agent_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
            <option value="">Select an agent</option>
            @foreach($agents as $id => $name)
                <option value="{{ $id }}" {{ (old('agent_id', optional($task)->agent_id) == $id) ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
        @error('agent_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Title</label>
        <input type="text" name="title" value="{{ old('title', optional($task)->title) }}" class="mt-1 block w-full border-gray-300 rounded-md" required>
        @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md">{{ old('description', optional($task)->description) }}</textarea>
        @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Due Date</label>
            <input type="date" name="due_date" value="{{ old('due_date', optional(optional($task)->due_date)->format('Y-m-d') ?? '') }}" class="mt-1 block w-full border-gray-300 rounded-md">
            @error('due_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Priority</label>
            <select name="priority" class="mt-1 block w-full border-gray-300 rounded-md" required>
                @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $value => $label)
                    <option value="{{ $value }}" {{ (old('priority', optional($task)->priority ?? 'medium') == $value) ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('priority')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 block w-full border-gray-300 rounded-md" required>
                @foreach(['open' => 'Open', 'in_progress' => 'In Progress', 'completed' => 'Completed'] as $value => $label)
                    <option value="{{ $value }}" {{ (old('status', optional($task)->status ?? 'open') == $value) ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
