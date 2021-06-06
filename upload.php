<?php

if (empty($_FILES)) {
    return response(100);
}
$temp_path = $_FILES['file']['tmp_name'];
$temp_name = $_FILES['file']['name'];
$temp_size = $_FILES['file']['size'];
$temp_error = $_FILES['file']['error'];
$temp_type = $_FILES['file']['type'];

if ($temp_size == 0) return response(100);
if ($temp_error != 0) return response(101, [$temp_error]);

// 超过最大限制
if ($temp_size > 20000000) return response(102);

$file_content = file_get_contents($temp_path);
$filename = md5($file_content) . "." . getExtension($temp_name);
$relative_path = "donline/$filename";
$root = "/home/admin/files";
$domain = "http://files.uponup.cn";

move_uploaded_file("$temp_path", "$root/$relative_path");

$data = [
    [
        "filename" => "$temp_name",
        "url" => "$domain/$relative_path"
    ]
];

// 将记录插入数据库
$sql = "INSERT INTO files SET name = '$temp_name', type = '$temp_type', size = $temp_size, path = '/$relative_path'";

$pdo = getPDO();
$pdo->exec($sql);

return response(200, $data);

function getExtension($filename)
{
    $components = explode(".", $filename);
    if (count($components) <= 1) return "";
    return end($components);
}

function response($ret, $data = [])
{
    header("Access-Control-Allow-Origin: *");
    echo json_encode([
        'ret' => $ret,
        'data' => $data
    ]);
}

function getPDO() {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=donline", 'donline', 'donline@1407');
        return $pdo;
    }catch (PDOException $e) {
        echo("数据库链接失败" . $e->getMessage());
    }
}
return;
