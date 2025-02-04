<?php
/**
 * Main 主體
 *  - 麵包屑
 *  - 內文
 *  - 子章節
 */

use J7\PowerDocs\Plugin;

echo /*html*/'<div class="px-8 py-6">';

Plugin::get('breadcrumb');

echo /*html*/'</div>';
