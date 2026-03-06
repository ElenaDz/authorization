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
            let url = $(event.currentTarget).attr('href');
            $.get(url, (data, textStatus, jqXHR) => {
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
    initSubmit() {
        this.auth_modal.$context.find('form').on('submit', (e) => {
            e.preventDefault();
            let form = $(e.currentTarget);
            $.ajax({
                url: form.attr("action"),
                data: form.serialize(),
                type: 'POST',
                dataType: 'html',
                success: (response, textStatus, jqXHR) => {
                    // fixme у тебя DOMParser 3 раза на этой странице а должно быть 1 раз, вынеси содержание этого в функцию
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(response, 'text/html');
                    let content = $(doc).find('body').html();
                    // fixme избавиться от этого if , заменить на проверку кода ответа (это будет в jqXHR.status),
                    //  1) если код ответа 400 и больше например 404 500 и тд
                    //  то бросаем исключение с полным текстом ответа (сообщение об ошибке, имя файла и номер строки
                    //  это будет в response)
                    //  2) если вод ответа >= 300 но < 400 например 301 302 делаем редирект с помощью js на нужную
                    //  страницу (например главную)
                    //  3) иначе (например код ответа 200) вставляем содержание response в модальное окно
                    if ($(doc).find('.b_auth').length == 0) {
                        this.resetButton(content);
                        this.auth_modal.close();
                    }
                    this.auth_modal.setContent(content);
                    this.initSubmit();
                    this.initClickLink();
                },
            });
        });
    }
    initClickLink() {
        this.auth_modal.$context.find('a').on('click', (e) => {
            e.preventDefault();
            let url = $(e.currentTarget).attr('href');
            this.loadForm(url);
        });
    }
    loadForm(url) {
        $.get(url, (response) => {
            let parser = new DOMParser();
            let doc = parser.parseFromString(response, 'text/html');
            let content = $(doc).find('body').html();
            this.auth_modal.setContent(content);
        });
    }
    // fixme не понадобиться, убрать
    resetButton(button) {
        this.$context.empty();
        this.$context.prepend(button);
    }
    static create($context = $('.b_auth_btn')) {
        return new AuthBtn($context);
    }
}
