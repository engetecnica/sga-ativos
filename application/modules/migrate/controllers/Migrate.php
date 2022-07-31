<?php 

defined("BASEPATH") or exit("No direct script access allowed");

class Migrate extends MY_Controller{

  private $env_error_msg = "";

    public function __construct()
    {
      parent::__construct();
      $this->env_error_msg = "\n\033[1;33mAtenção!\033[0m\n\nDefina o \033[1;34mENVIRONMENT\033[0m modo para desenvolvimento (\033[1;32mdevelopment\033[0m) antes de executar as migrações. 
      É perigoso executar esse procedimento em produção (\033[1;31mproduction\033[0m)\n\n";
    }

    public function index($version = null) 
    {
      if (getenv('CI_ENV') == 'development') {
        $this->load->library("migration");
        $error =  "\n\033[1;31mOcorreu um Erro ao migrar o Banco de Dados\n\n\033[0m";
        $migrations = $this->migration->find_migrations();

        if (count($migrations) == 0) {
          echo "\n\033[1;33mNenhum arquivo de migração criado!\n\n\033[0m";
          echo "Execute o seguninte comando para criar:";
          echo "\nphp index.php migrate create <migration_name>\n\n";
          return;
        }
        
        if ($version == null){
          if(!$this->migration->latest()){
            echo $error;
            show_error($this->migration->error_string());
            return;
          }
        } else {
          if(!$this->migration->version($version)){
            echo $error;
            show_error($this->migration->error_string());
            return;
          }  
        }
        
        if ($this->show(true) > 0) echo "\n\033[1;32mBanco de dados migrado com Sucesso!\033[0m\n";
        return;
      }
      
      echo $this->env_error_msg;   
    }

    public function rollback($version) 
    {
      return $this->index($version);  
    }

    public function create($name = null, $table = null)
    {
      if ($name) {
        $this->load->helper('file');

        $filename =  date('YmdHis');
        $formated_name = str_replace(' ', '_', strtolower($name));
        $migration = sprintf("%s_%s",date('Ymdhis', strtotime('now')),$formated_name );
        $path = sprintf("%s%s",APPPATH,'migrations/');
        $filename = sprintf("%s%s.php",$path, $migration);
        $class_name = sprintf("%s%s",'Migration_',ucwords($formated_name, '_'));

        if (file_exists($filename) || count(glob($path."*_{$formated_name}.php")) == 1) {
          echo "\n\033[1;33m\nUm Arquivo correspondente a {$formated_name} já existe!\033[0m\n\n";
          return;
        }

        $file_data = "<?php\n" . $this->load->view(
          "template", 
          [
            "class_name" => $class_name,
            "table_name" => $table ? $table : 'table_name',
          ], 
          true
        );

        if (!write_file($filename, $file_data)){
          echo "\n\033[1;31mImpossibilitado de gravar o arquivo!\n\n\033[0m";
        }
        else{
          echo "\n\033[1;32mArquivo criado com Sucesso!\033[0m\n\n";
          echo "{$filename}\n\n";
        }
        return;
      }

      echo "\n\033[1;33mAtenção!\033[0m\n\033[1;31mDeve especificar um nome para o arquivo no primeiro parâmetro!\n\033[0m";
      echo "Ex: php index.php migrate create <migration_name>\n\n";
    }



    public function show($afterVersion = false){ 
      echo "\n\033[1;34mMigrations\033[0m\n";

      if(!isset($this->migration)) {
        $this->load->library("migration");
      }
      
      $migrations = $this->migration->find_migrations();
      $total = 0;

      foreach ($migrations as $key => $file) {
        $version = (int) $this->db->from('migrations')->get()->result()[0]->version ?? null;

        if(
          ($afterVersion && $key > $version) ||
          (!$afterVersion && $key < $version) ||
          !$version
        ) {
          $file = explode('migrations', $file)[1];
          $name = ucwords(trim(str_replace(["/{$key}", ".php", "_"], ["", "", " "], $file)));
          echo "\n$key : $name\n";
          $total++;
        }
      }

      if ($total === 0) {
        echo "\n\033[1;31mNenhum arquivo a ser executado!\n\033[0m";
      }

      echo "\nTotal: {$total}\n";
      return $total;
    }
}