<?php

require_once __DIR__ . '/conexao.php';

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'adicionar') {
        $titulo = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');

        if (mb_strlen($titulo) < 3) {
            $erro = 'Digite um título com pelo menos 3 caracteres.';
        } else {
            $tarefa = R::dispense('tarefas');
            $tarefa->titulo = $titulo;
            $tarefa->descricao = $descricao !== '' ? $descricao : null;
            $tarefa->concluida = 0;

            R::store($tarefa);
            header('Location: index.php?adicionada=1');
            exit;
        }
    }

    if ($acao === 'alternar') {
        $tarefa = R::load('tarefas', (int) ($_POST['id'] ?? 0));

        if ($tarefa->id) {
            $tarefa->concluida = $tarefa->concluida ? 0 : 1;
            R::store($tarefa);
        }

        header('Location: index.php');
        exit;
    }
}

if (isset($_GET['adicionada'])) {
    $mensagem = 'Nova tarefa adicionada à lista.';
}

$filtro = $_GET['filtro'] ?? 'todas';
$ordem = 'ORDER BY concluida ASC, criada_em DESC';

if ($filtro === 'pendentes') {
    $tarefas = R::findAll('tarefas', 'WHERE concluida = 0 ' . $ordem);
} elseif ($filtro === 'concluidas') {
    $tarefas = R::findAll('tarefas', 'WHERE concluida = 1 ' . $ordem);
} else {
    $tarefas = R::findAll('tarefas', $ordem);
}

$total = R::count('tarefas');
$pendentes = R::count('tarefas', 'concluida = 0');
$concluidas = R::count('tarefas', 'concluida = 1');

paginaInicio('Minha lista');
?>
<section class="intro-block">
    <div>
        <span class="eyebrow">PAINEL DE ORGANIZAÇÃO</span>
        <h1>Foco no que<br>vem agora.</h1>
        <p>Registre, organize e conclua suas tarefas sem complicação.</p>
    </div>
    <div class="counter-box">
        <span>ITENS NA LISTA</span>
        <strong><?= $total ?></strong>
        <small><?= $pendentes ?> pendente(s)</small>
    </div>
</section>

<?php if ($mensagem): ?>
    <div class="feedback success"><?= e($mensagem) ?></div>
<?php endif; ?>

<?php if ($erro): ?>
    <div class="feedback error"><?= e($erro) ?></div>
<?php endif; ?>

<section class="workspace">
    <div class="list-panel">
        <div class="panel-heading">
            <div>
                <span class="eyebrow">TAREFAS</span>
                <h2><?= $filtro === 'todas' ? 'Minha lista' : ucfirst($filtro) ?></h2>
            </div>
            <div class="mini-stats">
                <span><?= $pendentes ?> abertas</span>
                <span><?= $concluidas ?> concluídas</span>
            </div>
        </div>

        <nav class="filter-tabs">
            <a class="<?= $filtro === 'todas' ? 'active' : '' ?>" href="?filtro=todas">Todas</a>
            <a class="<?= $filtro === 'pendentes' ? 'active' : '' ?>" href="?filtro=pendentes">Pendentes</a>
            <a class="<?= $filtro === 'concluidas' ? 'active' : '' ?>" href="?filtro=concluidas">Concluídas</a>
        </nav>

        <?php if (!$tarefas): ?>
            <div class="empty-state">
                <strong>Nenhuma tarefa nesta lista.</strong>
                <span>Use o formulário ao lado para começar.</span>
            </div>
        <?php else: ?>
            <div class="task-list">
                <?php foreach ($tarefas as $tarefa): ?>
                    <article class="task-card <?= $tarefa->concluida ? 'is-complete' : '' ?>">
                        <form method="post" class="status-form">
                            <input type="hidden" name="acao" value="alternar">
                            <input type="hidden" name="id" value="<?= (int) $tarefa->id ?>">
                            <button class="status-button" type="submit" title="Alterar status">
                                <?= $tarefa->concluida ? 'Concluída' : 'Concluir' ?>
                            </button>
                        </form>
                        <div class="task-main">
                            <strong><?= e($tarefa->titulo) ?></strong>
                            <?php if ($tarefa->descricao): ?>
                                <span><?= e(mb_strimwidth($tarefa->descricao, 0, 95, '…')) ?></span>
                            <?php endif; ?>
                        </div>
                        <a class="remove-link" href="exclusao.php?id=<?= (int) $tarefa->id ?>">Excluir</a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <aside class="add-panel">
        <span class="eyebrow">NOVA TAREFA</span>
        <h2>Adicionar à lista</h2>
        <p>Preencha os campos abaixo. A tarefa aparecerá aqui mesmo após salvar.</p>

        <form method="post" action="index.php" class="task-form" autocomplete="off">
            <input type="hidden" name="acao" value="adicionar">

            <div class="field">
                <label for="titulo">Título</label>
                <input id="titulo" name="titulo" maxlength="180" required placeholder="O que precisa ser feito?">
            </div>

            <div class="field">
                <label for="descricao">Descrição <span>opcional</span></label>
                <textarea id="descricao" name="descricao" placeholder="Adicione um detalhe, se necessário."></textarea>
            </div>

            <button class="submit-button" type="submit">Adicionar tarefa</button>
        </form>
    </aside>
</section>
<?php paginaFim(); ?>
