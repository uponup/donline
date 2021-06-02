<?php

if (empty($_FILES)) {
    return response(100);
}
$temp_path = $_FILES['file']['tmp_name'];
$temp_name = $_FILES['file']['name'];
$temp_size = $_FILES['file']['size'];
$temp_error = $_FILES['file']['error'];

if ($temp_error != 0) return response(101, [$temp_error]);

// echo json_encode($_FILES);

// return;
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
return response(200, $data);

function getExtension($filename)
{
    $components = explode(".", $filename);
    if (count($components) <= 1) return "";
    return end($components);
}

function response($ret, $data = [])
{
    echo json_encode([
        'ret' => $ret,
        'data' => $data
    ]);
}
return;
