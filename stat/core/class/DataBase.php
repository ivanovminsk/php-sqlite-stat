<?php

class DB extends PDO
{
    public $error = true; // выводить сообщения об ошибках на экран? (true/false)
    
    public function __construct($dsn, $username='', $password='', $driver_options=array())
    {
		
		global $dberror;
		
		try {
            parent::__construct('sqlite:' . $dsn, $username, $password, $driver_options);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->exec('PRAGMA journal_mode = MEMORY;');
			$this->exec('PRAGMA synchronous = OFF;');
			$this->exec('PRAGMA PAGE_SIZE = 4096;');
			$this->exec('PRAGMA busyTimeout = 7000;');
			$this->exec('PRAGMA encoding = "UTF-8";');
			$this->exec('PRAGMA count_changes = off;');
			$this->exec('PRAGMA temp_store = MEMORY;');
			$this->exec('PRAGMA foreign_keys = true;');
			$this->exec('PRAGMA locking_mode = exclusive;');
			$this->exec('BEGIN IMMEDIATE;');
        }
        catch(PDOException $e) {
			$dberror = "Произошла ошибка в работе с базой данных... Не удалось подключиться ...";
			getdberror();
			echo $dberror;
            exit();
        }
    }
    
    public function prepare($sql, $driver_options=array())
    {
		try {
            return parent::prepare($sql, $driver_options);
        }  
        catch(PDOException $e) {  
            $this->error($e->getMessage());
        }
    }
    
    public function query($sql)
    {
		try {
			return parent::query($sql);
        }  
        catch(PDOException $e) {  
            $this->error($e->getMessage());
        }
    }
    
    public function exec($sql)
    {
		try {
			return parent::exec($sql);
        }  
        catch(PDOException $e) {  
            $this->error($e->getMessage());
        }
    }
    
    public function error($msg)
    {
        if($this->error)
        {
            $dberror = $msg;
			getdberror();
			echo $msg;
        }
        else
        {
			$dberror = "Произошла непонятная ошибка в работе с базой данных...";
			echo $dberror;
			getdberror();
        }
        
        exit();
    }
	
	public function dbexit()
    {
        try {
            return parent::exec('COMMIT;');
			$this->null;
        }  
        catch(PDOException $e) {  
            sleep(1);
			return parent::exec('COMMIT;');
			$this->error($e->getMessage());
			sleep(3); // ??
			return parent::exec('COMMIT;'); // ??
			$this->null; // ??
        }
    }
	
}

function dbconnect()
{

	global $spath, $dbs;

	$dbpath = $spath . '/db/';
	
	$dbname = preg_replace("/^http:\/\/|www\d{0,3}[.]|[\w\d\.\-_]*\//","",$_SERVER['SERVER_NAME']);

	$dbstatname = $dbname . '_stat.dtf';
	
	$dbs = new DB($dbpath . $dbstatname);

	if (!file_exists($dbpath . $dbstatname . '.dtf'))
	{
		include_once $spath . '/core/inc/install.php';
	}

}

function dbclose()
{
	global $dbs;
	
	$dbs->dbexit();
	
}

?>
