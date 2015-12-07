<?php
/**
 * Created by PhpStorm.
 * User: core01
 * Date: 07.12.15
 * Time: 18:32
 */

header('Content-type: text/html; charset=utf-8');
defined('ALTO_DIR') || define('ALTO_DIR', dirname(dirname(__FILE__)));
require_once('../app/config/config.local.php');
$cfg = $config['db']['params'];
$host = $cfg['host'];
$db = $cfg['dbname'];
$charset = $cfg['charset'];
$user = $cfg['user'];
$pass = $cfg['pass'];
$prefix = $config['db']['table']['prefix'];
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = array(
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);
$pdo = new PDO($dsn, $user, $pass, $opt);
$query = $pdo->query('SELECT * FROM '.$prefix.'user'.' WHERE user_profile_avatar !=""');
$rows = $query->fetchALL();
$f =0;
$stmt = $pdo->prepare('UPDATE '.$prefix.'user'.' SET user_profile_avatar = "" WHERE user_id = ?');
$count = count($rows);
$ids = array();
foreach($rows as $row){
    if (!file_exists(ALTO_DIR.$row['user_profile_avatar'])) {
        $stmt->execute([$row['user_id']]);
        $ids[$row['user_login']] = $row['user_id'];
        $f++;
    }
}
echo $f." аватарок не было обнаружено из ".$count.'<br />';
echo "Пользователи с отсутствующими файлами аватарок: <br /><pre>";
print_r($ids);
echo "</pre>";



