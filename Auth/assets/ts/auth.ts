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

		console.log('test');
	}

	public static create($context = $('.b_auth')): Auth
	{
		return new Auth($context);
	}
}