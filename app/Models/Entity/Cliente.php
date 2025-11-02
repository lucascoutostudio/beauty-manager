<?php
class Cliente extends Pessoa {
    private int $id;
    private string $tipocliente;
    private ?int $idparceiro = null;
    private int $status;
    private int $excluir;
    private ?string $dataexclusao = null;

    // Construtor Vazio para PDO::FETCH_CLASS
    public function __construct() {}
    public function __destruct(){}

    public function getId(){ return $this->id ;}
    public function setId($id) { $this->id = $id ;}

    public function getTipoCliente(){ return $this->tipocliente ;}
    public function setTipoCliente($tipocliente) { $this->tipocliente = $tipocliente ;}

    public function getIdParceiro(){ return $this->idparceiro ;}
    public function setIdParceiro($idparceiro) { $this->idparceiro = $idparceiro ;}

    public function getStatus(){ return $this->status ;}
    public function setStatus($status) { $this->status = $status ;}

    public function getExcluir(){ return $this->excluir ;}
    public function setExcluir($excluir) { $this->excluir = $excluir ;}

    public function getDataExclusao(){ return $this->dataexclusao ;}
    public function setDataExclusao($dataexclusao) { $this->dataexclusao = $dataexclusao ;}
}


?>