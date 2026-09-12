<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-6 min-h-screen">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-slate-800 mb-2">My To-Do List</h1>
        <p class="text-slate-600 mb-6">Kelola tugas harian dengan mudah.</p>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl border border-slate-200">
                <span class="text-xs font-semibold text-slate-500 uppercase">Total</span>
                <p class="text-2xl font-bold text-slate-800">{{ $totalTasks }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200">
                <span class="text-xs font-semibold text-amber-500 uppercase">Belum Selesai</span>
                <p class="text-2xl font-bold text-amber-600">{{ $pendingTasks }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-slate-200">
                <span class="text-xs font-semibold text-emerald-500 uppercase">Selesai</span>
                <p class="text-2xl font-bold text-emerald-600">{{ $completedTasks }}</p>
            </div>
        </div>

        <!-- Form Tambah -->
        <div class="bg-white p-6 rounded-xl border border-slate-200 mb-6">
            <form action="{{ route('tasks.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div class="md:col-span-2">
                    <input type="text" name="title" placeholder="Tambah tugas baru..." required class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <select name="priority" class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                <div>
                    <input type="date" name="due_date" class="w-full px-4 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Tambah Tugas</button>
                </div>
            </form>
        </div>

        <!-- Daftar Tugas -->
        <div class="bg-white p-6 rounded-xl border border-slate-200">
            <div class="divide-y divide-slate-100">
                @forelse ($tasks as $task)
                    <div class="py-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-6 h-6 rounded-full border-2 flex items-center justify-center {{ $task->is_completed ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300' }}">
                                    @if($task->is_completed) ✓ @endif
                                </button>
                            </form>
                            <div>
                                <p class="font-medium {{ $task->is_completed ? 'line-through text-slate-400' : 'text-slate-800' }}">
                                    {{ $task->title }}
                                </p>
                                <div class="flex gap-2 items-center text-xs text-slate-500 mt-1">
                                    <span class="px-2 py-0.5 rounded {{ $task->priority === 'High' ? 'bg-rose-100 text-rose-700' : ($task->priority === 'Medium' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                        {{ $task->priority }}
                                    </span>
                                    @if($task->due_date)
                                        <span>Tenggat: {{ $task->due_date }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-rose-500 hover:text-rose-700 text-sm">Hapus</button>
                        </form>
                    </div>
                @empty
                    <p class="text-center text-slate-500 py-4">Belum ada tugas.</p>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>