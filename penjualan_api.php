<?php
header('Content-Type: application/json');
include 'config.php';

// Mengambil metode HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Menangani request API berdasarkan metode
switch ($method) {
    case 'GET':
        // Endpoint untuk mendapatkan data berdasarkan id
        if (isset($_GET['type']) && $_GET['type'] == 'user' && isset($_GET['id'])) {
            getUserById($conn, $_GET['id']);
        } elseif (isset($_GET['type']) && $_GET['type'] == 'order' && isset($_GET['id'])) {
            getOrderById($conn, $_GET['id']);
        } elseif (isset($_GET['type']) && $_GET['type'] == 'user') {
            getAllUsers($conn);
        } elseif (isset($_GET['type']) && $_GET['type'] == 'order') {
            getAllOrders($conn);
        } elseif (isset($_GET['user_id'])) {
            getOrdersByUserId($conn, $_GET['user_id']);
        } else {
            echo json_encode(["message" => "Please specify type of data (user or order)"]);
        }
        break;
    
    case 'POST':
        // Endpoint untuk membuat user atau order
        if (isset($_GET['type']) && $_GET['type'] == 'user') {
            createUser($conn);
        } elseif (isset($_GET['type']) && $_GET['type'] == 'order') {
            createOrder($conn);
        } else {
            echo json_encode(["message" => "Invalid POST request"]);
        }
        break;
    
    case 'PUT':
        // Endpoint untuk memperbarui user atau order
        if (isset($_GET['type']) && $_GET['type'] == 'user' && isset($_GET['id'])) {
            updateUser($conn, $_GET['id']);
        } elseif (isset($_GET['type']) && $_GET['type'] == 'order' && isset($_GET['id'])) {
            updateOrder($conn, $_GET['id']);
        } else {
            echo json_encode(["message" => "Invalid PUT request"]);
        }
        break;
    
    case 'DELETE':
        // Endpoint untuk menghapus user atau order
        if (isset($_GET['type']) && $_GET['type'] == 'user' && isset($_GET['id'])) {
            deleteUser($conn, $_GET['id']);
        } elseif (isset($_GET['type']) && $_GET['type'] == 'order' && isset($_GET['id'])) {
            deleteOrder($conn, $_GET['id']);
        } else {
            echo json_encode(["message" => "Invalid DELETE request"]);
        }
        break;
    
    default:
        echo json_encode(["message" => "Request method not supported"]);
        break;
}

// Fungsi untuk mengambil semua pengguna
function getAllUsers($conn) {
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode($users);
    } else {
        echo json_encode(["message" => "No users found"]);
    }
}

// Fungsi untuk mengambil data pengguna berdasarkan ID
function getUserById($conn, $id) {
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(["message" => "User not found"]);
    }
}

// Fungsi untuk menambahkan pengguna baru
function createUser($conn) {
    // Mengambil data JSON yang dikirimkan
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Memastikan data yang diterima valid
    if (isset($data['username']) && isset($data['email'])) {
        $username = $data['username'];
        $email = $data['email'];

        // SQL untuk memasukkan data ke database
        $sql = "INSERT INTO users (username, email) VALUES ('$username', '$email')";

        // Mengeksekusi query
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["message" => "New user created successfully"]);
        } else {
            echo json_encode(["message" => "Error: " . $sql . " - " . $conn->error]);
        }
    } else {
        echo json_encode(["message" => "Invalid data"]);
    }
}


// Fungsi untuk memperbarui data pengguna
function updateUser($conn, $id) {
    $data = json_decode(file_get_contents("php://input"), true);
    $username = $data['username'];
    $email = $data['email'];
    
    $sql = "UPDATE users SET username='$username', email='$email' WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "User updated successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $sql . " - " . $conn->error]);
    }
}

// Fungsi untuk menghapus pengguna
function deleteUser($conn, $id) {
    $sql = "DELETE FROM users WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "User deleted successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $sql . " - " . $conn->error]);
    }
}

// Fungsi untuk mengambil semua pesanan
function getAllOrders($conn) {
    $sql = "SELECT * FROM orders";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        echo json_encode($orders);
    } else {
        echo json_encode(["message" => "No orders found"]);
    }
}

// Fungsi untuk mengambil pesanan berdasarkan user_id
function getOrdersByUserId($conn, $user_id) {
    $sql = "SELECT * FROM orders WHERE user_id = $user_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        echo json_encode($orders);
    } else {
        echo json_encode([]);
    }
}

// Fungsi untuk mengambil data pesanan berdasarkan ID
function getOrderById($conn, $id) {
    $sql = "SELECT * FROM orders WHERE id = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        echo json_encode($order);
    } else {
        echo json_encode(["message" => "Order not found"]);
    }
}

// Fungsi untuk menambahkan pesanan baru
function createOrder($conn) {
    $data = json_decode(file_get_contents("php://input"), true);
    $order_date = $data['order_date'];
    $user_id = $data['user_id'];
    
    $sql = "INSERT INTO orders (order_date, user_id) VALUES ('$order_date', $user_id)";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "New order created successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $sql . " - " . $conn->error]);
    }
}

// Fungsi untuk memperbarui pesanan
function updateOrder($conn, $id) {
    $data = json_decode(file_get_contents("php://input"), true);
    $order_date = $data['order_date'];
    
    $sql = "UPDATE orders SET order_date='$order_date' WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Order updated successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $sql . " - " . $conn->error]);
    }
}

// Fungsi untuk menghapus pesanan
function deleteOrder($conn, $id) {
    $sql = "DELETE FROM orders WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Order deleted successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $sql . " - " . $conn->error]);
    }
}

$conn->close();
?>
