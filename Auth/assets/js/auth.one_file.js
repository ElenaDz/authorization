

class Auth {
    constructor($context) {
        this.$context = $context;
        // @ts-ignore
        if (this.$context[0].Auth)
            return this.$context[0].Auth;
        // @ts-ignore
        this.$context[0].Auth = this;
        console.log('test');
    }
    static create($context = $('.b_auth')) {
        return new Auth($context);
    }
}
