<?php
// Подключение стилей/скриптов
if (!is_admin()) {
	//подключаем стили
	add_action('get_footer', 'clean_wp_template_style');
	function clean_wp_template_style()
	{
		wp_enqueue_style('style', get_stylesheet_directory_uri() . '/assets/css/theme.min.css');
	}
	//подключаем скрипты
	add_action( 'wp_enqueue_scripts', 'clean_wp_template_scripts' );
	function clean_wp_template_scripts() 
	{
		/*
		* Приписка #asyncload в конце url скрипта добавляем асинхронную загрузку скриптов (можно работать и без нее)
		*/


		//добавляем свою версию jquery
		// wp_deregister_script('jquery');
		// wp_register_script('jquery', get_stylesheet_directory_uri() . '/assets/js/jquery-3.2.1.min.js#asyncload');
		// wp_enqueue_script('jquery');

		//добавляем свои скрипты в подвал сайта
		wp_enqueue_script( 'theme_js', get_stylesheet_directory_uri() . '/assets/js/theme.min.js#asyncload', '', '', true);
	} 
}

//Функция для асинхронной загрузки скриптов
add_filter('clean_url', 'add_async_forscript', 11, 1);
function add_async_forscript($url)
{
	if (strpos($url, '#asyncload')===false)
		return $url;
	else if (is_admin())
		return str_replace('#asyncload', '', $url);
	else
		return str_replace('#asyncload', '', $url)."' async='async"; 
}

//теперь тайтл управляется самим вп
add_theme_support('title-tag'); 

//регистрируем меню
register_nav_menus([
	'header_menu' => 'Меню в хэдере',
	'footer_menu' => 'Меню в футере',
]);

add_theme_support('post-thumbnails'); // включаем поддержку миниатюр
//set_post_thumbnail_size(250, 150); //Задаем кастомный размер миниатюры
//add_image_size('big-thumb', 400, 400, true); //Добавляем еще один размер картинкам

//добавляем noindex на страницы с вложениями (медиафайлы)
add_action('wp_head', 'attachmentpages_noindex');
function attachmentpages_noindex() {
	if(is_attachment()) {
		echo '<meta name="robots" content="noindex" />';
	}
}

//Добавляем кнопку сброса фильтров в админке
add_action( 'restrict_manage_posts', 'filter_reset_button' );
function filter_reset_button() {
	$type = 'post';
	if (isset($_GET['post_type'])) {
		$type = $_GET['post_type'];
	}
	echo '<input type="button" name="reset_button" class="button" value="Сбросить фильтр" onclick="location.href=\'?post_type='.$type.'\'" style="float: right; margin: 1px 8px 0 0;">';
}

//добавляем формат svg/webp на загрузку файлов в медиафайлы
add_filter('upload_mimes', 'cc_mime_types');
function cc_mime_types($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	$mimes['webp'] = 'image/webp';
	return $mimes;
}

//Include с напоминаниемя в админке при редактировании постов
require get_template_directory() . '/include/admin_notice.php';