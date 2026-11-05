<?php

require_once __DIR__ . '/conexao.php';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$tarefa = R::load('tarefas', $id);

if (!$tarefa->id) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    R::trash($tarefa);
    header('Location: index.php?excluida=1');
    exit;
}

paginaInicio('Excluir tarefa');
?>
<section class="confirmation-page">
    <a class="back-link" href="index.php">Voltar para a lista</a>
    <div class="confirmation-card">
        <span class="eyebrow">REMOVER ITEM</span>
        <h1>Excluir esta tarefa?</h1>
        <p>A ação removerá o item da sua lista.</p>

        <div class="confirmation-item">
            <strong><?= e($tarefa->titulo) ?></strong>
        </div>

        <form method="post" class="confirmation-actions">
            <input type="hidden" name="id" value="<?= (int) $tarefa->id ?>">
            <a class="secondary-button" href="index.php">Cancelar</a>
            <button class="danger-button" type="submit">Excluir tarefa</button>
        </form>
    </div>
</section>
<?php paginaFim(); ?>
