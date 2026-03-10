class Auth {
    constructor($context) {
        this.$context = $context;
        // @ts-ignore
        if (this.$context[0].Auth)
            return this.$context[0].Auth;
        // @ts-ignore
        this.$context[0].Auth = this;
        this.initErrorHide();
    }
    initErrorHide() {
        this.$context.find('input').on('input', (input) => {
            let $input = $(input.currentTarget);
            $input.parents('.error').removeClass('error');
            let item_button = this.$context.find('button').parents('.error');
            item_button.removeClass('error');
        });
    }
    static create($context = $('.b_auth')) {
        return new Auth($context);
    }
}
