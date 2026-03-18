class AuthBtn
{
    private readonly COOKIE_NAME_OPEN_URL = 'cookie_name_open_url';

    private readonly $context: JQuery;

    private auth_modal: AuthModal;
    private url_from_cookie: string;

    constructor($context: JQuery)
    {
        this.$context = $context;

        // @ts-ignore
        if (this.$context[0].AuthBtn) return this.$context[0].AuthBtn;

        // @ts-ignore
        this.$context[0].AuthBtn = this;

        this.auth_modal = AuthModal.create();

        this.initOpenUrl();

        this.initOpen();
    }

    private initOpenUrl()
    {
        if ( ! this.$context.data(this.COOKIE_NAME_OPEN_URL)) return;

        this.url_from_cookie = AuthBtn.getCookie(this.$context.data(this.COOKIE_NAME_OPEN_URL));

        // todo сдлеать рабочий код удаление куки ( этот не работает)

        document.cookie = this.COOKIE_NAME_OPEN_URL + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

        if (this.url_from_cookie)
        {
            this.request(this.url_from_cookie, 'POST')
                .done(() =>
                {
                    this.auth_modal.open();
                });
        }
    }

    private initOpen()
    {
        this.$context.find('.open').on('click',(event) =>
        {
            // fixme почему из за того что у нас отрылось какой то окно у нас должна перестать работать кнопка "Вход" ок

            event.preventDefault();

            let url = $(event.currentTarget).attr('href');

            this.request(url)
                .done(() =>
                {
                    this.auth_modal.open();
                });
        });
    }

    private static getCookie(name)
    {
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