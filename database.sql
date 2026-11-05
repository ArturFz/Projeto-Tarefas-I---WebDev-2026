CREATE DATABASE IF NOT EXISTS gestao_tarefas
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE gestao_tarefas;

CREATE TABLE IF NOT EXISTS tarefas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(180) NOT NULL,
    descricao TEXT NULL,
    prazo DATE NULL,
    concluida TINYINT(1) NOT NULL DEFAULT 0,
    criada_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizada_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tarefas_status (concluida),
    INDEX idx_tarefas_criacao (criada_em)
) ENGINE=InnoDB; 
