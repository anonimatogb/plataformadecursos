
namespace Appcurso;

public partial class TelaProfessor : ContentPage
{
    public static int CursoSelecionado;
    private async void AbrirCurso(
    object sender,
    SelectionChangedEventArgs e)
    {
        var curso =
            e.CurrentSelection
            .FirstOrDefault()
            as CursoProfessor;

        if (curso != null)
        {
            CursoSelecionado =
                curso.Id;


            listacursosprof.SelectedItem =
                null;
        }
    }
    public class CursoProfessor
    {
        public int Id { get; set; }
        public string Nome { get; set; }

        public string Foto { get; set; }
    }
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
        var idProfessor =
    new Database.Service()
    .IdUsuario(MainPage.EmailLog);
        listacursosprof.ItemsSource =
new Database.Service()
    .BuscarCursosProfessor(idProfessor);
    }
    private async void AbrirPagina(
    object sender,
    SelectionChangedEventArgs e)
    {
        await Navigation.PushAsync(new DetalhesProf());
    }
}