<?php
# Require AutoLoad
require_once APPPATH . "../vendor/autoload.php";

trait Ativo_veiculo_abastecimento {
    function abastecimento($id_ativo_veiculo = null, $id_ativo_veiculo_abastecimento = null) {
        $data = ["id_ativo_veiculo" => $id_ativo_veiculo];
        $this->merger_anexo_data('abastecimento', $data, $id_ativo_veiculo, $id_ativo_veiculo_abastecimento);

        if($id_ativo_veiculo) {
            $data['veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo($id_ativo_veiculo);
        }

        if($this->input->method() == 'post' && $id_ativo_veiculo) {
            return $this->abastecimento_salvar();
        }

        if($this->input->method() === 'delete' && $id_ativo_veiculo && $id_ativo_veiculo_abastecimento) {
            return $this->abastecimento_deletar($id_ativo_veiculo, $id_ativo_veiculo_abastecimento);
        }

        if(
            ($id_ativo_veiculo && $id_ativo_veiculo_abastecimento) &&
            $id_ativo_veiculo_abastecimento !== 'paginate' &&
            $id_ativo_veiculo_abastecimento !== 'adicionar'
        ) {
            $data['abastecimento'] = $this->ativo_veiculo_model
                ->abastecimento_query($id_ativo_veiculo, $id_ativo_veiculo_abastecimento)
                ->get()->row();
        }

        if(
            $id_ativo_veiculo &&
            ($id_ativo_veiculo_abastecimento && $id_ativo_veiculo_abastecimento === 'paginate')
        ) {
            return $this->abastecimento_paginate($data);
        }

        if (
            $id_ativo_veiculo &&
            ($id_ativo_veiculo_abastecimento && $id_ativo_veiculo_abastecimento !== 'paginate')
        ) {
            $data['fornecedores'] = $this->ativo_veiculo_model->get_fornecedores();
            $data['combustiveis'] = $this->ativo_veiculo_model->get_combustiveis();
            return $this->get_template('abastecimento/form', $data); 
        }
        
        $this->get_template('abastecimento/index', $data);
    }

    private function abastecimento_paginate(array $data = []){
        $id_ativo_veiculo = $data['id_ativo_veiculo'] ?? null;

        return $this->paginate_json([
            "query" => $this->ativo_veiculo_model->abastecimento_query($id_ativo_veiculo),
            "templatesData" => $data,
            "after" => function(&$row) {
                $row->combustivel_html = $row->data_html = ucfirst($row->combustivel); 
                $unidade = $row->combustivel_unidade_tipo == '0' ? 'L' : "M&sup3;";
                $row->combustivel_unidade_total_html = "{$row->combustivel_unidade_total} {$unidade}"; 
                $row->abastecimento_custo_html = $this->formata_moeda($row->abastecimento_custo); 
                $row->abastecimento_data_html = $this->formata_data($row->abastecimento_data); 
            },
            "templates" => [
                [
                    "name" => "id_link",
                    "view" => "abastecimento/link",
                    "data" => function($row, $data) {
                        return  array_merge($data, [
                            'text' => $row->id_ativo_veiculo_abastecimento,
                            'link' => base_url('ativo_veiculo')."/abastecimento/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_abastecimento}", 
                        ]);
                    }
                ],
                [                       
                    "name" => "actions",
                    "view" => "abastecimento/actions"
                ]
            ]
        ]);
    }

    # Salvar Abastecimento
    private function abastecimento_salvar($returnJson = false)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $msg = "Usuário sem permissão!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("/"));
            return false;
        }
        
