<div class="container">
    <h2>Parceiros</h2>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><?= $titulo; ?></h5>
                    <form method="POST" action="/parceiro/criar/" id="formCriarParceiro">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="nomeparceiro" class="form-label">Parceiro</label>
                                <input type="text" class="form-control" id="nomeparceiro" name="nomeparceiro" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="">Selecione o Tipo</option>
                                    <option value="Acessórios">Acessórios</option>
                                    <option value="Cantora">Cantora</option>                                    
                                    <option value="Casa de festas">Casa de festas</option>
                                    <option value="Cerimonialista">Cerimonialista</option>
                                    <option value="Clínica de estética">Clínica de estética</option>
                                    <option value="Cosmético">Cosmético</option>
                                    <option value="Fotógrafo">Fotógrafo</option>
                                    <option value="Salão de beleza">Salão de beleza</option>
                                    <option value="Saúde">Saúde</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input type="text" class="form-control" id="instagram" name="instagram" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="tipoparceria" class="form-label">Tipo de parceria</label>
                                <input type="text" class="form-control" id="tipoparceria" name="tipoparceria" required>
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