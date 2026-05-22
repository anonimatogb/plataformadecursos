
using System.Linq;

namespace Appcurso;

public partial class TelaUsuario : ContentPage
{
    public class UsuarioLista
    {
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
        new List<UsuarioLista>()
        {
            new UsuarioLista
            {
                Nome = "Php",
                Foto = "php2.png"
            },

            new UsuarioLista
            {
                Nome = "Css",
                Foto = "css.png"
            },

            new UsuarioLista
            {
                Nome = "Html",
                Foto = "html.png"
            }
        };

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