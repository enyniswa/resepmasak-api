<?php
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include "../../koneksi/koneksi.php";

// CRUD untuk Kategori
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_kategori'])) { // GET berdasarkan ID
        $id_kategori = intval($_GET['id_kategori']);
        $stmt = $conn->prepare("
            SELECT k.*, 
                (SELECT COUNT(*) FROM resep WHERE id_kategori = k.id_kategori) AS jumlah_resep 
            FROM kategori k WHERE k.id_kategori = ?");
        $stmt->bind_param('i', $id_kategori);
        $stmt->execute();
        $result = $stmt->get_result();
        echo json_encode($result->fetch_assoc());
    } else { // GET semua kategori dengan jumlah resep
        $result = $conn->query("
            SELECT k.*, 
                (SELECT COUNT(*) FROM resep WHERE id_kategori = k.id_kategori) AS jumlah_resep 
            FROM kategori k");
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
    $stmt->bind_param('s', $data['nama_kategori']);
    $stmt->execute();
    echo json_encode(['id' => $conn->insert_id, 'status' => 'Kategori berhasil ditambahkan']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $_PUT);
    $id_kategori = intval($_PUT['id_kategori']);
    $stmt = $conn->prepare("UPDATE kategori SET nama_kategori = ? WHERE id_kategori = ?");
    $stmt->bind_param('si', $_PUT['nama_kategori'], $id_kategori);
    $stmt->execute();
    echo json_encode(['status' => 'Kategori berhasil diperbarui']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents('php://input'), $_DELETE);
    $id_kategori = intval($_DELETE['id_kategori']);
    $stmt = $conn->prepare("DELETE FROM kategori WHERE id_kategori = ?");
    $stmt->bind_param('i', $id_kategori);
    $stmt->execute();
    echo json_encode(['status' => 'Kategori berhasil dihapus']);
}
?>