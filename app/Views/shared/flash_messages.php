<?php
// Obtém a mensagem flash (se houver) e a remove da sessão
$flash = BaseController::getFlash();

if ($flash):
    // Usa as classes de alerta do Bootstrap (success, danger, etc.)
?>
    <div class="container mt-3">
        <div class="alert alert-<?= htmlspecialchars($flash['type']); ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($flash['content']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
<?php 
endif; 
?>