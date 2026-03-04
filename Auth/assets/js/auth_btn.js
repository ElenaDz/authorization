class AuthBtn {
    constructor($context) {
        this.$context = $context;
        // @ts-ignore
        if (this.$context[0].AuthBtn)
            return this.$context[0].AuthBtn;
        // @ts-ignore
        this.$context[0].AuthBtn = this;
        this.$context.find('.open').on('click', (event) => {
            AuthModal.renderModal();
            this.auth_modal = AuthModal.create();
            event.preventDefault();
            let logon = $(event.currentTarget);
            $.get(logon.attr('href'), (data) => {
                let element = Auth.getHtmlData(data).querySelector('.b_auth');
                let form = $(element)[0].outerHTML;
                this.auth_modal.setForm(form);
                this.auth_modal.open();
            });
        });
    }
    resetButton(button) {
        this.$context.empty();
        this.$context.prepend(button);
    }
    static create($context = $('.b_auth_btn')) {
        return new AuthBtn($context);
    }
}
