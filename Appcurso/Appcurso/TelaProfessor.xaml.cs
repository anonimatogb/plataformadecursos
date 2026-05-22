namespace Appcurso;

public partial class TelaProfessor : ContentPage
{
	public TelaProfessor()
	{
		InitializeComponent();
        var nome = "";
        var emailuser = MainPage.EmailLog;
        var nomelog = new Database.Service().NomeUsuario(emailuser);
        var nomecad = Cadastro.NomeCad;
        if (nomelog == "")
        {
            nome = nomecad;

        }
        else
        {
            nome = nomelog;
        }

        bemvindo.Text = $"Bem vindo Prof " + nome;
    }
}