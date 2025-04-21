<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link rel="icon" type="image/png" href="{{ asset('image/logotdl.png') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
</head>
<body>

<div class="container mt-4">
    <div class="row">
        <!-- Navbar -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md rounded-b-2xl py-4 px-6 max-w-screen-xl mx-auto flex justify-between items-center">
            <!-- Kiri: Judul dan Tambah Tugas -->
            <div class="flex items-center gap-4">
                <h1 class="text-2xl font-bold text-gray-900">
                    Rei<span class="text-blue-600">ToDo</span>List
                </h1>
            </div>

            <!-- Kanan: Placeholder buat future fitur -->
            <div class="flex items-center gap-4">
                <button 
                    class="bg-blue-600 text-white font-medium py-2 px-4 rounded hover:bg-blue-700 transition"
                    data-bs-toggle="modal" 
                    data-bs-target="#addTaskModal">
                    Tambah Tugas
                </button>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="pt-24 px-6 max-w-screen-xl mx-auto">
            <!-- Header: Filter dan Kalender -->
            <div class="flex flex-col md:flex-row justify-end items-center gap-4 mb-6">

            <!-- Tombol Kalender -->
            <button 
                type="button"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded shadow transition"
                data-bs-toggle="modal" data-bs-target="#calendarModal">
                Kalender Deadline
            </button>

            <!-- Filter Prioritas -->
            <form method="GET" action="{{ route('tasks.index') }}">
                <select name="priority" onchange="this.form.submit()" 
                    class="text-sm border border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>🚨 High</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>⚠️ Medium</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>✅ Low</option>
                </select>
            </form>

            </div>


            <h2 class="text-2xl font-bold mb-4">Daftar Tugas</h2>

            <!-- Pending Tasks -->
            <!-- Tugas Berlangsung -->
            <h4 class="text-lg font-semibold mb-2">Tugas Berlangsung</h4>
            @if($tasks->where('completed', false)->isEmpty())
                <p class="text-gray-500 italic">Tugas belum ditambahkan.</p>
            @endif

            <div id="task-list">
            @foreach($tasks->where('completed', false) as $task)
                <div class="card my-3" id="task{{ $task->id }}">
                    <div class="card-body">
                        
                    <div class="bg-white border rounded-lg shadow p-4 mb-4">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                        <div>
                            <h5 class="text-lg font-semibold">{{ $task->title }}</h5>
                            <p class="text-sm text-gray-500">{{ $task->deadline ? $task->deadline->format('d M Y H:i') : 'Tidak ada deadline' }}</p>
                        </div>
                        <!-- Progress Bar yang Lebih Stylish -->
                        <div class="mt-3">
                            <div class="relative w-full bg-gray-100 rounded-full h-3 shadow-inner overflow-hidden">
                                <div 
                                    class="absolute top-0 left-0 h-full bg-green-500 transition-all duration-500 ease-in-out" 
                                    style="width: {{ $task->progress() }}%;">
                                </div>
                            </div>
                            <div class="text-right mt-1 text-xs text-gray-600 font-medium">
                                {{ round($task->progress()) }}% selesai
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs px-2 py-1 rounded font-semibold
                                {{ $task->priority == 'high' ? 'bg-red-500 text-white' :
                                ($task->priority == 'medium' ? 'bg-yellow-400 text-black' : 'bg-green-500 text-white') }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                                    
                            <!-- Action Buttons -->
                         <div class="flex items-center gap-2">
                                <form action="{{ route('tasks.complete', $task->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" title="Selesaikan" class="text-green-600 hover:text-green-800">
                                        <i data-feather="check-circle"></i>
                                    </button>
                                </form>
                                <button data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $task->id }}" title="Edit" class="text-yellow-500 hover:text-yellow-600">
                                    <i data-feather="edit"></i>
                                </button>
                                <button data-bs-toggle="modal" data-bs-target="#deleteTaskModal{{ $task->id }}" title="Hapus" class="text-red-500 hover:text-red-600">
                                    <i data-feather="trash-2"></i>
                                </button>
                                <button data-bs-toggle="modal" data-bs-target="#addSubTaskModal{{ $task->id }}" title="Tambah SubTask" class="text-gray-600 hover:text-gray-800">
                                    <i data-feather="plus-circle"></i>
                                </button>
                            </div>
                            
                        </div>
                    </div>

                    

                </div>

                        <!-- SubTasks -->
                        <ul class="mt-3 list-group subtask-list" data-task-id="{{ $task->id }}" id="subtask-list-{{ $task->id }}">
                            @foreach($task->subTasks as $subTask)
                                <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $subTask->id }}">
                                <button class="btn btn-secondary btn-sm">☰</button>
                                    <span class="{{ $subTask->completed ? 'text-decoration-line-through text-muted' : '' }}">
                                        {{ $subTask->title }}
                                    </span>
                                    <div>
                                        @if(!$subTask->completed)
                                            <form action="{{ route('subtasks.complete', $subTask->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Selesaikan</button>
                                            </form>
                                        @else
                                            <span class="badge bg-success">Selesai.</span>
                                        @endif
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSubTaskModal{{ $subTask->id }}">Edit</button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteSubTaskModal{{ $subTask->id }}">Delete</button>
                                    </div>
                                </li>
                                    <!-- Modal Edit SubTask -->
                                <div class="modal fade" id="editSubTaskModal{{ $subTask->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('subtasks.update', $subTask->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Ubah sub tugas</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="text" name="title" class="form-control" value="{{ $subTask->title }}" required>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success">Perbarui</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Delete SubTask -->
                                <div class="modal fade" id="deleteSubTaskModal{{ $subTask->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Apakah kamu yakin ingin menghapus <strong>{{ $subTask->title }}</strong>?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Data ini akan terhapus secara <strong>PERMANEN</strong> di database.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ route('subtasks.destroy', $subTask->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
            </div>

            <!-- Completed Tasks -->
            <h5 class="mt-4 text-lg font-semibold mb-2">Tugas selesai</h5>
            @if($tasks->where('completed', true)->isEmpty())
                <p class="text-muted text-gray-500 italic">Belum ada tugas yang selesai.</p>
            @endif

            @foreach($tasks->where('completed', true) as $task)
                <div class="card my-3" id="task{{ $task->id }}">
                    <div class="card-body">
                        <h5 class="text-decoration-line-through text-muted">{{ $task->title }}</h5>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteTaskModal{{ $task->id }}">Delete</button>
                    </div>
                </div>
            @endforeach

            <!-- kalendar -->
            <div class="modal fade" id="calendarModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Kalender Tugas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Add Task -->
