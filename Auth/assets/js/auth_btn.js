class AuthBtn {
    constructor($context) {
        this.$context = $context;
        // @ts-ignore
        if (this.$context[0].AuthBtn)
            return this.$context[0].AuthBtn;
        // @ts-ignore
        this.$context[0].AuthBtn = this;
        this.auth_modal = AuthModal.create();
        this.$context.find('.open').on('click', (event) => {
            event.preventDefault();
            let logon = $(event.currentTarget);
            $.get(logon.attr('href'), (form) => {
                this.auth_modal.setForm(form);
                this.auth_modal.open();
            });
        });
    }
    static create($context = $('.b_auth_btn')) {
        return new AuthBtn($context);
    }
}
