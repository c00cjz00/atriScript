<?php
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;



// Check if file already exists
if ((substr($target_file,-8,8)==".message") || (substr($target_file,-7,7)==".output") || (substr($target_file,-6,6)==".Query") || (substr($target_file,-5,5)==".List") || (substr($target_file,-5,5)==".Scan")){
 $uploadOk = 1;
}else{
 echo "Sorry, file format is not allowed.";    
 $uploadOk = 0;
}


// Check if file already exists
/*if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}*/

// Check file size
if ($_FILES["fileToUpload"]["size"] > 50000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    unlink($target_file); sleep(1);
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
