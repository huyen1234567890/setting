<?php
session_start();

include './inc/header.php';
$conn = mysqli_connect("localhost", "root", "", "bookreview");

// Lấy thông tin user_ID từ session
$_SESSION['user_id'] = 1;
$user_id = $_SESSION['user_id']; 

// Kiểm tra xem user_id có tồn tại trong session không
if (!isset($user_id)) { 
    header("Location: login.php");
    exit;
}
// Lấy thông tin người dùng từ database dựa trên user_id từ session 
$query ="SELECT Email, Username, Avatar, Password FROM users WHERE UserID = $user_id";
$result = $conn->query($query); 

// Kiểm tra nếu có dữ liệu người dùng
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Không tìm thấy thông tin người dùng.";
    exit;
}

// Khi người dùng nhấn nút thay đổi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin từ form
    $email = $_POST['Email'];
    $username = $_POST['Username'];
    $old_password = $_POST['current_password'];

    // Xử lý avatar nếu người dùng upload file mới
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $avatar = './asset/avatar/' . basename($_FILES['avatar']['name']);
        echo $avatar;
        move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar);
    } else {
        $avatar = $user['Avatar']; // Nếu không thay đổi, giữ nguyên avatar cũ
    }


    // Kiểm tra nếu người dùng muốn thay đổi mật khẩu
    if (!empty($old_password) && !empty($new_password)) {
        // Kiểm tra mật khẩu cũ có đúng không
        if (password_verify($old_password, $user['Password'])) {
            // Mã hóa mật khẩu mới và cập nhật
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
        } else {
            echo "Mật khẩu cũ không đúng!";
            exit;
        }
    } else {
        $hashed_new_password = $user['Password']; // Nếu không thay đổi mật khẩu, giữ nguyên mật khẩu cũ
    }

    // Cập nhật thông tin người dùng vào database
     $update_query = "UPDATE users SET Username = '$username', Avatar = '$avatar', Password = '$hashed_new_password' WHERE UserID = $user_id"; 

    if ($conn->query($update_query) === TRUE) {
        echo "Thông tin đã được cập nhật thành công!";
        // Lấy lại thông tin người dùng sau khi cập nhật
        header("Location: setting.php");
        exit;
    } else {
        echo "Cập nhật thất bại, vui lòng thử lại.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./asset/css/main.css">
    <link rel="stylesheet" href="./setting.css">
    <link rel="stylesheet" href="./asset/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <title>Profile Setting  - Book Review</title>
</head>

<body>

<div class="container">
    <div class="profile-section">
        
        <div class="avatar-container">
            <img src=".\asset\avatar\<?php echo $user['Avatar']; ?>" alt="Avatar" id="avatar-preview">
            <label for="avatar" class="change-avatar-button">Đổi ảnh đại diện</label>
            <input type="file" name="avatar" id="avatar" accept=".jpg, .jpeg, .png" onchange="previewAvatar()" style="display: none;">
        </div>

        
        <div class="form-container">
            <form action="setting.php" method="POST" enctype="multipart/form-data">
                <div class="form-container-2">

                    <label for="email">Email:</label>
                    <input type="email" name="Email" id="email" value="<?php echo $user['Email']; ?>" readonly>
        
                    <label for="username">Tên đăng nhập:</label>
                    <input type="text" name="Username" id="username" value="<?php echo $user['Username']; ?>" required>
        
                    <label for="current_password">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" id="current_password" required>
        
                    <label for="new_password">Mật khẩu mới</label>
                    <input type="password" name="new_password" id="new_password">
                </div>

                <button type="submit">Thay đổi thông tin</button>
            </form>
        </div>
    </div>
</div>

<script>
    function previewAvatar() {
        const input = document.getElementById('avatar');
        const preview = document.getElementById('avatar-preview');
        const reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
        };

        if (input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?php
    include './inc/footer.php'
?>
</body>
</html>