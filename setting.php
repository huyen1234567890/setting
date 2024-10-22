
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
            <img src="path_to_avatar.jpg" alt="Avatar" id="avatar-preview">
            <label for="avatar" class="change-avatar-button">Đổi ảnh đại diện</label>
            <input type="file" name="avatar" id="avatar" accept="image/*" onchange="previewAvatar()" style="display: none;">
        </div>

        
        <div class="form-container">
            <form action="update_profile.php" method="POST" enctype="multipart/form-data">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="huyenngoc3654238@gmail.com" required>

            <label for="username">Tên đăng nhập:</label>
            <input type="text" name="username" id="username" value="Huèn" required>

            <label for="current_password">Mật khẩu hiện tại</label>
            <input type="password" name="current_password" id="current_password" required>

            <label for="new_password">Mật khẩu mới</label>
            <input type="password" name="new_password" id="new_password">

                <button font-size: 20px type="submit">Thay đổi thông tin</button>
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

</body>
</html>