interface ButterupOptions {
	title?: string;
	message?: string;
	type?: 'success' | 'error' | 'warning' | 'info';
	location?: 'top-left' | 'top-right' | 'bottom-left' | 'bottom-right' | 'top-center' | 'bottom-center';
	icon?: boolean;
	dismissable?: boolean;
	onRender?: (toast: HTMLElement) => void;
	onClick?: (toast: HTMLElement) => void;
}

declare const butterup: {
	// Основной метод
	toast: (options: ButterupOptions) => void;

	// Глобальные настройки
	options: {
		maxToasts: number;
		toastLife: number;
		[key: string]: any; // Для прочих внутренних настроек
	};

	// Вспомогательные методы (пресеты)
	success: (options: ButterupOptions) => void;
	error: (options: ButterupOptions) => void;
	warning: (options: ButterupOptions) => void;
	info: (options: ButterupOptions) => void;
};