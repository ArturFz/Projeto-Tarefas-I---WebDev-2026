<?php

declare(strict_types=1);

require_once __DIR__ . '/class/rb.php';

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: '3306';
$name = getenv('DB_NAME') ?: 'gestao_tarefas';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';

R::setup(
    "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4",
    $user,
    $pass
);

R::exec(
    'CREATE TABLE IF NOT EXISTS tarefas (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(180) NOT NULL,
        descricao TEXT NULL,
        prazo DATE NULL,
        concluida TINYINT(1) NOT NULL DEFAULT 0,
        criada_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        atualizada_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_tarefas_status (concluida),
        INDEX idx_tarefas_criacao (criada_em)
    ) ENGINE=InnoDB'
);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function paginaInicio(string $titulo): void
{
    ?>
    <!doctype html>
    <html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= e($titulo) ?> | Foco em lista</title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <header class="site-header">
            <a class="wordmark" href="index.php">
                <span class="wordmark-box">FL</span>
                <span>Foco em lista</span>
            </a>
            <span class="header-note">ORGANIZAÇÃO PESSOAL</span>
        </header>
        <main class="site-main">
    <?php
}

function paginaFim(): void
{
    ?>
        </main>
        <footer class="site-footer">
            <span>Foco em lista</span>
            <span>Organização simples, ação objetiva.</span>
        </footer>
    </body>
    </html>
    <?php
}
