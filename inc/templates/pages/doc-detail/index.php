<?php
/**
 * 文件詳情頁
 * 由 sider 和 main 組成
 */

use J7\PowerDocs\Plugin;

Plugin::get('doc-detail/sider/mobile-menu');

echo /*html*/ '<div class="flex flex-col xl:flex-row tw-container mx-auto pt-8 bn-container">';


echo /*html*/ '<div id="doc-detail__sider" class="
z-[10000]
[&_#pd-sider]:py-4 pl-4 bg-base-200 h-screen overflow-auto w-3/4 max-[calc(100vw-3rem)] tw-fixed top-0 left-[-100%]
[&_#pd-sider]:xl:py-0 xl:pl-0 xl:bg-transparent xl:h-auto xl:overflow-visible xl:w-72 xl:block xl:relative xl:top-[unset] xl:left-[unset]">';
Plugin::get('doc-detail/sider');
echo /*html*/ '</div>';

echo /*html*/ '<div class="flex-1">';
Plugin::get('doc-detail/main');
echo /*html*/ '</div>';

echo /*html*/ '<div class="w-72 tw-hidden xl:block">';

echo /*html*/ '</div>';

echo /*html*/ '</div>';
