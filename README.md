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
APP_MONETARY="pt_BR"

#Email Notifications
APP_NOTIFICATION_FROM_EMAIL="from@example.com"
APP_NOTIFICATION_TO_NAME="Engetecnica Admin"
APP_NOTIFICATION_TO_EMAIL="adm@example.com"
#APP_NOTIFICATION_TO_NAME2="Engetecnica Admin 2"
#APP_NOTIFICATION_TO_EMAIL2="adm2@example.com"

SMTP_USER="user@example.com"
SMTP_PASS="userpasshere"
SMTP_HOST="mail.example.com"
SMTP_PORT="465"
SMTP_AUTH="true"
#SMTP_REPLY="user@example.com"

#OneSignal
ONESIGNAL_APPID="825688da-a801-4c3e-9d05-8d643c5af4e7"
ONESIGNAL_APIKEY="YjhiZGU5ZjItMThhNy00M2I3LTk2ZjctMmFmNzY2Mzg5MDIz"
ONESIGNAL_APIURL="https://onesignal.com"
ONESIGNAL_SAFARI_WEB_ID="web.onesignal.auto.44e66786-7e94-4ade-8822-3a1650cda83f"


#Database
#DB_URL=""
#DB_DATABASE_PREFIX=""
#DB_MG_VERSION=""
DB_DRIVER="mysqli"
DB_PORT=33061
DB_HOST="172.17.0.1"
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

## Configurar OneSignal API (Para notificações push)
Crie uma conta e siga as instruções para criar um App ID e uma API key [One Signal](https://app.onesignal.com/apps/825688da-a801-4c3e-9d05-8d643c5af4e7/settings/keys_and_ids).

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


## Deploy CPanel\Git
Para deploy no cpanel, crie um diretorio chamado .ssh na raiz da hospedagem, gere localmente as chaves `id_rsa e id_rsa.pub`, faça
upload dos arquivos para a hospedagem.
No git, nas configurações do repositório em `Settings > Deploy keys`,  crie uma chave deploy tendo como conteúdo o arquivo `id_rsa.pub`.
Feito isso, no Cpanel em `Git Version Control` crie um reporsitório em `Criar`, preencha os campos seguindo o
[tutorial](https://stackoverflow.com/questions/53941990/git-repo-connection-failed-in-cpanel) abaixo ou no link para preencher a `Clone URL`.


> I have added SSH keys to cPanel

Then you should use an SSH URL (git@server:user/repo), not an HTTPS one (which starts with https://...)

See "Guide to Git - Set Up Access to Private Repositories" from the CPanel Knowledge Base.

git clone git@example.com:MyStuff/private-repository.git
You can see here a typical GitHub SSH URL:
```
git@github.com:<account_name>/<repo_name>.git
# or
ssh://git@github.com/<account_name>/<repo_name>.git
```


## Inicia modificação e Upload pro Github

Na master:
```bash
git pull --rebase origin master
git checkout -b nome_da_nova_branch_das_modificacoes
```

Dentro de nome_da_nova_branch_das_modificacoes para enviar pro git hub;
Se quiser ver o que modificou:
```bash
git status
```

Adiciona todos os arquivos modificados:
```bash
git add .
```

Cria pacote (commit):
```bash
git commit -m "Descricao do commit sem caracteres especiais ou cedilha"
```

Para realmente enviar pro Github:
git push origin nome_da_nova_branch_das_modificacoes

E se modificou alguma arquivo depois de enviar pra nova branch (muito cuidado):
```bash
git add .
git commit --amend
#CTRL + X ou Salva o arquivo
git push origin nome_da_nova_branch_das_modificacoes -f
```

Após feito isso, criar um novo pull request no Github, se não houver um, com as modificacoes da branch chamada nome_da_nova_branch_das_modificacoes (esse nome pode ser o que preferir desde que não tenha espaços ou caracteres especiais, regra do git mesmo).

Nos vídeos abaixo fazemos o pull request e o merge (juntar os códigos):

* [Upload pro git hub](https://www.loom.com/share/3dc536b6893f450bbfbe12cfb005fdf7) 
* [Deploy na hospedagem](https://www.loom.com/share/c185dff1a7684c709db557fb46d72006)

## Ilustrações
As images utilizadas são de uso gratuitos de acordo com a lincença [`unDraw`](https://undraw.co/license)
