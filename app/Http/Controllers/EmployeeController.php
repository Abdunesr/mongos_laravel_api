<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $employees = Employee::all();
            return response()->json($employees, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve employees',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created employee.
     */
   public function store(Request $request)
    {
        // Manual validation instead of $request->validate()
        $validator = Validator::make($request->all(), [
            'emp_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'phone' => 'required|numeric|unique:employees,phone'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $employee = Employee::create($request->all());
            return response()->json($employee, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create employee',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Display the specified employee.
     */
    public function show(string $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            return response()->json($employee, Response::HTTP_OK);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Employee not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve employee',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified employee.
     */
    public function update(Request $request, string $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            
            $validated = $request->validate([
                'emp_name' => 'sometimes|string|max:255',
                'dob' => 'sometimes|date',
                'phone' => 'sometimes|numeric|unique:employees,phone,'.$id
            ]);

            $employee->update($validated);
            
            return response()->json([
                'message' => 'Employee updated successfully',
                'data' => $employee
            ], Response::HTTP_OK);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Employee not found'
            ], Response::HTTP_NOT_FOUND);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update employee',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified employee.
     */
    public function destroy(string $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();
            
            return response()->json([
                'message' => 'Employee deleted successfully'
            ], Response::HTTP_OK);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Employee not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete employee',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
