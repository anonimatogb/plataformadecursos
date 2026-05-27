
using System.Linq;

namespace Appcurso;

public partial class TelaUsuario : ContentPage
{
    public class CursoLista
    {
        public int Id { get; set; }
        public string Nome { get; set; }

        public string Foto { get; set; }

    }
    public TelaUsuario()
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
		
		BemVindo.Text = $"Bem vindo " + nome;
        listaUsuarios.ItemsSource =
new Database.Service()
    .BuscarCursos();

    }
    private async void AbrirPagina(
    object sender,
    SelectionChangedEventArgs e)
    {
        await Navigation.PushAsync(new Detalhes());
    }
    private async void Deslogar(object sender, TappedEventArgs e)
    {
        await Navigation.PushAsync(new MainPage());
    }

}