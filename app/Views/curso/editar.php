<div class="container">
    <h2>Cursos</h2>
    <h5 class="fst-italic">Treinamentos - Cursos</h5>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 mb-3">
            <div class="card">
                <div class="card-body">
                <h5 class="card-title fw-bold"><?= $titulo; ?></h5>
                    <form method="POST" action="/curso/atualizar/<?= htmlspecialchars($curso->getId()); ?>" id="formEditarCurso">
                        <div class="mb-3">
                            <label for="curso" class="form-label">Curso</label>
                            <input type="text" class="form-control" id="curso" name="curso" required
                                value="<?= htmlspecialchars($curso->getCurso()); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3"
                                required><?= htmlspecialchars($curso->getDescricao()); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="conteudo" class="form-label">Conteúdo</label>
                            <textarea class="form-control" id="conteudo" name="conteudo" rows="6">
                                <?php if($curso->getConteudo() !== NULL){ htmlspecialchars($curso->getConteudo());} ?></textarea>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-md-3">
                                <label for="cargahoraria" class="form-label">Carga Horária</label>
                                <input type="number" class="form-control" id="cargahoraria" name="cargahoraria" required value="<?= htmlspecialchars($curso->getCargaHoraria()); ?>">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="vagas" class="form-label">Vagas</label>
                                <input type="number" class="form-control" id="vagas" name="vagas" required value="<?= htmlspecialchars($curso->getVagas()); ?>">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="vip" class="form-label">VIP</label>
                                <select class="form-select" id="vip" name="vip" required>
                                    <option value="0" <?= $curso->getVip() === 0 ? 'selected' : ''; ?>>Não</option>
                                    <option value="1" <?= $curso->getVip() === 1 ? 'selected' : ''; ?>>Sim</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-3">
                                <label for="preco" class="form-label">Preço (R$)</label>
                                <input type="number" class="form-control" id="preco" name="preco" required value="<?= htmlspecialchars($curso->getPreco()); ?>" step="0.01" min="00.00">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Salvar alterações</button>
                        <a href="/curso/index/1" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>