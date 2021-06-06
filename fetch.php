<?php

$uid = $_POST['uid'];
$pdo = getPDO();
$sql = "SELECT files WHERE state = 1 AND upload_by = $uid";
$res = $pdo->query($sql, PDO::FETCH_ASSOC)->fetchAll();
$res = array_map(function($file) {
    unset($file['state']);
    unset($file['upload_by']);
    $domain = "http://files.uponup.cn";
    $file['path'] = $domain . $file['path'];
    return $file;
}, $res);
echo json_encode($res);

function getPDO() {
    try {
        $pdo = new PDO("mysql:host='localhost';dbname='donline'", 'donline', 'donline@1407');
        return $pdo;
    }catch (PDOException $e) {
        echo("数据库链接失败" . $e->getMessage());
    }
}

return;