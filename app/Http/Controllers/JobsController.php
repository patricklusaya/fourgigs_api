<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15); 
        $jobs = Job::all();
    
        $response = [
           
           
            'data' => $jobs, // Only include job data
        ];
    
        return response()->json($response);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        // Step 1: Validate the incoming request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',

            'salary_range' => 'required|string|max:255',
            'job_type' => 'required|string|max:255',
            'job_category' => 'required|string|max:255',
         
        ]);
    
        
        $job = Job::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'company_name' => $validatedData['company_name'],
            'location' => $validatedData['location'],
            'salary_range' => $validatedData['salary_range'],
            'job_type' => $validatedData['job_type'],
            'job_category' => $validatedData['job_category'],
            'employer_id' => Auth::id(),
          
        ]);
    
      
        return response()->json([
            'message' => 'Job created successfully',
            'job' => $job,
        ], 201); // 201 Created
    }
    
    public function show(string $id)
    {
    
        $job = Job::find($id);
    
       
        if (!$job) {
            return response()->json([
                'message' => 'Job not found',
            ], 404);
        }
    
       
        $response = [
            'id' => $job->id,
            'title' => $job->title,
            'description' => $job->description,
            'company_name' => $job->company_name,
            'location' => $job->location,
            'salary_range' => $job->salary_range,
            'job_type' => $job->job_type,
            'job_category' => $job->job_category,
            'employer_id' => $job->employer_id,
        ];
    
     
        return response()->json($response, 200); // 200 OK
    }
    
 
    public function update(Request $request, string $id)
    {
       
        $job = Job::find($id);
    
       
        if (!$job) {
            return response()->json([
                'message' => 'Job not found',
            ], 404); // 404 Not Found
        }
    
        
        if ($job->employer_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized to update this job',
            ], 403); // 403 Forbidden
        }
    
      
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'company' => 'sometimes|required|string|max:255',
            'location' => 'sometimes|required|string|max:255',
            'salary_range' => 'sometimes|required|string', 
            'job_type' => 'sometimes|required|string|max:50', 
            'job_category' => 'sometimes|required|string|max:50', 
        ]);
    
        
        $job->update($validatedData);
    
       
        return response()->json([
            'message' => 'Job updated successfully',
            'job' => $job,
        ], 200);
    }
    

    public function destroy(string $id)
    {
        $job = Job::find($id);
    
        if (!$job) {
            return response()->json([
                'message' => 'Job not found',
            ], 404);
        }
    
        if ($job->employer_id !== Auth::id()) {
            return response()->json([
                'message' => 'Unauthorized to delete this job',
            ], 403);
        }
    
        $job->delete();
    
        return response()->json([
            'message' => 'Job deleted successfully',
        ], 200);
    }
    
    public function filterJobs(Request $request)
    {
        // Initialize the query
        $query = Job::query();
    
        // Filter by date posted
        if ($request->has('date') && !empty($request->input('date'))) {
            $date = $request->input('date');
            $query->whereDate('created_at', $date); // Adjust 'created_at' if you have a different date column
        }
    
        // Filter by job category
        if ($request->has('category') && !empty($request->input('category'))) {
            $category = $request->input('category');
            $query->where('job_category', $category); // Adjust the column name if necessary
        }
    
        // Filter by job type
        if ($request->has('job_type') && !empty($request->input('job_type'))) {
            $jobType = $request->input('job_type');
            $query->where('job_type', $jobType); // Adjust the column name if necessary
        }
    
        // Get the filtered results
        $jobs = $query->get();

         // Check if the result is empty and return a custom message if it is
    if ($jobs->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No jobs found matching the provided filters.'
        ], 200);
    }
    
        // Return the filtered jobs as a JSON response
        return response()->json($jobs);
    }
    
}