<div class="modal fade" id="addTaskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="title">Nama tugas</label>
                    <input type="text" name="title" class="form-control" placeholder="Task Title" required>
                    <br>
                    <label for="deadline">Deadline</label>
                    <input type="datetime-local" name="deadline" class="form-control">
                    <br>
                    <label for="priority">Prioritas</label>
                    <select name="priority" class="form-control">
                        <option value="low">✅ Low</option>
                        <option value="medium">⚠️ Medium</option>
                        <option value="high">🚨 High</option>
                    </select>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($tasks as $task)
    <!-- Modal Edit Task -->
    <div class="modal fade" id="editTaskModal{{ $task->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah tugas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label for="title">Nama tugas</label>
                        <input type="text" name="title" class="form-control" value="{{ $task->title }}" required>
                        <br>
                        <label for="deadline">Deadline</label>
                        <input type="datetime-local" name="deadline" class="form-control" value="{{ $task->deadline }}">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete Task -->
    <div class="modal fade" id="deleteTaskModal{{ $task->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Apakah kamu yakin ingin menghapus <strong>{{ $task->title }}</strong>?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Data ini akan terhapus secara <strong>PERMANEN</strong> di database.</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add SubTask -->
    <div class="modal fade" id="addSubTaskModal{{ $task->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('subtasks.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah sub tugas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        <input type="text" name="title" class="form-control" placeholder="SubTask Title" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script>
    feather.replace();
</script>


<script>
    // drag and drop
    $(function() {
        $("#task-list").sortable({
            update: function(event, ui) {
                let taskOrder = $(this).sortable('toArray', { attribute: 'data-id' });

                $.ajax({
                    url: "{{ route('tasks.updateOrder') }}",
                    method: "POST",
                    data: {
                        tasks: taskOrder,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        console.log(response);
                    }
                });
            }
        });
    });

    // Kalendar
    document.addEventListener('DOMContentLoaded', function () {
    let calendar;
    const modal = document.getElementById('calendarModal');
    
        modal.addEventListener('shown.bs.modal', function () {
            if (!calendar) {
                calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                    initialView: 'dayGridMonth',
                    events: "{{ route('tasks.kalender.events') }}",
                    height: 500,
                    eventColor: '#0d6efd'
                });
                calendar.render();
            }
        });
    });

    // Drag and drop subtask
    document.addEventListener('DOMContentLoaded', function () {
            @foreach ($tasks as $task)
                new Sortable(document.getElementById('subtask-list-{{ $task->id }}'), {
                    animation: 150,
                    onEnd: function (evt) {
                        let order = [];
                        document.querySelectorAll('#subtask-list-{{ $task->id }} li').forEach(function (el) {
                            order.push(el.dataset.id);
                        });

                        fetch("{{ route('subtasks.updateOrder') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                task_id: {{ $task->id }},
                                order: order
                            })
                        });
                    }
                });
            @endforeach
        });
</script>

</body>
</html>
