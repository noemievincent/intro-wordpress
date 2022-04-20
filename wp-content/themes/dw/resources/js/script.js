class DW_Controller {
    constructor() {
        // Ici, le DOM n'est pas encore prêt
        // Pour le moment, rien à faire
    }

    run() {
        // Ici, le DOM est prêt
    }
}

window.dw = new DW_Controller();
window.addEventListener('load', () => window.dw.run());