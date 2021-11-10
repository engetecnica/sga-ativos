<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Database extends CI_Migration {
	private $database;

	public function __construct()
	{
		parent::__construct();
		$this->database = getenv('DB_DATABASE') !== null ? getenv('DB_DATABASE') : 'engetecnica';
	}
	
    //Upgrade migration
	public function up(){
		$this->dbforge->createDatabase($this->database, true);
	}
	
    //Downgrade migration
	public function down(){
		$this->dbforge->dropDatabase($this->database, true);
	}
}