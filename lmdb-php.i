%module lmdb_php
%include exception.i    

%{
#include "lmdb.h"
%}

%ignore mdb_env_create;
%ignore mdb_env_open;
%ignore mdb_txn_begin;
%ignore mdb_dbi_open;
%ignore mdb_cursor_open;
%ignore mdb_env_get_maxreaders;
%ignore mdb_env_get_path;
%ignore mdb_env_get_fd;
%ignore mdb_env_get_flags;
%ignore mdb_env_set_userctx;
%ignore mdb_dbi_flags;
%ignore mdb_cursor_count;
%ignore mdb_reader_check;
%ignore mdb_stat;
%ignore mdb_set_relctx;
%ignore mdb_env_stat;
%ignore mdb_env_info;
%ignore mdb_set_compare;
%ignore mdb_set_dupsort;
%ignore mdb_set_relfunc;
%ignore mdb_env_set_assert;
#if MDB_VERSION_PATCH > 10
%ignore mdb_env_set_userctx;
#endif

%include "lmdb.h"

%newobject mdb_env_create_swig;
%newobject mdb_env_open_swig;
%newobject mdb_txn_begin_swig;
%newobject mdb_dbi_open_swig;
%newobject mdb_cursor_open_swig;
%newobject mdb_val_create;
%newobject mdb_env_info_swig;
%newobject mdb_env_stat_swig;
%newobject mdb_set_relctx_swig;
%newobject mdb_stat_swig;
%newobject mdb_env_get_fd_swig;
#if MDB_VERSION_PATCH > 10
%newobject mdb_env_set_userctx_swig;
#endif


%rename (mdb_env_create) mdb_env_create_swig();
%rename (mdb_env_open) mdb_env_open_swig (MDB_env *env, const char *path, unsigned int flags, int mode);
%rename (mdb_txn_begin) mdb_txn_begin_swig (MDB_env *env, MDB_txn *parent, unsigned int flags);
%rename (mdb_dbi_open) mdb_dbi_open_swig (MDB_txn *txn, const char *name, unsigned int flags);
%rename (mdb_cursor_open) mdb_cursor_open_swig(MDB_txn *txn, MDB_dbi dbi);
%rename (mdb_env_get_maxreaders) mdb_env_get_maxreaders_swig(MDB_env *env);
%rename (mdb_env_get_fd) mdb_env_get_fd_swig(MDB_env *env);
%rename (mdb_env_get_path) mdb_env_get_path_swig(MDB_env *env);
%rename (mdb_env_get_flags) mdb_env_get_flags_swig(MDB_env *env);
%rename (mdb_dbi_flags) mdb_dbi_flags_swig(MDB_txn *txn, MDB_dbi dbi);
%rename (mdb_cursor_count) mdb_cursor_count_swig(MDB_cursor *cursor);
%rename (mdb_reader_check) mdb_reader_check_swig(MDB_env *env);
%rename (mdb_stat) mdb_stat_swig(MDB_txn *txn, MDB_dbi dbi);
%rename (mdb_set_relctx) mdb_set_relctx_swig(MDB_txn *txn, MDB_dbi dbi, char *value);
%rename (mdb_env_stat) mdb_env_stat_swig(MDB_env *env);
%rename (mdb_env_info) mdb_env_info_swig(MDB_env *env);

#if MDB_VERSION_PATCH > 10
%rename (mdb_env_set_userctx) mdb_env_set_userctx_swig(MDB_env *env, char *value);
#endif

