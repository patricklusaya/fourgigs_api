<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'location' => 'required|min:6',
            'roles' => 'required|array',
            'profile_image' => 'nullable|image|max:2048', 
        ]);
    
       
        $profileImagePath = null;
    
      
        if ($request->hasFile('profile_image')) {

            // Store the uploaded image and get its path
            $profileImagePath = $request->file('profile_image')->store('profiles', 'public');
        }
    
        // Create the user with validated data
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'location' => $validatedData['location'],
            'profile_image' => $profileImagePath, 
            'password' => bcrypt($validatedData['password']),
        ]);

        $token = $user->createToken('mytoken')->plainTextToken;
    
        // Attach roles to the user
        $user->roles()->attach($validatedData['roles']);
    
     
        return response()->json([
            "user" => $user,
            "token" => $token,
            "message" => "User created successfully"
        ]);
    }
    
    public function signin(Request $request)
    {
        // Validate the incoming request data
         $request->validate([
           
            'email' => 'required|email',
            'password' => 'required',
          
        ]);

        $user = User::where('email', $request->email)->first();
        

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                "message" => "Wrong credentials"
            ], 401);
        }

        $token = $user->createToken('myToken')->plainTextToken;
        $roles = $user->roles()->get();
    
        return response()->json([
            "user" => $user,
            "roles" => $roles,
            "token" => $token,
            "message" => "Signed in successfully"
        ], 200);
    }

    public function signout()
    {
        $user = request()->user();
        $user->tokens()->delete();
        return  response()->json(
        [
          'message' => 'logged out'
        ] );
            
    }


    
    public function findUser($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json($user);
        } else {
            // Return a 404 response with a JSON message if the user is not found
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    

  
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
