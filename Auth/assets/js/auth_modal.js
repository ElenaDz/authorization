class AuthModal {
    constructor($context) {
        if ($context.length == 0) {
            this.render();
            $context = $(AuthModal.CLASS_MODAL);
        }
        this.$context = $context;
        // fixme вернуть этот код он нужен ok
        // @ts-ignore
        if (this.$context[0].AuthModal)
            return this.$context[0].AuthModal;
        // @ts-ignore
        this.$context[0].AuthModal = this;
        this.initExit();
    }
    // todo сделай приватным и вызывай из конструктора ok
    render() {
        $('body').prepend(this.getHtml());
    }
    getHtml() {
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
    // fixme переписать этот метод на использование метода close ок
    initExit() {
        this.$context.find('.exit').on('click', () => {
            // fixme не удалять а скрывать, удаление в контекте, что ты создаешь объекты этого класса может вызывать проблемы ок
            this.close();
        });
        $('html').on('click', (e) => {
            if ($(e.target).hasClass('model_fon')) {
                this.close();
            }
        });
    }
    open() {
        this.$context.addClass('open');
    }
    close() {
        // todo ок
        this.$context.removeClass('open');
    }
    // fixme переименовать в setContent ok
    setContent(content) {
        this.deleteContent();
        this.$context.find('.inner_content').prepend(content);
        // fixme удалить, этот скрипт должен быть инлайн скриптом в html который ты здесь вставляешь ok
        // fixme удалить, вижу везде эти вызовы видимо это связано с тем что ты удаляешь а не скрываешь, поэтому удалять и нельзя ok
    }
    // fixme переименовать в deleteContent ok
    deleteContent() {
        // fixme переписать в соответствии с новым названием ok
        this.$context.find('.inner_content').empty();
    }
    static create($context = $(AuthModal.CLASS_MODAL)) {
        return new AuthModal($context);
    }
}
AuthModal.CLASS_MODAL = '.b_auth_modal';
