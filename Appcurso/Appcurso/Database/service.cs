using MySqlConnector;

namespace Appcurso.Database
{
    public class Service
    {
        private readonly string _connectionString;

        public Service()
        {
        }

        public Service(string connectionString)
        {
            _connectionString = connectionString;
        }

        public void InserirUsuario(string nome, int idade)
        {
            using var connection = new MySqlConnection(_connectionString);
            connection.OpenAsync();

            using var cmd = new MySqlCommand(
                "INSERT INTO usuarios (nome, idade) VALUES (@nome, @idade)",
                connection);

            cmd.Parameters.AddWithValue("@nome", nome);
            cmd.Parameters.AddWithValue("@idade", idade);

            cmd.ExecuteNonQuery();
        }
        public bool VerificarUsuario(string email, string senha)
        {
            using var connection = new MySqlConnection(_connectionString);
            connection.OpenAsync();
            using var cmd = new MySqlCommand(
                "SELECT COUNT(*) FROM usuarios WHERE email = @email AND senha = @senha",
                connection);
            cmd.Parameters.AddWithValue("@email", email);
            cmd.Parameters.AddWithValue("@senha", senha);
            long count = (long)cmd.ExecuteScalar();
            return count > 0;
        }
    }
}
