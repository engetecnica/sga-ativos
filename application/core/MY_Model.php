<?php

class MY_model extends CI_Model {

    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = $this->buscar_dados_logado($this->session->userdata('logado'));
    }

    public function formatArrayReplied($items = [], $id_item = null){
        $lista = [];
        if ((count($items) > 0) && $id_item) {
            foreach($items as $item) {
                if (!isset($lista[$item->{$id_item}])) {
                    $lista[$item->{$id_item}] = (object) $item;
                }
            } 
        }
		return $lista;
    }

    public function buscar_dados_logado($logado=null){
        if($logado) {
            $user = $this->db
            ->select('usuario.*, empresa.*')
            ->where("usuario.id_usuario = {$logado->id_usuario}")
            ->join('empresa', "empresa.id_empresa = {$logado->id_empresa}")
            ->get('usuario')
            ->row();

            if ($user) {
                unset($user->senha);
                return $user;
            }
            unset($logado->senha);
            return $logado;
        }
        return null;
    }

    public function get_obra_base(){
        return $this->db
            ->select('ob.*')
            ->from('obra ob')
            ->where('obra_base = 1')
            ->group_by('ob.id_obra')
            ->get()
            ->row();
    }

    public function formata_moeda($valor = 0, $num_format = false){
        if($num_format){
            return  number_format($valor, 2, '.', '');
        }
        return "R$ ". number_format($valor, 2, ',', '.');
    }

    public function dd(...$data){
        foreach($data as $dt) {
            echo "<pre>";
            echo print_r($dt, true);
            echo "</pre>";
        }
        exit;
    }
}