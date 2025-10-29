<div class="container">
    <h2>Gerenciamento de Contas</h2>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 mb-3">
            <div class="card">
                <div class="card-body">
                <h5 class="card-title fw-bold"><?= $titulo; ?></h5>
                    <form method="POST" action="/conta/atualizar/<?php echo htmlspecialchars($conta->getId()); ?>" id="formEditarConta">
                        <div class="mb-3">
                            <label for="idbanco" class="form-label">Banco</label>
                            <select class="form-select" id="idbanco" name="idbanco" required>
                                <option value="">Selecione um Banco</option>
                                <?php 
                                // Assumindo que $bancos é um array de objetos Banco (ou DTOs simples)
                                foreach (($bancos ?? []) as $banco): 
                                ?>
                                    <option value="<?= htmlspecialchars($banco->getId()); ?>" <?php if ($banco->getId() == $conta->getIdBanco()) echo 'selected'; ?>>
                                        <?= htmlspecialchars($banco->getId()); ?> - <?= htmlspecialchars($banco->getNome()); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="idusuario" class="form-label">Titular da Conta</label>
                            <select class="form-select" id="idusuario" name="idusuario" required>
                                <option value="">Selecione o Titular</option>
                                <?php 
                                // Assumindo que $usuarios é um array de objetos User (ou DTOs simples)
                                foreach (($usuarios ?? []) as $usuario): 
                                ?>
                                    <option value="<?= htmlspecialchars($usuario->getId()); ?>" <?php if ($usuario->getId() == $conta->getIdUsuario()) echo 'selected'; ?>>
                                        <?= htmlspecialchars($usuario->getNome()); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label for="agencia" class="form-label">Agência</label>
                                <input type="text" class="form-control" id="agencia" name="agencia" maxlength="10" required value="<?php echo htmlspecialchars($conta->getAgencia()); ?>"   >
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="numero" class="form-label">Número da Conta</label>
                                <input type="text" class="form-control" id="numero" name="numero" maxlength="20" required value="<?php echo htmlspecialchars($conta->getNumero()); ?>" >
                            </div>
                            <div class="mb-3 col-md-2">
                                <label for="digito" class="form-label">Dígito</label>
                                <input type="text" class="form-control" id="digito" name="digito" maxlength="2" required value="<?php echo htmlspecialchars($conta->getDigito()); ?>" >
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Conta</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Selecione o Tipo</option>
                                <option value="Conta Corrente" <?php htmlspecialchars($conta->getTipo() == 'Conta Corrente') ?? 'Selected'; ?>>Conta Corrente</option>
                                <option value="Poupança" <?php htmlspecialchars($conta->getTipo() == 'Poupança') ?? 'Selected'; ?>>Conta Poupança</option>
                                <option value="Salário" <?php htmlspecialchars($conta->getTipo() == 'Salário') ?? 'Selected'; ?>>Conta Salário</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="chavepix" class="form-label">Chave Pix (Opcional)</label>
                            <input type="text" class="form-control" id="chavepix" name="chavepix" placeholder="CPF/CNPJ, E-mail, Telefone ou Chave Aleatória" value="<?php echo htmlspecialchars($conta->getChavePix()); ?>">
                        </div>                        
                        <button type="submit" class="btn btn-success">Salvar alterações</button>
                        <a href="/conta/index/1" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>