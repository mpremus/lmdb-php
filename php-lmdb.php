<?php 
class MDB_env{
	protected $mdbEnvPtr = null;

	public function create (){
		ob_start();

		$this->setPtr(mdb_env_create());

		$output = ob_get_contents();

		ob_end_clean();

		$this->validatePtr($output);

		return 0;
	}

	public function open($path, $flags, $mode){	
		$this->validatePtr();

		return mdb_env_open($this->getPtr(), $path, $flags, $mode);
	}
 
	public function copy($path) {
		$this->validatePtr();

		return mdb_env_copy($this->getPtr(),$path);
	}

	public function copyfd($fd) {
		$this->validatePtr();

		return mdb_env_copyfd($this->getPtr(),$fd);
	}

	public function copy2($path,$flags) {
		$this->validatePtr();

		return mdb_env_copy2($this->getPtr(),$path,$flags);
	}

	public function copyfd2($fd,$flags) {
		$this->validatePtr();

		return mdb_env_copyfd2($this->getPtr(),$fd,$flags);
	}

	public function stat() {
		$this->validatePtr();

		$statPtr =  mdb_env_stat($this->getPtr());

		return new MDB_stat($statPtr);
	}

	public function info() {
		$this->validatePtr();

		$infoPtr =  mdb_env_info($this->getPtr());
		
		return new MDB_envinfo($infoPtr);
	}

	public function sync($force) {
		$this->validatePtr();

		return mdb_env_sync($this->getPtr(),$force);
	}

	public function close() {
		$this->validatePtr();

		mdb_env_close($this->getPtr());
	}

	public function set_flags($flags,$onoff) {
		$this->validatePtr();

		return mdb_env_set_flags($this->getPtr(),$flags,$onoff);
	}

	public function get_flags() {
		$this->validatePtr();

		return mdb_env_get_flags($this->getPtr());
	}

	public function get_path() {
		$this->validatePtr();

		return mdb_env_get_path($this->getPtr());
	}

	public function get_fd() {
		$this->validatePtr();

		return mdb_env_get_fd($this->getPtr());
	}

	public function set_mapsize($size) {
		$this->validatePtr();

		return mdb_env_set_mapsize($this->getPtr(),$size);
	}

	public function set_maxreaders($readers) {
		$this->validatePtr();

		return mdb_env_set_maxreaders($this->getPtr(),$readers);
	}

	public function get_maxreaders() {
		$this->validatePtr();

		return mdb_env_get_maxreaders($this->getPtr());
	}

	public function set_maxdbs($dbs) {
		$this->validatePtr();

		return mdb_env_set_maxdbs($this->getPtr(),$dbs);
	}


	public function get_maxkeysize() {
		$this->validatePtr();

		return mdb_env_get_maxkeysize($this->getPtr());
	}

	public function set_userctx($ctx) {
		$this->validatePtr();

		return mdb_env_set_userctx($this->getPtr(),$ctx);
	}

	public function get_userctx() {
		$this->validatePtr();

		return mdb_env_get_userctx($this->getPtr());
	}

	protected function validatePtr($message = "MDB_env is NULL"){		
		if($this->getPtr() == null){
			throw new Exception("Exception with message: $message", 1);			
		}
	}

	public function getPtr(){
		return $this->mdbEnvPtr;
	}

	public function setPtr($mdbEnvPtr){
		$this->mdbEnvPtr = $mdbEnvPtr;
	}
}

class MDB_txn {
	protected $mdbTxnPtr = null;

	public function begin($env, $parent, $flags) {
		ob_start();

		if($parent != null)
			$parent = $parent->getPtr();

		$this->setPtr(mdb_txn_begin($env->getPtr(), $parent, $flags));

		$output = ob_get_contents();

		ob_end_clean();

		$this->validatePtr($output);		

		return 0;
	}

	public function commit(){
		$this->validatePtr();

		return mdb_txn_commit($this->mdbTxnPtr);
	}

