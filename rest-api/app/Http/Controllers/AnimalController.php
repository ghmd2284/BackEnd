<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnimalController extends Controller
{
    private $animals;

    public function __construct()
    {
       $this->animals = ['Kucing', 'Ayam', 'Ikan'];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        echo "Menampilkan Data Animals" . "\n";
        foreach ($this->animals as $animal) {
            echo "- $animal" . "\n";
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $nama = $request->input('nama');
        array_push($this->animals, $nama);
        echo "Menambahkan Data Hewan" . "\n";
        echo "Nama Hewan: $nama" . "\n";
        $this->index();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $nama = $request->nama;

        if ($this->animals[$id]) {
            $this->animals[$id] = $nama;
            echo "Mengupdate Data Hewan $id" . "\n";
            echo "Nama Hewan: $nama" . "\n";
        } else {
            echo "Data Hewan $id tidak ditemukan";
        }

        $this->index();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        if ($this->animals[$id]) {
            unset($this->animals[$id]);
            echo "Menghapus Data Hewan $id" . "\n";
        } else {
            echo "Data Hewan $id tidak ditemukan";
        }

        $this->index();
    }
}
