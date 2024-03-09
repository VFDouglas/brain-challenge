# Brain Challenge

### Also available in [English](https://github.com/VFDouglas/brain-challenge)

## Sobre o projeto

Projeto pessoal para o Trabalho de Conclusão de Curso (TCC) da pós-graduação MBA em Desenvolvimento Web Full Stack, pela PUC-MG.

O projeto consite em sistema web que possibilita criar seu próprio ambiente de gamificação.
O foco principal aqui é encorajar usuários a resolver tarefas e ganhar pontos/prêmios por isso.

Pode ser usado por uma empresa, escola ou outra organização que precisa construir um ambiente
para melhorar seus processos.

## Como utilizar o sistema

O projeto pode ser acessado através do link: http://144.22.158.0.

Existem três tipos de usuários:

- Estudantes
- Professores
- Administradores

## Alunos

- Login: estudante@student.com
- Senha: estudante

É aquele que vai estudar e resolver tarefas.

Os alunos podem ir até os professores e marcar presença em seus estandes, ver o andamento
de suas tarefas, confira as apresentações que podem assistir, os horários, veja seus prêmios
e também responder às dúvidas das apresentações que assistem.

## Professores

- Login: professor@professor.com
- Senha: professor

É ele quem vai criar apresentações e perguntas para ele.

Durante o evento, eles poderão ter alguns estandes e compartilhar códigos QR para os alunos marcarem
seu comparecimento.

## Administradores

- Login: admin@admin.com
- Senha: administrador

Os responsáveis pela gestão do evento.

Eles podem criar os eventos, professores, alunos e tudo mais no sistema.
Todos os recursos podem ser gerenciados através do painel de controle do administrador.

## Guia de instalação

Clone o projeto e entre no diretório:
```
git clone https://github.com/VFDouglas/brain-challenge.git && cd brain-challenge
```
Crie o arquivo `.env`:
```
cp .env.example .env
```
Execute os comandos:
```
docker compose up --build -d

# Entrar no contêiner
docker compose exec php sh

composer install
php artisan key:generate
npm ci && npm run dev
```
