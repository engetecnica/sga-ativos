<?php
# Require AutoLoad
require_once APPPATH . "../vendor/autoload.php";

trait Ativo_veiculo_manutencao {
    function manutencao($id_ativo_veiculo = null, $id_ativo_veiculo_manutencao = null) {

        $data = ["id_ativo_veiculo" => $id_ativo_veiculo];
        $this->merger_anexo_data('manutencao', $data, $id_ativo_veiculo, $id_ativo_veiculo_manutencao);

        if($id_ativo_veiculo) {
            $data['veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo($id_ativo_veiculo);
            $data['manutencao_lista'] = $this->ativo_veiculo_model->get_ativo_manutencao_lista($id_ativo_veiculo);
        }

        if($this->input->method() == 'post' && $id_ativo_veiculo) {
            return $this->manutencao_salvar();
        }

        if($this->input->method() === 'delete' && $id_ativo_veiculo && $id_ativo_veiculo_manutencao) {
            return $this->manutencao_deletar($id_ativo_veiculo, $id_ativo_veiculo_manutencao);
        }

        if(
            ($id_ativo_veiculo && $id_ativo_veiculo_manutencao) &&
            $id_ativo_veiculo_manutencao !== 'paginate' &&
            $id_ativo_veiculo_manutencao !== 'adicionar'
        ) {
            $data['manutencao'] = $this->ativo_veiculo_model
                ->manutencao_query($id_ativo_veiculo, $id_ativo_veiculo_manutencao)
                ->get()->row();
        }

        if(
            $id_ativo_veiculo &&
            ($id_ativo_veiculo_manutencao && $id_ativo_veiculo_manutencao === 'paginate')
        ) {
            return $this->manutencao_paginate($data);
        }

        if (
            $id_ativo_veiculo &&
            ($id_ativo_veiculo_manutencao && $id_ativo_veiculo_manutencao !== 'paginate')
        ) {
            $data['tipo_servico'] = $this->ativo_veiculo_model->get_tipo_servico(10, 'Serviços Mecânicos');
            $data['fornecedores'] = $this->ativo_veiculo_model->get_fornecedores();
            return $this->get_template('manutencao/form', $data); 
        }
        
        $this->get_template('manutencao/index', $data);
    }

    private function manutencao_paginate(array $data = []){
        $id_ativo_veiculo = $data['id_ativo_veiculo'] ?? null;

        $b = $this->paginate_json([
            "query" => $this->ativo_veiculo_model->manutencao_query($id_ativo_veiculo),
            "templatesData" => $data,
            "after" => function(&$row) {
                $row->veiculo_custo_html = $this->formata_moeda($row->veiculo_custo); 
                $row->manutencao_data_saida_html = $this->formata_data($row->data_saida);
                $row->manutencao_data_entrada_html = $this->formata_data($row->data_entrada);  
                $row->manutencao_data_vencimento_html = $this->formata_data($row->data_vencimento); 
            },
            "templates" => [
                [
                    "name" => "id_link",
                    "view" => "manutencao/link",
                    "data" => function($row, $data) {
                        return  array_merge($data, [
                            'text' => $row->id_ativo_veiculo_manutencao,
                            'link' => base_url('ativo_veiculo')."/manutencao/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_manutencao}", 
                        ]);
                    }
                ],
                [                       
                    "name" => "actions",
                    "view" => "manutencao/actions"
                ]
            ]
        ]);

            return $b;
        //$this->dd(json_decode($b->final_output));
    }

    public function manutencao_salvar()
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $data['id_ativo_veiculo'] = (int) $this->input->post('id_ativo_veiculo');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);

