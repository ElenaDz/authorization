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
            // fixme заменить на вызов request ok
            this.request(url);
            this.auth_modal.open();
        });
    }
    initAjix() {
        // todo эти функции связаны, они делают одно дело и их можно вызывать всегда вместе поэтому объедениям их в один вызов
        //  более того их можно не выносить в отдельные функции а просто вставить код в этот метод, так мы упростим код
        //  не нужно думать когда что вызывать ok
        this.auth_modal.$context.find('form').on('submit', (e) => {
            // todo необходимо добавить блокировку кнопки отправить, разблокировать ее можно только после получения ответа ok
            e.preventDefault();
            let form = $(e.currentTarget);
            form.find('button').prop('disabled', true);
            this.request(form.attr("action"), 'POST', form.serialize());
            // fixme заменить на вызов request ok
        });
        this.auth_modal.$context.find('a').on('click', (e) => {
            e.preventDefault();
            let url = $(e.currentTarget).attr('href');
            this.request(url);
            // fixme заменить на вызов request ok
        });
    }
    request(url, type = 'GET', data = '') {
        $.ajax({
            url: url,
            type: type,
            data: data,
        })
            .done((response, textStatus, jqXHR) => {
            // todo ok
            if (jqXHR.status == 201) {
                window.location.href = '/';
                return;
            }
            let parser = new DOMParser();
            let doc = parser.parseFromString(response, 'text/html');
            let content = $(doc).find('body').html();
            this.auth_modal.setContent(content);
            this.initAjix();
        })
            .fail((jqXHR, textStatus, errorThrow) => {
            // todo ok
            throw new Error("Ошибка: " + errorThrow + ". Ответ сервера: " + jqXHR.responseText);
        });
    }
    static create($context = $('.b_auth_btn')) {
        return new AuthBtn($context);
    }
}
