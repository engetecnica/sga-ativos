<?php
# Require AutoLoad
require_once APPPATH . "../vendor/autoload.php";

trait Ativo_veiculo_quilometragem {
    function quilometragem($id_ativo_veiculo = null, $id_ativo_veiculo_quilometragem = null) {
        $data = ["id_ativo_veiculo" => $id_ativo_veiculo];
        $this->merger_anexo_data('quilometragem', $data, $id_ativo_veiculo, $id_ativo_veiculo_quilometragem);

        if($id_ativo_veiculo) {
            $data['veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo($id_ativo_veiculo);
        }

        if($this->input->method() == 'post' && $id_ativo_veiculo) {
            return $this->quilometragem_salvar();
        }

        if($this->input->method() === 'delete' && $id_ativo_veiculo && $id_ativo_veiculo_quilometragem) {
            return $this->quilometragem_deletar($id_ativo_veiculo, $id_ativo_veiculo_quilometragem);
        }

        if(
            ($id_ativo_veiculo && $id_ativo_veiculo_quilometragem) &&
            $id_ativo_veiculo_quilometragem !== 'paginate' &&
            $id_ativo_veiculo_quilometragem !== 'adicionar'
        ) {
            $data['quilometragem'] = $this->ativo_veiculo_model
                ->km_query($id_ativo_veiculo, $id_ativo_veiculo_quilometragem)
                ->get()->row();
        }

        if(
            $id_ativo_veiculo &&
            ($id_ativo_veiculo_quilometragem && $id_ativo_veiculo_quilometragem === 'paginate')
        ) {
            return $this->quilometragem_paginate($data);
        }

        if (
            $id_ativo_veiculo &&
            ($id_ativo_veiculo_quilometragem && $id_ativo_veiculo_quilometragem !== 'paginate')
        ) {
            return $this->get_template('quilometragem/form', $data); 
        }
        
        $this->get_template('quilometragem/index', $data);
    }

    private function quilometragem_paginate(array $data = []){
        $id_ativo_veiculo = $data['id_ativo_veiculo'] ?? null;

        return $this->paginate_json([
            "query" => $this->ativo_veiculo_model->km_query($id_ativo_veiculo),
            "templatesData" => $data,
            "after" => function(&$row) {
                $row->data_html = $this->formata_data($row->data);
            },
            "templates" => [
                [
                    "name" => "id_link",
                    "view" => "quilometragem/link",
                    "data" => function($row, $data) {
                        return  array_merge($data, [
                            'text' => $row->id_ativo_veiculo_quilometragem,
                            'link' => base_url('ativo_veiculo')."/quilometragem/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_quilometragem}", 
                        ]);
                    }
                ],
                [
                    "name" => "veiculo_km_link",
                    "view" => "quilometragem/link",
                    "data" => function($row, $data) {
                        return  array_merge($data, [
                            'text' =>  $row->veiculo_km,
                            'link' => base_url('ativo_veiculo')."/quilometragem/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_quilometragem}", 
                        ]);
                    }
                ],
  
                [                       
                    "name" => "actions",
                    "view" => "quilometragem/actions"
                ]
            ]
        ]);
    }

    # Salvar KM
    private function quilometragem_salvar($returnJson = false)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $msg = "Usuário sem permissão!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("/"));
            return false;
        }

        $data['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $data['id_ativo_veiculo_quilometragem'] = $this->input->post('id_ativo_veiculo_quilometragem');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);
        $ultimo_km = $this->db->where("id_ativo_veiculo = {$data['id_ativo_veiculo']}")
                        ->order_by('data', 'desc')
                        ->limit(1)
                        ->get('ativo_veiculo_quilometragem')
                        ->row();

        if ($veiculo) {
            $veiculo_km = (int) $this->input->post('veiculo_km');
            if ($ultimo_km && $veiculo_km < $ultimo_km->veiculo_km) {
                $msg =  "KM atual deve ser maior que a quilometragem inicial do veículo e anterior lançada!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => false]);
                $this->session->set_flashdata('msg_erro', $msg);
                return $this->redirect($veiculo, 'quilometragem', $data);
            }
            
            $data['veiculo_km'] = $veiculo_km;
            $data['data'] = $this->input->post('data');

            if (!$data['id_ativo_veiculo_quilometragem']) {

                // Salvar Log
                $this->salvar_log(9, null, 'adicionar', $data);

                $this->db->insert('ativo_veiculo_quilometragem', $data);
                $msg = "Novo registro inserido com sucesso!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => true]);
                $this->session->set_flashdata('msg_success', $msg);
            } else {
                $this->db->where('id_ativo_veiculo_quilometragem', $data['id_ativo_veiculo_quilometragem'])
                    ->update('ativo_veiculo_quilometragem', $data);

                // Salvar Log
                $this->salvar_log(9, $data['id_ativo_veiculo_quilometragem'], 'editar', $data);   
                 
                $msg = "Registro atualizado com sucesso!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => true]);
                $this->session->set_flashdata('msg_success', $msg);
            }

            $last_id = $data['id_ativo_veiculo_quilometragem'] ? $data['id_ativo_veiculo_quilometragem'] : $this->db->insert_id() ;
            echo redirect(base_url("ativo_veiculo/quilometragem/{$data['id_ativo_veiculo']}/{$last_id}"));
            return;
        }

        $msg = "Veiculo não encontrado!";
        if ($returnJson) return $this->json(['message' => $msg, 'success' => false]);
        $this->session->set_flashdata('msg_erro', $msg);
        echo redirect(base_url("ativo_veiculo"));
    }
    
    private function quilometragem_deletar($id_ativo_veiculo, $id_ativo_veiculo_quilometragem, $returnJson = false)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $msg = "Usuário sem permissão!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("/"));
            return false;
        }

        $quilometragem = $this->db
            ->where("id_ativo_veiculo = {$id_ativo_veiculo}")
            ->where("id_ativo_veiculo_quilometragem = {$id_ativo_veiculo_quilometragem}")
            ->get('ativo_veiculo_quilometragem')->num_rows() == 1;

        if (!$quilometragem) {
            $msg = "Lançamento de Quilometragem não encontrado!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("ativo_veiculo/gerenciar/quilometragem/{$id_ativo_veiculo}"));
            return false;
        }

        if (!$this->ativo_veiculo_model->permit_delete_km($id_ativo_veiculo, $id_ativo_veiculo_quilometragem)) {
            $msg = "Lançamento Quilometragem não pode ser excluído!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("ativo_veiculo/gerenciar/quilometragem/{$id_ativo_veiculo}"));
            return false;
        }

        $this->deletar_anexos('ativo_veiculo', $id_ativo_veiculo, 'quilometragem', $id_ativo_veiculo_quilometragem);
        $this->db->where("id_ativo_veiculo_quilometragem = {$id_ativo_veiculo_quilometragem}")->delete('ativo_veiculo_quilometragem');

        if ($returnJson) return $this->json(['success' => true]);
        echo redirect(base_url("ativo_veiculo/gerenciar/quilometragem/{$id_ativo_veiculo}"));
        return true;
    }    
}