defined('BASEPATH') OR exit('No direct script access allowed');

class <?php echo $class_name; ?> extends CI_Migration {
	private $table = '<?php echo $table_name; ?>';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			// Outras colunas
			->create_table($this->table);
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table)) {
			$this->dbforge->drop_table($this->table);
		}
	}
}