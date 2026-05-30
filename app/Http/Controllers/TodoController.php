<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Todo;
use App\Models\Recurrence;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function showAll(Request $request)
    {

        // Get the authenticated user's ID
        $userId = auth()->id();

        // Filter todos by authenticated user and completion status
        $todos = Todo::with('recurrence')
            ->where('user_id', $userId)
            ->where('completed', false)->orderByRaw("CASE WHEN due_date IS NULL THEN 1 ELSE 0 END, due_date ASC")
            ->latest()
            ->paginate(5);


        foreach ($todos as $todo) {
            $recId = !empty($todo->parent_id) ? $todo->parent_id : $todo->id;
            $recurrence = Recurrence::where('task_id', $recId)->first(); // find() instead of findOrFail() to avoid exception if not found
            $todo->rec_id = $recurrence ? $recurrence->id : 0;
        }


        // Filter completed todos by authenticated user
        $completedTodos = Todo::with('recurrence')
            ->where('user_id', $userId)
            ->where('completed', true)->orderByRaw("CASE WHEN due_date IS NULL THEN 1 ELSE 0 END, due_date ASC")
            ->latest()
            ->paginate(5);

        foreach ($completedTodos as $todo) {
            $recId = !empty($todo->parent_id) ? $todo->parent_id : $todo->id;
            $recurrence = Recurrence::where('task_id', $recId)->first();
            $todo->rec_id = $recurrence ? $recurrence->id : 0;
        }

        if ($request->ajax()) {
            return response()->json([
                'todos' => $todos,
                'completedTodos' => $completedTodos,
                'next_page_url' => $todos->nextPageUrl(),
                'next_page_url_completed' => $completedTodos->nextPageUrl(),
                'success' => 'Todos retrieved successfully!',
            ]);
        }

        // activity()->event('access')->log('Accessed all todo page');

        return view('backend.todos.all_todos', compact('todos', 'completedTodos'));
    }

    // Store a new Todos
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'repeat_every' => 'nullable|string',
            'custom_days' => 'nullable|string',

        ]);

        $user = Auth::user();
        // $batchUuid = Str::uuid();
        // $lettersOnlyUuid = preg_replace('/[0-9\-]/', '', $batchUuid);

        $repeatEvery = $request->repeat_every;
        $dueDate = $request->due_date;
        $customDays = $request->custom_days ?? null;
        $nextDueDate = $this->calculateNextDueDate($repeatEvery, $dueDate, $customDays);

        $todo = Todo::create([
            'name' => $request->title,
            'due_date' => $dueDate,
            // 'repeat_every' => $repeatEvery,
            'completed' => false,
            'user_id' => $user->id,
        ]);
        // Check if the Todo has recurrence
        if ($repeatEvery && $repeatEvery !== 'none') {
            Recurrence::create([
                'task_id' => $todo->id,
                'repeat_every' => $repeatEvery,
                'custom_days' => $customDays,  // Store custom days if it's a custom recurrence
                'next_due_date' => $nextDueDate,
            ]);
        }


        // Log the activity
        //   activity()->causedBy($user)->performedOn($todo)->withProperties($todo)->event('created')->log('Todo created');
        //   Log::info($batchUuid);

        if ($todo) {
            return response()->json([
                'success' => 'Todo added successfully!',
                'todo' => $todo,

            ]);
        } else {
            return response()->json([
                'error' => 'Failed to add Todo.',
            ]);
        }
    }

    private function calculateNextDueDate($repeatEvery, $dueDate, $customDays = null)
    {
        $nextDueDate = Carbon::parse($dueDate);

        switch (strtolower($repeatEvery)) {
            case 'daily':
                $nextDueDate->addDay();
                break;

            case 'weekdays':

                $nextDueDate->addDay();
                if ($nextDueDate->isWeekend()) {
                    $nextDueDate->addDays(2);
                }
                break;

            case 'weekly':
                $nextDueDate->addWeek();
                break;

            case 'monthly':
                $nextDueDate->addMonth();
                break;

            case 'yearly':
                $nextDueDate->addYear();
                break;

            case 'custom':

                if ($customDays) {
                    $nextDueDate->addDays($customDays);
                }
                break;

            default:
                break;
        }

        return $nextDueDate;
    }

    // Update an existing Todo
    public function update(Request $request, Todo $todo)
    {
        $todosvalues = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'date',
            'repeat_every' => 'nullable|string',
            'custom_days' => 'nullable|string',
        ]);

        $user = Auth::user();

        $repeatEvery = $request->repeat_every;
        $dueDate = $request->due_date;
        $customDays = $request->custom_days ?? null;
        $nextDueDate = $this->calculateNextDueDate($repeatEvery, $dueDate, $customDays);

        $originalData = $todo->getOriginal();


        $todo->update([
            'name' => $request->title,
            'due_date' => $dueDate,
            'completed' => false,
            'user_id' => $user->id,
        ]);

        $recurrence = Recurrence::where('task_id', $todo->id)->first();

        if ($repeatEvery && $repeatEvery !== 'none') {
            if ($recurrence) {
                $recurrence->update([
                    'repeat_every' => $repeatEvery,
                    'custom_days' => $customDays,
                    'next_due_date' => $nextDueDate,
                ]);
            } else {
                Recurrence::create([
                    'task_id' => $todo->id,
                    'repeat_every' => $repeatEvery,
                    'custom_days' => $customDays,
                    'next_due_date' => $nextDueDate,
                ]);
            }


            $updatedFields = [];
            foreach ($todosvalues as $field => $value) {
                if ($originalData[$field] ?? null !== $value) {
                    $updatedFields[$field] = [
                        'old' => $originalData[$field] ?? null,
                        'new' => $value,
                    ];
                }
            }
            // Log the activity
            //  activity()->causedBy($user)->performedOn($todo)->withProperties($updatedFields)->event('updated')->log('Todo updated');
            // ->withProperties(['title' => $request->title, 'due_date' => $dueDate])

            if ($todo) {

                return response()->json([
                    'success' => 'Todo updated successfully!',
                    'todo' => $todo,
                ]);
            } else {

                return response()->json([
                    'error' => 'Failed to update Todo.',
                ]);
            }
        } elseif ($recurrence) {
            $recurrence->delete();
        }
    }

    // Mark a Todo as Complete
    public function markComplete(Todo $todo)
    {
        $todo->update(['completed' => true]);

        // Log the activity
        // activity()->performedOn($todo)->withProperties($todo)->event('completed')->log('Todo marked as completed');

        return back()->with('success', 'Todo marked as completed!');
        // return response()->json([
        //     'success' => 'Todo marked as completed!',
        // ]);
    }

    // Mark a Todo as InComplete
    public function markIncomplete(Todo $todo)
    {
        $todo->update(['completed' => false]);

        // Log the activity
        // activity()->performedOn($todo)->withProperties($todo)->event('incompleted')->log('Todo marked as incompleted');

        return back()->with('success', 'Todo marked as incompleted!');
        // return response()->json([
        //     'success' => 'Todo marked as incompleted!',
        // ]);
    }


    public function delete($id, Request $request)
    {
        $task = Todo::find($id);

        if (!$task) {
            return response()->json([
                'error' => 'Todo could not be found',
            ]);
        }


        if ($request->deleteRecurrences) {

            $task->recurrence()->delete();
        }


        $task->delete();

        // Log the activity
        //   activity()->performedOn($task)->withProperties($task)->event('deleted')->log('Todo deleted');

        return response()->json([
            'success' => 'Todo deleted successfully!',
        ]);
    }
}
