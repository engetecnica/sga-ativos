![logo](assets/images/icon/logo.png)

# Engetecnica App


<br>

## Como Instalar
Assumindo que tenha php e composer instalados e configurados na sua maquina local ou servidor, e ainda que esteja no diretório raiz da aplicação, <br>execute o comando a seguir para instalar as dependências do projeto via composer.

```bash
composer install
```

<br>

## Configurações
Deve copiar o arquivo de exemplo `.env.example` e preencher o arquivo de configuração `.env` na raiz da aplicação.

> `.env`

```bash
#App
CI_ENV="development"
APP_NAME="Engetecnica App"
APP_UPLOAD_MAX_SIZE=50
APP_TIMEZONE="America/Sao_Paulo"


#Email Notifications
APP_NOTIFICATION_FROM_EMAIL="app@mail.com"
APP_NOTIFICATION_TO_NAME="Administrador"
APP_NOTIFICATION_TO_EMAIL="adm@mail.com"
APP_NOTIFICATION_TO_NAME2="Administrador 2"
APP_NOTIFICATION_TO_EMAIL2="adm2@mail.com"
#APP_NOTIFICATION_TO_NAMELX ...
#APP_NOTIFICATION_TO_EMAILX ...

#OneSignal
ONESIGNAL_APPID="825688da-a801-4c3e-9d05-789uythgrcxv"
ONESIGNAL_APIKEY="YjhiZGU5ZjItMThhNy19Z2I3LTk2ZjctMmFmNzY2Mzg5MDIz"
ONESIGNAL_APIURL="https://onesignal.com"

#SendGrid
SENDGRID_APIKEY="SG.KT_MWSDd3SjCINrr6mYYrIg.Z_R0RCXgIUhGjE-qANTvp5MlenN-ObhtVL6HjRMQ8k4"

#Database
#DB_URL=""
DB_DRIVER="mysqli"
DB_PORT=33061
DB_HOST="localhost"
DB_USER="root"
DB_PASS="root"
DB_DATABASE="engetecnica"
#DB_DATABASE_PREFIX=""
#DB_MG_VERSION=""
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

* migration_version = um número que se refere a data e hora de criação do arquivo de migração, esse faz parte do nome do proprio arquivo assim que criar com com o comando create.<br>
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
docker volume create engetecnica/
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


## Automações
Deve ser executada uma chamada uma vez ao dia o endpoint [`/app/automacoes`](http://localhost:8000/app/automacoes)

* Rodar o comando Contab abaixo:
```
contab -e
```
* Adicionar a linha a baixo ao final do arquivo e salvar com CTLR+x em seguida Y (Yes/Sim):
```
00 00 * * * bash <path>/engetecnica/setup/automations.sh
```
> \<path\> :  Caminho para o dieretório raiz da aplicação


## :)

Se tudo correu como o esperado, a aplicação de está online localmente em [http://localhost:8000](http://localhost:8000).


## Ilustrações
As images utilizadas são de uso gratuitos de acordo com a lincença [`unDraw`](https://undraw.co/license)