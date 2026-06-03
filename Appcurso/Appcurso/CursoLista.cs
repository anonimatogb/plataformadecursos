namespace Appcurso;

public class CursoLista
{
    public int Id { get; set; }

    public string Nome { get; set; }

    public string Foto { get; set; }

    // Descrição curta do curso para exibição
    public string Descricao { get; set; }

    // Nome do professor responsável
    public string ProfessorName { get; set; }
}