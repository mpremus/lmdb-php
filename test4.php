<?php
include_once("php-lmdb.php");

$env = new MDB_env();
$rc = $env->create();
$rc = $env->open("./testdb", 0, 0664);

$txn = new MDB_txn();
$rc = $txn->begin($env, null, 0);

$dbi = new MDB_dbi();
$rc = $dbi->open($txn,null,0);

$rcDel = $txn->delValue($dbi, 10);
$rc = $txn->commit();

if($rcDel == 0)
	print_r("value successfully deleted\n");
else
	print_r("Error on delete. Code: $rcDel. If error code if -30798 key cant be found in DB");

$txn->abort();
$dbi->close($env);
$env->close();

?>
