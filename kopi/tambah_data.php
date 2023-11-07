<?php
// Latihan 1: Buatkan saya koneksi database menggunakan default mysql
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'kopi';
try {
    $conn = new PDO(
    "mysql:host=$host;dbname=$database;charset=utf8",
    $username,
    $password,
    array(PDO::ATTR_PERSISTENT => true));
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e) {
    echo json_encode(['message' => "Koneksi database gagal: " . $e->getMessage(),'status' => false]);
}
// Latihan 2: Buatkan saya post endpoint untuk menambahkan data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    if (isset($data->biji_title) && isset($data->biji_description)) {
        $biji_title = $data->biji_title;
        $biji_description = $data->biji_description;

        try {
            $sql = "INSERT INTO kopi_table (biji_title, biji_description) VALUES (:biji_title, :biji_description)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':biji_title', $biji_title);
            $stmt->bindParam(':biji_description', $biji_description);

            if ($stmt->execute()) {
                $id = $conn->lastInsertId();
                echo json_encode(['message' => 'Data berhasil ditambahkan', 'status' => true, 'id' => $id]);
            } else {
                echo json_encode(['message' => 'Gagal menambahkan data', 'status' => false]);
            }
        } 
        catch (PDOException $e) {
            echo json_encode(['message' => 'Error: ' . $e->getMessage(),'status' => false]);
        }
    }else {
        echo json_encode(['message' => 'Parameter kurang','status' => false]);
    }
}
$conn=null;
?>