	public function abort(){
		$this->validatePtr();

		return mdb_txn_abort($this->getPtr());
	}


	public function env() {
		$this->validatePtr();

		return mdb_txn_env($this->getPtr());
	}

	public function id() {
		$this->validatePtr();

		return mdb_txn_id($this->getPtr());
	}

	public function reset() {
		$this->validatePtr();

		mdb_txn_reset($this->getPtr());
	}

	public function renew() {
		$this->validatePtr();

		return mdb_txn_renew($this->getPtr());
	}

	protected function validatePtr($message = "MDB_txn is NULL"){
		if($this->getPtr() == NULL){
			throw new Exception("Exception with message: $message", 1);			
		}
	}

	public function getPtr(){
		return $this->mdbTxnPtr;
	}

	public function setPtr($mdbTxnPtr){
		$this->mdbTxnPtr = $mdbTxnPtr;
	}
}

class MDB_dbi {
	protected $mdbDbiPtr;
	protected $mdbEnvPtr;

	public function open($txn, $name, $flags) {
		ob_start();

		if($txn != null)
			$txn = $txn->getPtr();

		$mdbDbiPtr = mdb_dbi_open($txn, $name, $flags);

		$output = ob_get_contents();

		ob_end_clean();

		if(!empty($output))
			$mdbDbiPtr = null;

		$this->setPtr($mdbDbiPtr);

		$this->validatePtr($output);

		return 0;
	}

	public function close($env){
		$this->validatePtr();

		if($env != null)
			$env = $env->getPtr();

		mdb_dbi_close($env, $this->getPtr());
	}


	public function flags($txn) {
		$this->validatePtr();

		if($txn != null)
			$txn = $txn->getPtr();

		return mdb_dbi_flags($txn,$this->getPtr());
	}

	protected function validatePtr($message = "MDB_dbi is NULL"){
		if($this->getPtr() == NULL){
			throw new Exception("Exception with message: $message", 1);			
		}
	}

	public function getPtr(){
		return $this->mdbDbiPtr;
	}

	public function setPtr($mdbDbiPtr){
		$this->mdbDbiPtr = $mdbDbiPtr;
	}
}

class MDB_cursor {
	protected $mdbCursorPtr;

	public function open($txn, $dbi) {
		ob_start();

		if($txn != null)
			$txn = $txn->getPtr();

		if($dbi != null)
			$dbi = $dbi->getPtr();

		$this->setPtr(mdb_cursor_open($txn, $dbi));

		$output = ob_get_contents();

		ob_end_clean();

		$this->validatePtr($output);

		return 0;
	}

	public function get($key, $data, $flags){
		$this->validatePtr();	

		if($key != null)
			$key = $key->getPtr();

		if($data != null)
			$data = $data->getPtr();

		return mdb_cursor_get($this->getPtr(), $key, $data, $flags);
	}

	public function close(){
		$this->validatePtr();	

		mdb_cursor_close($this->getPtr());	
	}

	public function renew($txn) {
		$this->validatePtr();	

		return mdb_cursor_renew($txn->getPtr(),$this->getPtr());
	}

	public function txn() {
		$this->validatePtr();	

		return mdb_cursor_txn($this->getPtr());
	}

	public function dbi() {
		$this->validatePtr();	

		return mdb_cursor_dbi($this->getPtr());
	}
	
	public function put($key,$data,$flags) {
		$this->validatePtr();	

		return mdb_cursor_put($this->getPtr(),$key->getPtr(),$data->getPtr(),$flags);
	}

	public function del($flags) {
		$this->validatePtr();	

		return mdb_cursor_del($this->getPtr(),$flags);
	}

	public function count() {
		$this->validatePtr();	

		return mdb_cursor_count_swig($this->getPtr());
	}


	protected function validatePtr($message = "MDB_cursor is NULL"){
		if($this->getPtr() == NULL){
			throw new Exception("Exception with message: $message", 1);			
		}
	}

	public function getPtr(){
		return $this->mdbCursorPtr;
	}

