var startOneSignalIsLoaded = false
function startOneSignal() {
    try {
        OneSignal.push(function () {
            OneSignal.SERVICE_WORKER_PARAM = { scope: '/' };
            OneSignal.SERVICE_WORKER_PATH = 'assets/js/OneSignalSDKWorker.js'
            OneSignal.SERVICE_WORKER_UPDATER_PATH = 'assets/js/OneSignalSDKUpdaterWorker.js'

            OneSignal.isPushNotificationsEnabled(function (isEnabled) {
                if (!isEnabled) OneSignal.showHttpPrompt()
                axios.get(`${base_url}usuario/permit_notification_push/${user.id_usuario}/${isEnabled ? 1 : 2}`)
            })
            OneSignal.init({
                appId: configuracao.one_signal_appid,
                safari_web_id: configuracao.one_signal_safari_web_id,
                subdomainName: base_url,
                allowLocalhostAsSecureOrigin: false,
                persistNotification: true,
                promptOptions: {
                    siteName: configuracao.app_descricao,
                    actionMessage: "Gostaríamos de mostrar notificações sobre as últimas notícias e atualizações.",
                    exampleNotificationTitle: 'Exemplo de Notificação',
                    exampleNotificationMessage: 'Esse é um exemplo de noticicação.',
                    exampleNotificationCaption: 'Você pode desfazer sua inscrição a quanquer momento',
                    acceptButtonText: "Permitir",
                    cancelButtonText: "Não, Obrigado!",
                    autoAcceptTitle: 'Sim, Permitir!',
                    slidedown: {
                        enabled: true,
                        autoPrompt: true,
                        timeDelay: 20,
                        pageViews: 3
                    }
                },
                notifyButton: {
                    enable: true,
                    showCredit: false,
                    size: 'medium',
                    //theme: 'default',
                    position: 'bottom-right',
                    offset: {
                        bottom: '20px',
                        right: '20px',
                        //right: '0px'
                    },
                    text: {
                        'tip.state.unsubscribed': 'Inscreva-se pra receber as notificações',
                        'tip.state.subscribed': "Você está inscrito pra receber as notificações",
                        'tip.state.blocked': "Você está bloqueado para notificações",
                        'message.prenotify': 'Click para se inscrever e receber as notificações',
                        'message.action.subscribed': "Obrigado por se inscrever!",
                        'message.action.resubscribed': "Você está inscrito para receber as notificações",
                        'message.action.unsubscribed': "Você não receberá notificações a partir de agora",
                        'dialog.main.title': 'Gerenciar notificações do site',
                        'dialog.main.button.subscribe': 'Inscreva-se!',
                        'dialog.main.button.unsubscribe': 'Desfazer inscrição',
                        'dialog.blocked.title': 'Desbloquear Notificações',
                        'dialog.blocked.message': "Siga as instruções para permitir as notificações"
                    },
                    colors: {
                        'circle.background': '#e7a339',
                        'circle.foreground': 'white',
                        'badge.background': 'rgb(84,110,123)',
                        'badge.foreground': 'white',
                        'badge.bordercolor': 'white',
                        'pulse.color': 'white',
                        'dialog.button.background.hovering': '#fd7a14',
                        'dialog.button.background.active': '#e7a339',
                        'dialog.button.background': '#e7a339',
                        'dialog.button.foreground': 'white'
                    },
                }
            })
            if (user) {
                OneSignal.setExternalUserId(user.id_usuario)
                OneSignal.sendTag("id_empresa", user.id_empresa)
                OneSignal.sendTag("id_obra", user.id_obra)
                OneSignal.sendTag("usuario", user.usuario)
                OneSignal.sendTag("situacao", user.situacao)
                OneSignal.sendTag("situacao_nome", user.situacao_nome == "0" ? 'Ativo' : 'Inativo')
                OneSignal.sendTag("nivel", user.nivel)
                OneSignal.sendTag("nivel_nome", user.nivel_nome)
                OneSignal.sendTag("empresa", user.empresa)
                OneSignal.sendTag("empresa_razao", user.empresa_razao)
                OneSignal.sendTag("codigo_obra", user.codigo_obra)
                OneSignal.sendTag("data_criacao", user.data_criacao)
            }
        })

        return true
    } catch (e) {}

    return false
}

if (
    app_env === 'production' &&
    (configuracao && configuracao.permit_notificacoes_push == 1)
) {
    let started = startOneSignal()
    let startAttemps = 0
    while (!started && startAttemps <= 100) {
        startAttemps++
        started = startOneSignal()
    }
}
