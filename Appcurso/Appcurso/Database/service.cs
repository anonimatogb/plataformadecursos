using MySqlConnector;
using System.Globalization;
using static Appcurso.TelaUsuario;

namespace Appcurso.Database
{
    public class Service
    {
        private string _connectionString;

        public Service()
        {
            _connectionString =
                "Server=localhost;Database=plataformacursos;User=root;Password=;SslMode=None;";
        }

        public void InserirUsuario(string nome, int idade)
        {
            using var connection =
                new MySqlConnection(_connectionString);

            connection.Open();

            using var cmd = new MySqlCommand(
                "INSERT INTO usuarios (nome, idade) VALUES (@nome, @idade)",
                connection);

            cmd.Parameters.AddWithValue("@nome", nome);
            cmd.Parameters.AddWithValue("@idade", idade);

            cmd.ExecuteNonQuery();
        }

        public bool VerificarUsuario(string email, string senha)
        {
            using var connection =
                new MySqlConnection(_connectionString);

            connection.Open();

            using var cmd = new MySqlCommand(
                "SELECT COUNT(*) FROM usuarios WHERE email = @email AND senha = @senha",
                connection);

            cmd.Parameters.AddWithValue("@email", email);
            cmd.Parameters.AddWithValue("@senha", senha);

            long count = (long)cmd.ExecuteScalar();

            return count > 0;
        }

