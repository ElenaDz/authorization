
let showErrorAjix = (jqXHR: XMLHttpRequest) =>
{
	const [title, ...rest] = jqXHR.responseText.split('\n');
	const msg = rest.join('\n');

	console.error(title+"\r\n"+msg);

	butterup.options.maxToasts = 3;
	butterup.options.toastLife = 20*1000;

	butterup.toast({
		title: title,
		message: msg,
		location: 'bottom-right',
		type: 'error',
		icon: true,
		dismissable: true,
	});
};