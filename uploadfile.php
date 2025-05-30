<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form enctype="multipart/form-data" action="upload.php" method="POST">
    Chọn file để upload:
    <br>
    <input type="text" name="name" placeholder="Name">
    <input type="text" name="description" placeholder="Description">
    <br>
    <input type="file" name="fileupload" id="fileupload">
    <input type="submit" value="Đăng ảnh" name="submit">
        <form>
</body>
</html>