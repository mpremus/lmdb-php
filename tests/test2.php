<?php
include_once("../lmdb-php.php");

$env = new MDB_env();
$rc = $env->create();
$rc = $env->open("./testdb", 0, 0664);

$txn = new MDB_txn();
$rc = $txn->begin($env, null, 0);

$dbi = new MDB_dbi();
$rc = $dbi->open($txn,null,0);

$key = new MDB_val(1);
$data = new MDB_val("This is test write");

$rc = MDB::put($txn, $dbi, $key, $data, 0);

$rc = $txn->commit();
$rc = $txn->begin($env, NULL, 0);

$cursor = new MDB_cursor();
$rc = $cursor->open($txn, $dbi);

while ($cursor->get($key, $data, MDB_NEXT) == 0) {
	print_r("KEY values\n");
	print_r("size: ".$key->getMvSize()."\n");
	print_r("data: ".$key->getMvData()."\n");

	print_r("DATA values\n");
	print_r("size: ".$data->getMvSize()."\n");
	print_r("data: ".$data->getMvData()."\n");
}

$rc = $cursor->get($key,$data,0);

if($rc != 0)
	return 0;

print_r("Retrived data:\n");
print_r($data->getMvSize()."\n");
print_r($data->getMvData()."\n");

$cursor->close();
$txn->abort();
$dbi->close($env);
$env->close();

return 1;
?>
