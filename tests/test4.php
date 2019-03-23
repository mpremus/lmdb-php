<?php
include_once("../lmdb-php.php");

$env = new MDB_env();
$rc = $env->create();
$rc = $env->open("./testdb", 0, 0664);

$txn = new MDB_txn();
$rc = $txn->begin($env, null, 0);

$dbi = new MDB_dbi();
$rc = $dbi->open($txn,null,0);

$rcDel = $txn->delValue($dbi, 10);
$rc = $txn->commit();

if($rc != 0)
	return 0;

if($rcDel == 0)
	print_r("value successfully deleted\n");
else
	print_r("Error on delete. Code: $rcDel. If error code if -30798 key cant be found in DB");

$dbi->close($env);
$env->close();

return 1;
?>
