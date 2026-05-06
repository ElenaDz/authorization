class LK {
    constructor($context) {
        this.$context = $context;
        // @ts-ignore
        if (this.$context[0].LK)
            return this.$context[0].LK;
        // @ts-ignore
        this.$context[0].LK = this;
    }
    static create($context = $('.b_lk')) {
        return new LK($context);
    }
}
