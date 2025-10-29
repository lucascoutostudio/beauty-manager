<?php

    class Banco{
        
        private $id;
        private $nome;
        private $descricao;
        private $status;
        private $excluir;
        private $dataexclusao;

        public function __construct(){}
        
        public function __destruct(){}

        public function getId(){ return $this->id ;}
        public function setId($id) { $this->id = $id ;} 

        public function getNome(){ return $this->nome ;}
        public function setNome($nome) { $this->nome = $nome ;}

        public function getDescricao(){ return $this->descricao ;}
        public function setDescricao($descricao) { $this->descricao = $descricao ;}

        public function getStatus(){ return $this->status ;}
        public function setStatus($status) { $this->status = $status ;}

        public function getExcluir(){ return $this->excluir ;}
        public function setExcluir($excluir) { $this->excluir = $excluir ;}

        public function getDataexclusao(){ return $this->dataexclusao ;}
        public function setDataexclusao($dataexclusao) { $this->dataexclusao = $dataexclusao ;}
    }    
?>