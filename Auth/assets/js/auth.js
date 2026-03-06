class Auth {
    constructor($context) {
        this.$context = $context;
        // @ts-ignore
        if (this.$context[0].Auth)
            return this.$context[0].Auth;
        // @ts-ignore
        this.$context[0].Auth = this;
        // todo добавь тут функционал скрытия сообщения об ошибке когда человек начал менять поле рядом с которым это сообщение об ошибке
    }
    static create($context = $('.b_auth')) {
        return new Auth($context);
    }
}
