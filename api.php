<?php
include 'dbkoneksi.php';

// Konfigurasi XMPP (Ganti dengan detail Anda)
define('XMPP_HOST', 'your_xmpp_server_host');
define('XMPP_PORT', 5222);
define('XMPP_USER', 'your_bot_username');
define('XMPP_PASSWORD', 'your_bot_password');
define('XMPP_SERVER', 'your_xmpp_server_domain');

function send_xmpp_message($recipient_jid, $message_body) {
    // Fungsi ini hanya simulasi
    try {
        file_put_contents('xmpp_log.txt', "To: $recipient_jid\nBody: $message_body\n---\n", FILE_APPEND);
        return true;
    } catch (Exception $e) {
        error_log('XMPP Sending failed: ' . $e->getMessage());
        return false;
    }
}

header('Content-Type: application/json');
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_reminders':
        $query = "SELECT id, title, description, reminder_date AS date FROM reminders ORDER BY reminder_date ASC";
        $result = mysqli_query($konek, $query);
        if ($result) {
            $reminders = mysqli_fetch_all($result, MYSQLI_ASSOC);
            echo json_encode($reminders);
        } else {
            echo json_encode(['error' => 'Gagal mengambil data: ' . mysqli_error($konek)]);
        }
        break;


    case 'add_reminder':
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $date = $_POST['date'] ?? '';
        $recipient = $_POST['recipient'] ?? '';

        if (empty($title) || empty($date) || empty($recipient)) {
            echo json_encode(['success' => false, 'message' => 'Judul, tanggal, dan penerima tidak boleh kosong.']);
            exit;
        }

        $stmt = mysqli_prepare($konek, "INSERT INTO reminders (title, description, reminder_date) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $title, $description, $date);

        if (mysqli_stmt_execute($stmt)) {
            $lastId = mysqli_insert_id($konek);
            $formatted_date = (new DateTime($date))->format('l, j F Y \p\u\k\u\l H:i');
            $message_body = "Pengingat Baru: {$title}\nTanggal: {$formatted_date}\nDeskripsi: {$description}";
            $xmpp_sent = send_xmpp_message($recipient, $message_body);
            $message = $xmpp_sent ? 'Pengingat ditambahkan dan notifikasi XMPP terkirim.' : 'Pengingat ditambahkan, tapi notifikasi XMPP gagal.';
            echo json_encode(['success' => true, 'id' => $lastId, 'message' => $message]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($konek)]);
        }
        mysqli_stmt_close($stmt);
        break;

    case 'delete_reminder':
        $id = $_POST['id'] ?? '';
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID tidak tersedia.']);
            exit;
        }

        $stmt = mysqli_prepare($konek, "DELETE FROM reminders WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($konek)]);
        }
        mysqli_stmt_close($stmt);
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}

mysqli_close($konek);
?>