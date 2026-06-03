namespace Appcurso;

public partial class Cadastro : ContentPage
{
    public static string NomeCad;
    public Cadastro()
    {
        InitializeComponent();
    }
    private async void CadastrarUsuario(object sender, EventArgs e)
    {
        var nome = txt_nome.Text;
        var email = txt_emailcadastro.Text;
        var senha = txt_senhacadastro.Text;
        var cargo = txt_cargo.SelectedItem?.ToString();
        new Database.Service()
            .InserirUsuario(nome, email, senha, cargo);
        NomeCad = nome;
        if (cargo == "Aluno")
        {
            await Navigation.PushAsync(new Pagamento());
        }
        
    }
}