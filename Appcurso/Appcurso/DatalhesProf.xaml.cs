namespace Appcurso;

public partial class DetalhesProf : ContentPage
{
    public DetalhesProf()
    {
        InitializeComponent();
        var idCurso =
    TelaProfessor.CursoSelecionado;
        var imagem = new Database.Service().FotoCurso(idCurso);
        var nomecurso = new Database.Service().NomeCurso(idCurso);
        var descricaocurso = new Database.Service().DescricaoCurso(idCurso);
        foto.Source= imagem;
        nome.Text= nomecurso;
        descricao.Text = descricaocurso;
    }
        private async void Deslogar(object sender, TappedEventArgs e)
    {
        await Navigation.PushAsync(new MainPage());
    }
    private async void Voltar_Clicked(object sender, TappedEventArgs e)
    {
        await Navigation.PushAsync(new TelaProfessor());
    }

}
