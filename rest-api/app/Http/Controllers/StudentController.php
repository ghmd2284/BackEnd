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

        // $input = [
        //     'nama' => $request->nama,
        //     'nim' => $request->nim,
        //     'email' => $request->email,
        //     'jurusan' => $request->jurusan
        // ];
        $validatedata = $request->validate([
            'nama'   => 'required',
            'nim' => 'numeric|required',
            'email' => 'email|required',
            'jurusan' => 'required'

        ]);
        $students = Student::create($validatedata);

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
        if ($students) {
            $input = [
                'nama' => $request->nama ?? $request->nama,
                'nim' => $request->nim ?? $request->nim,
                'email' => $request->email ?? $request->email,
                'jurusan' => $request->jurusan ?? $request->jurusan
            ];
            $students->update($input);

            $data = [
                'message' => 'Students is Update successfully',
                'data' => $students
            ];

            return response()->json($data, 200);
        } else {
            $data = [
                'message' => 'Students is Update Not successfully',
                'data' => $students
            ];

            return response()->json($data, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $students = Student::find($id);

        if ($students) {
            $students->delete();

            $data = [
                'message' => 'Students is Delete successfully',
                'data' => $students
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'message' => 'Students is Delete Not successfully',
                'data' => ''
            ];
            return response()->json($data, 200);
        }
    }
    public function show(string $id)
    {
        if (empty($id)) {
            $data = [
                'message' => 'No ID provided',
                'data' => ''
            ];
            return response()->json($data, 400);
        }

        $students = Student::find($id);

        if ($students) {
            $data = [
                'message' => 'Student is Show successfully',
                'data' => $students
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                'message' => 'Student is Show not successfully',
                'data' => ''
            ];
            return response()->json($data, 404);
        }
    }

    public function search()
    {
        $dataStudent = [
            'nama',
            'nim',
            'email',
            'jurusan'
        ];

        if (request()->has('sort') && request()->has('order')) {
            $sort = request('sort');
            $order = request('order');

            $formatOrders = ['asc', 'desc'];
            $order = in_array(strtolower($order), $formatOrders) ? strtolower($order) : 'asc';

            $students = Student::orderBy($sort, $order)->get();
            if ($students->isEmpty()) {
                $data = [
                    'message' => 'No students found',
                    'data' => ''
                ];
                return response()->json($data, 404);
            } else {
                $data = [
                    'message' => 'Get searched resource',
                    'data' => $students
                ];
                return response()->json($data, 200);
            }
        } else {
            $query = Student::query();
            foreach ($dataStudent as $field) {
                if (request()->has($field)) {
                    $query = $query->orWhere($field, 'LIKE', '%' . request($field) . '%');
                }
            }
            $students = $query->get();
            if ($students->isEmpty()) {
                $data = [
                    'message' => 'No students found',
                    'data' => ''
                ];
                return response()->json($data, 404);
            } else {
                $data = [
                    'message' => 'Get searched resource',
                    'data' => $students
                ];
                return response()->json($data, 200);
            }
        }
    }
}
