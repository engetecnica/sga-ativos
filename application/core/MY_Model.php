<?php

class MY_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
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
}