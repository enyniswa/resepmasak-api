<?php
header("Access-Control-Allow-Origin:*");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include "../../koneksi/koneksi.php";

// CRUD untuk Favorit
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $result = $conn->query("SELECT f.id_favorit, r.* FROM favorit f JOIN resep r ON f.id_resep = r.id_resep");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("INSERT INTO favorit (id_resep) VALUES (?)");
    $stmt->bind_param('i', $data['id_resep']);
    $stmt->execute();
    echo json_encode(['id' => $conn->insert_id, 'status' => 'Resep berhasil ditambahkan ke favorit']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents('php://input'), $_DELETE);
    $id_favorit = intval($_DELETE['id_favorit']);
    $stmt = $conn->prepare("DELETE FROM favorit WHERE id_favorit = ?");
    $stmt->bind_param('i', $id_favorit);
    $stmt->execute();
    echo json_encode(['status' => 'Favorit berhasil dihapus']);
}
?>