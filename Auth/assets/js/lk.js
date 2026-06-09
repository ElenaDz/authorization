class LK {
    constructor($context) {
        this.$context = $context;
        // @ts-ignore
        if (this.$context[0].LK)
            return this.$context[0].LK;
        // @ts-ignore
        this.$context[0].LK = this;
        // поменять клик на проверку ссылки
        this.$context.find('.history').on('click', (event) => {
            // event.preventDefault();
            let url = $(event.currentTarget).data('href');
            let user_id = this.$context.data('user_id');
            $.ajax({
                url: url,
                type: 'GET',
                data: user_id,
            })
                .done((response, textStatus, jqXHR) => {
            });
        });
    }
    // fixme нотация
    // fixme убрать $context он уже есть у личного кабинета он задан через create
    static is_authorized($context = $('.b_lk')) {
        return !!$context.data('is_authorized');
    }
    static create($context = $('.b_lk')) {
        return new LK($context);
    }
}
