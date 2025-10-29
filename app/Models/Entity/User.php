<?php

class User{
    private $id;
    private $cpf;
    private $nome;
    private $usuario;
    private $email;
    private $senha;
    private $nivel;
    private $datainclusao;
    private $status;
    private $excluir;
    private $dataexclusao;

    public function __construct(){}

    public function __destruct(){}

    public function getId(){ return $this->id ;}
    public function setId($id) { $this->id = $id ;}
    public function getCpf(){ return $this->cpf ;}
    public function setCpf($cpf) { $this->cpf = $cpf ;}
    public function getNome(){ return $this->nome ;}
    public function setNome($nome) { $this->nome = $nome ;}
    public function getUsuario(){ return $this->usuario ;}
    public function setUsuario($usuario) { $this->usuario = $usuario ;}
    public function getEmail(){ return $this->email ;}
    public function setEmail($email) { $this->email = $email ;}
    public function getSenha(){ return $this->senha ;}
    public function setSenha($senha) { $this->senha = $senha ;}
    public function getNivel(){ return $this->nivel ;}
    public function setNivel($nivel) { $this->nivel = $nivel ;}
    public function getDatainclusao(){ return $this->datainclusao ;}
    public function setDatainclusao($datainclusao) { $this->datainclusao = $datainclusao ;}
    public function getStatus(){ return $this->status ;}
    public function setStatus($status) { $this->status = $status ;}
    public function getExcluir(){ return $this->excluir ;}
    public function setExcluir($excluir) { $this->excluir = $excluir ;}
    public function getDataexclusao(){ return $this->dataexclusao ;}
    public function setDataexclusao($dataexclusao) { $this->dataexclusao = $dataexclusao ;}
    
}

?>