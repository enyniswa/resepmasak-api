<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include "../../koneksi/koneksi.php";

// CRUD untuk Resep
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_resep'])) { // GET berdasarkan ID
        $id_resep = intval($_GET['id_resep']);
        $stmt = $conn->prepare("SELECT * FROM resep WHERE id_resep = ?");
        $stmt->bind_param('i', $id_resep);
        $stmt->execute();
        $result = $stmt->get_result();
        echo json_encode($result->fetch_assoc());
    } else { // GET semua data resep
        $result = $conn->query("SELECT * FROM resep");
        echo json_encode($result->fetch_all(MYSQLI_ASSOC));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("INSERT INTO resep (gambar, nama_resep, id_kategori, bahan, cara_membuat) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('ssiss', $data['gambar'], $data['nama_resep'], $data['id_kategori'], $data['bahan'], $data['cara_membuat']);
    $stmt->execute();
    echo json_encode(['id' => $conn->insert_id, 'status' => 'Resep berhasil ditambahkan']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $_PUT);
    $id_resep = intval($_PUT['id_resep']);
    $stmt = $conn->prepare("UPDATE resep SET gambar = ?, nama_resep = ?, id_kategori = ?, bahan = ?, cara_membuat = ? WHERE id_resep = ?");
    $stmt->bind_param('ssissi', $_PUT['gambar'], $_PUT['nama_resep'], $_PUT['id_kategori'], $_PUT['bahan'], $_PUT['cara_membuat'], $id_resep);
    $stmt->execute();
    echo json_encode(['status' => 'Resep berhasil diperbarui']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents('php://input'), $_DELETE);
    $id_resep = intval($_DELETE['id_resep']);
    $stmt = $conn->prepare("DELETE FROM resep WHERE id_resep = ?");
    $stmt->bind_param('i', $id_resep);
    $stmt->execute();
    echo json_encode(['status' => 'Resep berhasil dihapus']);
}
?>
