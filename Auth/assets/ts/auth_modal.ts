class AuthModal
{
    public readonly $context: JQuery;

    constructor($context: JQuery)
    {
        this.$context = $context;
        //
        // // @ts-ignore
        // if (this.$context[0].AuthModal) return this.$context[0].AuthModal;
        //
        // // @ts-ignore
        // this.$context[0].AuthModal = this;

        this.initExit();
    }

    public static renderModal()
    {
        $('body').prepend(this.getHtml());
    }

    private static getHtml() {
        return `
                <div class="b_auth_modal">
                    <div class="content_modal">
                        <div class="exit"></div>
                    </div>
                    
                    <div class="model_fon"></div>
                </div>  
            `;
    }

    private initExit()
    {
        this.$context.find('.exit').on('click',() =>
        {
            this.$context.remove();
        });

        $('html').on('click',(e) =>
        {
            if ($(e.target).hasClass('model_fon')) {
                this.$context.remove();
            }
        });
    }

    public open()
    {
        this.$context.addClass('open');
    }

    public setForm(form: string)
    {
        this.deleteForm();

        this.$context.find('.content_modal').prepend(form);

        Auth.create();
        AuthModal.create();
    }

    private deleteForm()
    {
        this.$context.find('.b_auth').remove();
    }

    public static create($context = $('.b_auth_modal')): AuthModal
    {
        return new AuthModal($context);
    }
}