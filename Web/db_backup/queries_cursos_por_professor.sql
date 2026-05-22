-- Verifica quais cursos existem para um professor (troque o id)

SELECT *
FROM cursos
WHERE professor = 1; -- <-- altere para o $_SESSION['id'] do professor

