class AuthBtn
{
    private readonly $context: JQuery;

    private auth_modal: AuthModal;
    private url_from_cookie: string;

    static readonly COOKIE_NAME_AUTH_BTN_OPEN_URL = 'auth_btn_open_url';

    constructor($context: JQuery)
    {
        this.$context = $context;

        // @ts-ignore
        if (this.$context[0].AuthBtn) return this.$context[0].AuthBtn;

        // @ts-ignore
        this.$context[0].AuthBtn = this;

        this.auth_modal = AuthModal.create();

        this.url_from_cookie = AuthBtn.getCookie(AuthBtn.COOKIE_NAME_AUTH_BTN_OPEN_URL);

        if (this.url_from_cookie)
        {
            this.request(this.url_from_cookie, 'POST');

            this.auth_modal.open()
        }

        this.initOpen();
    }

    private initOpen()
    {
        this.$context.find('.open').on('click',(event) =>
        {
            if (this.url_from_cookie) return;

            event.preventDefault();

            let url = $(event.currentTarget).attr('href');

            this.request(url)
                .done(() =>
                {
                    this.auth_modal.open();
                });
        });
    }

    private static getCookie(name) {
        let matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    private request(url: string, type = 'GET', data = ''): JQueryPromise<any>
    {
        return $.ajax({
            url: url,
            type: type,
            data: data,
        })
            .done((response: any, textStatus: string, jqXHR: JQueryXHR) =>
            {
                if (jqXHR.status == 201)
                {
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
            .fail((jqXHR: JQueryXHR, textStatus: string, errorThrow: string) =>
            {
                throw new Error("Ошибка: " + errorThrow + ". Ответ сервера: " + jqXHR.responseText);
            });
    }

    private initAjix()
    {
        this.auth_modal.$context.find('form').on('submit',(e) =>
        {
            e.preventDefault();

            let form = $(e.currentTarget);

            form.find('button').prop('disabled', true);

            this.request(form.attr("action"), 'POST', form.serialize())
        });

        this.auth_modal.$context.find('a').on('click',(e) =>
        {
            e.preventDefault();

            let url = $(e.currentTarget).attr('href');

            this.request(url);
        });
    }

    public static create($context = $('.b_auth_btn')): AuthBtn
    {
        return new AuthBtn($context);
    }
}