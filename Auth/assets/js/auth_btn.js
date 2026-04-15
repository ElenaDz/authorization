class AuthBtn {
    constructor($context) {
        this.COOKIE_NAME_OPEN_URL = 'auth_btn_open_url';
        this.$context = $context;
        // @ts-ignore
        if (this.$context[0].AuthBtn)
            return this.$context[0].AuthBtn;
        // @ts-ignore
        this.$context[0].AuthBtn = this;
        this.initOpenUrl();
        this.initOpen();
        this.initAvatar();
    }
    initAvatar() {
        this.$context.find('.avatar').on('click', (event) => {
            this.isOpenListProfileOptions() ? this.closeListProfileOptions() : this.openListProfileOptions();
        });
        $('body').on('click', (e) => {
            if ($(e.target).hasClass('model_fon')) {
                this.closeListProfileOptions();
            }
        });
    }
    closeListProfileOptions() {
        if (this.auth_modal.$context) {
            this.auth_modal.$context.remove();
        }
        this.$context.removeClass('open_list');
    }
    openListProfileOptions() {
        this.auth_modal = AuthModal.create();
        this.auth_modal.open();
        this.auth_modal.$context.find('.exit').remove();
        this.$context.addClass('open_list');
    }
    isOpenListProfileOptions() {
        return this.$context.hasClass('open_list');
    }
    initOpenUrl() {
        if (!this.$context.data(this.COOKIE_NAME_OPEN_URL))
            return;
        this.url_from_cookie = AuthBtn.getCookie(this.$context.data(this.COOKIE_NAME_OPEN_URL));
        document.cookie = this.COOKIE_NAME_OPEN_URL + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        if (this.url_from_cookie) {
            this.auth_modal = AuthModal.create();
            this.request(this.url_from_cookie, 'POST')
                .done(() => {
                this.auth_modal.open();
            });
        }
    }
    initOpen() {
        this.$context.find('.open').on('click', (event) => {
            this.$context.find('.loader').addClass('active');
            this.$context.find('.open').hide();
            event.preventDefault();
            let url = $(event.currentTarget).data('href');
            this.auth_modal = AuthModal.create();
            this.request(url)
                .done(() => {
                this.auth_modal.open();
                this.$context.find('.loader').removeClass('active');
                this.$context.find('.open').show();
            })
                .fail((jqXHR, textStatus, errorThrow) => {
                throw new Error("Ошибка: " + errorThrow + ". Ответ сервера: " + jqXHR.responseText);
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
