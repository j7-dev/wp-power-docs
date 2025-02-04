<?php
/**
 * 文件詳情頁
 * 由 sider 和 main 組成
 */

use J7\PowerDocs\Plugin;

echo /*html*/'<div class="flex container mx-auto">';
Plugin::get('doc-detail/sider');
Plugin::get('doc-detail/main');
echo /*html*/'</div>';
