class AuthBtn {
    constructor($context) {
        this.$context = $context;
        // @ts-ignore
        if (this.$context[0].AuthBtn)
            return this.$context[0].AuthBtn;
        // @ts-ignore
        this.$context[0].AuthBtn = this;
        this.$context.find('.open').on('click', (event) => {
            this.auth_modal = AuthModal.create();
            event.preventDefault();
            let url = $(event.currentTarget).attr('href');
            $.get(url, (data) => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(data, 'text/html');
                let content = $(doc).find('body').html();
                this.auth_modal.setContent(content);
                this.auth_modal.open();
                this.initClickLink();
                this.initSubmit();
            });
        });
    }
    // fixme удалить, ответственность за эту функциональность лежит на кнопке авторизации а не на авторизации ok
    initSubmit() {
        this.auth_modal.$context.find('form').on('submit', (e) => {
            e.preventDefault();
            let form = $(e.currentTarget);
            $.ajax({
                url: form.attr("action"),
                data: form.serialize(),
                type: 'POST',
                dataType: 'html',
                success: (response) => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(response, 'text/html');
                    let content = $(doc).find('body').html();
                    if ($(doc).find('.b_auth').length == 0) {
                        this.resetButton(content);
                        this.auth_modal.close();
                    }
                    this.auth_modal.setContent(content);
                    this.initSubmit();
                    this.initClickLink();
                }
            });
        });
    }
    // fixme во первых перенести в кнопку авторизации ok
    // fixme во вторых переделать на работу с любой ссылкой в рамках контанта внутри модального окна ok
    initClickLink() {
        this.auth_modal.$context.find('a').on('click', (e) => {
            e.preventDefault();
            let url = $(e.currentTarget).attr('href');
            this.loadForm(url);
        });
    }
    loadForm(url) {
        $.get(url, (data) => {
            let parser = new DOMParser();
            let doc = parser.parseFromString(data, 'text/html');
            // fixme что это? используй jquery там все есть Не знаю что ты тут пытаешься делать, возможно подойдет функцию html() ok
            let content = $(doc).find('body').html();
            this.auth_modal.setContent(content);
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
