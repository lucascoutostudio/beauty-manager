<div class="container">
    <h2><?= $titulo; ?></h2>
    <p>Bem-vindo de volta, <?= $_SESSION['user_name']; ?>!</p>
    
    <div class="card mt-4 p-3">
        <h3>Próximos compromissos</h3>
        <ul>
            <li>Nenhum compromisso agendado.</li>
        </ul>
    </div>
    
    <div class="card mt-4 p-3">
        <h3>Resumo Financeiro</h3>
        <p>Saldo atual: R$ 0,00</p>
        <p>Despesas do mês: R$ 0,00</p>
        <p>Receitas do mês: R$ 0,00</p>
</div>