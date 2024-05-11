<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * This method is responsible for listing the tasks
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        try {

            $tasks = Task::with('comments');

            if ($request->has('status') && !empty($request->status)) {

                if (TaskStatus::isValid($request->status)) {
                    $tasks->where('status', $request->status);
                } else {
                    throw new Exception('Invalid status informed. List of possible statuses: ' . implode(', ', TaskStatus::getValues()), self::USER_ERROR_CODE);
                }
            }

            if ($request->has('title') && !empty($request->title)) {
                $tasks->where('title', 'like', '%'.$request->title.'%');
            }

            if ($request->has('building_id') && !empty($request->building_id)) {
                $tasks->where('building_id', $request->building_id);
            }
            
            if ($request->has('user_id') && !empty($request->user_id)) {
                $tasks->where('user_id', $request->user_id);
            }
            
            if (
                $request->has('start_date') && $request->has('end_date') &&
                !empty($request->start_date) && !empty($request->end_date)
            ) {
                
                if ($request->end_date < $request->start_date) {
                    throw new Exception('Invalid date informed. The end_date should be greater than start_date.', self::USER_ERROR_CODE);
                }

                $tasks->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($request->start_date)), date('Y-m-d 23:59:59', strtotime($request->end_date))]);
            }
            elseif ($request->has('start_date') && !empty($request->start_date)) {
                $tasks->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime($request->start_date)));
            }
            elseif ($request->has('end_date') && !empty($request->end_date)) {
                $tasks->where('created_at', '<', date('Y-m-d 23:59:59', strtotime($request->end_date)));
            }

            $filteredTasks = $tasks->get();

            return response()->json($filteredTasks);

        } catch (\Throwable $th) {

            if ($th->getCode() === self::USER_ERROR_CODE) {
                return response()->json([
                    'message' => $th->getMessage()
                ], 400);
            }

            return response()->json([
                'message' => "Sorry... we're facing some issue right now and we are working on it. Try again later."
            ], 500);
        }
        
    }

    /**
     * This method is responsible for creating a new task
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'building_id' => 'required|exists:buildings,id',
            ]);
    
            $validatedData['status'] = TaskStatus::OPEN;
            $validatedData['user_id'] = Auth::id();
            $validatedData['created_by'] = Auth::id();

            $task = Task::create($validatedData);
    
            return response()->json($task, 201);

        }
        catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
        catch (\Throwable $th) {
            return response()->json([
                'message' => "Sorry... we're facing some issue right now and we are working on it. Try again later."
            ], 500);
        }
    }

    /**
     * This method is responsible for showing a specific task
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            
            $task = Task::with('comments')->findOrFail($id);

            return response()->json($task);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Sorry... we're facing some issue right now and we are working on it. Try again later."
            ], 500);
        }
    }

    /**
     * This method is responsible for updating a specific task
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            
            $validatedData = $request->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'status' => 'nullable|in:' . implode(',', TaskStatus::getValues()),
                'user_id' => 'nullable|exists:users,id',
                'building_id' => 'nullable|exists:buildings,id',
            ]);

            $validatedData['updated_by'] = Auth::id();
    
            $task = Task::findOrFail($id);
    
            if (!$task->canChangeStatus()) {
                throw new Exception('Cannot update the task status when it is COMPLETED.', self::USER_ERROR_CODE);
            }
    
            $task->update($validatedData);
    
            return response()->json($task);

        }
        catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
        catch (\Throwable $th) {

            if ($th->getCode() === self::USER_ERROR_CODE) {
                return response()->json([
                    'message' => $th->getMessage()
                ], 400);
            }

            return response()->json([
                'message' => "Sorry... we're facing some issue right now and we are working on it. Try again later."
            ], 500);
        }
    }

    /**
     * This method is responsible for removing a specific task
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            
            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json(null, 204);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Sorry... we're facing some issue right now and we are working on it. Try again later."
            ], 500);
        }
    }

    /**
     * This method is responsible for commenting in a specific task
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function comment(Request $request, $id)
    {
        try {
            
            $request->validate([
                'message' => 'required|string|max:255'
            ]);
    
            $task = Task::findOrFail($id);
    
            $comment = $task->createComment($request->message);
    
            $task->comments->add($comment);
    
            return response()->json($task->load('comments'));

        }
        catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        } 
        catch (\Throwable $th) {
            return response()->json([
                'message' => "Sorry... we're facing some issue right now and we are working on it. Try again later."
            ], 500);
        }
    }
}