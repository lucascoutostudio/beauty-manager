<?php

class Conta{

    private ?int $id = null;
    private ?int $idbanco = null; 
    private ?int $idusuario = null; 
    private ?int $agencia = null;
    private ?int $numero = null;
    private ?int $digito = null;
    private ?string $tipo = null;
    private ?string $chavepix = null;
    private ?int $status = null;
    private ?int $excluir = 0;
    private ?string $dataexclusao = null;
    
    // Objeto aninhado para Lazy Loading
    private ?Banco $banco = null;
    private ?User $usuario = null;

    // Construtor Vazio para PDO::FETCH_CLASS
    public function __construct() {}

    public function __destruct(){}

    public function getId(){ return $this->id ;}
    public function setId($id) { $this->id = $id ;} 

    public function getIdBanco(){ return $this->idbanco ;}
    public function setIdBanco($idbanco) { $this->idbanco = $idbanco ;}

    public function getIdUsuario(){ return $this->idusuario ;}
    public function setIdUsuario($idusuario) { $this->idusuario = $idusuario ;}

    public function getAgencia(){ return $this->agencia ;}
    public function setAgencia($agencia) { $this->agencia = $agencia ;}

    public function getNumero(){ return $this->numero ;}
    public function setNumero($numero) { $this->numero = $numero ;}

    public function getDigito(){ return $this->digito ;}
    public function setDigito($digito) { $this->digito = $digito ;}

    public function getTipo(){ return $this->tipo ;}
    public function setTipo($tipo) { $this->tipo = $tipo ;}

    public function getChavePix(){ return $this->chavepix ;}
    public function setChavePix($chavepix) { $this->chavepix = $chavepix ;}

    public function getStatus(){ return $this->status ;}
    public function setStatus($status) { $this->status = $status ;}

    public function getExcluir(){ return $this->excluir ;}
    public function setExcluir($excluir) { $this->excluir = $excluir ;}

    public function getDataExclusao(){ return $this->dataexclusao ;}
    public function setDataExclusao($dataexclusao) { $this->dataexclusao = $dataexclusao ;}

    /**
     * Retorna o objeto Banco. Faz a busca no BD apenas na primeira chamada.
     * @return Banco|null
     */
    public function getBanco(): ?Banco {
        // Se o objeto ainda não foi carregado E a FK existe:
        if ($this->banco === null && $this->idbanco !== null) {
            $bancoDao = new BancoDAO(); 
            $this->banco = $bancoDao->bancoPorId($this->idbanco);
        }
        return $this->banco;
    }

    /**
     * Retorna o objeto User (proprietário da conta). Faz a busca no BD apenas na primeira chamada.
     * @return User|null
     */
    public function getUsuario(): ?User {
        // Se o objeto ainda não foi carregado E a FK existe:
        if ($this->usuario === null && $this->idusuario !== null) {
            $userDao = new UserDAO(); 
            // Assumindo que você terá um findById no seu UserDAO
            $this->usuario = $userDao->userPorId($this->idusuario); 
        }
        return $this->usuario;
    }
}
?>