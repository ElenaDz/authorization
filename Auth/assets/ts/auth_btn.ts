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

            // fixme заменить на вызов request
            $.get(
                url,
                (response, textStatus, jqXHR) =>
                {
                    this.auth_modal.setContent(this.getContent(response));

                    this.auth_modal.open();

                    this.initClickLink();

                    this.initSubmit();
                });
        });
    }

    private initAjix()
    {
        // todo эти функции связаны, они делают одно дело и их можно вызывать всегда вместе поэтому объедениям их в один вызов
        //  более того их можно не выносить в отдельные функции а просто вставить код в этот метод, так мы упростим код
        //  не нужно думать когда что вызывать
        this.initClickLink();
        this.initSubmit();
    }

    private initSubmit()
    {
        this.auth_modal.$context.find('form').on('submit',(e) =>
        {
            e.preventDefault();

            let form = $(e.currentTarget)

            // fixme заменить на вызов request
            $.ajax({
                url: form.attr("action"),
                data: form.serialize(),
                type:'POST',
                dataType:'html',
                success: (response, textStatus, jqXHR) =>
                {
                    if (jqXHR.status == 201)
                    {
                        window.location.href = '/';
                        return;
                    }

                    this.auth_modal.setContent(this.getContent(response));

                    this.initSubmit();

                    this.initClickLink();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    throw new Error("Ошибка: " + errorThrown + ". Ответ сервера: " + jqXHR.responseText);
                }
            });
        });
    }

    private initClickLink()
    {
        this.auth_modal.$context.find('a').on('click',(e) =>
        {
            e.preventDefault();

            let url = $(e.currentTarget).attr('href');

            // fixme заменить на вызов request
            $.get(url, (response) =>
            {
                this.auth_modal.setContent(this.getContent(response));

                this.initSubmit();
            });
        });
    }

    private request(url, type = 'GET', data = [], )
    {
        $.ajax({
            url: url,
            type: type,
            data: data,
        })
            .done((response: any, textStatus: string, jqXHR: JQueryXHR) =>
            {
               // todo
            })
            .fail((jqXHR: JQueryXHR, textStatus: string, errorThrow: string) =>
            {
                // todo
            });
    }

    // fixme удалить так как после появления метода request всего один вызов этой функции
    private getContent(response: string)
    {
        let parser = new DOMParser();

        let doc = parser.parseFromString(response, 'text/html');

        return $(doc).find('body').html();
    }

    public static create($context = $('.b_auth_btn')): AuthBtn
    {
        return new AuthBtn($context);
    }
}