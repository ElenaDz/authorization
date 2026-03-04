class AuthBtn
{
    private readonly $context: JQuery;
    private auth_modal: AuthModal;
    private auth: Auth;

    constructor($context: JQuery)
    {
        this.$context = $context;

        // @ts-ignore
        if (this.$context[0].AuthBtn) return this.$context[0].AuthBtn;

        // @ts-ignore
        this.$context[0].AuthBtn = this;

        this.$context.find('.open').on('click',(event) =>
        {
            AuthModal.renderModal();

            this.auth_modal = AuthModal.create();

            event.preventDefault();

            let logon = $(event.currentTarget);

            $.get(logon.attr('href'), (data) =>
            {

                let element = Auth.getHtmlData(data).querySelector('.b_auth');

                let form = $(element)[0].outerHTML;

                this.auth_modal.setForm(form);

                this.auth_modal.open();
            });
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