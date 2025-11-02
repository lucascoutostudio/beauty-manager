<?php
abstract class Pessoa{
    private int $id;
    private string $nome;
    private string $telefone;
    private ?string $instagram = null;
    private ?string $email = null;
    private ?string $rg = null;
    private ?string $cpf = null;
    private ?string $cep = null;
    private ?string $endereco = null;
    private ?string $bairro = null;
    private ?string $cidade = null;
    private ?string $uf = null;
    private ?string $datanascimento = null;
    private DateTime $datacadastro;
    private int $status;
    private int $excluir;
    private ?string $dataexclusao = null;

    // Construtor Vazio para PDO::FETCH_CLASS
    public function __construct() {}
    public function __destruct(){}

    public function getId(){ return $this->id ;}
    public function setId($id) { $this->id = $id ;}

    public function getNome(){ return $this->nome ;}
    public function setNome($nome) { $this->nome = $nome ;}

    public function getTelefone(){ return $this->telefone ;}
    public function setTelefone($telefone) { $this->telefone = $telefone ;}
    
    public function getInstagram(){ return $this->instagram ;}
    public function setInstagram($instagram) { $this->instagram = $instagram ;}

    public function getEmail(){ return $this->email ;}
    public function setEmail($email) { $this->email = $email ;}

    public function getRg(){ return $this->rg ;}
    public function setRg($rg) { $this->rg = $rg ;}

    public function getCpf(){ return $this->cpf ;}
    public function setCpf($cpf) { $this->cpf = $cpf ;}

    public function getCep(){ return $this->cep ;}
    public function setCep($cep) { $this->cep = $cep ;}

    public function getEndereco(){ return $this->endereco ;}
    public function setEndereco($endereco) { $this->endereco = $endereco ;}

    public function getBairro(){ return $this->bairro ;}
    public function setBairro($bairro) { $this->bairro = $bairro ;}

    public function getCidade(){ return $this->cidade ;}
    public function setCidade($cidade) { $this->cidade = $cidade ;}

    public function getUf(){ return $this->uf ;}
    public function setUf($uf) { $this->uf = $uf ;}

    public function getDataNascimento(){ return $this->datanascimento ;}
    public function setDataNascimento($datanascimento) { $this->datanascimento = $datanascimento ;}

    public function getDataCadastro(){ return $this->datacadastro ;}
    public function setDataCadastro($datacadastro) { $this->datacadastro = $datacadastro ;}

    public function getStatus(){ return $this->status ;}
    public function setStatus($status) { $this->status = $status ;}

    public function getExcluir(){ return $this->excluir ;}
    public function setExcluir($excluir) { $this->excluir = $excluir ;}

    public function getDataExclusao(){ return $this->dataexclusao ;}
    public function setDataExclusao($dataexclusao) { $this->dataexclusao = $dataexclusao ;}
}
?>