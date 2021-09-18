<!-- MAIN CONTENT-->
<div id="relatorio_gerar" class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"></h2>
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
                                    <div class="col-12 col-md-10">
                                        <select required="required" v-model="tipo" @change="set_tipo($event)" class="form-control" id="tipo" name="tipo">
                                            <option :value="null">Selecione um Tipo de Relatório</option>
                                            <option v-for="(relatorio, key) in relatorios" :value="key" :key="key">{{relatorio.titulo}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div v-if="relatorio && relatorio.filtros.includes('id_empresa')" class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_empresa" class=" form-control-label">Empresa</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select v-model="form.id_empresa" class="form-control" id="id_empresa" name="id_empresa">
                                            <option :value="null">Todas as Empresas</option>
                                            <option v-for="(empresa, key) in empresas" :value="empresa.id_empresa" :key="key">{{empresa.razao_social}}</option>
                                        </select>
                                    </div>
                                </div>


                                <div v-if="relatorio && relatorio.filtros.includes('id_obra')" class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_obra" class=" form-control-label">Obra</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select v-model="form.id_obra" class="form-control" id="id_obra" name="id_obra">
                                            <option :value="null">Todas as Obras</option>
                                            <option v-for="(obra, key) in filter_obras" :value="obra.id_obra" :key="key">{{obra.codigo_obra}}</option>
                                        </select>
                                    </div>
                                </div>


                                <div v-if="relatorio && relatorio.filtros.includes('id_funcionario')" class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="id_funcionario" class=" form-control-label">Funcionário</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select v-model="form.id_funcionario" class="form-control" id="id_funcionario" name="id_funcionario">
                                            <option :value="null">Todos os Funcionários</option>
                                            <option v-for="(funcionario, key) in filter_funcionarios" :value="funcionario.id_funcionario" :key="key">{{funcionario.nome}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div v-if="relatorio && relatorio.filtros.includes('tipo_veiculo')" class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="tipo_veiculo" class=" form-control-label">Tipo de Veículo</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select v-model="form.tipo_veiculo" class="form-control" id="tipo_veiculo" name="tipo_veiculo">
                                            <option v-for="(tipo, key) in tipos_veiculos" :value="key" :key="key">{{tipo}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div v-if="relatorio && relatorio.filtros.includes('veiculo_placa')" class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="veiculo_placa" class=" form-control-label">Veículo/Placa</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <input type="text" v-model="form.veiculo_placa" v-mask="'AAA-#N##'" class="form-control veiculo_placa" id="veiculo_placa" name="veiculo_placa"/>
                                    </div>
                                </div>


                                <div v-if="relatorio && relatorio.filtros.includes('periodo')" class="row form-group">
                                    <div class="col col-md-2">
                                        <label for="periodo_tipo" class=" form-control-label">Período</label>
                                    </div>
                                    <div class="col-12 col-md-10">
                                        <select required="required" v-model="form.periodo.tipo" class="form-control" id="periodo_tipo" name="periodo_tipo">
                                            <option v-for="(periodo, key) in periodos" :value="key" :key="key">{{periodo.titulo}}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row form-group" v-if="(form.periodo && form.periodo.tipo) && form.periodo.tipo == 'outro'">
                                    <div class="col-12 col-md-6">
                                        <div class="col col-md-2">
                                            <label for="tipo" class=" form-control-label">Início</label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                            <input class="form-control" required="required" v-model="form.periodo.inicio" type="date" id="periodo_inicio" name="periodo_inicio" />
                                        </div>
                                    </div>


                                    <div class="col-12 col-md-6">
                                        <div class="col col-md-2">
                                            <label for="tipo" class=" form-control-label">Fim</label>
                                        </div>
                                        <div class="col-12 col-md-10">
                                        <input class="form-control" required="required" v-model="form.periodo.fim" type="date"  id="periodo_fim" name="periodo_fim" />
                                        </div>
                                    </div>
                                </div>

                                <div v-if="relatorio && relatorio.filtros.includes('valor_total')" class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="valor_total" class=" form-control-label">Relatório com Valores</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select v-model="form.valor_total" class="form-control" id="valor_total" name="valor_total">
                                            <option :value="false">Não</option>
                                            <option :value="true">Sim</option>
                                        </select>
                                    </div>
                                </div>

                                <div v-if="relatorio" class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="tipo_arquivo" class=" form-control-label">Tipo de Arquido de Saída</label>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <select :required="true" v-model="form.tipo_arquivo" class="form-control" id="tipo_arquivo" name="tipo_arquivo">
                                            <option v-for="(value, key) in relatorio.arquivo_saida" :value="value">{{key}}</option>
                                        </select>
                                    </div>
                                </div>

                          
                                <div v-if="show_chart" id="grafico" class="relatorio_grafico m-b-20"></div>
                        
                                <div class="text-center m-t-30">
                                    <a v-if="url.link" class="text-center btn btn-md btn-primary m-t-20  m-b-10" :href="url.link" download>                                                    
                                        <i class="fa fa-file-pdf-o 3x"></i>&nbsp;
                                        Baixar Relatório <span class="badge badge-pill badge-warning" ><small>{{(url.validade)}}s</small></span>
                                    </a>
                                </div>


                                <hr>
                                <div class="pull-left">
                                    <button  :disabled="!tipo" @click="gerar()" class="btn btn-success" type="button">                                                    
                                        <i class="fa fa-line-chart"></i>&nbsp;
                                        <span id="submit-form">Gerar</span>
                                    </button>
                                    <button @click="reset()" class="btn btn-info" type="button">                                                    
                                        <i class="fa fa-remove "></i>&nbsp;
                                        <span id="cancelar-form">Limpar</span>
                                    </button>  
                                </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="copyright">
                        <p>Copyright © <?php echo date("Y"); ?>. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MAIN CONTENT-->
<!-- END PAGE CONTAINER-->

<script>
  var relatorios = JSON.parse('<?php echo json_encode($relatorios); ?>') || []
  var periodos = JSON.parse('<?php echo json_encode($periodos); ?>')
  var empresas =  JSON.parse('<?php echo json_encode($empresas); ?>')
  var tipos_veiculos = JSON.parse('<?php echo json_encode($tipos_veiculos); ?>')
  var obras = JSON.parse('<?php echo json_encode($obras); ?>')
  var status_lista = JSON.parse('<?php echo json_encode($this->status_lista()); ?>')
    
  var relatorio_gerar = new Vue({
    el: "#relatorio_gerar",
    data() {
      return {
        base_url: base_url,
        relatorios: relatorios,
        periodos: periodos,
        empresas: empresas,
        obras: obras,
        status_lista: status_lista,
        relatorio: null,
        tipo: null,
        form: {
          id_empresa: null,
          id_obra: null,
          id_funcionario: null,
          periodo: {
            tipo: 'todo_periodo',
            inicio: null,
            fim: null,
          }, //or null
          status: null,
          situacao: null,
          tipo_veiculo: 'todos',
          veiculo_placa: null,
          valor_total: true,
          tipo_arquivo: 'pdf',
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
        filter_obras(){
            return this.form.id_empresa ? this.obras.filter((obra) => {return obra.id_empresa == this.form.id_empresa}) : this.obras
        },
        filter_funcionarios(){
            return (this.form.id_empresa || this.form.id_obra) ? this.funcionarios.filter((func) => {return (func.id_empresa == this.form.id_empresa) || ((func.id_empresa == this.form.id_empresa) && (func.id_obra == this.form.id_obra))}) : this.funcionarios
        }
    },
    watch: {
        tipo(){
            if(this.chart){
                this.chart.destroy()
                this.show_chart = false
            }

            if(this.tipo){
                this.form.tipo_arquivo = 'pdf'
                this.relatorio = this.relatorios[this.tipo]
                return;
            }

            this.relatorio = null
            this.form.id_empresa = null
        },
        "form.id_empresa"(){
            this.form.id_obra = null
        },
        "form.id_obra"(){
            this.form.id_funcionario = null
        },
        "form.periodo.tipo"(){
            if (this.form.periodo.tipo && this.form.periodo.tipo != 'todo_periodo') {
                this.form.periodo.inicio = this.periodos[this.form.periodo.tipo].periodo_inicio
                this.form.periodo.fim = this.periodos[this.form.periodo.tipo].periodo_fim
                return
            }
            this.form.periodo.inicio = this.periodos['todo_periodo'].periodo_inicio
            this.form.periodo.fim = this.periodos['todo_periodo'].periodo_fim
        },
    },
    methods: {
        set_tipo(event){
            this.tipo = event.target.value
        },
        reset(){
            this.tipo = null
            this.url.link = null
            if(this.chart){
                this.chart.destroy()
                this.show_chart = false
            }
        },
        gerar(){
            if (this.tipo) {
                this.url.link = null
                this.url.validade = null

                if(this.chart) {
                    this.chart.destroy()
                    this.show_chart = false
                }

                if(this.relatorio.tipo == 'grafico' | this.relatorio.tipo.includes('grafico')){
                    this.gerar_grafico()
                }

                if(this.relatorio.tipo == 'arquivo' | this.relatorio.tipo.includes('arquivo')){
                    this.gerar_arquivo()
                }
            }
        },
        gerar_grafico(){
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

                        if (format && format == 'money'){
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
                                y: tipo == 'column' ? (parseInt(value) || 0): (value || 0),
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
        gerar_arquivo(){
            if (this.tipo) {
                window.$.ajax({
                    method: "POST",
                    url: `${base_url}relatorio/gerar_arquivo/${this.tipo}`,
                    data: this.form
                })
                .done(function(response) {
                    if(response.relatorio){
                        relatorio_gerar.url.link = response.relatorio
                        relatorio_gerar.url.validade = response.validade
                        relatorio_gerar.url.interval = setInterval(() => {
                            if (relatorio_gerar.url.validade == 0){
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
        set_chart(dataPoints = [], title = 'Relatório', type = 'pie', axisY = {}, axisX = {}){
            this.dataPoints = dataPoints
            this.chart = new CanvasJS.Chart("grafico", {
                animationEnabled: true,
                exportEnabled: false,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title:{ 
                    text: title, color: '#FF0000',
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
    mounted(){
        this.form.periodo.inicio = this.periodos['todo_periodo'].periodo_inicio
        this.form.periodo.fim = this.periodos['todo_periodo'].periodo_fim
    }
  });
  </script>
  <script src="<?php echo base_url("assets/js/canvasjs.min.js");?>"></script>