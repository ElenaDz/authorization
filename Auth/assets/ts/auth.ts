// todo ты задаешься вопросом за что отвечает этот класс? пока ни за что, пока оставляй пустым
class Auth
{
	private readonly $context: JQuery;
	private auth_modal: AuthModal;

	constructor($context: JQuery)
	{
		this.$context = $context;

		// @ts-ignore
		if (this.$context[0].Auth) return this.$context[0].Auth;

		// @ts-ignore
		this.$context[0].Auth = this;

		// fixme удалить, авторизация ни чего не знает кнопку авторизации это кнопка знает про авторизацию но не наоборот
		AuthBtn.create();

		// fixme удалить, авторизация ни чего не знает окно авторизации
		this.auth_modal = AuthModal.create();

		this.initSubmit();
		this.initReg();
		this.initRecoverPass();
	}

	// fixme удалить, ответственность за эту функциональность лежит на кнопке авторизации а не на авторизации
	private initSubmit()
	{
		this.$context.find('form').on('submit',(e) =>
		{
			e.preventDefault();

			let form = $(e.currentTarget)

			$.ajax({
				url: form.attr("action"),
				data: form.serialize(),
				type:'POST',
				dataType:'html',
				success: (response)=>{
					let element = Auth.getHtmlData(response).querySelector('.b_auth_btn');

					let auth_btn_str = $(element).html();

					if (auth_btn_str)
					{
						let auth_btn = AuthBtn.create();

						auth_btn.resetButton(auth_btn_str);

						this.auth_modal.$context.remove();
					}

					this.auth_modal.setForm(response);
				}
			});
		});
	}

	// fixme во первых перенести в кнопку авторизации
	// fixme во вторых переделать на работу с любой ссылкой в рамках контанта внутри модального окна
	private initReg()
	{
		this.$context.find('.reg_a').on('click',(e) =>
		{
			e.preventDefault();

			let url = $(e.currentTarget).attr('href');

			this.loadForm(url);
		});
	}

	// fixme удалить так как вышестоящий метод будет универсальным для всех ссылок
	private initRecoverPass()
	{
		this.$context.find('.recover_pass_a').on('click',(e) =>
		{
			e.preventDefault();

			let url = $(e.currentTarget).attr('href');

			this.loadForm(url);
		});
	}

	private loadForm(url)
	{
		$.get(url, (data) =>
		{
			// fixme что это? используй jquery там все есть Не знаю что ты тут пытаешься делать, возможно подойдет функцию html()
			let element = Auth.getHtmlData(data).querySelector('.b_auth');

			let form = $(element)[0].outerHTML;

			this.auth_modal.setForm(form);
		});
	}

	// fixme удалить
	public static getHtmlData(data)
	{
		let parser = new DOMParser();

		return parser.parseFromString(data, 'text/html');
	}

	public static create($context = $('.b_auth')): Auth
	{
		return new Auth($context);
	}
}