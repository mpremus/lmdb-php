#lmdb-php
This is a PHP binding for LMDB (http://symas.com/mdb/), an extremely fast and lightweight transactional key-value store database.

##About this module

The aim of this module is to provide PHP bindings so that people can use LMDB in their applications.
 
##Examples

### PHP-style example
```
include_once("lmdb-php.php");

$key = new MDB_val(1);
$data = new MDB_val("This is test write");
 
$env = new MDB_env();
$rc = $env->create();
$rc = $env->open("./testdb", 0, 0664);
 
$txn = new MDB_txn();
$rc = $txn->begin($env, null, 0);
 
$dbi = new MDB_dbi();
$rc = $dbi->open($txn,null,0);
$rc = MDB::put($txn, $dbi, $key, $data, 0);
$rc = $txn->commit();
 
$cursor->close();
$txn->abort();
$dbi->close($env);
$env->close();
```
### C-style example
```
$key = mdb_val_create(2);
$data = mdb_val_create("This is test write");

$env = mdb_env_create();
$rc = mdb_env_open($env, "./testdb", 0, 0664);

$txn = mdb_txn_begin($env, null, 0);
$dbi = mdb_dbi_open($txn,null,0);
$rc = mdb_put($txn, $dbi, $key, $data, 0 );
$rc = mdb_txn_commit($txn);

mdb_cursor_close($cursor);
mdb_txn_abort($txn);
mdb_dbi_close($env, $dbi);
mdb_env_close($env);
```

##Requirements

- LMDB
- PHP 5.4 and above

##How to:

- LMDB compile / install guide:
    - git clone https://gitorious.org/mdb/mdb.git
    - make
    - sudo make install
- LMDB-PHP compile guide:
    - make
    - make test
- Necessary php.ini modifications:
    - Windows: http://php.net/manual/en/install.windows.extensions.php
    - Linux: add "extension=lmdb-php.so" to your php.ini file, typically located in /etc/php5/
- Run example program:
    - php -d extension=./lmdb-php.so tests/test.php

LMDB-PHP supports both object-oriented ("PHP-style") and procedural ("C-style") usage.  To use the object-oriented PHP interface you must include lmdb-php.php in your PHP code.

##Basic LMDB concepts:
- MDB_env represents a database environment that can be used in multiple processes. Created MDB_env object must be used by one process only but in global picture all threads operate with the same environment.
- MDB_dbi represents a DB which belongs to a environment. The same environment can contain multiple named databases or an unnamed database.
- MDB_txn represents a transaction. Multiple threads can open transactions for the same MDB_env, but a particular Txn object must only be accessed by one thread, and only one Txn object can be used on a thread at a time. Note that only one write transaction can be open in an environment in any given time. mdb_txn_begin() will simply block until the previous one is either mdb_txn_commit() or mdb_txn_abort.
- MDB_cursor objects is used to iterate through data stored in DB or to iterate over data for same key if DB supports multiple keys in same DB. MDB_cursor is also used to insert or retrieve data.
- MDB_val object is used to store data for insertion or for fetching data from DB.
- MDB_stat object have current status of DB.
- MDB_envinfo represents info received for current MDB_env object.

##IMPORTANT
- Before using this module look at test files to see how to use interfaces.
- Do NOT forget to close MDB_env, MDB_dbi, MDB_txn and MDB_cursor when you are done using this objects.
- More examples how to use LMDB can be found in your local lmdb folder (location of git clone for LMDB).They are written in C code but the logic is same and function names are same.

##Documentation

Learn more about LMDB and visit official site
    http://symas.com/mdb

For complete list of functions and descriptions visit 
    http://symas.com/mdb/doc/group__internal.html

For complete list if error codes visit 
    http://symas.com/mdb/doc/group__errors.html

#### List of functions that have same name as original API but that have different number of parameters and/or return type (other functions have same parameters and return type as original function):
- MDB_env *mdb_env_create_swig();
- MDB_txn *mdb_txn_begin_swig(MDB_env *env, MDB_txn *parent, unsigned int flags);
- MDB_dbi mdb_dbi_open_swig (MDB_txn *txn, const char *name, unsigned int flags);
- MDB_cursor *mdb_cursor_open_swig (MDB_txn *txn, MDB_dbi dbi);
- MDB_val *mdb_val_create(char *value);
- MDB_envinfo *mdb_env_info (MDB_env *env);
- MDB_stat *mdb_env_stat (MDB_env *env);
- MDB_stat *mdb_stat (MDB_txn *txn, MDB_dbi dbi);
- int mdb_reader_check (MDB_env *env);
- int mdb_cursor_count (MDB_cursor *cursor);
- int mdb_dbi_flags (MDB_txn *txn, MDB_dbi dbi);
- int mdb_env_get_flags (MDB_env *env);
- char *mdb_env_get_path (MDB_env *env);
- mdb_filehandle_t *mdb_env_get_fd_swig (MDB_env *env);
- int mdb_env_get_maxreaders_swig(MDB_env *env);

All functions returning pointers (* sign) and char will return NULL if there is some error and function will print error code. All error codes can be found on official website.
Functions that returns int values will also print error code but return int will be -1.

####Additional list for helper functions to retrieve data from objects:
- int mdb_val_size (MDB_val *key);
- char *mdb_val_data (MDB_val *key);
- int mdb_stat_psize (MDB_stat *stat);
- int mdb_stat_depth(MDB_stat *stat);
- int mdb_stat_branch_pages(MDB_stat *stat);
- int mdb_stat_leaf_pages(MDB_stat *stat);
- int mdb_stat_overflow_pages(MDB_stat *stat);
- int mdb_stat_entries(MDB_stat *stat)
- char *mdb_info_mapaddr(MDB_envinfo *info);
- int mdb_info_mapsize(MDB_envinfo *info);
- int mdb_info_last_pgno(MDB_envinfo *info)
- int mdb_info_last_txnid(MDB_envinfo *info)
- int mdb_info_maxreaders(MDB_envinfo *info)
- int mdb_info_numreaders(MDB_envinfo *info)

####From current version of LMDB these functions are not implemented:
- mdb_env_set_assert
- mdb_set_compare
- mdb_set_dupsortgit 
- mdb_set_relfunc
- mdb_reader_list

## Authors
- Mateo Premus (mpremus@gmail.com), Davor Ocelic
