all:
	gcc -shared -fpic  lmdb-php_wrap.c -I/usr/include/php5/Zend/ -I/usr/include/php5/ -I/usr/include/php5/TSRM/ -I/usr/include/php5/main/ -llmdb -o lmdb-php.so
test:
	cd tests && ( for p in *php; do echo -n "$$p: " php -d lmdb-php.so $$p > /dev/null  && echo OK || echo NOT OK; done )



