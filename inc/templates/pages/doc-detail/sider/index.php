<?php
/**
 * Sider 組件
 */

global $post;

use J7\Powerhouse\Resources\Post\Utils as PostUtils;
use J7\PowerDocs\Resources\Doc\Utils;

$parent_id = PostUtils::get_top_post_id($post->ID);

$html = $parent_id ? Utils::get_children_posts_html($parent_id) : '';
?>

<style>
	.icon-arrow svg{
		transform: rotate(0deg);
		transition: all 0.3s ease-in-out;
	}
	.expanded .icon-arrow svg{
		transform: rotate(90deg);
		transition: all 0.3s ease-in-out;
	}
</style>

<div id="pd-sider" class="w-64 pr-2" style="display: none;border-right: 1px solid var(--fallback-bc,oklch(var(--bc)/.1));">
	<?php echo $html; ?>
</div>


<script type="module" async>
	(function($){
	// 點擊箭頭展開或收合章節
	$('#pd-sider').on('click', 'li .icon-arrow', function(){
		const $li = $(this).closest('li');
		const $sub_ul = $li.next('ul'); // 子章節

		if($sub_ul.length > 0){
			$li.toggleClass('expanded'); // 如果有找到子章節
			$sub_ul.slideToggle('fast'); // 如果有找到子章節
		}
	})

	// 跳轉頁面前先記錄展開的章節
	$('#pd-sider').on('click', 'li a', function(e){
		// 阻止原本的超連結行為
		e.preventDefault();
		e.stopPropagation();

		handle_save_expanded_post_ids()

		// 然後才跳轉頁面
		const href = $(this).attr('href');
		window.location.href = href;
	})

	// 離開頁面時，恢復章節的展開狀態
	$(window).on('beforeunload', function(e) {
		// 避免顯示確認框，不要使用 preventDefault()
		handle_save_expanded_post_ids()
	});

	restore_expanded_post_ids();

	// 把當前展開的章節 id 先記錄起來
	function handle_save_expanded_post_ids(){
		const expanded_post_ids = $('#pd-sider li.expanded').map(function(){
			return $(this).data('post-id');
		}).get();

		// 記錄到 sessionStorage
		sessionStorage.setItem('expanded_post_ids', JSON.stringify(expanded_post_ids));
	}

	// 恢復章節的展開狀態
	function restore_expanded_post_ids(){
		const expanded_post_ids_string = sessionStorage.getItem('expanded_post_ids') // 拿不到為 null
		const expanded_post_ids = expanded_post_ids_string ? JSON.parse(expanded_post_ids_string) : [];
		if(expanded_post_ids.length > 0){
			expanded_post_ids.forEach(function(post_id){
				const $li = $(`#pd-sider li[data-post-id="${post_id}"]`);
				if($li.length > 0){
					$li.addClass('expanded');
					$li.next('ul').show();
				}
			});
		}

		// 恢復完畢，清除 sessionStorage，顯示 #pd-sider
		sessionStorage.removeItem('expanded_post_ids');
		$('#pd-sider').show();
	}





	})(jQuery)
</script>
