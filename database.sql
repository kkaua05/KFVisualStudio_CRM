-- Arquivo: database.sql

CREATE DATABASE IF NOT EXISTS kf_visual_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kf_visual_db;

-- Tabela de Clientes
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),
    servico VARCHAR(100) NOT NULL,
    valor_mensal DECIMAL(10, 2) NOT NULL,
    vencimento_dia INT NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Pagamentos
CREATE TABLE pagamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    valor_pago DECIMAL(10, 2) NOT NULL,
    data_pagamento DATE NOT NULL,
    mes_referencia VARCHAR(7) NOT NULL, -- Formato YYYY-MM
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE
);

-- Dados de Exemplo (Seed)
INSERT INTO clientes (nome, email, telefone, servico, valor_mensal, vencimento_dia) VALUES
('Empresa Alpha', 'contato@alpha.com', '(11) 99999-1111', 'Gestão de Redes Sociais', 1500.00, 5),
('Loja Beta', 'loja@beta.com', '(11) 98888-2222', 'Identidade Visual', 2500.00, 10),
('Consultoria Gamma', 'gamma@consult.com', '(21) 97777-3333', 'Web Design', 3200.00, 15);

-- Pagamento de Exemplo
INSERT INTO pagamentos (cliente_id, valor_pago, data_pagamento, mes_referencia) VALUES
(1, 1500.00, CURDATE(), DATE_FORMAT(CURDATE(), '%Y-%m'));