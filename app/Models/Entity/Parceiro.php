<?php
class Parceiro{
    private int $id;
    private string $nomeparceiro;
    private string $tipo;
    private string $descricao;
    private string $instagram;
    private string $tipoparceria;
    private int $status;
    private int $excluir;
    private ?string $dataexclusao = null;

    // Construtor Vazio para PDO::FETCH_CLASS
    public function __construct() {}
    public function __destruct(){}

    public function getId(){ return $this->id ;}
    public function setId($id) { $this->id = $id ;}

    public function getNomeParceiro(){ return $this->nomeparceiro ;}
    public function setNomeParceiro($nomeparceiro) { $this->nomeparceiro = $nomeparceiro ;}

    public function getTipo(){ return $this->tipo ;}
    public function setTipo($tipo) { $this->tipo = $tipo ;}

    public function getDescricao(){ return $this->descricao ;}
    public function setDescricao($descricao) { $this->descricao = $descricao ;}

    public function getInstagram(){ return $this->instagram ;}
    public function setInstagram($instagram) { $this->instagram = $instagram ;}

    public function getTipoParceria(){ return $this->tipoparceria ;}
    public function setTipoParceria($tipoparceria) { $this->tipoparceria = $tipoparceria ;}

    public function getStatus(){ return $this->status ;}
    public function setStatus($status) { $this->status = $status ;}

    public function getExcluir(){ return $this->excluir ;}
    public function setExcluir($excluir) { $this->excluir = $excluir ;}

    public function getDataExclusao(){ return $this->dataexclusao ;}
    public function setDataExclusao($dataexclusao) { $this->dataexclusao = $dataexclusao ;}     
}
?>