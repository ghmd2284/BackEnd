<?php

namespace App\Http\Controllers;

use App\Models\Patient;

use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Daftar filter yang dapat digunakan untuk pencarian
            $dataFilter = [
                'name',
                'phone',
                'address',
                'status',
            ];

            // Format urutan yang dapat digunakan (ascending dan descending)
            $formatOrder = [
                'asc',
                'desc'
            ];

            // Daftar kolom yang dapat digunakan untuk pengurutan
            $dataSort = [
                'address',
                'in_date_at',
                'out_date_at'
            ];

            foreach ($dataFilter as $field) {
                if (request()->has($field)) {
                    // Jika parameter pencarian ada, cari data berdasarkan parameter tersebut
                    $patient = Patient::where($field, 'LIKE', "%" . request($field) . "%")->get();
                } else if (request()->has('sort') && request()->has('order')) {
                    // Jika ada parameter pengurutan, urutkan data sesuai dengan parameter tersebut
                    $sort = request('sort');
                    $order = request('order');
                    $order = in_array(strtolower($order), $formatOrder) ? strtolower($order) : '';
                    $sort = in_array(strtolower($sort), $dataSort) ? strtolower($sort) : '';
                    $patient = Patient::orderBy($sort, $order)->get();
                } else {
                    // Jika tidak ada parameter pencarian atau pengurutan, tampilkan semua data
                    $patient = Patient::all();
                }
            }

            if ($patient->isEmpty()) {
                $data = [
                    'Message' => 'Data Tidak Ditemukan',
                    'Data' => ''
                ];
                return response()->json($data, 404);
            } else {
                $data = [
                    'Message' => 'Data Ditemukan',
                    'Data' => $patient
                ];
                return response()->json($data, 200);
            }
        } catch (\Exception $err) {
            $messageError = $err->getMessage();

            $responseError = [
                'Message' => 'Kesalahan 500',
                'Error' => $messageError
            ];
            return response()->json($responseError, 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi data yang diterima dari permintaan
            $validateData = $request->validate([
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'status' => 'required|in:positif,sembuh,meninggal',
                'in_date_at' => 'nullable|date',
                'out_date_at' => 'nullable|date',
            ]);

            // Buat entitas Pasien baru berdasarkan data yang divalidasi
            $patient = Patient::create($validateData);

            // Siapkan respons JSON untuk memberi tahu bahwa data berhasil dibuat
            $data = [
                'Message' => 'Data berhasil dibuat',
                'Data' => $patient
            ];

            // Kembalikan respons dengan kode status 201 (Created)
            return response()->json($data, 201);
        } catch (\Exception $err) {
            // Tangani kesalahan jika terjadi
            $messageError = $err->getMessage();

            // Siapkan respons JSON yang menyatakan terjadi kesalahan server (kode status 500) dengan pesan kesalahan
            $responseError = [
                'Message' => 'Error 500',
                'Error' => $messageError
            ];

            // Kembalikan respons dengan kode status 500
            return response()->json($responseError, 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Cari data Pasien berdasarkan ID yang diberikan
            $patient = Patient::find($id);

            // Periksa apakah data ditemukan
            if (!$patient) {
                // Jika data tidak ditemukan, buat respons JSON dengan kode status 404 (Not Found)
                $data = [
                    'Message' => "Data dengan ID $id tidak ditemukan",
                    'Data' => ''
                ];
                return response()->json($data, 404);
            } else {
                // Jika data ditemukan, buat respons JSON dengan kode status 200 (OK)
                $data = [
                    'Message' => "Data dengan ID $id ditemukan",
                    'Data' => $patient
                ];
                return response()->json($data, 200);
            }
        } catch (\Exception $err) {
            // Tangani kesalahan jika terjadi
            $messageError = $err->getMessage();

            // Buat respons JSON yang menyatakan terjadi kesalahan server (kode status 500) dengan pesan kesalahan
            $responseError = [
                'Message' => 'Error 500',
                'Error' => $messageError
            ];

            // Kembalikan respons dengan kode status 500
            return response()->json($responseError, 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Temukan pasien berdasarkan ID
            $patient = Patient::find($id);

            if (!$patient) {
                // Jika pasien tidak ditemukan, buat respons JSON dengan kode status 404 (Not Found)
                $data = [
                    'Message' => "Data dengan ID $id tidak ditemukan",
                    'Data' => ''
                ];
                return response()->json($data, 404);
            }

            // Update data pasien dengan data yang diterima dari permintaan, jika ada
            $patient->update([
                'name' => $request->input('name', $patient->name),
                'phone' => $request->input('phone', $patient->phone),
                'address' => $request->input('address', $patient->address),
                'status' => $request->input('status', $patient->status),
                'in_date_at' => $request->input('in_date_at', $patient->in_date_at),
                'out_date_at' => $request->input('out_date_at', $patient->out_date_at),
            ]);

            // Simpan perubahan ke database
            $patient->save();

            // Buat respons JSON yang mengindikasikan bahwa data telah berhasil diperbarui
            $data = [
                'Message' => 'Data berhasil diperbarui',
                'Data' => $patient
            ];

            // Kembalikan respons dengan kode status 200 (OK)
            return response()->json($data, 200);
        } catch (\Exception $err) {
            // Tangani kesalahan jika terjadi
            $messageError = $err->getMessage();

            // Buat respons JSON yang menyatakan terjadi kesalahan server (kode status 500) dengan pesan kesalahan
            $responseError = [
                'Message' => 'Error 500',
                'Error' => $messageError
            ];

            // Kembalikan respons dengan kode status 500
            return response()->json($responseError, 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Cari pasien berdasarkan ID
            $patient = Patient::find($id);

            if (!$patient) {
                // Jika pasien tidak ditemukan, buat respons JSON dengan kode status 404 (Not Found)
                $data = [
                    'Message' => "Data dengan ID $id tidak ditemukan",
                    'Data' => ''
                ];
                return response()->json($data, 404);
            }

            // Hapus pasien dari database
            $patient->delete();

            // Buat respons JSON yang mengindikasikan bahwa data telah berhasil dihapus
            $data = [
                'Message' => "Data dengan ID $id berhasil dihapus",
                'Data' => $patient
            ];

            // Kembalikan respons tanpa konten dengan kode status 204 (No Content)
            return response()->json($data, 204);
        } catch (\Exception $err) {
            // Tangani kesalahan jika terjadi
            $messageError = $err->getMessage();

            // Buat respons JSON yang menyatakan terjadi kesalahan server (kode status 500) dengan pesan kesalahan
            $responseError = [
                'Message' => 'Error 500',
                'Error' => $messageError
            ];

            // Kembalikan respons dengan kode status 500
            return response()->json($responseError, 500);
        }
    }
}
