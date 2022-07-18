<?php
//var_dump($_FILES);
$target_dir = PROJECT_DIR . "/upload/user_images/";
$target_file = $target_dir . $kullaniciAdi.".jpg";
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
// Allow certain file formats
if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
    $check = getimagesize($_FILES["user-image-upload"]["tmp_name"]);
// Check if image file is a actual image or fake image
    if ($check !== false) {
// Check if file already exists
//if (file_exists($target_file)) {
//    echo "Sorry, file already exists.";
//    $uploadOk = 0;
//}
// Check file size
        $max_image_size = 153600; //150 KB kadar izin verir.
        if ($_FILES["user-image-upload"]["size"] > $max_image_size) {
            // echo "Sorry, your file is too large.";
            $maxDim = 500;
            list($width, $height, $type, $attr) = getimagesize($_FILES['user-image-upload']['tmp_name']);
            if ($width > $maxDim || $height > $maxDim) {
                $target_filename = $_FILES['user-image-upload']['tmp_name'];
                $fn = $_FILES['user-image-upload']['tmp_name'];
                $size = getimagesize($fn);
                $ratio = $size[0] / $size[1]; // width/height
                if ($ratio > 1) {
                    $width = $maxDim;
                    $height = $maxDim / $ratio;
                } else {
                    $width = $maxDim * $ratio;
                    $height = $maxDim;
                }
                $src = imagecreatefromstring(file_get_contents($fn));
                $dst = imagecreatetruecolor($width, $height);
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
                imagedestroy($src);
                imagejpeg($dst, $target_filename); // adjust format as needed
                imagedestroy($dst);
            }
            if (move_uploaded_file($target_filename, $target_file)) {
                // echo "The file ". basename( $_FILES["user-image-upload"]["name"]). " has been uploaded.";
            } else {
                // echo "Sorry, there was an error uploading your file.";
            }
        } else {
            if (move_uploaded_file($_FILES["user-image-upload"]["tmp_name"], $target_file)) {
                // echo "The file ". basename( $_FILES["user-image-upload"]["name"]). " has been uploaded.";
            } else {
                // echo "Sorry, there was an error uploading your file.";
            }
        }
    }
}
?>