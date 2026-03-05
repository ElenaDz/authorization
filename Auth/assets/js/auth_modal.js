class AuthModal {
    constructor($context) {
        if ($context.length == 0) {
            this.render();
            $context = $(AuthModal.CLASS_MODAL);
        }
        this.$context = $context;
        // @ts-ignore
        if (this.$context[0].AuthModal)
            return this.$context[0].AuthModal;
        // @ts-ignore
        this.$context[0].AuthModal = this;
        this.initExit();
    }
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
    initExit() {
        this.$context.find('.exit').on('click', () => {
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
        this.$context.removeClass('open');
    }
    setContent(content) {
        this.deleteContent();
        this.$context.find('.inner_content').prepend(content);
    }
    deleteContent() {
        this.$context.find('.inner_content').empty();
    }
    static create($context = $(AuthModal.CLASS_MODAL)) {
        return new AuthModal($context);
    }
}
AuthModal.CLASS_MODAL = '.b_auth_modal';
