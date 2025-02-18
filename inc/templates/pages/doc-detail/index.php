<?php
/**
 * 文件詳情頁
 * 由 sider 和 main 組成
 */

use J7\PowerDocs\Plugin;

echo /*html*/'<div class="flex container mx-auto pt-8 bn-container">';

echo /*html*/'<div class="w-72 tw-hidden xl:block">';
Plugin::get('doc-detail/sider');
echo /*html*/'</div>';

echo /*html*/'<div class="flex-1">';
Plugin::get('doc-detail/main');
echo /*html*/'</div>';

echo /*html*/'<div class="w-72 tw-hidden xl:block">';

echo /*html*/'</div>';

echo /*html*/'</div>';
