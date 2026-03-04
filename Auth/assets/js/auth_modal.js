class AuthModal {
    constructor($context) {
        this.$context = $context;
        //
        // // @ts-ignore
        // if (this.$context[0].AuthModal) return this.$context[0].AuthModal;
        //
        // // @ts-ignore
        // this.$context[0].AuthModal = this;
        this.initExit();
    }
    static renderModal() {
        $('body').prepend(this.getHtml());
    }
    static getHtml() {
        return `
                <div class="b_auth_modal">
                    <div class="content_modal">
                        <div class="exit"></div>
                    </div>
                    
                    <div class="model_fon"></div>
                </div>  
            `;
    }
    initExit() {
        this.$context.find('.exit').on('click', () => {
            this.$context.remove();
        });
        $('html').on('click', (e) => {
            if ($(e.target).hasClass('model_fon')) {
                this.$context.remove();
            }
        });
    }
    open() {
        this.$context.addClass('open');
    }
    setForm(form) {
        this.deleteForm();
        this.$context.find('.content_modal').prepend(form);
        Auth.create();
        AuthModal.create();
    }
    deleteForm() {
        this.$context.find('.b_auth').remove();
    }
    static create($context = $('.b_auth_modal')) {
        return new AuthModal($context);
    }
}
