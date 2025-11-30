<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tasks / Završni i diplomski radovi') }}
            </h2>
            @if(Auth::user()->isNastavnik())
                <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create New Task
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($tasks->isEmpty())
                        <p class="text-gray-500 text-center py-8">No tasks available yet.</p>
                    @else
                        <div class="grid gap-6">
                            @foreach ($tasks as $task)
                                <div class="border rounded-lg p-6 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-xl font-semibold text-gray-900">{{ $task->title }}</h3>
                                            <p class="text-sm text-gray-600 italic">{{ $task->title_en }}</p>
                                        </div>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                                            @if($task->study_type === 'strucni') bg-green-100 text-green-800
                                            @elseif($task->study_type === 'preddiplomski') bg-blue-100 text-blue-800
                                            @else bg-purple-100 text-purple-800
                                            @endif">
                                            {{ ucfirst($task->study_type) }}
                                        </span>
                                    </div>

                                    <p class="text-gray-700 mb-4">{{ Str::limit($task->description, 200) }}</p>

                                    <div class="flex justify-between items-center mt-4 pt-4 border-t">
                                        <div class="text-sm text-gray-500">
                                            Created by: <span class="font-medium">{{ $task->user->name }}</span>
                                            <span class="mx-2">•</span>
                                            {{ $task->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                                View Details
                                            </a>
                                            @if(Auth::user()->isNastavnik() && $task->user_id === Auth::id())
                                                <span class="text-gray-300">|</span>
                                                <a href="{{ route('tasks.edit', $task) }}" class="text-yellow-600 hover:text-yellow-800 font-medium">
                                                    Edit
                                                </a>
                                                <span class="text-gray-300">|</span>
                                                <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $tasks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
