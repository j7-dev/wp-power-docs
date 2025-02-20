<?php
/**
 * 手機板 Sider 出現的選單
 */

?>
<div class="h-10 flex xl:tw-hidden items-center px-4" style="border-bottom: 1px solid var(--fallback-bc,oklch(var(--bc)/.1))">
	<div id="doc-detail__sider-toggle" class="flex items-center gap-x-2 text-sm cursor-pointer">
		<svg class="size-4 stroke-base-content/30" viewBox="0 0 48 48" fill="none">
			<path d="M42 9H6" stroke="#78716c" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
			<path d="M34 19H6" stroke="#78716c" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
			<path d="M42 29H6" stroke="#78716c" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
			<path d="M34 39H6" stroke="#78716c" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
		</svg>
		選單
	</div>
</div>

<div id="doc-detail__black-wrap" class="tw-fixed tw-hidden top-0 left-0 w-full h-full bg-black/50 z-[9999]"></div>
<script>
	(function($) {
		$(document).ready(function() {
			class SiderToggler{
				_isOpen = false;
				$toggle = null;
				$blackWrap = null;
				$sider = null;

				constructor(){
					this.init();
					this.attachEvent();
				}

				get isOpen(){
					return this._isOpen;
				}

				set isOpen(value) {
					this._isOpen = value;

					console.log('setter', this._isOpen);

					if(this._isOpen){
						// 開啟時要做什麼
						this.$sider.animate({
							left: '0'
						}, 300);
						this.$blackWrap.fadeIn();
						return;
					}

					// 關閉時要做什麼
					this.$sider.animate({
						left: '-100%'
					}, 300);
					this.$blackWrap.fadeOut();
				}

				init(){
					this.$toggle = $('#doc-detail__sider-toggle');
					this.$blackWrap = $('#doc-detail__black-wrap');
					this.$sider = $('#doc-detail__sider');
				}

				attachEvent(){
					this.$toggle.click(() => {
						this.isOpen = !this.isOpen;
					});
					this.$blackWrap.click(() => {
						this.isOpen = false;
					});
				}
			}

			new SiderToggler();
		});
	})(jQuery);
</script>
