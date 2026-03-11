class AuthBtn
{
    private readonly $context: JQuery;

    private auth_modal: AuthModal;

    constructor($context: JQuery)
    {
        this.$context = $context;

        // @ts-ignore
        if (this.$context[0].AuthBtn) return this.$context[0].AuthBtn;

        // @ts-ignore
        this.$context[0].AuthBtn = this;

        this.auth_modal = AuthModal.create();

        this.$context.find('.open').on('click',(event) =>
        {
            event.preventDefault();

            let url = $(event.currentTarget).attr('href');

            this.request(url)
                .done(() =>
                {
                    this.auth_modal.open();
                });
        });
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
                    //  авторизации нету
                    window.location.href = '/';
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