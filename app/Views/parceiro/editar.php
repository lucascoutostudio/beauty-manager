<div class="container">
    <h2>Parceiros</h2>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><?= $titulo; ?></h5>
                    <form method="POST" action="/parceiro/atualizar/<?php echo htmlspecialchars($parceiro->getId()); ?>" id="formEditarParceiro">
                        <div class="row">
                            <div class="mb-3 col-md-9">
                                <label for="nomeparceiro" class="form-label">Parceiro</label>
                                <input type="text" class="form-control" id="nomeparceiro" name="nomeparceiro" required value="<?php echo htmlspecialchars($parceiro->getNomeParceiro()); ?>">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="">Selecione o Tipo</option>
                                    <option value="Acessórios" <?php htmlspecialchars($parceiro->getTipo() == 'Acessórios') ?? 'Selected'; ?>>Acessórios</option>
                                    <option value="Cantora" <?php htmlspecialchars($parceiro->getTipo() == 'Cantora') ?? 'Selected'; ?>>Cantora</option>                                    
                                    <option value="Casa de festas" <?php htmlspecialchars($parceiro->getTipo() == 'Casa de festas') ?? 'Selected'; ?>>Casa de festas</option>
                                    <option value="Cerimonialista" <?php htmlspecialchars($parceiro->getTipo() == 'Cerimonialista') ?? 'Selected'; ?>>Cerimonialista</option>
                                    <option value="Clínica de estética" <?php htmlspecialchars($parceiro->getTipo() == 'Clínica de estética') ?? 'Selected'; ?>>Clínica de estética</option>
                                    <option value="Cosmético" <?php htmlspecialchars($parceiro->getTipo() == 'Cosmético') ?? 'Selected'; ?>>Cosmético</option>
                                    <option value="Fotógrafo" <?php htmlspecialchars($parceiro->getTipo() == 'Fotógrafo') ?? 'Selected'; ?>>Fotógrafo</option>
                                    <option value="Salão de beleza" <?php htmlspecialchars($parceiro->getTipo() == 'Salão de beleza') ?? 'Selected'; ?>>Salão de beleza</option>
                                    <option value="Saúde" <?php htmlspecialchars($parceiro->getTipo() == 'Saúde') ?? 'Selected'; ?>>Saúde</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3" required><?php echo htmlspecialchars($parceiro->getDescricao()); ?></textarea>
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input type="text" class="form-control" id="instagram" name="instagram" required value="<?php echo htmlspecialchars($parceiro->getInstagram()); ?>">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="tipoparceria" class="form-label">Tipo de parceria</label>
                                <input type="text" class="form-control" id="tipoparceria" name="tipoparceria" required value="<?php echo htmlspecialchars($parceiro->getTipoParceria()); ?>">
                            </div>                            
                        </div>
                        
                        <button type="submit" class="btn btn-success">Salvar alterações</button>
                        <a href="/parceiro/index/1" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>