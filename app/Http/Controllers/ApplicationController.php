<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
   
    public function index(Request $request,)
    {

        $userApplications =$request->user()->applications()->with('job')->get(); // Make sure 'job' is the correct relationship name in the Application model
    
        return response()->json([
            'applications' => $userApplications,
        ]);
    }


    public function myJobs(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Get all jobs posted by the user and their applications
        $jobsWithApplications = $user->jobs()->with('applications')->get();

        return response()->json([
            'data' => $jobsWithApplications,
        ]);
    }


    public function seekerApplications(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Get all jobs posted by the user and their applications
        $jobSeekerApplications = $user->applications()->get();

        return response()->json([
            'data' => $jobSeekerApplications,
        ]);
    }

    //accept or reject an applicant
    public function changeStatus(Request $request, $id)
    {
        // Get the authenticated user
        $user = $request->user();
    
        $application = Application::find($id);
    
        // Check if the application exists
        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }
    
       
        if ($application->jobs->employer_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to change the status'], 403);
        }
    
        
        $validatedData = $request->validate([
            'status' => 'required|in:Accepted,Rejected',
        ]);
    
        // Update the application status
        $application->status = $validatedData['status'];
        $application->save();
    
        return response()->json(['message' => 'Application status updated successfully']);
    }
    

    public function showAppliedJobs(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Get all jobs posted by the user and their applications
        $userApplications = $user->applications()->get();

        return response()->json([
            'applications' => $userApplications,
        ]);
    }
    
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'job_id' => 'required|exists:job_listing,id',
            'user_id' => 'required|exists:users,id',
            'cover_letter' => 'required|string|max:5000',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);
    
        // Check if the user has already applied for the same job
        $existingApplication = Application::where('job_id', $validatedData['job_id'])
            ->where('user_id', $validatedData['user_id'])
            ->first();
    
        if ($existingApplication) {
            // Return an error response if an application already exists
            return response()->json([
                'message' => 'Failed: You have already applied for this job.',
            ], 400); // Use an appropriate status code (e.g., 400 for Bad Request)
        }
    
        // Store the uploaded resume if it exists
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        } else {
            $resumePath = null;
        }
    
        // Create a new application
        $application = Application::create([
            'job_id' => $validatedData['job_id'],
            'user_id' => $validatedData['user_id'],
            'cover_letter' => $validatedData['cover_letter'],
            'resume' => $resumePath,
            'status' => 'Pending',
        ]);
    
        // Return a success response
        return response()->json([
            'application' => $application,
            'message' => 'Application submitted successfully!',
        ], 201);
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
