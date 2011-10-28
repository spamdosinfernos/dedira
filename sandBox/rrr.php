<?php 


$sql = "call sp_1(1,'RE00480','43806b9920711e28332d854d5c190d7e','','','','')";

$dsn = "mysql:host=192.168.130.244;dbname=sca;port=3306";
$conexao = new PDO($dsn, 'sys_usr_upsat', '71e5fcdf2d9217a8437cb5261fa41b4a');
$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$res = new PDOStatement();
$res = $conexao->query($sql);

$arr = $res->fetchAll();

?>