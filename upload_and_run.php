<?php
/**
 * Created by PhpStorm.
 * User: Yian.Tung
 * Date: 2018/12/6
 * Time: 下午 09:08
 */

// Start 如果有上傳檔案放到正確的地方
session_start();
$target_dir = "data/";
$file_size = 0;

if($_FILES["test_result"]["size"] > 0){
    if(!empty($_FILES["test_result"]["tmp_name"])){
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir."test_result.xlsx" ;
        $file_size = ($_FILES["test_result"]["size"] / 1024);

        if (move_uploaded_file($_FILES['test_result']['tmp_name'], $target_file)) {
        } else {
            echo "Possible file upload attack!\\\\n";
        }
        // End 如果有上傳檔案放到正確的地方
    }
} else {
    $url = "file_management.php";
    echo "<script type='text/javascript'>";
    echo "window.location.href='$url?error=blank_file'";
    echo "</script>";
}

if($_FILES["test_stats"]["size"] > 0){
    if(!empty($_FILES["test_stats"]["tmp_name"])){
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir."test_stats.xlsx" ;
        $file_size = ($_FILES["test_stats"]["size"] / 1024);

        if (move_uploaded_file($_FILES['test_stats']['tmp_name'], $target_file)) {
        } else {
            echo "Possible file upload attack!\\\\n";
        }
        // End 如果有上傳檔案放到正確的地方
    }
} else {
    $url = "file_management.php";
    echo "<script type='text/javascript'>";
    echo "window.location.href='$url?error=blank_file'";
    echo "</script>";
}

$command_inline = "sudo -u www-data python3.4 scripts/parse_data_to_result.py";
$command = exec($command_inline);

?>


