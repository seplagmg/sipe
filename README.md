Bem vindos ao sistema SIPE - Sistema de indexação de pastas eletrônicas. Este é um sistema construído em PHP/MySQL para indexar documentos armazenados no SEI formando "pastas funcionais" virtuais para substituir as pastas funcionais físicas. Este sistema foi criado pela Secretaria de Estado de Planejamento e Gestão do Governo de Minas Gerais. O sistema foi construído usando o framework CodeIgniter 3, com alguns frameworks abertos de javascript.

A instalação se dá manualmente. Até o momento, não construímos a instalação via docker para ele. A homologação desta instalação se deu em um ambiente Debian 10. Para outros ambientes o desenvolvedor irá precisar adaptar alguns dos passos e dependências utilizadas.

## SMTP

Tanto o cadastro de usuário, quanto a recuperação de senha dependem de disparos de email. O sistema está utilizando o SMTP para realizar o envio de email. Como isso é uma parte central do sistema, o desenvolvedor deve alterar as credenciais utilizadas para um servidor na qual ele tenha controle.


## Criptografia das senhas no banco

O CodeIgniter, framework utilizado na construção do sistema, utiliza uma biblioteca própria para criptografar e descriptografar as senhas. O desenvolvedor pode alterar a chave de criptografia utilizada no processo alterando a configuração `encryption_key`, no arquivo `application/config/config.php`.

## Usuário administrador

Para alterar um usuário para administrador, o desenvolvedor pode fazer isso via sql:

update tb_usuarios set es_perfil=3 where pr_usuario=<id_do_usuario_aqui>;


## Server

O servidor utiliza as seguintes dependências:

	- php7.3
	- php-fpm
	- php-pgsql
	- php-mbstring 
	- php-curl 
	- php7.3-mysql 
	- nginx 
	- sendmail

### Variáveis de ambiente

É necessário cadastrar os parâmetros de banco de dados e da aplicação na pasta '/application/configs', arquivos 'database.php' e 'custom.php'. 
Para fazer o webservice do SEI funcionar é necessário solicitar esse acesso para a equipe responsável. Esse parâmetro também deve ser informado no arquivo citado acima.


## Usuários

No script de criação da estrutura do banco de dados existe a criação de um usuário inicial: 
 
Login: 99999999999
Senha: teste123


## Detalhamento final

O sistema foi criado com base na lógica de processos do Poder Executivo de Minas Gerais. Nesse sentido, várias nomenclaturas e funcionalidades são específicas e devem ser adaptadas internamente. Exemplos:

- O campo MASP significa a matrícula (código numérico) de cadastro dos servidores públicos.
- O campo admissão é um contador numérico do vínculo do empregado. No SIPE, cada pasta funcional varia de acordo com o MASP e a admissão. Servidores com mais de uma admissão possuem uma pasta por admissão.
- O sistema foi criado para ser uma única instalação centralizada para várias instituições do Poder Executivo mineiro e não uma instalação por instituição. Nesse sentido, várias funcionalidades refletem essa realidade. Em caso de implantações isoladas (para uma instituição única), algumas adaptações serão necessárias.

É fundamental avaliar o manual de usuário do sistema para se ter maior contato com as funcionalidades.
