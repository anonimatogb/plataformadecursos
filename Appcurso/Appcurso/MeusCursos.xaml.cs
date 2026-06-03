namespace Appcurso;

public partial class MeusCursos : ContentPage
{
	public MeusCursos()
	{
		InitializeComponent();
        var service =
        new Database.Service();

        var alunoId =
            service.IdUsuario(
                MainPage.EmailLog);

        matriculas.ItemsSource =
            service.BuscarMatriculas(
                alunoId);
    }
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

        TelaUsuario.CursoSelecionado =
            cursoSelecionado.Id;

        await Navigation.PushAsync(
            new Detalhes());

        matriculas.SelectedItem =
            null;
    }
}