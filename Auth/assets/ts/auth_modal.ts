class AuthModal
{
    public readonly $context: JQuery;

    static readonly CLASS_MODAL  = '.b_auth_modal';

    constructor($context: JQuery)
    {
        if ($context.length == 0)
        {
            $('body').prepend(this.getHtml());

            $context = $(AuthModal.CLASS_MODAL);
        }

        this.$context = $context;

        // @ts-ignore
        if (this.$context[0].AuthModal) return this.$context[0].AuthModal;

        // @ts-ignore
        this.$context[0].AuthModal = this;

        this.initExit();
    }

    private getHtml() {
        return `
            <div class="b_auth_modal">
                <div class="content_modal">
                    <div class="inner_content"></div>
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
            this.close();
        });

        $('html').on('click',(e) =>
        {
            if ($(e.target).hasClass('model_fon')) {
                this.close();
            }
        });
    }

    public open()
    {
        this.$context.addClass('open');
    }

    public close()
    {
        this.$context.removeClass('open');
    }

    public setContent(content: string)
    {
        this.deleteContent();

        this.$context.find('.inner_content').prepend(content);
    }

    public deleteContent()
    {
        this.$context.find('.inner_content').empty();
    }

    public static create($context = $(AuthModal.CLASS_MODAL)): AuthModal
    {
        return new AuthModal($context);
    }
}