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
// Latihan 4: Buatkan saya post endpoint untuk mengupdate data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->id) && (isset($data->biji_title) || isset($data->biji_description))) {
        $id = $data->id;
        $biji_title = isset($data->biji_title) ? $data->biji_title : null;
        $biji_description = isset($data->biji_description) ? $data->biji_description : null;
        try{
            if ($biji_title || $biji_description) {
            $sql = "UPDATE kopi_table SET ";
            if ($biji_title) {
                $sql .= "biji_title = :biji_title, ";
            }
            if ($biji_description) {
                $sql .= "biji_description = :biji_description, ";
            }
            $sql = rtrim($sql, ", ");
            $sql .= " WHERE id = :id";

            $stmt = $conn->prepare($sql);

            if ($biji_title) {
                $stmt->bindParam(':biji_title', $biji_title);
            }
            if ($biji_description) {
                $stmt->bindParam(':biji_description', $biji_description);
            }
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                echo json_encode(['message' => 'Data berhasil diupdate', 'status' => true]);
            } else {
                echo json_encode(['message' => 'Gagal mengupdate data', 'status' => false]);
            }
        } else {
            echo json_encode(['message' => 'Parameter kurang','status' => false]);
        }
        }catch (PDOException $e) {
            echo json_encode(['message' => 'Error: ' . $e->getMessage(),'status' => false]);
        }

    }
}
$conn=null;
?>