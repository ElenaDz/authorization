class LK
{
    public readonly $context: JQuery;

    constructor($context: JQuery)
    {
        this.$context = $context;

        // @ts-ignore
        if (this.$context[0].LK) return this.$context[0].LK;

        // @ts-ignore
        this.$context[0].LK = this;

    }

    public static is_authorized($context: JQuery = $('.b_lk')): boolean {
        return !!$context.data('is_authorized');
    }

    public static create($context = $('.b_lk')): LK
    {
        return new LK($context);
    }
}