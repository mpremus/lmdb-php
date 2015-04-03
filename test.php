<?php

$env = mdb_env_create();
$rc = mdb_env_open($env, "./testdb", 0, 0664);

$txn = mdb_txn_begin($env, null, 0);

$dbi = mdb_dbi_open($txn,null,0);

$key = mdb_val_create(2);
$data = mdb_val_create("This is test write");

$rc = mdb_put($txn, $dbi, $key, $data, 0 );
$rc = mdb_txn_commit($txn);

$txn = mdb_txn_begin($env, NULL, 0);

$cursor= mdb_cursor_open($txn, $dbi);

while (mdb_cursor_get($cursor, $key, $data, MDB_NEXT ) == 0) {
	print_r("KEY values\n");
	print_r("size: ".mdb_val_size($key)."\n");
	print_r("data: ".mdb_val_data($key)."\n");

	print_r("DATA values\n");
	print_r("size: ".mdb_val_size($data)."\n");
	print_r("data: ".mdb_val_data($data)."\n");
}

$rc = mdb_cursor_get($cursor,$key,$data,0);

print_r("Retrived data:\n");
print_r(mdb_val_size($data)."\n");
print_r(mdb_val_data($data)."\n");

mdb_cursor_close($cursor);
mdb_txn_abort($txn);

mdb_dbi_close($env, $dbi);
mdb_env_close($env);

?>
