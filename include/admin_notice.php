<?php
//Добавляем блок с напоминанием на страницу редактирования/добавления постов
add_action('admin_notices','admin_notice_for_posts');
function admin_notice_for_koloda_edit_or_add()
{
	$screen = get_current_screen();

	if( $screen->post_type !='post' || $screen->base != 'post')
		return;
?>
	<div class="notice notice-info">
		<p>
			Текст с пояснением для администратора
		</p>
	</div>
<?php
}
//конец Добавляем блок с напоминанием на страницу редактирования/добавления постов