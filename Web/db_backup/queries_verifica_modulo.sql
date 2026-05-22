-- Verifica se a tabela 'modulo' existe e como está

SHOW TABLES LIKE 'modulo';

-- Mostra a estrutura caso exista
SHOW COLUMNS FROM modulo;

-- Amostra dados
SELECT * FROM modulo ORDER BY id DESC LIMIT 20;