        $data['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $data['id_ativo_veiculo_abastecimento'] = $this->input->post('id_ativo_veiculo_abastecimento');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);

        if ($veiculo) {
            $veiculo_km = (int) $this->input->post('veiculo_km');
            $ultimo_km = $this->db->where("id_ativo_veiculo = {$data['id_ativo_veiculo']}")
                        ->order_by('data', 'desc')
                        ->limit(1)
                        ->get('ativo_veiculo_quilometragem')
                        ->row();

            if ($ultimo_km && $veiculo_km < $ultimo_km->veiculo_km) {
                $msg =  "KM atual deve ser maior que a KM inicial do veículo e última lançada!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => false]);
                $this->session->set_flashdata('msg_erro', $msg);
                return $this->redirect($veiculo, 'abastecimento', $data, $data['id_ativo_veiculo_abastecimento'] ? "editar" : "adicionar");
            }
            
            $data['veiculo_km'] = $veiculo_km;
            $data['combustivel'] = $this->input->post('combustivel'); 
            $data['id_fornecedor'] = $this->input->post('id_fornecedor');   
            $data['combustivel_unidade_tipo'] = $this->input->post('combustivel_unidade_tipo') == 'litro' ? '0' : '1';   
            $data['combustivel_unidade_valor'] = $this->formata_moeda_float($this->input->post('combustivel_unidade_valor'));
            $data['abastecimento_custo'] = $this->formata_moeda_float($this->input->post('abastecimento_custo'));
            $data['abastecimento_data'] = $this->input->post('abastecimento_data') ?: date("Y-m-d");
            $data['combustivel_unidade_total'] = number_format(($data['abastecimento_custo'] / $data['combustivel_unidade_valor']), 2);

            if (!$data['id_ativo_veiculo_abastecimento']) {

                // Salvar LOG
                $this->salvar_log(9, $data['id_ativo_veiculo_abastecimento'], 'adicionar', $data);

                $this->db->insert('ativo_veiculo_abastecimento', $data);

                if ($ultimo_km && $veiculo_km > $ultimo_km->veiculo_km) {

                    // Salvar LOG
                    $this->salvar_log(9, $data['id_ativo_veiculo'], 'adicionar', $data);

                    $this->db->insert('ativo_veiculo_quilometragem', ["data" =>  $data['abastecimento_data'], "veiculo_km" => $veiculo_km, "id_ativo_veiculo" => $data["id_ativo_veiculo"]]);
                }
                $msg = "Novo registro inserido com sucesso!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => true]);
                $this->session->set_flashdata('msg_success', $msg);
            } else {
                $this->db->where('id_ativo_veiculo_abastecimento', $data['id_ativo_veiculo_abastecimento'])
                    ->update('ativo_veiculo_abastecimento', $data);

                // Salvar LOG
                $this->salvar_log(9, $data['ativo_veiculo_abastecimento'], 'editar', $data);

                $msg = "Registro atualizado com sucesso!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => true]);
                $this->session->set_flashdata('msg_success', $msg);
            }


            $last_id = $data['id_ativo_veiculo_abastecimento'] ? $data['id_ativo_veiculo_abastecimento'] : $this->db->insert_id() ;
            echo redirect(base_url("ativo_veiculo/abastecimento/{$data['id_ativo_veiculo']}/{$last_id}"));
            return;
        }

        $msg = "Veiculo não encontrado!";
        if ($returnJson) return $this->json(['message' => $msg, 'success' => false]);
        $this->session->set_flashdata('msg_erro', $msg);
        echo redirect(base_url("ativo_veiculo"));
    }

    private function abastecimento_deletar($id_ativo_veiculo, $id_ativo_veiculo_abastecimento, $returnJson = false)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $msg = "Usuário sem permissão!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("/"));
            return false;
        }

        $abastecimento = $this->db
            ->where("id_ativo_veiculo = {$id_ativo_veiculo}")
            ->where("id_ativo_veiculo_abastecimento = {$id_ativo_veiculo_abastecimento}")
            ->get('ativo_veiculo_abastecimento')->num_rows() == 1;

        if (!$abastecimento) {
            $msg = "Lançamento de Abastecimento não encontrado!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("ativo_veiculo/abastecimento/{$id_ativo_veiculo}"));
            return false;
        }

        if (!$this->ativo_veiculo_model->permit_delete_abastecimento($id_ativo_veiculo, $id_ativo_veiculo_abastecimento)) {
            $msg = "Lançamento Quilometragem não pode ser excluído!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("ativo_veiculo/abastecimento/{$id_ativo_veiculo}"));
            return false;
        }

        $this->deletar_anexos('ativo_veiculo', $id_ativo_veiculo, 'abastecimento', $id_ativo_veiculo_abastecimento);
        $this->db->where("id_ativo_veiculo_abastecimento = {$id_ativo_veiculo_abastecimento}")->delete('ativo_veiculo_abastecimento');

        if ($returnJson) return $this->json(['success' => true]);
        echo redirect(base_url("ativo_veiculo/abastecimento/{$id_ativo_veiculo}"));
        return true;
    }  
}