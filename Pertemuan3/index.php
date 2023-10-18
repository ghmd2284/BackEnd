<?php 
class Animal
{
    private $animals = [];

    public function __construct($initialData)
    {
        $this->animals = $initialData;
    }

    public function index()
    {
        foreach ($this->animals as $index => $animal) {
            echo "$index: $animal <br>";
        }
    }

    public function store($newAnimal)
    {
        $this->animals[] = $newAnimal;
    }

    public function update($index, $newAnimal)
    {
        if (isset($this->animals[$index])) {
            $this->animals[$index] = $newAnimal;
        } else {
            echo "Index $index tidak valid. Tidak ada hewan dengan indeks tersebut.<br>";
        }
    }

    public function destroy($index)
    {
        if (isset($this->animals[$index])) {
            array_splice($this->animals, $index, 1);
        } else {
            echo "Index $index tidak valid. Tidak ada hewan dengan indeks tersebut.<br>";
        }
    }
}

$animal = new Animal(['kucing', 'anjing', 'burung']);

echo "Index - Menampilkan seluruh hewan <br>";
$animal->index();
echo "<br>";

echo "Store - Menambahkan hewan baru <br>";
$animal->store('ikan');
$animal->index();
echo "<br>";

echo "Update - Mengupdate hewan <br>";
$animal->update(0, 'Kucing Anggora');
$animal->index();
echo "<br>";

echo "Destroy - Menghapus hewan <br>";
$animal->destroy(1);
$animal->index();
echo "<br>";

?>