	public function setPtr($mdbCursorPtr){
		$this->mdbCursorPtr = $mdbCursorPtr;
	}
}

class MDB_val {
	protected $mvSize;
	protected $mvData;
	protected $mdbValPrt;

	function __construct($data) {		
		$this->setPtr(mdb_val_create($data));
		$this->mvSize = strlen($data);
		$this->mvData = $data;
	}

	public function getMvSize(){
		if($this->getPtr() == NULL) return 0;

		return mdb_val_size($this->getPtr());
	}

	public function getMvData(){
		if($this->getPtr() == NULL) return NULL;

		return trim(mdb_val_data($this->getPtr()));
	}

	protected function validatePtr($message = "MDB_val is NULL"){
		if($this->getPtr() == NULL){
			throw new Exception("Exception with message: $message", 1);			
		}
	}

	public function getPtr(){
		return $this->mdbValPrt;
	}

	public function setPtr($mdbValPrt){
		$this->mdbValPrt = $mdbValPrt;
	}
}

class MDB {
	
	public static function version($major,$minor,$patch) {
		return mdb_version($major,$minor,$patch);
	}
	
	public static function strerror($err) {
		return mdb_strerror($err);
	}
	
	public static function stat($txn,$dbi) {
		$statPtr = mdb_stat($txn->getPtr(),$dbi->getPtr());

		return new MDB_stat($statPtr);
	}
	
	public static function drop($txn,$dbi,$del) {
		return mdb_drop($txn->getPtr(),$dbi->getPtr(),$del);
	}
	
	public static function set_relctx($txn,$dbi,$ctx) {			   
		return mdb_set_relctx($txn->getPtr(),$dbi->getPtr(),$ctx);
	}
	
	public static function get($txn,$dbi,$key,$data) {
		return mdb_get($txn->getPtr(),$dbi->getPtr(),$key->getPtr(),$data->getPtr());
	}
	
	public static function put($txn,$dbi,$key,$data,$flags) {
		return mdb_put($txn->getPtr(),$dbi->getPtr(),$key->getPtr(),$data->getPtr(),$flags);
	}
	
	public static function del($txn,$dbi,$key,$data) {
		return mdb_del($txn->getPtr(),$dbi->getPtr(),$key->getPtr(),$data->getPtr());
	}
	
	public static function cmp($txn,$dbi,$a,$b) {
		return mdb_cmp($txn->getPtr(),$dbi->getPtr(),$a->getPtr(),$b->getPtr());
	}
	
	public static function dcmp($txn,$dbi,$a,$b) {
		return mdb_dcmp($txn->getPtr(),$dbi->getPtr(),$a->getPtr(),$b->getPtr());
	}
	
	public static function reader_check($env) {
		return mdb_reader_check($env->getPtr());
	}
}


class MDB_stat {
	protected $mdbStatPrt;
	protected $ms_psize;			/**< Size of a database page. This is currently the same for all databases. */										
	protected $ms_depth;			/**< Depth (height) of the B-tree */
	protected $ms_branch_pages;	/**< Number of internal (non-leaf) pages */
	protected $ms_leaf_pages;		/**< Number of leaf pages */
	protected $ms_overflow_pages;	/**< Number of overflow pages */
	protected $ms_entries;			/**< Number of data items */

	function __construct($statPtr){
		$this->setPtr($statPtr);
		$this->validatePtr();

		$this->setMsPsize(mdb_stat_psize($statPtr));
		$this->setMsDepth(mdb_stat_depth($statPtr));
		$this->setBranchPages(mdb_stat_branch_pages($statPtr));
		$this->setMsLeafPages(mdb_stat_leaf_pages($statPtr));
		$this->setMsOverflowPages(mdb_stat_overflow_pages($statPtr));
		$this->setMsEnteris(mdb_stat_entries($statPtr));
	}

	public function getMsPsize(){
		return $this->ms_psize;
	}

	public function setMsPsize($ms_psize){
		$this->ms_psize = $ms_psize;
	}

