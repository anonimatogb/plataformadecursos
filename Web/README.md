# Plataforma de Cursos — Documentação da Parte Web (PHP)


## 1) Visão geral
A parte web do projeto é uma aplicação PHP que segue uma organização parecida com **MVC**:

- **`controller/`**: recebe dados da View, chama lógica do Model e retorna resultados.
- **`model/`**: faz acesso ao banco (via **PDO**) e contém consultas/updates.
- **`view/`**: páginas públicas/semipúblicas que renderizam HTML e tratam POST/GET.
- **`db/`**: configuração de conexão com MySQL.
- **`css/` e `IMG/`**: estilos e imagens.

O sistema usa **`session_start()`** para manter o usuário logado e usa `$_SESSION['cargo']` para decidir o destino do usuário e/ou quais ações são permitidas (aluno/professor/admin).

---

## 2) Estrutura de pastas

- `Web/controller/`
  - `UsuarioController.php`
  - `CursosController.php`
  - `ModuloController.php`
  - `MatriculaController.php`

- `Web/model/`
  - `UsuarioModel.php`
  - `CursosModel.php`
  - `ModuloModel.php`
  - `MatriculaModel.php`

- `Web/view/`
  - `Index.php` (Login)
  - `PaginaInicial.php` (Home aluno)
  - `Professor.php` (Área professor)
  - `Admin.php` (Área admin)
  - Páginas de cadastro/edição:
    - `CadastrarUsuario.php`, `EditarUsuario.php`
    - `CadastrarCurso.php`, `Editarcurso.php`, `DeletarCurso.php`
    - `CadastroModulo.php`, `deletarmodulo.php`
  - Páginas de matrícula/conteúdo:
    - `Matricular.php`, `Meus.php`, `Detalhes.php`
  - Outras: `Assinar.php`, `Continuar.php`, `Download.php`, `Trans.php`, `logout.php`

- `Web/db/`
  - `database.php` (conexão via PDO)

- `Web/db_backup/`
  - `plataformacursos.sql` (backup/DDL do banco)

- `Web/css/`
  - `style.css`, `style2.css`

- `Web/IMG/`
  - imagens do layout (logo, ícones, fotos)

---

## 3) Conexão com o banco — `db/database.php`
Arquivo: `Web/db/database.php`

- Cria um `PDO` para MySQL.
- Configura modo de erro:
  - `PDO::ATTR_ERRMODE = PDO::ERRMODE_EXCEPTION`

A aplicação depende que a variável `$pdo` esteja disponível (normalmente as Views carregam `database.php`).

---

## 4) Como funciona (MVC)

### 4.1) Model (acesso aos dados)
Cada Model encapsula consultas/updates do banco.

Exemplos principais:
- **`CursosModel.php`**
  - `buscarTodos()` → lista cursos
  - `todosaluno($alunoId)` → cursos do aluno (via `matriculas` JOIN `cursos`)
  - `buscarcursos($id)` → cursos de um professor
  - `cadastrarcursos(...)` → INSERT em `cursos`
  - `atualizar(...)` → UPDATE em `cursos`
  - `desativar($id)` → desativa curso e também desativa `modulo` e `matriculas` relacionados

- **`ModuloModel.php`**
  - `cadastrar(...)` → INSERT em `modulo`
  - `porprof($professor_id)` → lista módulos vinculados aos cursos daquele professor
  - `porcurso($cursoId)` → lista módulos de um curso
  - `desativar($id)` → UPDATE `modulo.ativo`

- **`MatriculaModel.php`**
  - `matricular($aluno_id, $cursos_id, $professor_id)` → INSERT em `matriculas`
  - `concluirCurso($matriculaId)` → marca `concluido = 1`
  - `relacao()` → lista todas matrículas com JOIN (aluno x curso)

- **`UsuarioModel.php`**
  - `cadastrar(...)` → INSERT em `usuarios`
  - `login($email, $senha)` → SELECT por email e senha
  - `atualizar(...)` → UPDATE em `usuarios`
  - `trans($id)` → altera `cargo` para `admin`

