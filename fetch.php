<?php

$uid = $_POST['uid'];
$pdo = getPDO();
$sql = "SELECT files WHERE state <> 2 AND upload_by = $uid";
$res = $pdo->query($sql, PDO::FETCH_ASSOC)->fetchAll();
$res = array_map(function($file) {
    unset($file['upload_by']);
    $domain = "http://files.uponup.cn";
    $file['url'] = $domain . $file['path'];
    return $file;
}, $res);
return response(200, $res);

function getPDO() {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=donline", 'donline', 'donline@1407');
        return $pdo;
    }catch (PDOException $e) {
        echo("数据库链接失败" . $e->getMessage());
    }
}

function response($ret, $data = [])
{
    echo json_encode([
        'ret' => $ret,
        'data' => $data
    ]);
}

return;