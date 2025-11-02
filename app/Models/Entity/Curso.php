<?php
class Curso{
    private int $id;
    private string $curso;
    private string $descricao;
    private ?string $conteudo = NULL;
    private int $cargahoraria;
    private ?int $vagas = NULL;
    private int $vip;
    private float $preco;
    private int $status;
    private int $excluir;
    private ?DateTime $dataexclusao = NULL;

    public function __construct(){}

    public function __destruct(){}

    public function getId(){ return $this->id; }
    public function setId(int $id) { $this->id = $id; }

    public function getCurso(){ return $this->curso; }
    public function setCurso(string $curso) { $this->curso = $curso;}

    public function getDescricao() { return $this->descricao; }
    public function setDescricao(string $descricao) { $this->descricao = $descricao; }

    public function getConteudo() { return $this->conteudo; }
    public function setConteudo(string $conteudo) { $this->conteudo = $conteudo; }

    public function getCargaHoraria() { return $this->cargahoraria; }
    public function setCargaHoraria(int $cargahoraria) { $this->cargahoraria = $cargahoraria; }

    public function getVagas() { return $this->vagas; }
    public function setVagas(int $vagas) { $this->vagas = $vagas; }

    public function getVip() { return $this->vip; }
    public function setVip(int $vip) { $this->vip = $vip; }

    public function getPreco() { return $this->preco; }
    public function setPreco(float $preco) { $this->preco = $preco; }

    public function getStatus() { return $this->status; }
    public function setStatus(int $status) { $this->status = $status; }

    public function getExcluir() { return $this->excluir; }
    public function setExcluir(int $excluir) { $this->excluir = $excluir; }

    public function getDataExclusao() { return $this->dataexclusao; }
    public function setDataExclusao(DateTime $dataexclusao) { $this->dataexclusao = $dataexclusao; }

}
?>