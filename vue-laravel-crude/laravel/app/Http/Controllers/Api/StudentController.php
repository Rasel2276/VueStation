<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index(){
        $students = Student::all();
        if($students->count() > 0){
            return response()->json([
                'status' => 200,
                'students' => $students
            ], 200);
        }
        else{
             return response()->json([
                'status' => 404,
                'students' => 'No records found'
            ], 404);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:191',
            'course' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required|digits:10',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'errors' => $validator->messages()
            ], 422);
        }else{
            $student = Student::create([
                'name' => $request->name,
                'course' => $request->course,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            if($student){
                return response()->json([
                    'status' => 200,
                    'message' => "Student created successfully"
                ], 200);
            }else{
                 return response()->json([
                    'status' => 500,
                    'message' => "Something went wrong"
                ], 500);
            }
        }
    }

    public function update(Request $request ,$id){

        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'course' => 'required|string|max:200' .$id,
            'email' => 'required|email|max:120',
            'phone' => 'required|string|max:10'
        ]);

        $student = Student::findOrFail($id);

        $student -> update($validated);

        return response()->json([
            'message' => 'Student Updated Successfully!',
            'data' => $validated
        ],200);
    }

    public function show($id)
{
    $student = Student::find($id);

    if($student){
        return response()->json([
            'status' => 200,
            'student' => $student
        ], 200);
    }else{
        return response()->json([
            'status' => 404,
            'message' => 'Student not found'
        ], 404);
    }
}

}