%inline %{
    MDB_envinfo *mdb_env_info_swig(MDB_env *env){
        MDB_envinfo *info;
        info = (MDB_envinfo*)malloc( sizeof( MDB_envinfo ) );

        int rc =  mdb_env_info(env, info);

        if(rc != 0){
            php_error_docref(NULL, E_NOTICE, "mdb_env_info: %d\n", rc);
        }

        return info;
    }

    MDB_stat *mdb_env_stat_swig(MDB_env *env){
        MDB_stat *stat;
        stat = (MDB_stat*)malloc( sizeof( MDB_stat ) );

        int rc =  mdb_env_stat(env, stat);

        if(rc != 0){
            php_error_docref(NULL, E_NOTICE, "mdb_env_stat: %d\n", rc);
        }

        return stat;
    }

    int mdb_set_relctx_swig(MDB_txn *txn, MDB_dbi dbi, char *value){
        int length = strlen(value) + 1;
        char *res = malloc(length);
        strncpy(res, value, length);

        return mdb_set_relctx(txn, dbi, (void*)res);
    }

    MDB_stat  *mdb_stat_swig(MDB_txn *txn, MDB_dbi dbi){
        MDB_stat *stat;
        stat = (MDB_stat*)malloc( sizeof( MDB_stat ) );

        int rc = mdb_stat(txn, dbi, stat);

         if(rc != 0){
            php_error_docref(NULL, E_NOTICE, "mdb_stat: %d\n", rc);
        }

        return stat;
    }

    int mdb_reader_check_swig(MDB_env *env){
        int dead;

        int rc = mdb_reader_check(env, &dead);

        if(rc != 0){
            php_error_docref(NULL, E_NOTICE, "mdb_reader_check: %d\n", rc);
        }

        return dead;
    }

    int mdb_cursor_count_swig(MDB_cursor *cursor){
        size_t duplicates;

        int rc = mdb_cursor_count(cursor, &duplicates);

        if(rc != 0){
            php_error_docref(NULL, E_NOTICE, "mdb_cursor_count: %d\n", rc);
        }

        return duplicates;
    }

    int mdb_dbi_flags_swig(MDB_txn *txn, MDB_dbi dbi){
        unsigned int flags;

        int rc = mdb_dbi_flags(txn, dbi, &flags);

        if(rc != 0){
            php_error_docref(NULL, E_NOTICE, "mdb_dbi_flags: %d\n", rc);
        }

        return flags;
    }   

    int mdb_env_get_flags_swig(MDB_env *env){
        unsigned int flags;

        int rc = mdb_env_get_maxreaders(env, &flags);

        if(rc != 0){
            php_error_docref(NULL, E_NOTICE, "mdb_env_get_flags: %d\n", rc);
        }

        return flags;
    }

    char *mdb_env_get_path_swig(MDB_env *env){
        const char *path;

        int rc = mdb_env_get_path(env, &path);

        if(rc != 0){
            php_error_docref(NULL, E_NOTICE, "mdb_env_get_path: %d\n", rc);
        }

        return (unsigned char *)path;
    }

    mdb_filehandle_t *mdb_env_get_fd_swig (MDB_env *env){
        mdb_filehandle_t fd;

        int rc = mdb_env_get_fd(env, &fd);

        if(rc != 0){
            php_error_docref(NULL, E_NOTICE, "mdb_env_get_fd: %d\n", rc);
        }

        mdb_filehandle_t * fdp;
        fdp = &fd;

        return fdp;
    }

    int mdb_env_get_maxreaders_swig(MDB_env *env){
        unsigned int readers;

        int rc = mdb_env_get_maxreaders(env, &readers);

        if(rc != 0){
            php_error_docref(NULL, E_NOTICE, "mdb_env_get_maxreaders: %d\n", rc);
        }

        return readers;
    }

    MDB_env *mdb_env_create_swig() {
        MDB_env *env;

        int rc = mdb_env_create(&env);

        if(rc != 0){	
            php_error_docref(NULL, E_NOTICE, "mdb_txn_begin: %d\n", rc);
        }

        return env;
    }

    int mdb_env_open_swig(MDB_env *env, const char *path, unsigned int flags, int mode) {
        return mdb_env_open(env, path, flags, mode);
    }

    struct MDB_txn *mdb_txn_begin_swig(MDB_env *env, MDB_txn *parent, unsigned int flags){
        MDB_txn *txn;

        int rc = mdb_txn_begin(env, parent, flags, &txn);

        if(rc != 0){
            php_error_docref(NULL, E_NOTICE,"mdb_txn_begin: %d\n", rc);        
        }

        return txn;
    }

    MDB_dbi mdb_dbi_open_swig (MDB_txn *txn, const char *name, unsigned int flags){
        MDB_dbi dbi;

        int rc = mdb_dbi_open(txn, name, flags, &dbi);

        if(rc != 0){
            php_error_docref(NULL, E_NOTICE, "mdb_dbi_open: %d\n", rc);
        }

        return dbi;
    }


    struct MDB_cursor *mdb_cursor_open_swig (MDB_txn *txn, MDB_dbi dbi){
        MDB_cursor *cursor;

        int rc = mdb_cursor_open(txn, dbi, &cursor);

        if(rc != 0){
            php_error_docref(NULL, E_NOTICE, "mdb_cursor_open: %d\n", rc);
        }

        return cursor;
    }

    MDB_val *mdb_val_create(char *value){
        MDB_val *key;
        key = (MDB_val*)malloc( sizeof( MDB_val ) );

        int length = strlen(value) + 1;
        char *res = malloc(length);
        strncpy(res, value, length);

        key->mv_size = length;
        key->mv_data = res;

        return key;
    }

    int mdb_val_size( MDB_val *key){
        return (int)key->mv_size;
    }

    char *mdb_val_data( MDB_val *key){
        return (char*)key->mv_data;
    }

    int mdb_stat_psize(MDB_stat *stat){
        return stat->ms_psize;
    }

    int mdb_stat_depth(MDB_stat *stat){
        return stat->ms_depth;
    }

    int mdb_stat_branch_pages(MDB_stat *stat){
        return stat->ms_branch_pages;
    }

    int mdb_stat_leaf_pages(MDB_stat *stat){
        return stat->ms_leaf_pages;
    }

    int mdb_stat_overflow_pages(MDB_stat *stat){
        return stat->ms_overflow_pages;
    }

    int mdb_stat_entries(MDB_stat *stat){
        return stat->ms_entries;
    }

    char *mdb_info_mapaddr(MDB_envinfo *info){
        return (char*)info->me_mapaddr;
    }

    int mdb_info_mapsize(MDB_envinfo *info){
        return info->me_mapsize;
    }

    int mdb_info_last_pgno(MDB_envinfo *info){
        return info->me_last_pgno;
    }

    int mdb_info_last_txnid(MDB_envinfo *info){
        return info->me_last_txnid;
    }

    int mdb_info_maxreaders(MDB_envinfo *info){
        return info->me_maxreaders;
    }

    int mdb_info_numreaders(MDB_envinfo *info){
        return info->me_numreaders;
    }  
%}

#if MDB_VERSION_PATCH > 10
%inline %{
    int mdb_env_set_userctx_swig(MDB_env *env, char *value){
        int length = strlen(value) + 1;
        char *res = malloc(length);
        strncpy(res, value, length);

        return mdb_env_set_userctx(env, (void*)res);
    }

    char *mdb_env_get_userctx_swig(MDB_env *env){
        void *pointer = mdb_env_get_userctx(env);

        return (char *)pointer;
    }
%}
#endif




