using MySqlConnector;

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

    }
}