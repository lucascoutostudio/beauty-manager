<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="/assets/css/menu.css" rel="stylesheet">
    <?php if (isset($css_extra)): ?>
        <link rel="stylesheet" href="/assets/css/<?= htmlspecialchars($css_extra); ?>">
    <?php endif; ?>
    <title>MANAGER - Karol Couto Studio</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button id="toggle-btn" type="button">
                    <i class="bi bi-grid-fill"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#">Manager</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="https://manager.karolcouto.com.br/" class="sidebar-link">
                        <i class="bi bi-laptop"></i>
                        <span>Home</span>
                    </a>
                </li>    
                <li class="sidebar-item">
                    <a href="https://manager.karolcouto.com.br/usuario" class="sidebar-link">
                        <i class="bi bi-person"></i>
                        <span>Perfil</span>
                    </a>
                </li>
                <!--<li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-person"></i>
                        <span>Tarefas</span>
                    </a>
                </li>-->
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse"
                        data-bs-target="#service" aria-expanded="false" aria-controls="service">
                        <i class="bi bi-brush"></i>
                        <span>Serviços</span>
                    </a>
                    <ul id="service" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="https://manager.karolcouto.com.br/tiposervico/" class="sidebar-link">Tipo de serviço</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="https://manager.karolcouto.com.br/servicos/" class="sidebar-link" >Serviços</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="https://manager.karolcouto.com.br/pacotes/" class="sidebar-link" >Pacotes</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse"
                        data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                        <i class="bi bi-cash-coin"></i>
                        <span>Financeiro</span>
                    </a>
                    <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                            <a href="Banco" class="sidebar-link">Bancos</a>
                        </li>
                    <li class="sidebar-item">
                        <a href="Conta" class="sidebar-link">Contas</a>
                    </li>    
                    <li class="sidebar-item">
                            <a href="TipoDespesa" class="sidebar-link">Tipo de despesa</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="Despesa" class="sidebar-link" >Despesas</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="Fatura" class="sidebar-link" >Faturas</a>
                        </li>
                    </ul>
                </li>
                <!--<li class="sidebar-item">
                    <a href="#" class="sidebar-link has-dropdown collapsed" data-bs-toggle="collapse"
                        data-bs-target="#multi" aria-expanded="false" aria-controls="multi">
                        <i class="bi bi-cash-coin"></i>
                        <span>Multi</span>
                    </a>
                    <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse"
                                data-bs-target="#multi-two" aria-expanded="false" aria-controls="multi-two">
                                <span>Two links</span>
                            </a>
                            <ul id="multi-two" class="sidebar-dropdown list-unstyled collapse">
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link">
                                        <span>Link 1</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link">
                                        <span>Link 2</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>-->
                
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-chat-square-fill"></i>
                        <span>Notificações</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-gear-wide"></i>
                        <span>Configurações</span>
                    </a>
                </li>
                <li class="sidebar-item mt-5">
                    <a href="https://manager.karolcouto.com.br/logout/" class="sidebar-link">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="text">Sair</span>
                    </a>    
                </li>
            </ul>
            <!--<div class="sidebar-footer">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="text">Sair</span>
                </a>
            </div>-->
</aside>