        if ($veiculo) {
            $data['id_fornecedor'] = (int) $this->input->post('id_fornecedor');
            $data['id_ativo_configuracao'] = (int) $this->input->post('id_ativo_configuracao');
            $data['id_ativo_veiculo_manutencao'] = (int) $this->input->post('id_ativo_veiculo_manutencao');
            $data['veiculo_km_atual'] = (int) $this->input->post('veiculo_km_atual');
            $data['veiculo_horimetro_atual'] = (int) $this->input->post('veiculo_horimetro_atual');
            $data['veiculo_km_proxima_revisao'] = (int) $this->input->post('veiculo_km_proxima_revisao');
            $data['veiculo_horimetro_proxima_revisao'] = (int) $this->input->post('veiculo_horimetro_proxima_revisao');
    
            if ($data['veiculo_km_atual'] < $veiculo->veiculo_km_atual) {
                $this->session->set_flashdata('msg_erro', "KM atual deve ser maior ou igual a quilometragem atual do veículo!");
                return $this->redirect($veiculo, 'manutencao', $data);
            }

            if ($data['veiculo_km_proxima_revisao'] > 0  && 
                ($data['veiculo_km_proxima_revisao'] < $veiculo->veiculo_km_atual || ((int) $data['veiculo_km_proxima_revisao'] < (int) $data['veiculo_km_atual']))) {
                $this->session->set_flashdata('msg_erro', "KM para a próxima revisão deve ser maior ou igual a quilometragem atual do veículo!");
                return $this->redirect($veiculo, 'manutencao', $data);
            }

            if ($data['veiculo_horimetro_atual'] < $veiculo->veiculo_horimetro_atual) {
                $this->session->set_flashdata('msg_erro', "Horimetro atual deve ser maior ou igual ao valor atual do veículo!");
                return $this->redirect($veiculo, 'manutencao', $data);
            }

            if ($data['veiculo_horimetro_proxima_revisao'] > 0  && 
                ($data['veiculo_horimetro_proxima_revisao'] < $veiculo->veiculo_horimetro_atual || ((int) $data['veiculo_horimetro_proxima_revisao'] < (int) $data['veiculo_horimetro_atual']))) {
                $this->session->set_flashdata('msg_erro', "Horimetro para a próxima revisão deve ser maior ou igual ao valor atual do veículo!");
                return $this->redirect($veiculo, 'manutencao', $data);
            }

            $data['veiculo_custo'] = $this->remocao_pontuacao($this->input->post('veiculo_custo'));
            $data['descricao'] = $this->input->post('descricao');
            $data['data_entrada'] = $this->input->post('data_entrada');
            $data['data_vencimento'] = $this->input->post('data_vencimento');
            
            if ($data['id_ativo_veiculo_manutencao'] == '' || !$data['id_ativo_veiculo_manutencao']) {
                if (!$data['data_vencimento']) {
                    unset($data['data_vencimento']);
                }
                
                $this->db->insert('ativo_veiculo_manutencao', $data);
                $this->db->insert('ativo_veiculo_quilometragem', ["data" =>  $data['data_entrada'], "veiculo_km" => $data['veiculo_km_atual']]);
                $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            } else {
                $this->db->where('id_ativo_veiculo_manutencao', $data['id_ativo_veiculo_manutencao'])
                    ->update('ativo_veiculo_manutencao', $data);
                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            }

            $last_id = $data['id_ativo_veiculo_manutencao'] ? $data['id_ativo_veiculo_manutencao'] : $this->db->insert_id() ;
            if ($last_id) {
                $this->insert_km_and_operacao(
                    $veiculo, 
                    $data['veiculo_km_atual'],
                    $data['veiculo_horimetro_atual']
                );
                echo redirect(base_url("ativo_veiculo/manutencao/{$data['id_ativo_veiculo']}/{$last_id}"));
                return;
            }

            echo redirect(base_url("ativo_veiculo"));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }


    public function manutencao_saida($id_ativo_veiculo, $id_ativo_veiculo_manutencao)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($id_ativo_veiculo);
        $manutencao = $this->db->where('id_ativo_veiculo_manutencao', $id_ativo_veiculo_manutencao)
            ->where('id_ativo_veiculo', $id_ativo_veiculo)
            ->get('ativo_veiculo_manutencao')->row();

        if ($this->input->method() == 'post' && ($veiculo && $manutencao)) {
            if (!isset($manutencao->ordem_de_servico) && strlen($manutencao->ordem_de_servico) > 0) {
                $this->session->set_flashdata('msg_info', "Deve anexar a Ordem de Serviço!");
                echo redirect(base_url("ativo_veiculo/manutencao/$id_ativo_veiculo"));
                return;
            }

            $manutencao->data_saida = date('Y-m-d H:i:s', strtotime('now'));
            $this->db->where('id_ativo_veiculo_manutencao', $id_ativo_veiculo_manutencao)
                ->update('ativo_veiculo_manutencao', (array) $manutencao);
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            echo redirect(base_url("ativo_veiculo/manutencao/$id_ativo_veiculo"));
            return;
        }

        $this->session->set_flashdata('msg_erro', "Nenhuma manutenção encontrada!");
        echo redirect(base_url("ativo_veiculo/manutencao/$id_ativo_veiculo"));
        return;
    }

    public function manutencao_deletar($id_ativo_veiculo, $id_ativo_veiculo_manutencao)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $manutencao = $this->db
                ->where("id_ativo_veiculo = {$id_ativo_veiculo}")
                ->where("id_ativo_veiculo_manutencao = {$id_ativo_veiculo_manutencao}")
                ->get('ativo_veiculo_manutencao')->num_rows() == 1;

        if (!$manutencao) {
            $this->session->set_flashdata('msg_erro', "Manutenção não encontrada!");
            echo redirect(base_url("ativo_veiculo/manutencao/{$id_ativo_veiculo}"));
            return false;
        }

        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("ativo_veiculo/manutencao/{$id_ativo_veiculo}"));
            return false;
        }

        $this->deletar_anexos('ativo_veiculo', $id_ativo_veiculo, 'ordem_de_servico', $id_ativo_veiculo_manutencao);
        $this->db
            ->where("id_ativo_veiculo_manutencao = {$id_ativo_veiculo_manutencao}")
            ->delete('ativo_veiculo_manutencao');
        echo redirect(base_url("ativo_veiculo/manutencao/{$id_ativo_veiculo}"));
        return true;
    }
}