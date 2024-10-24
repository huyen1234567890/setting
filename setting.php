<?php
// $plain_password = '1234567';
// $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
// echo $hashed_password;
// $2y$10$4q6Rxic6Q1dE.2k1JMjIHu7FFthBPT3Vllp3B0gteVhzJESEtLQta
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
<?php
session_start();

// include './inc/header.php';
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
if (isset($_POST["submit"])) {
    // Lấy thông tin từ form
    // $email = $_POST['Email'];
    $username = $_POST['Username'];
    $old_password = $_POST['current_password'];

    

    // // Xử lý avatar nếu người dùng upload file mới
    if (isset($_FILES['avatars'])/** && $_FILES['avatar']['error'] == 0 */) { 
        $avatar = $_FILES['avatars']['name'];          
        $temp_name = $_FILES['avatars']['tmp_name'];  
        $folder = "asset/avatar/";                      
    
        move_uploaded_file($temp_name, $folder . $avatar);

       
    } else {
        $avatar = $user['Avatar']; // Nếu không thay đổi, giữ nguyên avatar cũ
    }
    
    // Kiểm tra nếu người dùng muốn thay đổi mật khẩu
    if (!empty($old_password) && !empty($_POST['new_password'])) {
        // Kiểm tra mật khẩu cũ có đúng không
        
        if (password_verify($old_password, $user['Password'])) {
            // Mã hóa mật khẩu mới và cập nhật
            $new_password = $_POST['new_password'];
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

        } else {
            echo "Mật khẩu cũ không đúng!";
            exit;
        }
    } else {
        $hashed_new_password = $user['Password']; // Nếu không thay đổi mật khẩu, giữ nguyên mật khẩu cũ
    }

    //Cập nhật thông tin người dùng vào database
     $update_query = "UPDATE users SET Username = '$username', Avatar = '$avatar', Password = '$hashed_new_password' WHERE UserID = $user_id"; 

    if ($conn->query($update_query) === TRUE) {
        echo "Thông tin đã được cập nhật thành công!";
        // Lấy lại thông tin người dùng sau khi cập nhật
        header("Location: setting.php");
        exit;
    } else {
        echo "Cập nhật thất bại, vui lòng thử lại.";
    }


    // $update_query = "UPDATE users SET Username = ?, Avatar = ? Password = ? WHERE UserID = ?";
    // $update_stmt = $conn->prepare($update_query);
    // $update_stmt->bind_param('sssi', $username, $avatar, $hashed_new_password, $user_id);

    // if ($update_stmt->execute()) {
    //     echo "Thông tin đã được cập nhật thành công!";
    //     // Lấy lại thông tin người dùng sau khi cập nhật
    //     header("Location: setting.php");
    //     exit;
    // } else {
    //     echo "Cập nhật thất bại, vui lòng thử lại.";
    // }

}
?>
<div class="container">
    <div class="profile-section">
        <form action="setting.php" method="POST" enctype="multipart/form-data">
        <div class="avatar-container">
            <img src="./asset/avatar/<?php echo htmlspecialchars($user['Avatar']); ?>" alt="Avatar" id="avatar-preview">
            <label for="avatar" class="change-avatar-button">Đổi ảnh đại diện</label>
            <input type="file" name="avatars" id="avatar" accept="image/*" onchange="previewAvatar()" style="display: none;">
        </div>

        
        <div class="form-container">
            
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

                <button type="submit" name="submit">Thay đổi thông tin</button>
            
        </div>
    </div>
    </form>
</div>

<script>
    function previewAvatar() {
        var file = document.getElementById("avatar").files[0];
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById("avatar-preview").src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
</script>
<?php
    include './inc/footer.php'
?>
</body>
</html>