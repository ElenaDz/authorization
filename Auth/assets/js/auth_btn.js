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
            $.get(url, (response, textStatus, jqXHR) => {
                this.auth_modal.setContent(this.getContent(response));
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
                    // fixme у тебя DOMParser 3 раза на этой странице а должно быть 1 раз, вынеси содержание этого в функцию ok
                    // fixme избавиться от этого if , заменить на проверку кода ответа (это будет в jqXHR.status), ок
                    //  1) если код ответа 400 и больше например 404 500 и тд
                    //  то бросаем исключение с полным текстом ответа (сообщение об ошибке, имя файла и номер строки
                    //  это будет в response)
                    //  2) если вод ответа >= 300 но < 400 например 301 302 делаем редирект с помощью js на нужную
                    //  страницу (например главную)
                    //  3) иначе (например код ответа 200) вставляем содержание response в модальное окно
                    if (jqXHR.status == 201) {
                        window.location.href = '/';
                    }
                    this.auth_modal.setContent(this.getContent(response));
                    this.initSubmit();
                    this.initClickLink();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    throw new Error("Ошибка: " + errorThrown + ". Ответ сервера: " + jqXHR.responseText);
                }
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
            this.auth_modal.setContent(this.getContent(response));
            this.initSubmit();
        });
    }
    getContent(response) {
        let parser = new DOMParser();
        let doc = parser.parseFromString(response, 'text/html');
        return $(doc).find('body').html();
    }
    static create($context = $('.b_auth_btn')) {
        return new AuthBtn($context);
    }
}
