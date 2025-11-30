<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('tasks.task_details') }}
            </h2>
            <a href="{{ route('tasks.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('tasks.back_to_tasks') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <!-- Header -->
                    <div class="mb-6 pb-6 border-b">
                        <div class="flex justify-between items-start mb-3">
                            <h1 class="text-3xl font-bold text-gray-900">{{ $task->title }}</h1>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full
                                @if($task->study_type === 'strucni') bg-green-100 text-green-800
                                @elseif($task->study_type === 'preddiplomski') bg-blue-100 text-blue-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                {{ ucfirst($task->study_type) }}
                            </span>
                        </div>
                        <p class="text-lg text-gray-600 italic">{{ $task->title_en }}</p>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ __('tasks.task_description') }}</h3>
                        <div class="prose max-w-none">
                            <p class="text-gray-700 whitespace-pre-line">{{ $task->description }}</p>
                        </div>
                    </div>

                    <!-- Meta Information -->
                    <div class="mt-6 pt-6 border-t">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-semibold">{{ __('tasks.created_by') }}:</span>
                                <span class="ml-2">{{ $task->user->name }}</span>
                            </div>
                            <div>
                                <span class="font-semibold">{{ __('tasks.created_on') }}:</span>
                                <span class="ml-2">{{ $task->created_at->format('F d, Y') }}</span>
                            </div>
                            @if($task->created_at != $task->updated_at)
                                <div>
                                    <span class="font-semibold">{{ __('tasks.last_updated') }}:</span>
                                    <span class="ml-2">{{ $task->updated_at->format('F d, Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    @if(Auth::user()->isNastavnik() && $task->user_id === Auth::id())
                        <div class="mt-8 pt-6 border-t flex gap-3">
                            <a href="{{ route('tasks.edit', $task) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('tasks.edit_task') }}
                            </a>
                            <a href="{{ route('tasks.applications', $task) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('tasks.view_applications') }} ({{ $task->applications()->count() }})
                            </a>
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline" onsubmit="return confirm('{{ __('tasks.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('tasks.delete_task') }}
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Student Application Section -->
                    @if(Auth::user()->isStudent())
                        @php
                            $userApplication = $task->applications()->where('user_id', Auth::id())->first();
                        @endphp

                        <div class="mt-8 pt-6 border-t">
                            @if($userApplication)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <span class="text-lg font-semibold">{{ __('tasks.application_status') }}:</span>
                                        <span class="px-4 py-2 rounded-full text-sm font-semibold
                                            @if($userApplication->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($userApplication->status === 'accepted') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($userApplication->status) }}
                                        </span>
                                    </div>
                                    @if($userApplication->status === 'pending')
                                        <form method="POST" action="{{ route('tasks.withdraw', $task) }}" class="inline" onsubmit="return confirm('{{ __('tasks.confirm_withdraw') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                                {{ __('tasks.withdraw_application') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <form method="POST" action="{{ route('tasks.apply', $task) }}">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded text-lg">
                                        {{ __('tasks.apply_for_task') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
