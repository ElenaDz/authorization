class AuthBtn {
    constructor($context) {
        this.$context = $context;
        // @ts-ignore
        if (this.$context[0].AuthBtn)
            return this.$context[0].AuthBtn;
        // @ts-ignore
        this.$context[0].AuthBtn = this;
        this.auth_modal = AuthModal.create();
        this.url_from_cookie = AuthBtn.getCookie(AuthBtn.COOKIE_NAME_AUTH_BTN_OPEN_URL);
        if (this.url_from_cookie) {
            this.request(this.url_from_cookie, 'POST');
            this.auth_modal.open();
        }
        this.initOpen();
    }
    initOpen() {
        this.$context.find('.open').on('click', (event) => {
            if (this.url_from_cookie)
                return;
            event.preventDefault();
            let url = $(event.currentTarget).attr('href');
            this.request(url)
                .done(() => {
                this.auth_modal.open();
            });
        });
    }
    static getCookie(name) {
        let matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }
    request(url, type = 'GET', data = '') {
        return $.ajax({
            url: url,
            type: type,
            data: data,
        })
            .done((response, textStatus, jqXHR) => {
            if (jqXHR.status == 201) {
                // fixme здесь не редирект на главную, а обновление текущей страницы без записи в историю браузера,
                //  мы можем находиться не на главной во время авторизации и причин переходить на главную из-за
                //  авторизации нету ок
                window.location.replace(window.location.href);
                return;
            }
            let parser = new DOMParser();
            let doc = parser.parseFromString(response, 'text/html');
            let content = $(doc).find('body').html();
            this.auth_modal.setContent(content);
            this.initAjix();
        })
            .fail((jqXHR, textStatus, errorThrow) => {
            throw new Error("Ошибка: " + errorThrow + ". Ответ сервера: " + jqXHR.responseText);
        });
    }
    initAjix() {
        this.auth_modal.$context.find('form').on('submit', (e) => {
            e.preventDefault();
            let form = $(e.currentTarget);
            form.find('button').prop('disabled', true);
            this.request(form.attr("action"), 'POST', form.serialize());
        });
        this.auth_modal.$context.find('a').on('click', (e) => {
            e.preventDefault();
            let url = $(e.currentTarget).attr('href');
            this.request(url);
        });
    }
    static create($context = $('.b_auth_btn')) {
        return new AuthBtn($context);
    }
}
AuthBtn.COOKIE_NAME_AUTH_BTN_OPEN_URL = 'auth_btn_open_url';
