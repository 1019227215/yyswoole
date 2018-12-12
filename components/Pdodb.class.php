<?php

namespace components;
/**
 * pdo 数据抽象
 */
class Pdodb {
	/**
	 * 数据库连接对象
	 */
	protected $link = null;
	
	/**
	 * 数据库语句执行时间
	 */
	protected $execute_time = 0;
	
	/**
	 * 数据库连接对象通过prepare生成的statement，用于参数不同的多次执行
	 */
	protected $link_stmt = null;
	
	protected $fetchstyle = \PDO::FETCH_ASSOC; //PDO::FETCH_BOTH
	
	protected $column_id = null;
	
	protected $dsn = "";
	
	protected $debug = false;
	
	public function __construct($dsn = "", $user = "", $pass = "", $options = array(),$catch=true) {
		if (empty($dsn)) return false;
		try {
			$this->link = new \PDO($dsn, $user, $pass, $options);
			$this->dsn = $dsn;
		}
		catch(\PDOException $e) {
			if($catch){
				throw $e;
			}else{
				echo $e->getMessage();
				die();
			}
			
		}
	}
	
	public function getDsn(){
		return $this->dsn;
	}
	
	/**
	 * 执行sql，返回影响行数，可执行多条
	 * @param string $sql
	 * @return int
	 */
	public function exec($sql = "") {
		if (empty($sql)) return false;
		$this->execute_time = 0;
		$start_time = microtime(true);
		$result = $this->link->exec($sql);
		$this->execute_time = microtime(true) - $start_time;
		return $result;
	}
	
	/**
	 * 事务处理
	 */
	public function begintransaction() {
		$this->link->beginTransaction();
	}
	
	public function commit() {
		$this->link->commit();
	}
	
	public function rollback() {
		$this->link->rollBack();
	}
	
	/**
	 * 获取错误信息
	 * @return array
	 */
	public function getError() {
		$code = $this->link->errorCode();
		$info = $this->link->errorInfo();
		return array($code,$info);
	}
	
	/**
	 * 
	 */
	public function getCurrentExectime() {
		return $this->execute_time;
	}
	
	/**
	 * 执行sql，返回数据集，不可执行多条
	 * @param string $sql
	 * @param mixed
	 */
	public function query($sql = "") {
		if (empty($sql)) return false;
		$this->execute_time = 0;
		$start_time = microtime(true);
		$result = $this->link->query($sql);
		$this->execute_time = microtime(true) - $start_time;
		return $result;
	}
	
	
	/**
	 * 获取一个pdostatement
	 * @param string $sql
	 * @return object
	 */
	public function stmtprepare($sql = "") {
		if (empty($sql)) return false;
		return $this->link->prepare($sql);
	}
	
	/**
	 * 只做执行
	 * @param object $stmt
	 * @return bool
	 */
	public function stmtexec($stmt) {
		$this->execute_time = 0;
		$start_time = microtime(true);
		$result = $stmt->execute();
		$this->execute_time = microtime(true) - $start_time;
		return $result;
	}
	
	
	/**
	 * 只做取数据,一行
	 */
	public function stmtfetch($stmt,$style = '') {	
		if (empty($style)) $style = $this->fetchstyle;	
		return $stmt->fetch($style, $this->column_id);
	}
	
	/**
	 * 只做取数据,全部
	 */
	public function stmtfetchAll($stmt,$style = '') {
		if (empty($style)) $style = $this->fetchstyle;
		if (isset($this->column_id))
			return $stmt->fetchAll($style, $this->column_id);
		else
			return $stmt->fetchAll($style);
	}
	
	/**
	 * set fetch style default PDO::FETCH_BOTH
	 *
	 * @param int $style (PDO::COLUMN PDO::FETCH_UNIQUE PDO::FETCH_GROUP)
	 */
	public function setfetchstyle($style = \PDO::FETCH_BOTH) {
		$this->fetchstyle = $style;
	}
	
	public function setcolumnid($column_id = null) {
		$this->column_id = $column_id;
	}
	
	/**
	 * 获取stmtexec影响的记录数
	 */
	public function getCounts($stmt) {
		return $stmt->rowCount();
	}
	
	/**
	 * 直接取数据
	 * @param string $sql
	 * @param ref object $stmt
	 * @return mixed
	 */
	public function getOne($sql, &$stmt = null) {
		if (empty($sql)) return false;
		$stmt = $this->stmtprepare($sql);
		if ($this->stmtexec($stmt)) {
			return $this->stmtfetch($stmt);
		}
		else {
			return false;
		}
	}
	
	/**
	 * 取全部
	 * @param string $sql
	 * @param ref object $stmt
	 * @return mixed
	 */
	public function getAll($sql, &$stmt = null) {
		if (empty($sql)) return false;
		$stmt = $this->stmtprepare($sql);
		if ($this->stmtexec($stmt)) {
			return $this->stmtfetchAll($stmt);
		}
		else {
			return false;
		}
	}
	
	/**
	 * 执行SQL
	 * @param string $sql
	 * @param ref object $stmt
	 * @return mixed
	 */
	public function execute($sql, &$stmt = null) {
		if (empty($sql)) return false;
		$stmt = $this->stmtprepare($sql);
		return $this->stmtexec($stmt);
	}
	
	public function lastInsertId() {
		return $this->link->lastInsertId();
	}
	
	public function setDebug($status){
		$this->debug = $status;
	}
}



?>