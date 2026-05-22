

namespace Appcurso;

public partial class Pagamento : ContentPage
{
	public Pagamento()
	{
		InitializeComponent();
	}
    private async void PagamentoButton_Clicked(object sender, EventArgs e)
    {
        var nomecartao= nomenocartao.Text;
        var numerodocartao = numerocartao.Text;
        var datadevalidade = datavalidade.Text;
        var codigodeseguranca = cvv.Text;
        if (nomenocartao != null && numerodocartao != null && datadevalidade != null && codigodeseguranca != null)
        {
            await Navigation.PushAsync(new TelaUsuario());
        }
        
    }
}