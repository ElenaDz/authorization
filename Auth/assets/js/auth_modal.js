class AuthModal {
    constructor($context) {
        this.$context = $context;
        // // @ts-ignore
        // if (this.$context[0].AuthModal) return this.$context[0].AuthModal;
        //
        // // @ts-ignore
        // this.$context[0].AuthModal = this;
        this.$context.find('.exit').on('click', () => {
            this.close();
            if (!this.isOpen())
                this.$context.find('.b_auth').remove();
        });
        this.$context.find('.reg_a').on('click', (e) => {
            e.preventDefault();
            let reg = $(e.currentTarget);
            $.get(reg.attr('href'), (data) => {
                this.setForm(data);
            });
        });
        this.$context.find('form').on('submit', (e) => {
            e.preventDefault();
            let form = $(e.currentTarget);
            $.ajax({
                url: form.attr("action"),
                data: form.serialize(),
                type: 'POST',
                dataType: 'html',
                success: (response) => {
                    this.setForm(response);
                }
            });
        });
    }
    open() {
        this.$context.addClass('open');
    }
    close() {
        this.$context.removeClass('open');
    }
    isOpen() {
        return this.$context.hasClass('open');
    }
    setForm(form) {
        this.deleteForm();
        this.$context.prepend(form);
        AuthModal.create();
    }
    deleteForm() {
        this.$context.find('.b_auth').remove();
    }
    static create($context = $('.b_auth_modal')) {
        return new AuthModal($context);
    }
}
