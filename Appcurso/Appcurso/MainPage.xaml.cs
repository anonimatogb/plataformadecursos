
namespace Appcurso
{
    public partial class MainPage : ContentPage
    {
        public static string EmailLog;
        public void Clean() { resultado.IsVisible = false; }
        public MainPage()
        {
            InitializeComponent();
            resultado.IsVisible = false;
        }
        private void Campos_TextChanged(
        object sender,
        TextChangedEventArgs e)
        {
            if (string.IsNullOrWhiteSpace(txt_email.Text) ||
                string.IsNullOrWhiteSpace(txt_senha.Text))
            {
                Clean();
            }
        }
        private async void Button_Clicked(object sender, EventArgs e)
        {
            Clean();
            var email = txt_email.Text;
            var senha = txt_senha.Text;
            var cargo = new Database.Service().CargoUsuario(email);

            bool existe = new Database.Service()
                .VerificarUsuario(email, senha);

            if (existe == true)
            {
                EmailLog = email;
                Clean();
            }
            if(email != null && senha != null && cargo == "Aluno")
            {
                await Navigation.PushAsync(new TelaUsuario());
            }
            if (email != null && senha != null && cargo == "Professor")
            {
                await Navigation.PushAsync(new TelaProfessor());
            }
            else
            {
                resultado.IsVisible = true;
            }

        }
        private async void IrParaCadastro(object sender, TappedEventArgs e)
        {
            await Navigation.PushAsync(new Cadastro());
        }
    }
}