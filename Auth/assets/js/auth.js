class Auth {
    constructor($context) {
        this.$context = $context;
        // @ts-ignore
        if (this.$context[0].Auth)
            return this.$context[0].Auth;
        // @ts-ignore
        this.$context[0].Auth = this;
        AuthBtn.create();
        this.auth_modal = AuthModal.create();
        this.initSubmit();
        this.initReg();
        this.initRecoverPass();
    }
    initSubmit() {
        this.$context.find('form').on('submit', (e) => {
            e.preventDefault();
            let form = $(e.currentTarget);
            $.ajax({
                url: form.attr("action"),
                data: form.serialize(),
                type: 'POST',
                dataType: 'html',
                success: (response) => {
                    let element = Auth.getHtmlData(response).querySelector('.b_auth_btn');
                    let auth_btn_str = $(element).html();
                    if (auth_btn_str) {
                        let auth_btn = AuthBtn.create();
                        auth_btn.resetButton(auth_btn_str);
                        this.auth_modal.$context.remove();
                    }
                    this.auth_modal.setForm(response);
                }
            });
        });
    }
    initReg() {
        this.$context.find('.reg_a').on('click', (e) => {
            e.preventDefault();
            let url = $(e.currentTarget).attr('href');
            this.loadForm(url);
        });
    }
    initRecoverPass() {
        this.$context.find('.recover_pass_a').on('click', (e) => {
            e.preventDefault();
            let url = $(e.currentTarget).attr('href');
            this.loadForm(url);
        });
    }
    loadForm(url) {
        $.get(url, (data) => {
            let element = Auth.getHtmlData(data).querySelector('.b_auth');
            let form = $(element)[0].outerHTML;
            this.auth_modal.setForm(form);
        });
    }
    static getHtmlData(data) {
        let parser = new DOMParser();
        return parser.parseFromString(data, 'text/html');
    }
    static create($context = $('.b_auth')) {
        return new Auth($context);
    }
}
