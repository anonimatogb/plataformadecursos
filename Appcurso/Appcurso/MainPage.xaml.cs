
namespace Appcurso
{
    public partial class MainPage : ContentPage
    {
        public MainPage()
        {
            InitializeComponent();
        }

        private async void Button_Clicked(object sender, EventArgs e)
        {
            bool existe = new Database.Service()
                .VerificarUsuario(txt_email.Text, txt_senha.Text);

            if (.VerificarUsuario == true)
            {
                await DisplayAlert("Sucesso", "Login bem-sucedido!", "OK");
            }
            else
            {
                await DisplayAlert("Erro", "Email ou senha incorretos.", "OK");
            }
        }
    }
}