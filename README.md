![logo](assets/images/icon/logo.png)

# Engetecnica App


<br>

## Como Instalar
Assumindo que tenha php e composer instalados e configurados na sua maquina local ou servidor, e ainda que esteja no diretório raiz da aplicação, execute o comando a seguir para instalar as dependências do projeto via composer.

```bash
composer install
```

<br>

## Configurações
Deve copiar os arquivos exemplo e preencher os arquivos de configuração da aplicação em geral e banco de dados nos respectivos arquivos: 
`application/config/config.php` e `application/config/database.php`.

> `application/config/config.php`

```php
// Parte relevante referente a aplicação, o restante fical como o padrão

/** 
 * App Email Notifications
 */

// Endereço que assume o envio de emails como remetente
//From
$config['notifications_email'] = "mail@exemplo.com";

// Serão notificados em casos de avisos como exemplo o Informe de vencimentos de serviços e seguros e ipva.
 //to 
$config['notifications_address'] = [
    "Adm" => "adm@exemplo.com",
    "Adm2" => "adm2@exemplo.com",
    //...
];


//SendGrid
$config['sendgrid_apikey'] = "SG.j4b03xvNSOapiJF6gD_Hpw.QD4P607I3G9D4UioplkaeTnhCBCIY4nB1eEasSHQRHTE";

```

> `application/config/database.php`

```php
$active_group = 'default';
$query_builder = TRUE;


$db['default'] = array(
	//'dsn'	=> '',
	'hostname' => 'db',
	'username' => 'root',
	'password' => 'root',
	'database' => 'engetecnica',
    'port' => 3306, 
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'autoinit' => TRUE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
```

<br>

## Migrações do Banco de Dados
Temos a facilidade de criar e executar migrações para o banco de dados.

Para Criar um arquivo de migração execute o comando a seguir assumindo que esteja no diretório raiz do projeto:

### Criando Arquivos de Migrations
```bash
php index.php migrate create <nome_da_migration> <nome_da_tabela_no_banco>
```


### Rodando Migrations
Após devidamente criados os arquivos de migração desejados, execute o comando a seguir para de fato migrar o bando de dados.

> Muita atenção ao executar esse comando, tem certeza de tudo esteja correto nos arquivos de migração

```bash
php index.php migrate
```
Ou

```bash
php index.php migrate index
```
Ambos tem o mesmo resultado.


### Retornando Migrations
Execute o comando `rollback` para voltar uma ou mais migrações.
```bash
php index.php migrate rollback <migration_version>
```
> Muita atenção ao executar esse comando, dados podem ser perdidos ao voltar migrações

* migration_version = um número que se refere a data e hora de criação do arquivo de migração, esse faz parte do nome do proprio arquivo assim que criar com com o comando create.
Fica algo como `20210902070213` e o nome completo fica algo parecido com: `20210902070213_nome_da_minha_migration.php`


### Estrutura de uma Migration
As migrações consistem em dois métodos básicos, `up` e `down`,
como o nome sugere e se refere a upgrade e downgrade do banco de dados.

```php
class Migration_Nome_Da_Migration extends CI_Migration {
	private $table = 'nome_da_tabela';

	public function up(){
        //Executado ao migrar a versão (migrate)
	}

	public function down(){
	    //Executado ao retonar migração (rollback)
	}
}
```

O techo de código a seguir representa a inserção de um usuário no banco de dados atravez de um arquivo de migração, esse usuário também é removido em caso de error ou rollback.

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_Usuario_Default extends CI_Migration {
	private $table = 'usuario';

	//Executado ao migrar a versão (migrate)
	public function up(){
		if ($this->db->table_exists($this->table) && 
            $this->db->where("usuario='engetecnica'")->get($this->table)->num_rows() == 0) {
			/**
			 * Usuario: engetecnica
			 * Senha: 123456
			 * Nivel: Administrador
			*/
			
			$this->db->query(
				"INSERT INTO `usuario` 
				VALUES (1,1,1,'engetecnica','7c4a8d09ca3762af61e59520943dc26494f8941b','2020-08-13 15:58:49',1,'0');"
			);
		}
	}

	//Executado ao retonar migração (rollback)
	public function down(){
		if ($this->db->table_exists($this->table) && 
            $this->db->where("usuario='engetecnica'")->get($this->table)->num_rows() == 1) {
			$this->db->query("DELETE FROM {$this->table} WHERE usuario='engetecnica';");
		}
	}
}
```
 

<br>

## OneSignal API
Crie uma conta e siga as instruções para criar um App ID e uma API key [One Signal](https://app.onesignal.com/apps/825688da-a801-4c3e-9d05-8d643c5af4e7/settings/keys_and_ids).

<br>

## SendGrid API

Crie uma conta e siga as instruções para criar uma API key [SendGrid](https://app.sendgrid.com/settings/api_keys).

<br>

## Executando Projeto em Desenvolvimento
### Com PHP Web server 

Para utilizar o PHP Web server.
```bash
composer dev
```
### Com Docker (Apache e Mysql containers)
Para utilizar com o Docker (assumindo que tenha instalado) com containers Apache e Mysql, Se não existir um volume chamado `engetecnica` crie como o commando a seguir: 

```bash
docker volume create engetecnica
```
e inicie o ambiente de desenvolvimento com:

```bash
composer dev:docker
```
Ou


```bash
docker-compose up --build
```

Ambos tem o mesmo resultado.


## :)

Se tudo correu como o esperado, a aplicação de está online localmente em [http://localhost:8000](http://localhost:8000).