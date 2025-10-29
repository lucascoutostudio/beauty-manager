<div class="container">
    <h2>Bancos</h2>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><?= $titulo; ?></h5>

                    <form method="POST" action="/banco/criar/" id="formNovoBanco">
                    <div class="mb-3">
                            <label for="id" class="form-label">Código</label>
                            <input type="number" class="form-control" id="id" name="id" required>
                        </div>    
                    <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3"
                                required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Criar</button>
                        <a href="/banco/index/1" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>