---

### 4.2) Controller (ponte entre View e Model)
O Controller cria o Model com o `$pdo` e oferece métodos “de negócio” para as Views chamarem.

Exemplos principais:
- `UsuarioController`
  - `login($email, $senha)`
  - `cadastrar(...)`
  - `buscarUsuario($id)`
  - `atualizar(...)`
  - `trans($id)`

- `CursosController`
  - `todos()`, `buscarum($id)`, `todosprof($id)`, `cadastrar(...)`, `atualizar(...)`, `desativar($id)`, `todosaluno($id)`

- `ModuloController`
  - `cadastrar($titulo, $cursos_id, $video)` (usa `$_SESSION['id']` como professor)
  - `porprof($professor_id)`, `porcurso($cursoId)`, `desativar($id)`, `todos()`

- `MatriculaController`
  - `matricular(...)`, `confe($id)`, `macho($id)`, `relacao()`, `concluirCurso($matriculaId)`

---

### 4.3) View (páginas e fluxo do usuário)
As Views geralmente:
1. Iniciam sessão (`session_start();`)
2. Fazem `require_once` de controller e `database.php`
3. Lêem dados de `$_POST` (formulários) ou parâmetros de URL
4. Chamam métodos do Controller
5. Mostram HTML ou redirecionam com `header("Location: ...")`

---

## 5) Fluxo de Login (exemplo)
Arquivo: `Web/view/Index.php`

1. A página recebe `email` e `senha` via POST.
2. Instancia `UsuarioController($pdo)` e chama `UsuarioController->login($email, $senha)`.
3. Se login der certo, define em sessão:
   - `$_SESSION['cargo']`
   - `$_SESSION['nome']`
   - `$_SESSION['id']`
4. Redireciona por cargo:
   - `aluno` → `Paginainicial.php`
   - `professor` → `professor.php`
   - `admin` → `admin.php`

---

## 6) Endpoints/Funcionalidades por área

### 6.1) Aluno
- Ver cursos e/ou matrículas (ex.: `PaginaInicial.php`, `Meus.php`)
- Matrícula (ex.: `Matricular.php`, conforme regra da aplicação)
- Detalhes/conteúdo do curso (ex.: `Detalhes.php`)

### 6.2) Professor
- Criar curso (em páginas de cursos)
- Criar módulos para cursos (ex.: `CadastroModulo.php`)
- Visualizar módulos (por curso/professor)

### 6.3) Admin
- Visualizar/gerenciar usuários
- Visualizar matrículas (via relação)
- Possivelmente alterar permissões (ex.: `Trans.php`)

---

## 7) Backup/DDL do banco
Arquivo: `Web/db_backup/plataformacursos.sql`

Use para criar/restaurar tabelas e dados base do projeto.

---

## 8) Observações importantes do código (para estudo)
- A autenticação de `login` ocorre comparando senha diretamente com o valor no banco (como implementado no `UsuarioModel`).
- Existem trechos SQL usando concatenação direta com variáveis em alguns métodos (ex.: consultas com `$id` em `query(...)`). Para produção, o recomendado é padronizar tudo para consultas preparadas.

> Esta seção é apenas para entendimento acadêmico do que o código faz hoje.

---

## 9) Como rodar (resumo)
1. Coloque a pasta `Web/` no servidor PHP (ex.: XAMPP).
2. Crie o banco usando `Web/db_backup/plataformacursos.sql`.
3. Ajuste `Web/db/database.php` com host/port/dbname/usuário/senha do seu ambiente.
4. Acesse a URL de `Web/view/Index.php` (login).

---
## 10) Feedback

Vitor Cardoso: Falta muito para ser ruim
Dhiogo Marestoni: Falta css
Vitor Hugo: css ruim


### Arquivo principal desta documentação
- `Web/README.md`