	public function getMsDepth(){
		return $this->ms_depth;
	}

	public function setMsDepth($ms_depth){
		$this->ms_depth = $ms_depth;
	}

	public function getBranchPages(){
		return $this->ms_branch_pages;
	}

	public function setBranchPages($ms_branch_pages){
		$this->ms_branch_pages = $ms_branch_pages;
	}

	public function getMsLeafPages(){
		return $this->ms_leaf_pages;
	}

	public function setMsLeafPages($ms_leaf_pages){
		$this->ms_leaf_pages = $ms_leaf_pages;
	}

	public function getMsOverflowPages(){
		return $this->ms_overflow_pages;
	}

	public function setMsOverflowPages($ms_overflow_pages){
		$this->ms_overflow_pages = $ms_overflow_pages;
	}

	public function getMsEnteris(){
		return $this->ms_entries;
	}

	public function setMsEnteris($ms_entries){
		$this->ms_entries = $ms_entries;
	}

	protected function validatePtr($message = "MDB_stat is NULL"){
		if($this->getPtr() == NULL){
			throw new Exception("Exception with message: $message", 1);			
		}
	}

	public function getPtr(){
		return $this->mdbStatPrt;
	}

	public function setPtr($mdbStatPrt){
		$this->mdbStatPrt = $mdbStatPrt;
	}
} 

/** @brief Information about the environment */
class MDB_envinfo {
	protected $mdbInfoPrt;
	protected $me_mapaddr;			/**< Address of map, if fixed */
	protected $me_mapsize;				/**< Size of the data memory map */
	protected $me_last_pgno;			/**< ID of the last used page */
	protected $me_last_txnid;			/**< ID of the last committed transaction */
	protected $me_maxreaders;		/**< max reader slots in the environment */
	protected $me_numreaders;		/**< max reader slots used in the environment */

	function __construct($infoPtr){
		$this->setPtr($infoPtr);
		$this->validatePtr();

		$this->setMeMapAddr(mdb_info_mapaddr($infoPtr));
		$this->setMeMapsize(mdb_info_mapsize($infoPtr));
		$this->setMeLastPgno(mdb_info_last_pgno($infoPtr));
		$this->setMeLastTxnid(mdb_info_last_txnid($infoPtr));
		$this->setMeMaxReaders(mdb_info_maxreaders($infoPtr));
		$this->setMeNumReaders(mdb_info_numreaders($infoPtr));
	}

	public function getMeMapAddr(){
		return $this->me_mapaddr;
	}

	public function setMeMapAddr($me_mapaddr){
		$this->me_mapaddr = $me_mapaddr;
	}

	public function getMeMapSize(){
		return $this->me_mapsize;
	}

	public function setMeMapsize($me_mapsize){
		$this->me_mapsize = $me_mapsize;
	}

	public function getMeLastPgno(){
		return $this->me_last_pgno;
	}

	public function setMeLastPgno($me_last_pgno){
		$this->me_last_pgno = $me_last_pgno;
	}

	public function getMeLastTxnid(){
		return $this->me_last_txnid;
	}

	public function setMeLastTxnid($me_last_txnid){
		$this->me_last_txnid = $me_last_txnid;
	}

	public function getMeMaxReaders(){
		return $this->me_maxreaders;
	}

	public function setMeMaxReaders($me_maxreaders){
		$this->me_maxreaders = $me_maxreaders;
	}

	public function getMeNumReaders(){
		return $this->me_numreaders;
	}

	public function setMeNumReaders($me_numreaders){
		$this->me_numreaders = $me_numreaders;
	}

	protected function validatePtr($message = "MDB_envinfo is NULL"){
		if($this->getPtr() == NULL){
			throw new Exception("Exception with message: $message", 1);			
		}
	}

	public function getPtr(){
		return $this->mdbInfoPrt;
	}

	public function setPtr($mdbInfoPrt){
		$this->mdbInfoPrt = $mdbInfoPrt;
	}
} 
