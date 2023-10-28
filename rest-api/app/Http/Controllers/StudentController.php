<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();

        $data = [
            "message" => "Get All Students",
            "data" => $students
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $input = [
            'nama' => $request->nama,
            'nim' => $request->nim,
            'email' => $request->email,
            'jurusan' => $request->jurusan
        ];
        $students = Student::create($input);

        $data = [
            'message' => 'Students is created successfully',
            'data' => $students
        ];

        return response()->json($data, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $students = Student::find($id);
        // $students->nama = $request->nama;
        // $students->nim = $request->nim;
        // $students->email = $request->email;
        // $students->jurusan = $request->jurusan;
        $input = [
            'nama' => $request->nama,
            'nim' => $request->nim,
            'email' => $request->email,
            'jurusan' => $request->jurusan
        ];
        $students->update($input);
       
        $data = [
            'message' => 'Students is Update successfully',
            'data' => $students
        ];

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $students = Student::find($id);
        $students->delete();

        $data = [
            'message' => 'Students is Delete successfully',
            'data' => $students
        ];

        return response()->json($data, 200);
    }
}