        public void InserirUsuario(string nome, string email, string senha, string cargo)
        {
            using var connection =
                new MySqlConnection(_connectionString);
            connection.Open();
            using var cmd = new MySqlCommand(
                "INSERT INTO usuarios (nome, email, senha, cargo) VALUES (@nome, @email, @senha, @cargo)",
                connection);
            cmd.Parameters.AddWithValue("@nome", nome);
            cmd.Parameters.AddWithValue("@email", email);
            cmd.Parameters.AddWithValue("@senha", senha);
            cmd.Parameters.AddWithValue("@cargo", cargo);
            cmd.ExecuteNonQuery();
        }
        public string NomeUsuario(string email)
        {
            using var connection =
                new MySqlConnection(_connectionString);
            connection.Open();
            using var cmd = new MySqlCommand(
        "SELECT nome FROM usuarios WHERE email = @email",
        connection);

            cmd.Parameters.AddWithValue("@email", email);

            var resultado = cmd.ExecuteScalar();

            if (resultado != null)
            {
                return resultado.ToString();
            }

            return "";
        }
        public int IdUsuario(string email)
        {
            using var connection =
                new MySqlConnection(_connectionString);

            connection.Open();

            using var cmd = new MySqlCommand(
                "SELECT id FROM usuarios WHERE email = @email",
                connection);

            cmd.Parameters.AddWithValue(
                "@email",
                email);

            var resultado =
                cmd.ExecuteScalar();

            if (resultado != null)
            {
                return Convert.ToInt32(
                    resultado);
            }

            return 0;
        }
        public string CargoUsuario(string email)
        {
            using var connection =
                new MySqlConnection(_connectionString);
            connection.Open();
            using var cmd = new MySqlCommand("SELECT cargo FROM usuarios WHERE email = @email",
        connection);

            cmd.Parameters.AddWithValue("@email", email);

            var resultado = cmd.ExecuteScalar();

            if (resultado != null)
            {
                return resultado.ToString();
            }

            return "";
        }
        public List<CursoLista> BuscarCursos()
        {
            var lista = new List<CursoLista>();

            using var connection =
                new MySqlConnection(_connectionString);

            connection.Open();

            using var cmd = new MySqlCommand(
                "SELECT nome, fotocapa FROM cursos",
                connection);

            using var reader = cmd.ExecuteReader();

            while (reader.Read())
            {
                lista.Add(new CursoLista
                {
                    Nome = reader["nome"].ToString(),
                    Foto = reader["fotocapa"].ToString()
                });
            }

            return lista;
        }
        public List<CursoLista> BuscarCursosProfessor(
    int professorId)
        {
            var lista = new List<CursoLista>();

            using var connection =
                new MySqlConnection(_connectionString);

            connection.Open();

            using var cmd = new MySqlCommand(
                @"SELECT nome, fotocapa
          FROM cursos
          WHERE professor = @professor",
                connection);

            cmd.Parameters.AddWithValue(
                "@professor",
                professorId);

            using var reader =
                cmd.ExecuteReader();

            while (reader.Read())
            {
                lista.Add(new CursoLista
                {
                    Nome =
                        reader["nome"].ToString(),

                    Foto =
                        reader["fotocapa"].ToString()
                });
            }

            return lista;
        }
        public List<int> AlunosCurso(int idCurso)
        {
            var alunos = new List<int>();

            using var connection =
                new MySqlConnection(_connectionString);

            connection.Open();

            using var cmd = new MySqlCommand(
                "SELECT aluno_id FROM matriculas WHERE cursos_id = @curso_id",
                connection);

            cmd.Parameters.AddWithValue("@curso_id", idCurso);

            using var reader = cmd.ExecuteReader();

            while (reader.Read())
            {
                alunos.Add(
                    Convert.ToInt32(reader["aluno_id"]));
            }

            return alunos;
        }
        public string FotoCurso(int idCurso)
        {
            using var connection =
                new MySqlConnection(_connectionString);
            connection.Open();
            using var cmd = new MySqlCommand(
        "SELECT fotocapa FROM cursos WHERE id = @id",
        connection);

            cmd.Parameters.AddWithValue("@id", idCurso);

            var resultado = cmd.ExecuteScalar();

            if (resultado != null)
            {
                return resultado.ToString();
            }

            return "";
        }
        public string NomeCurso(int idCurso)
        {
            using var connection =
                new MySqlConnection(_connectionString);
            connection.Open();
            using var cmd = new MySqlCommand(
        "SELECT nome FROM cursos WHERE id = @id",
        connection);

            cmd.Parameters.AddWithValue("@id", idCurso);

            var resultado = cmd.ExecuteScalar();

            if (resultado != null)
            {
                return resultado.ToString();
            }

            return "";
        }
        public string DescricaoCurso(int idCurso)
        {
            using var connection =
                new MySqlConnection(_connectionString);
            connection.Open();
            using var cmd = new MySqlCommand(
        "SELECT descricao FROM cursos WHERE id = @id",
        connection);

            cmd.Parameters.AddWithValue("@id", idCurso);

            var resultado = cmd.ExecuteScalar();

            if (resultado != null)
            {
                return resultado.ToString();
            }

            return "";
        }
        public int CargaCurso(int idCurso)
        {
            using var connection =
                new MySqlConnection(_connectionString);
            connection.Open();
            using var cmd = new MySqlCommand(
        "SELECT carga_horaria FROM cursos WHERE id = @id",
        connection);

            cmd.Parameters.AddWithValue("@id", idCurso);

            var resultado = cmd.ExecuteScalar();

            if (resultado != null)
            {
                return Convert.ToInt32(resultado);
            }

            return 0;
        }
        public string Professor(int idCurso)
        {
            using var connection =
                new MySqlConnection(_connectionString);

            connection.Open();

            using var cmd = new MySqlCommand(
                "SELECT professor FROM cursos WHERE id = @id",
                connection);

            cmd.Parameters.AddWithValue(
                "@id",
                idCurso);

            var resultado =
                cmd.ExecuteScalar();

            if (resultado != null)
            {
                int idProfessor =
                    Convert.ToInt32(resultado);

                using var cmd2 =
                    new MySqlCommand(
                        "SELECT nome FROM usuarios WHERE id = @idProfessor",
                        connection);

                cmd2.Parameters.AddWithValue(
                    "@idProfessor",
                    idProfessor);

                var nomeProfessor =
                    cmd2.ExecuteScalar();

                if (nomeProfessor != null)
                {
                    return nomeProfessor.ToString();
                }
            }

            return "";
        }
    }
}