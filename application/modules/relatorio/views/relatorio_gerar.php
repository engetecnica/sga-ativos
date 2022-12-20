<!-- MAIN CONTENT-->
<div id="relatorio_gerar" class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <a href="<?php echo base_url('ativo_configuracao'); ?>">
                            <button class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-arrow-left"></i>todos</button></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <h2 class="title-1 m-b-25">Relatórios</h2>

                    <div class="card">
                        <div class="card-header">Gerar Relatório</div>
                        <div class="card-body">
                            <div class="row form-group">
                                <div class="col col-md-2">
                                    <label for="tipo" class=" form-control-label">Relatório</label>
                                </div>
                                <div class="col-12 col-md-4">
                                    <select required="required" v-model="tipo" @change="set_tipo($event)" class="form-control" id="tipo" name="tipo">
                                        <option :value="null">Selecione um Tipo de Relatório</option>
                                        <option v-for="(relatorio, key) in relatorios" :value="key" :key="key">{{relatorio.titulo}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row form-group">
                                <template v-if="relatorio && relatorio.filtros.includes('id_empresa')">
                                    <div class="col col-md-2">
                                        <label for="id_empresa" class=" form-control-label">Empresa</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select v-model="form.id_empresa" class="form-control" id="id_empresa" name="id_empresa">
                                            <option :value="null">Todas as Empresas</option>
                                            <option v-for="(empresa, key) in empresas" :value="empresa.id_empresa" :key="key">{{empresa.razao_social}}</option>
                                        </select>
                                    </div>
                                </template>

                                <template v-if="relatorio && relatorio.filtros.includes('id_obra')">
                                    <div class="col col-md-2">
                                        <label for="id_obra" class=" form-control-label">Obra</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select v-model="form.id_obra" class="form-control" id="id_obra" name="id_obra">
                                            <option :value="null">Todas as Obras</option>
                                            <option v-for="(obra, key) in filter_obras" :value="obra.id_obra" :key="key">{{obra.codigo_obra}}</option>
                                        </select>
                                    </div>
                                </template>
                            </div>

                            <!-- Usuários -->
                            <div v-if="relatorio && relatorio.filtros.includes('id_usuario')" class="row form-group">
                                <div class="col col-md-2">
                                    <label for="id_usuario" class=" form-control-label">Usuário</label>
                                </div>
                                <div class="col-12 col-md-5">
                                    <select v-model="form.id_usuario" class="form-control" id="id_usuario" name="id_usuario">
                                        <option :value="null">Todos os Usuários</option>
                                        <option v-for="(usuario, key) in filter_usuarios" :value="usuario.id_usuario" :key="key">{{usuario.nome}} ({{usuario.usuario}})</option>
                                    </select>
                                </div>
                            </div>                            


                            <div v-if="relatorio && relatorio.filtros.includes('id_funcionario')" class="row form-group">
                                <div class="col col-md-2">
                                    <label for="id_funcionario" class=" form-control-label">Funcionário</label>
                                </div>
                                <div class="col-12 col-md-5">
                                    <select v-model="form.id_funcionario" class="form-control" id="id_funcionario" name="id_funcionario">
                                        <option :value="null">Todos os Funcionários</option>
                                        <option v-for="(funcionario, key) in filter_funcionarios" :value="funcionario.id_funcionario" :key="key">{{funcionario.nome}}</option>
                                    </select>
                                </div>
                            </div>

                            <div v-if="relatorio && relatorio.filtros.includes('id_modulo')" class="row form-group">
                                <div class="col col-md-2">
                                    <label for="id_modulo" class=" form-control-label">Módulo</label>
                                </div>
                                <div class="col-12 col-md-5">
                                    <select v-model="form.id_modulo" class="form-control" id="id_modulo" name="id_modulo">
                                        <option :value="null">Todos os Módulos</option>
                                        <option v-for="(modulo, key) in filter_modulos" :value="modulo.id_modulo" :key="key">{{modulo.titulo}}</option>
                                    </select>
                                </div>
                            </div>

                            <div v-if="relatorio && relatorio.filtros.includes('insumo_configuracao')" class="row form-group">
                                <div class="col col-md-2">
                                    <label for="id_insumo_configuracao" class=" form-control-label">Insumo</label>
                                </div>
                                <div class="col-12 col-md-5">
                                    <select v-model="form.id_insumo_configuracao" class="form-control" id="id_insumo_configuracao" name="id_insumo_configuracao">
                                        <option :value="null">Todos os Insumos</option>
                                        <option v-for="(insumo, key) in filter_insumo_configuracao" :value="insumo.id_insumo_configuracao" :key="key">{{insumo.titulo}}</option>
                                    </select>
                                </div>
                            </div>

                            <div v-if="form.id_modulo" class="row form-group">
                                <div class="col col-md-2">
                                    <label for="id_submodulo" class=" form-control-label">Submódulo</label>
                                </div>
                                <div class="col-12 col-md-5">
                                    <select v-model="form.id_submodulo" class="form-control" id="id_submodulo" name="id_submodulo">
                                        <option :value="null">Todos os Submódulos</option>
                                        <option v-for="(submodulo, key) in submodulos" :value="submodulo.id_modulo" :key="key">{{submodulo.titulo}}</option>
                                    </select>
                                </div>
                            </div>    
                                                        

                            <div v-if="relatorio && relatorio.filtros.includes('acao')" class="row form-group">
                                <div class="col col-md-2">
                                    <label for="acao" class=" form-control-label">Ação</label>
                                </div>
                                <div class="col-12 col-md-3">
                                    <select v-model="form.acao" class="form-control" id="acao" name="acao">
                                        <option :value="null">Tipo de Ação</option>
                                        <option value="adicionar">Adicionou um registro novo</option>
                                        <option value="editar">Editou um registro</option>
                                        <option value="descartar">Descartou um registro</option>
                                        <option value="desfazer">Desfez um registro</option>
                                        <option value="acessou">Acessou o sistema</option>
                                        <option value="excluir">Excluir um registro</option>
                                    </select>
                                </div>
                            </div>                            

                            <div class="row form-group">
                                <template v-if="relatorio && relatorio.filtros.includes('tipo_veiculo')">
                                    <div class="col col-md-2">
                                        <label for="tipo_veiculo" class=" form-control-label">Tipo de Veículo</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select v-model="form.tipo_veiculo" class="form-control" id="tipo_veiculo" name="tipo_veiculo">
                                            <option v-for="(tipo, key) in tipos_veiculos" :value="key" :key="key">{{tipo}}</option>
                                        </select>
                                    </div>
                                </template>

                                <template v-if="form.tipo_veiculo != null">
                                    <template v-if="(relatorio && relatorio.filtros.includes('veiculo_placa')) &&  (form.tipo_veiculo != 'maquina')">
                                        <div class="col col-md-2">
                                            <label for="veiculo_placa" class=" form-control-label">Placa do Veículo</label>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <input type="text" v-model="form.veiculo_placa" v-mask="'AAA-#N##'" class="form-control veiculo_placa" id="veiculo_placa" name="veiculo_placa" />
                                        </div>
                                    </template>

                                    <template v-if="(relatorio && relatorio.filtros.includes('id_interno_maquina')) && form.tipo_veiculo == 'maquina'">
                                        <div class="col col-md-2">
                                            <label for="id_interno_maquina" class=" form-control-label">ID Interno</label>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <input type="text" v-model="form.id_interno_maquina" placeholder="ENG-MAG-0000" class="form-control" id="id_interno_maquina" name="id_interno_maquina" />
                                        </div>
                                    </template>
                                </template>
                            </div>


                            <div v-if="relatorio && relatorio.filtros.includes('periodo')" class="row form-group">
                                <div class="col col-md-2">
                                    <label for="periodo_tipo" class=" form-control-label">Período</label>
                                </div>
                                <div class="col-12 col-md-3">
                                    <select required="required" v-model="form.periodo.tipo" class="form-control" id="periodo_tipo" name="periodo_tipo">
                                        <option v-for="(periodo, key) in periodos" :value="key" :key="key">{{periodo.titulo}}</option>
                                    </select>
                                </div>

                                <template v-if="(form.periodo && form.periodo.tipo) && form.periodo.tipo == 'outro'">
                                    <div class="col col-md-1">
                                        <label for="tipo" class=" form-control-label">Início</label>
                                    </div>
                                    <div class="col-12 col-xs-4 col-md-2">
                                        <input class="form-control" required="required" v-model="form.periodo.inicio" type="date" id="periodo_inicio" name="periodo_inicio" />
                                    </div>

                                    <div class="col col-md-1">
                                        <label for="tipo" class=" form-control-label">Fim</label>
                                    </div>
                                    <div class="col-12 col-xs-4 col-md-2">
                                        <input class="form-control" required="required" v-model="form.periodo.fim" type="date" id="periodo_fim" name="periodo_fim" />
                                    </div>
                                </template>
                            </div>

                            <div class="row form-group">
                                <template v-if="relatorio && relatorio.filtros.includes('valor_total')">
                                    <div class="col col-md-2">
                                        <label for="valor_total" class=" form-control-label">Relatório com Valores</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <select v-model="form.valor_total" class="form-control" id="valor_total" name="valor_total">
                                            <option :value="false">Não</option>
                                            <option :value="true">Sim</option>
                                        </select>
                                    </div>
                                </template>

                                <template v-if="relatorio && Object.keys(relatorio.arquivo_saida).length > 1">
                                    <div class="col col-md-2">
                                        <label for="tipo_arquivo" class=" form-control-label">Tipo de Arquido de Saída</label>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <select :required="true" v-model="form.tipo_arquivo" class="form-control" id="tipo_arquivo" name="tipo_arquivo">
                                            <option v-for="(value, key) in relatorio.arquivo_saida" :value="value">{{key}}</option>
                                        </select>
                                    </div>
                                </template>
                            </div>

                            <input v-if="relatorio && relatorio.arquivo_saida.length == 1" type="hidden" v-model="form.tipo_arquivo" value="pdf" />


                            <div v-if="show_chart" id="grafico" class="relatorio_grafico m-b-20"></div>

                            <div class="text-center m-t-30">
                                <a v-if="url.link" class="text-center btn btn-md btn-primary m-t-20  m-b-10" :href="url.link" download>
                                    <i class="fa fa-file-pdf-o 3x"></i>&nbsp;
                                    Baixar Relatório <span class="badge badge-pill badge-warning"><small>{{(url.validade)}}s</small></span>
                                </a>
                            </div>


                            <hr>
                            <div class="pull-left">
                                <button :disabled="!tipo" @click="gerar()" class="btn btn-success" type="button">
                                    <i class="fa fa-line-chart"></i>&nbsp;
                                    <span id="submit-form">Gerar</span>
                                </button>
                                <button @click="reset()" class="btn btn-info" type="button">
                                    <i class="fa fa-ban "></i>&nbsp;
                                    <span id="cancelar-form">Limpar</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->


<?php
$rels = array();
foreach ($relatorios as $modulo => $relatorio) {
    foreach ($relatorios_permitidos as $key => $permitido) {
        if ("relatorio_" . $modulo == $permitido) {
            $rels[$modulo] = $relatorio;
        }
    }
}
?>

<script>
    var relatorios = JSON.parse(`<?php echo json_encode($rels); ?>`) || []
    var periodos = JSON.parse(`<?php echo json_encode($periodos); ?>`)
    var empresas = JSON.parse(`<?php echo json_encode($empresas); ?>`)
    var tipos_veiculos = JSON.parse(`<?php echo json_encode($tipos_veiculos); ?>`)
    var obras = JSON.parse(`<?php echo json_encode($obras); ?>`)
    var funcionarios = JSON.parse(`<?php echo json_encode($funcionarios); ?>`)
    var usuarios = JSON.parse(`<?php echo json_encode($usuarios); ?>`)
    var modulos = JSON.parse(`<?php echo json_encode($modulos); ?>`)
    var status_lista = JSON.parse(`<?php echo json_encode($this->status_lista()); ?>`)
    var insumo_configuracao = JSON.parse(`<?php echo json_encode($usuarios); ?>`)


    console.log('usuarios: ', usuarios)

    var relatorio_gerar = new Vue({
        el: "#relatorio_gerar",
        data() {
            return {
                base_url: base_url,
                relatorios: relatorios,
                periodos: periodos,
                empresas: empresas,
                obras: obras,
                funcionarios: funcionarios,
                status_lista: status_lista,
                usuarios: usuarios,
                insumo_configuracao: insumo_configuracao,
                modulos: modulos.modulo || [],
                submodulos: [],
                relatorio: null,
                tipo: null,
                form: {
                    id_empresa: null,
                    id_obra: null,
                    id_funcionario: null,
                    id_modulo: null,
                    id_submodulo: null,
                    id_usuario: null,
                    periodo: {
                        tipo: 'todo_periodo',
                        inicio: null,
                        fim: null,
                    }, //or null
                    status: null,
                    acao: null,
                    situacao: null,
                    tipo_veiculo: 'todos',
                    veiculo_placa: null,
                    id_interno_maquina: null,
                    valor_total: true,
                    tipo_arquivo: 'xls',
                },
                url: {
                    interval: null,
                    validade: null,
                    link: null,
                },
                show_chart: false,
                chart: null,
                dataPoints: [],
            }
        },
        computed: {
            filter_obras() {
                return this.form.id_empresa ? this.obras.filter((obra) => {
                    return obra.id_empresa == this.form.id_empresa
                }) : this.obras
            },
            filter_funcionarios() {
                return (this.form.id_empresa || this.form.id_obra) ? this.funcionarios.filter((func) => {
                    return (func.id_empresa == this.form.id_empresa) || ((func.id_empresa == this.form.id_empresa) && (func.id_obra == this.form.id_obra))
                }) : this.funcionarios
            },
            filter_modulos() {
                return this.form.id_modulo ? this.modulos.filter((modulo) => {
                    return modulo.id_modulo == this.form.id_modulo
                }) : this.modulos
            },
            
            filter_insumo_configuracao() {
                return this.form.id_insumo_configuracao ? this.insumo_configuracao.filter((insumoconf) => {
                    return insumoconf.id_insumo_configuracao == this.form.id_insumo_configuracao
                }) : this.insumoconf
            },
            filter_usuarios() {
                return this.form.id_usuario ? this.usuarios.filter((usuario) => {
                    
                    return usuario.id_usuario == this.form.id_usuario
                    
                }) : this.usuarios
            }
        },
        watch: {
            tipo() {
                if (this.chart) {
                    this.chart.destroy()
                    this.show_chart = false
                }

                if (this.tipo) {
                    if (this.tipo == 'maquina') this.form.veiculo_placa = null
                    if (this.tipo != 'maquina') this.form.id_interno_maquina = null
                    this.form.tipo_arquivo = (this.relatorio && Object.keys(this.relatorio.arquivo_saida).length > 1) ? 'xls' : 'pdf'
                    this.relatorio = this.relatorios[this.tipo]
                    return;
                }

                this.relatorio = null
                this.form.id_empresa = null
            },
            relatorio() {
                if (this.relatorio) {

                }
            },
            "form.tipo_veiculo"() {
                if (this.form.tipo_veiculo) {
                    if (this.form.tipo_veiculo == 'maquina') this.form.veiculo_placa = null
                    if (this.form.tipo_veiculo != 'maquina') this.form.id_interno_maquina = null
                }
            },
            "form.id_empresa"() {
                this.form.id_obra = null
            },
            "form.id_obra"() {
                this.form.id_funcionario = null
            },
            
            "form.periodo.tipo"() {
                if (this.form.periodo.tipo && this.form.periodo.tipo != 'todo_periodo') {
                    this.form.periodo.inicio = this.periodos[this.form.periodo.tipo].periodo_inicio
                    this.form.periodo.fim = this.periodos[this.form.periodo.tipo].periodo_fim
                    return
                }
                this.form.periodo.inicio = this.periodos['todo_periodo'].periodo_inicio
                this.form.periodo.fim = this.periodos['todo_periodo'].periodo_fim
            },
            "form.id_modulo"(){
                if (this.form.id_modulo) {
                    this.submodulos = this.modulos.find(m => m.id_modulo == this.form.id_modulo)?.submodulo || []
                } else {
                    this.form.id_submodulo = null
                    this.submodulos = []
                }
            }
        },
        methods: {
            set_tipo(event) {
                this.tipo = event.target.value
            },
            reset() {
                this.tipo = null
                this.url.link = null
                if (this.chart) {
                    this.chart.destroy()
                    this.show_chart = false
                }
                localStorage.removeItem("_data")
            },
            gerar() {
                if (this.tipo) {
                    this.url.link = null
                    this.url.validade = null

                    if (this.chart) {
                        this.chart.destroy()
                        this.show_chart = false
                    }

                    if (this.relatorio.tipo == 'grafico' | this.relatorio.tipo.includes('grafico')) {
                        this.gerar_grafico()
                    }

                    if (this.relatorio.tipo == 'arquivo' | this.relatorio.tipo.includes('arquivo')) {
                        this.gerar_arquivo()
                    }
                }
            },
            gerar_grafico() {
                if (this.tipo) {
                    this.show_chart = true
                    window.$.ajax({
                            method: "POST",
                            url: `${base_url}relatorio/gerar_grafico/${this.tipo}`,
                            data: this.form
                        })
                        .done(function(response) {
                            let dataPoints = []
                            let tipo = relatorio_gerar.relatorios[relatorio_gerar.tipo].grafico.tipo
                            let legendMarkerType = relatorio_gerar.relatorios[relatorio_gerar.tipo].grafico.legend_marker || []
                            let colors = relatorio_gerar.relatorios[relatorio_gerar.tipo].grafico.colors || []
                            let format = relatorio_gerar.relatorios[relatorio_gerar.tipo].format || null
                            let axisY = {
                                includeZero: true,
                                suffix: "",
                                prefix: "",
                                scaleBreaks: {
                                    autoCalculate: true
                                }
                            }
                            let axisX = {
                                prefix: "",
                                suffix: "",
                                scaleBreaks: {
                                    autoCalculate: true
                                }
                            }

                            Object.values(relatorio_gerar.relatorios[relatorio_gerar.tipo].grafico.column).forEach((key, index) => {
                                let formated_key = window.remove_acentos(key.toLowerCase().replaceAll('-', '_').replaceAll(' ', '_'))
                                let legend = `${key}: ${response[formated_key] || 0}`

                                if (format && format == 'money') {
                                    legend = `${key}: R$ ${response[formated_key]}`
                                    axisY.prefix = "R$"
                                }

                                let value = response[formated_key]
                                if (value != undefined || value != null) {
                                    dataPoints.push({
                                        showInLegend: true,
                                        label: key,
                                        indexLabel: legend,
                                        legendText: legend,
                                        legendMarkerType: legendMarkerType.length > 0 ? legendMarkerType[index] : 'circle',
                                        color: colors.length > 0 ? colors[index] : '',
                                        y: tipo == 'column' ? (parseInt(value) || 0) : (value || 0),
                                        x: tipo == 'column' ? index : 0,
                                    })
                                }
                            })

                            relatorio_gerar.set_chart(
                                dataPoints,
                                relatorio_gerar.relatorios[relatorio_gerar.tipo].titulo,
                                tipo,
                                axisY,
                                axisX
                            )
                        });
                }
            },
            gerar_arquivo() {
                if (this.tipo) {
                    window.$.ajax({
                            method: "POST",
                            url: `${base_url}relatorio/gerar_arquivo/${this.tipo}`,
                            data: this.form
                        })
                        .done(function(response) {
                            if (response.relatorio) {
                                relatorio_gerar.url.link = response.relatorio
                                relatorio_gerar.url.validade = response.validade
                                relatorio_gerar.url.interval = setInterval(() => {
                                    if (relatorio_gerar.url.validade == 0) {
                                        relatorio_gerar.url.link = null
                                        relatorio_gerar.url.validade = null
                                        return clearInterval(relatorio_gerar.url.interval)
                                    }
                                    relatorio_gerar.url.validade--
                                }, 1000);
                                return
                            }
                            relatorio_gerar.url.link = null
                        });
                }
            },
            set_chart(dataPoints = [], title = 'Relatório', type = 'pie', axisY = {}, axisX = {}) {
                this.dataPoints = dataPoints
                this.chart = new CanvasJS.Chart("grafico", {
                    animationEnabled: true,
                    exportEnabled: false,
                    theme: "light1", // "light1", "light2", "dark1", "dark2"
                    title: {
                        text: title,
                        color: '#FF0000',
                        fontFamily: "sans-serif",
                        fontSize: 25,
                    },
                    verticalAlign: "top", // "top", "center", "bottom"
                    horizontalAlign: "left", // "left", "right", "center"
                    axisY: axisY,
                    axisX: axisX,
                    data: [{
                        type: type, //change type to bar, line, area, pie, etc
                        //indexLabel: "{y}", //Shows y value on all Data Points
                        indexLabelFontColor: "#5A5757",
                        indexLabelPlacement: "outside",
                        indexLabelFontFamily: "sans-serif",
                        dataPoints: this.dataPoints
                    }]
                })
                this.chart.render();
            },
        },
        mounted() {
            this.form.periodo.inicio = this.periodos['todo_periodo'].periodo_inicio
            this.form.periodo.fim = this.periodos['todo_periodo'].periodo_fim
        }
    });
</script>
<script src="<?php echo base_url("assets/js/canvasjs.min.js"); ?>"></script>