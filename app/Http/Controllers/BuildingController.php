<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BuildingController extends Controller
{
    /**
     * This method is responsible for listing buildings
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function index()
    {
        try {
            
            $buildings = Building::all();

            return response()->json($buildings);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => "Sorry... we're facing some issue right now and we are working on it. Try again later."
            ], 500);
        }
    }

    /**
     * This method is responsible for creating a new building
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'manager' => 'required|string|max:255',
                'address' => 'required|string|max:255',
            ]);
    
            $building = Building::create($validatedData);
    
            return response()->json($building, 201);

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
     * This method is responsible for showing a specific building
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        try {
            
            $building = Building::findOrFail($id);

            return response()->json($building);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Sorry... we're facing some issue right now and we are working on it. Try again later."
            ], 500);
        }
        
    }

    /**
     * This method is responsible for updating a specific building
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            
            $validatedData = $request->validate([
                'name' => 'nullable|string|max:255',
                'manager' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
            ]);
    
            $building = Building::findOrFail($id);

            $building->update($validatedData);
    
            return response()->json($building, 200);

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
     * This method is responsible for removing a specific building
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            
            $building = Building::findOrFail($id);

            $building->delete();

            return response()->json(null, 204);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => "Sorry... we're facing some issue right now and we are working on it. Try again later."
            ], 500);
        }
        
    }
}
