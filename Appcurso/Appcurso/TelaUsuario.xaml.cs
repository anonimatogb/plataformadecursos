
using System.Linq;
using static Appcurso.TelaProfessor;

namespace Appcurso;

public partial class TelaUsuario : ContentPage
{
    public static int CursoSelecionado;
    private async void AbrirCurso(
    object sender,
    SelectionChangedEventArgs e)
    {
        var cursoSelecionado =
            e.CurrentSelection
            .FirstOrDefault()
            as CursoLista;

        if (cursoSelecionado == null)
            return;

        CursoSelecionado =
            cursoSelecionado.Id;


        await Navigation.PushAsync(
            new Detalhes());

        listaUsuarios.SelectedItem =
            null;
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
        string foto =
    new Database.Service()
    .FotoPerfilUsuario(idUsuario);

        if (foto != null)
        {
            imguser.Source = foto;
        }
        else
        {
            imguser.Source = "usuario.png"; 
        }
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

   
    private async void ImagemClicada(
        object sender,
        TappedEventArgs e)
    {
        await Navigation.PushAsync(new Detalhes());
    }
}