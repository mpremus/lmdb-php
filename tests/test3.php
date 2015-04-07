<?php
include_once("../lmdb-php.php");

$env = new MDB_env();
$rc = $env->create();
$rc = $env->open("./testdb", 0, 0664);

$txn = new MDB_txn();
$rc = $txn->begin($env, null, 0);

$dbi = new MDB_dbi();
$rc = $dbi->open($txn,null,0);

$rc = $txn->putValue($dbi, 10, "Some dummy data");
$rc = $txn->commit();

$rc = $txn->begin($env, NULL, 0);
$data = $txn->getValue($dbi, 10);

if(empty($data))
	return 0;

print_r("loaded data: $data\n");

$txn->abort();
$dbi->close($env);
$env->close();

return 1;
?>
