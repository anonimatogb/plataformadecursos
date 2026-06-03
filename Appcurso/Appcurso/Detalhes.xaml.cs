namespace Appcurso;

public partial class Detalhes : ContentPage
{
	public Detalhes()
	{
		InitializeComponent();
        var idCurso =
    TelaProfessor.CursoSelecionado;
        var imagem = new Database.Service().FotoCurso(idCurso);
        var nomecurso = new Database.Service().NomeCurso(idCurso);
        var descricaocurso = new Database.Service().DescricaoCurso(idCurso);
        var nomeprofessor = new Database.Service().Professor(idCurso);
        FotoCurso.Source = imagem;
        NomeCurso.Text = nomecurso;
        DescricaoCurso.Text = descricaocurso;
        Professor.Text= nomeprofessor;
    }
    private async void Deslogar(object sender, TappedEventArgs e)
    {
        await Navigation.PushAsync(new MainPage());
    }
    private async void Voltar(object sender, TappedEventArgs e)
    {
        await Navigation.PushAsync(new TelaProfessor());
    }
}
