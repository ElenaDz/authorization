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

        this.$context.find('.open').on('click',(event) =>
        {
            this.auth_modal = AuthModal.create();

            event.preventDefault();

            let url = $(event.currentTarget).attr('href');

            $.get(url, (data) =>
            {
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

    private initSubmit()
    {
        this.auth_modal.$context.find('form').on('submit',(e) =>
        {
            e.preventDefault();

            let form = $(e.currentTarget)

            $.ajax({
                url: form.attr("action"),
                data: form.serialize(),
                type:'POST',
                dataType:'html',
                success: (response) => {
                    let parser = new DOMParser();

                    let doc = parser.parseFromString(response, 'text/html');

                    let content = $(doc).find('body').html();

                    if ($(doc).find('.b_auth').length == 0)
                    {
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


    private initClickLink()
    {
        this.auth_modal.$context.find('a').on('click',(e) =>
        {
            e.preventDefault();

            let url = $(e.currentTarget).attr('href');

            this.loadForm(url);
        });
    }


    private loadForm(url: string)
    {
        $.get(url, (response) =>
        {
            let parser = new DOMParser();

            let doc = parser.parseFromString(response, 'text/html');

            let content = $(doc).find('body').html();

            this.auth_modal.setContent(content);
        });
    }


    public resetButton(button: string)
    {
        this.$context.empty();

        this.$context.prepend(button);
    }

    public static create($context = $('.b_auth_btn')): AuthBtn
    {
        return new AuthBtn($context);
    }
}