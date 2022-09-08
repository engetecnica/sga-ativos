<?php
# Require AutoLoad
require_once APPPATH . "../vendor/autoload.php";

trait Ativo_veiculo_operacao {
    function operacao($id_ativo_veiculo = null, $id_ativo_veiculo_operacao = null) {
        $data = ["id_ativo_veiculo" => $id_ativo_veiculo];
        $this->merger_anexo_data('operacao', $data, $id_ativo_veiculo, $id_ativo_veiculo_operacao);

        if($id_ativo_veiculo) {
            $data['veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo($id_ativo_veiculo);
        }

        if($this->input->method() == 'post' && $id_ativo_veiculo) {
            return $this->operacao_salvar();
        }

        if($this->input->method() === 'delete' && $id_ativo_veiculo && $id_ativo_veiculo_operacao) {
            return $this->operacao_deletar($id_ativo_veiculo, $id_ativo_veiculo_operacao);
        }

        if(
            ($id_ativo_veiculo && $id_ativo_veiculo_operacao) &&
            $id_ativo_veiculo_operacao !== 'paginate' &&
            $id_ativo_veiculo_operacao !== 'adicionar'
        ) {
            $data['operacao'] = $this->ativo_veiculo_model
                ->operacao_query($id_ativo_veiculo, $id_ativo_veiculo_operacao)
                ->get()->row();
        }

        if(
            $id_ativo_veiculo &&
            ($id_ativo_veiculo_operacao && $id_ativo_veiculo_operacao === 'paginate')
        ) {
            return $this->operacao_paginate($data);
        }

        if (
            $id_ativo_veiculo &&
            ($id_ativo_veiculo_operacao && $id_ativo_veiculo_operacao !== 'paginate')
        ) {
            return $this->get_template('operacao/form', $data); 
        }
        
        $this->get_template('operacao/index', $data);
    }

    private function operacao_paginate(array $data = []){
        $id_ativo_veiculo = $data['id_ativo_veiculo'] ?? null;

        return $this->paginate_json([
            "query" => $this->ativo_veiculo_model->operacao_query($id_ativo_veiculo),
            "templatesData" => $data,
            "after" => function(&$row) {
                $row->data_html = $this->formata_data($row->data);
            },
            "templates" => [
                [
                    "name" => "id_link",
                    "view" => "operacao/link",
                    "data" => function($row, $data) {
                        return  array_merge($data, [
                            'text' => $row->id_ativo_veiculo_operacao,
                            'link' => base_url('ativo_veiculo')."/operacao/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_operacao}", 
                        ]);
                    }
                ],
                [
                    "name" => "veiculo_horimetro_link",
                    "view" => "operacao/link",
                    "data" => function($row, $data) {
                        return  array_merge($data, [
                            'text' =>  $row->veiculo_horimetro,
                            'link' => base_url('ativo_veiculo')."/operacao/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_operacao}", 
                        ]);
                    }
                ],
  
                [                       
                    "name" => "actions",
                    "view" => "operacao/actions"
                ]
            ]
        ]);
    }

    public function count_operacao_horas($inicio, $fim)
    {
        return ((strtotime($fim) - strtotime($inicio)) / 60) / 60;
    }

    # Salvar Tempo de Operação para maquinas - Horimetro
    private function operacao_salvar($returnJson = false)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $msg = "Usuário sem permissão!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("/"));
            return false;
        }

        $data['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $data['id_ativo_veiculo_operacao'] = $this->input->post('id_ativo_veiculo_operacao');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);

        if ($veiculo) {
            $veiculo_horimetro = (int) $this->input->post('veiculo_horimetro');

            if ($veiculo->veiculo_horimetro_atual && $veiculo_horimetro < $veiculo->veiculo_horimetro_atual) {
                $msg =  "O valor atual deve ser maior que o valor do horimetro inicial do veículo e anterior lançada!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => false]);
                $this->session->set_flashdata('msg_erro', $msg);
                return $this->redirect($veiculo, 'operacao', $data);
            }
            
            $data['veiculo_horimetro'] = $veiculo_horimetro;
            $data['data'] = $this->input->post('data');

            if (!$data['id_ativo_veiculo_operacao']) {
                $this->db->insert('ativo_veiculo_operacao', $data);
                $msg = "Novo registro inserido com sucesso!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => true]);
                $this->session->set_flashdata('msg_success', $msg);
            } else {
                $this->db->where('id_ativo_veiculo_operacao', $data['id_ativo_veiculo_operacao'])
                    ->update('ativo_veiculo_operacao', $data);
                $msg = "Registro atualizado com sucesso!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => true]);
                $this->session->set_flashdata('msg_success', $msg);
            }

            $last_id = $data['id_ativo_veiculo_operacao'] ? $data['id_ativo_veiculo_operacao'] : $this->db->insert_id() ;
            echo redirect(base_url("ativo_veiculo/operacao/{$data['id_ativo_veiculo']}/{$last_id}"));
            return;
        }

        $msg = "Veiculo não encontrado!";
        if ($returnJson) return $this->json(['message' => $msg, 'success' => false]);
        $this->session->set_flashdata('msg_erro', $msg);
        echo redirect(base_url("ativo_veiculo"));
    }

    private function operacao_deletar($id_ativo_veiculo, $id_ativo_veiculo_operacao, $returnJson = false)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $msg = "Usuário sem permissão!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("/"));
            return false;
        }

        $operacao = $this->db
            ->where("id_ativo_veiculo = {$id_ativo_veiculo}")
            ->where("id_ativo_veiculo_operacao = {$id_ativo_veiculo_operacao}")
            ->get('ativo_veiculo_operacao')->num_rows() == 1;

        if (!$operacao) {
            $msg = "Operação não encontrada!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);

            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("ativo_veiculo/operacao/{$id_ativo_veiculo}"));
            return false;
        }

        if (!$this->ativo_veiculo_model->permit_delete_operacao($id_ativo_veiculo, $id_ativo_veiculo_operacao)) {
            $msg = "Lançamento Operação não pode ser excluído!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);

            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("ativo_veiculo/operacao/{$id_ativo_veiculo}"));
            return false;
        }

        $this->deletar_anexos('ativo_veiculo', $id_ativo_veiculo, 'operacao', $id_ativo_veiculo_operacao);
        $this->db->where("id_ativo_veiculo_operacao = {$id_ativo_veiculo_operacao}")->delete('ativo_veiculo_operacao');

        if ($returnJson) return $this->json(['success' => true]);
        echo redirect(base_url("ativo_veiculo/operacao/{$id_ativo_veiculo}"));
        return true;
    }
}