<?php
# Require AutoLoad
require_once APPPATH . "../vendor/autoload.php";

trait Ativo_veiculo_ipva {
    function ipva($id_ativo_veiculo = null, $id_ativo_veiculo_ipva = null) {
        $data = ["id_ativo_veiculo" => $id_ativo_veiculo];
        $this->merger_anexo_data('ipva', $data, $id_ativo_veiculo, $id_ativo_veiculo_ipva);

        if($id_ativo_veiculo) {
            $data['veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo($id_ativo_veiculo);
        }

        if($this->input->method() == 'post' && $id_ativo_veiculo) {
            return $this->ipva_salvar();
        }

        if($this->input->method() === 'delete' && $id_ativo_veiculo && $id_ativo_veiculo_ipva) {
            return $this->ipva_deletar($id_ativo_veiculo, $id_ativo_veiculo_ipva);
        }

        if(
            ($id_ativo_veiculo && $id_ativo_veiculo_ipva) &&
            $id_ativo_veiculo_ipva !== 'paginate' &&
            $id_ativo_veiculo_ipva !== 'adicionar'
        ) {
            $data['ipva'] = $this->ativo_veiculo_model
                ->ipva_query($id_ativo_veiculo, $id_ativo_veiculo_ipva)
                ->get()->row();
        }

        if(
            $id_ativo_veiculo &&
            ($id_ativo_veiculo_ipva && $id_ativo_veiculo_ipva === 'paginate')
        ) {
            return $this->ipva_paginate($data);
        }

        if (
            $id_ativo_veiculo &&
            ($id_ativo_veiculo_ipva && $id_ativo_veiculo_ipva !== 'paginate')
        ) {
            return $this->get_template('ipva/form', $data); 
        }
        
        $this->get_template('ipva/index', $data);
    }

    private function ipva_paginate(array $data = []){
        $id_ativo_veiculo = $data['id_ativo_veiculo'] ?? null;

        return $this->paginate_json([
            "query" => $this->ativo_veiculo_model->ipva_query($id_ativo_veiculo),
            "templatesData" => $data,
            "after" => function(&$row) {
                $row->ipva_custo_html = $this->formata_moeda($row->ipva_custo); 
                $row->ipva_data_pagamento_html = $this->formata_data($row->ipva_data_pagamento);
                $row->ipva_data_vencimento_html = $this->formata_data($row->ipva_data_vencimento); 
            },
            "templates" => [
                [
                    "name" => "id_link",
                    "view" => "ipva/link",
                    "data" => function($row, $data) {
                        return  array_merge($data, [
                            'text' => $row->id_ativo_veiculo_ipva,
                            'link' => base_url('ativo_veiculo')."/ipva/{$row->id_ativo_veiculo}/{$row->id_ativo_veiculo_ipva}", 
                        ]);
                    }
                ],
                [                       
                    "name" => "actions",
                    "view" => "ipva/actions"
                ]
            ]
        ]);
    }

    public function ipva_salvar()
    {
        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $data['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);

        if ($veiculo) {
            $data['id_ativo_veiculo_ipva'] = $this->input->post('id_ativo_veiculo_ipva');
            $data['ipva_ano'] = $this->input->post('ipva_ano');
            $data['ipva_custo'] = $this->remocao_pontuacao($this->input->post('ipva_custo'));
            $data['ipva_data_vencimento'] = $this->input->post('ipva_data_vencimento');
            $data['ipva_data_pagamento'] = $this->input->post('ipva_data_pagamento');
            $data['ipva_data_vencimento'] = $this->input->post('ipva_data_vencimento');

            if ($data['id_ativo_veiculo_ipva'] == '' || !$data['id_ativo_veiculo_ipva']) {
                if ($this->ativo_veiculo_model->permit_add_ipva($data['id_ativo_veiculo'], $data['ipva_ano'])) {

                    // Salvar LOG
                    $this->salvar_log(9, $data['id_ativo_veiculo_ipva'], 'adicionar', $data);

                    $this->db->insert('ativo_veiculo_ipva', $data);
                    $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
                } else {
                    $this->session->set_flashdata('msg_erro', "Já existe um lançamento de IPVA pra o mesmo ano!");
                    echo redirect(base_url("ativo_veiculo/ipva/adicionar/" . $this->input->post('id_ativo_veiculo')));
                    return;
                }
            } else {
                $this->db->where('id_ativo_veiculo_ipva', $data['id_ativo_veiculo_ipva'])
                    ->update('ativo_veiculo_ipva', $data);

                     // Salvar LOG
                     $this->salvar_log(9, $data['id_ativo_veiculo_ipva'], 'editar', $data);

                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            }

            $last_id = $data['id_ativo_veiculo_ipva'] ? $data['id_ativo_veiculo_ipva'] : $this->db->insert_id() ;
            echo redirect(base_url("ativo_veiculo/ipva/{$data['id_ativo_veiculo']}/{$last_id}"));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    public function ipva_deletar($id_ativo_veiculo, $id_ativo_veiculo_ipva)
    {
        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("ativo_veiculo/ipva/{$id_ativo_veiculo}"));
            return false;
        }

        $ipva = $this->db
            ->where("id_ativo_veiculo = {$id_ativo_veiculo}")
            ->where("id_ativo_veiculo_ipva = {$id_ativo_veiculo_ipva}")
            ->get('ativo_veiculo_ipva')->num_rows() == 1;

        if (!$ipva) {
            $this->session->set_flashdata('msg_erro', "Lançamento IPVA não encontrado!");
            echo redirect(base_url("ativo_veiculo/ipva/{$id_ativo_veiculo}"));
            return false;
        }

        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("ativo_veiculo/ipva/{$id_ativo_veiculo}"));
            return false;
        }

        if (!$this->ativo_veiculo_model->permit_delete_ipva($id_ativo_veiculo, $id_ativo_veiculo_ipva)) {
            $this->session->set_flashdata('msg_erro', "Lançamento IPVA não pode ser excluído!");
            echo redirect(base_url("ativo_veiculo/ipva/{$id_ativo_veiculo}"));
            return false;
        }

        $this->deletar_anexos('ativo_veiculo', $id_ativo_veiculo, 'ipva', $id_ativo_veiculo_ipva);
        $this->db->where("id_ativo_veiculo_ipva = {$id_ativo_veiculo_ipva}")->delete('ativo_veiculo_ipva');

        // Salvar LOG
        $data["id_ativo_veiculo"] = $id_ativo_veiculo;
        $data["id_ativo_veiculo_ipva"] = $id_ativo_veiculo_ipva;
        $this->salvar_log(9, $id_ativo_veiculo_ipva, 'excluir', $data);

        echo redirect(base_url("ativo_veiculo/ipva/{$id_ativo_veiculo}"));
        return true;
    }
}