<div class="container">
<h2><?= htmlspecialchars($titulo); ?></h2>

<p>Esta página demonstra o funcionamento do roteamento e passagem de parâmetros na sua estrutura MVC.</p>

<div class="card">
    <h3>Parâmetros Capturados da URL:</h3>
    <ul>
        <li>**Parâmetro 1:** <code><?= htmlspecialchars($param1); ?></code></li>
        <li>**Parâmetro 2:** <code><?= htmlspecialchars($param2); ?></code></li>
    </ul>
    
    <p>A URL que você acessou foi: <code>/home/teste/<?= htmlspecialchars($param1); ?>/<?= htmlspecialchars($param2); ?></code></p>
</div>

<p style="margin-top: 20px;"><a href="/">Voltar para o Dashboard</a></p>
</div>