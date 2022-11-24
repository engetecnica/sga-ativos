const config_lista = {
    async: true,
    serverSide: false,
    processing: false,
    loading: true,
    retrieve: true,
    destroy: true,
    stateSave: true,
    "buttons": [
        'excel', 'pdf'
    ],
    "columnDefs": [{
        "defaultContent": "-",
        "targets": "_all",
        "sortable": false,
        "searchable": false
    }],
    "aLengthMenu": [
        [10, 20, 30, 50, 100, -1],
        [10, 20, 30, 50, 100, "Todos"]
    ],
    "order": [0, 'desc'],
    "iDisplayLength": 10,
    "language" : {
        "sEmptyTable": "",
        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
        "sInfoPostFix": "",
        "sInfoThousands": ".",
        "sLengthMenu": "_MENU_ resultados por página",
        "sLoadingRecords": "Carregando...",
        "sProcessing": "Processando...",
        "sZeroRecords": "",
        "sSearch": "Pesquisar",
        "oPaginate": {
            "sNext": "Próximo",
            "sPrevious": "Anterior",
            "sFirst": "Primeiro",
            "sLast": "Último"
        },
        "oAria": {
            "sSortAscending": ": Ordenar colunas de forma ascendente",
            "sSortDescending": ": Ordenar colunas de forma descendente"
        }
    }
}
const ajaxDataTable = function (data, callback, settings = {}, options = {}){
    options.ajaxData = data
    options.async = true

    //alert();
    if(options?.url=="ferramental_estoque/buscar/grupos"){
        settings.oAjaxData.length=1000;
    }
    
    $.ajax({
        method: options?.method || 'GET',
        url: `${base_url}${options?.url || ''}`,
        async: options?.async || false, 
        data: {
            search: settings?.oAjaxData?.search?.value || null,
            start: settings?.oAjaxData?.start || 0,
            length: settings?.oAjaxData?.length || 10,
            order: settings?.oAjaxData?.order || [{column: options?.order[0], dir: options?.order[1]}] || [{column: 0, dir: "asc"}],
            columns: settings?.oAjaxData?.columns || null,
            filters: options?.filters || []
        },
        success: function(response) {
            try {
                response = JSON.parse(response)
            } catch (e) {
                response = response
            }

            callback({
                data: response?.data || [],
                ...getDataTablePaginationOptions(response)
            })
            
            if(options.ajaxOnSuccess && typeof options.ajaxOnSuccess == 'function') {
                options.ajaxOnSuccess(response)
            }

            mainCustomReady()
        }
    })
}

function getDataTablePaginationOptions(options = {}){
    return {
        recordsTotal: options?.total|| 0,
        recordsFiltered: options?.total,
        recordsDisplay: options?.total_page|| 0,
    }
}

function getDataTableDefaultOptions(options = {}){
    options = Object.assign(
       config_lista, 
       options
    )

    options.columnDefs[0].searchable = options?.searchable
    options.columnDefs[0].sortable = options?.sortable

    if (options.url) {
        options.serverSide = true
        options.ajax = (data, callback, settings) => ajaxDataTable(data, callback, settings, options)
    }

    return options
}

function loadDataTable(data_table_id, options = {}){
    options.data_table_id = data_table_id
    return $(`table#${data_table_id}`)?.DataTable(getDataTableDefaultOptions(options))
}

function loadDataTableDefault(data_table_id, options = {}){
    options.data_table_id = data_table_id
    return $(`table#${data_table_id}`)?.DataTable(options)
}

$(document).ready(function() {
    loadDataTable('lista')
    loadDataTable('lista1')
    loadDataTable('lista2')
    loadDataTable('lista3')
    loadDataTable('lista4')
    loadDataTable('lista5')
    loadDataTable('lista6')
    loadDataTable('lista7')
    loadDataTable('lista8')
    loadDataTable('lista9')
    loadDataTable('lista10')

    $('.paginate_button').click((e) => {
        setTimeout(() => {
            let lista = document.querySelector(`#${$(e.target).attr('aria-controls')}`)
            if (lista) {
                lista.scrollIntoView({behavior: "smooth"})
            }
        }, 100)
    })

    $('.has-sub>.list-unstyled>li').each((i, li) => {
        if ($(li).hasClass("active")) {
            $($(li).parent()).siblings('.js-arrow').click()
        }
    })

    $(".select2").select2()
})
