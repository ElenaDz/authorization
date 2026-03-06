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

		// todo добавь тут функционал скрытия сообщения об ошибке когда человек начал менять поле рядом с которым это сообщение об ошибке
	}

	public static create($context = $('.b_auth')): Auth
	{
		return new Auth($context);
	}
}