class Auth
{
	private readonly $context: JQuery;

	constructor($context: JQuery)
	{
		this.$context = $context;

		// @ts-ignore
		if (this.$context[0].Auth) return this.$context[0].Auth;

		// @ts-ignore
		this.$context[0].Auth = this;

		this.initErrorHide();
	}

	private initErrorHide()
	{
		this.$context.find('input').on('input',(input) =>
		{
			let $input = $(input.currentTarget);

			$input.parents('.error_auth').removeClass('error_auth');

			let item_button = this.$context.find('button').parents('.error_auth');

			item_button.removeClass('error_auth');
		})
	}

	public static create($context = $('.b_auth')): Auth
	{
		return new Auth($context);
	}
}