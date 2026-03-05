class AuthModal
{
    public readonly $context: JQuery;

    constructor($context: JQuery)
    {
        this.$context = $context;
        // fixme я просил не использовать этот метод комментирования только ручное с помощью // одна строка /** */ много строк
        // fixme вернуть этот код он нужен
        //
        // // @ts-ignore
        // if (this.$context[0].AuthModal) return this.$context[0].AuthModal;
        //
        // // @ts-ignore
        // this.$context[0].AuthModal = this;

        this.initExit();
    }

    // todo сделай приватным и вызывай из конструктора
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

    // fixme переписать этот метод на использование метода close
    private initExit()
    {
        this.$context.find('.exit').on('click',() =>
        {
            // fixme не удалять а скрывать, удаление в контекте, что ты создаешь объекты этого класса может вызывать проблемы
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

    public close()
    {
        // todo
    }


    // fixme переименовать в setContent
    public setForm(form: string)
    {
        this.deleteForm();

        this.$context.find('.content_modal').prepend(form);

        // fixme удалить, этот скрипт должен быть инлайн скриптом в html который ты здесь вставляешь
        Auth.create();
        // fixme удалить, вижу везде эти вызовы видимо это связано с тем что ты удаляешь а не скрываешь, поэтому удалять и нельзя
        AuthModal.create();
    }

    // fixme переименовать в deleteContent
    private deleteForm()
    {
        // fixme переписать в соответствии с новым названием
        this.$context.find('.b_auth').remove();
    }

    public static create($context = $('.b_auth_modal')): AuthModal
    {
        return new AuthModal($context);
    }
}