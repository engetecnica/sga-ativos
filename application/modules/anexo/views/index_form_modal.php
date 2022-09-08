<div class="modal" tabindex="1001" role="dialog" id="anexo_form_modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-files-o" aria-hidden="true"></i>&nbsp; Anexo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            <div class="row">
                <div class="col-lg-12" id="anexo_form">
             
                    <form  action="<?php echo base_url("anexo/salvar"); ?>" method="post" enctype="multipart/form-data"> 
                        <input  v-if="back_url" type="hidden"  id="back_url" name="back_url" :value="back_url" />
                        <input v-if="id_anexo" type="hidden"  name="id_anexo" v-model="id_anexo" />
                        <input  type="hidden"  id="modulo" name="modulo" :value="modulo" />
                        <input  type="hidden"  id="tipo" name="tipo" :value="tipo" />
                        <input  type="hidden"  id="id_modulo" name="id_modulo" :value="id_modulo" />
                        <input  type="hidden" id="tipo" name="tipo" :value="tipo" v-model="tipo" />
                        <input  type="hidden"  id="servico" name="servico" :value="servico" />
                        <input  type="hidden"  id="item" name="item" :value="item" />
                        <input  type="hidden"  id="subitem" name="subitem" :value="subitem" />

                       
                        <div v-if="show_tipo_select" class="row form-group">
                            <div class="col col-md-4">
                                <label for="anexo_tipo" class=" form-control-label">Tipo de Anexo</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <select :readonly="!modulo"  class="form-control" required="required" type="text" id="anexo_tipo" name="anexo_tipo" v-model="tipo">
                                    <option :value="null">Selecione um Tipo de Anexo</option>
                                    <option v-for="tipo in tiposFiltred" :value="tipo.slug">{{tipo.nome}}</option>
                                </select>
                            </div>
                        </div>
                       

                        <div class="row form-group">
                            <div class="col col-md-4">
                                <label for="titulo" class=" form-control-label">Titulo do Anexo</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <input type="text" id="titulo" name="titulo" v-model="titulo" placeholder="Titulo do Anexo" class="form-control"/>
                            </div>
                        </div>
                            
                        <div class="row form-group">
                            <div class="col col-md-4">
                                <label for="descricao" class=" form-control-label">Descrição</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <textarea id="descricao" name="descricao" v-model="descricao" placeholder="Descreva seu anexo aqui" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-4">
                                <label for="anexo" class=" form-control-label">Anexo</label>
                            </div>
                            <div class="col-12 col-md-8">
                                <input required="required" type="file" id="anexo" name="anexo" placeholder="Seu Anexo Aqui" class="form-control">
                                <small size='2'>Formatos aceito: <strong>*.PDF, *.XLS, *.XLSx, *.JPG, *.PNG, *.JPEG, *.GIF</strong></small>
                                <small size='2'>Tamanho Máximo: <strong><?php echo $upload_max_filesize;?></strong></small>
                            </div>
                        </div>

                        <hr>
                        <div class="pull-left">
                            <button class="btn btn-primary">                                                    
                                <i class="fa fa-send "></i>&nbsp;
                                <span id="submit-form">Salvar</span>
                            </button>
                            <button data-dismiss="modal" class="btn btn-secondary" type="button">                                   
                                <i class="fa fa-ban "></i>&nbsp;
                                <span id="cancelar-form">Cancelar</span>
                            </button>  
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script>    
var anexo_form = new Vue({
    el: "#anexo_form",
    data() {
      return {
        base_url: window.base_url,
        back_url: `<?php echo $back_url; ?>`|| null,
        user: window.user,
        modulo: JSON.parse(`<?php echo json_encode($modulo); ?>`).rota || null,
        id_anexo: null,
        id_modulo: `<?php echo $id_modulo; ?>` || null,
        start_tipo: `<?php echo $tipo; ?>`|| null ,
        tipo: `<?php echo $tipo; ?>`|| null ,
        item: `<?php echo $id_item; ?>`|| null,
        subitem: `<?php echo $id_subitem; ?>`|| null,
        servico: `<?php echo isset($id_configuracao) ? $id_configuracao : null; ?>`|| null,
        titulo: null,
        descricao: null,
        tipos: JSON.parse(`<?php echo json_encode($anexo_tipos); ?>`) || [],
        show_tipo_select: false,
      }
    },
    computed: {
        tiposFiltred() {
            return this.modulo ? this.tipos.filter((tipo) => tipo.modulos.includes(this.modulo)) : []
        },
    },
    mounted(){
        if(!this.tipo) this.show_tipo_select = true
    }
})

function addAnexo() {
    $("#anexo_form_modal").modal({show: true})
    $('#anexo').attr('required', true)
    anexo_form.id_anexo = null
    anexo_form.tipo = anexo_form.start_tipo
    anexo_form.descricao = null
    anexo_form.titulo = null
}

function editAnexo(options = {id: null, titulo: null, descricao: null, tipo: null}) {
    anexo_form.tipo = options.tipo
    anexo_form.descricao = options.descricao
    anexo_form.titulo = options.titulo
    anexo_form.id_anexo = options.id
    $("#anexo_form_modal").modal({show: true})
    $('#anexo').attr('required', options.id == null)
}
</script>