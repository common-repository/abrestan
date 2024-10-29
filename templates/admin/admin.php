<?php
$login=false;
if (get_option('abrestan_login')) {
    $login = true;
}
GenerateLog();
$statuses = array(
    'pending' => __('Pending payment', 'abrestan'),
    'processing' => __('Processing', 'abrestan'),
    'on-hold' => __('On hold', 'abrestan'),
    'completed' => __('Completed', 'abrestan'),
    'cancelled' => __('Cancelled', 'abrestan'),
    'refunded' => __('Refunded', 'abrestan'),
    'failed' => __('Failed', 'abrestan'),
    'checkout-draft' => __('Draft', 'abrestan'),
);
?>
<div class="setting-page-plugin">
    <div class="uk-column-1-1">
        <div class="wrap">
            <div class=" uk-flex uk-flex-between uk-flex-wrap-middle uk-flex-middle	uk-background-default uk-padding-small"
                 uk-sticky="offset: 30;" style="position: relative">
                <img src="<?php echo esc_attr(ABR_ADMIN_IMG) . 'logo-abrestan.png' ?>" alt="">
                <span class="uk-padding-small"
                      style="position: absolute;right: 250px;">نسخه <?php echo get_plugin_data(plugin_dir_path(dirname(__FILE__, 2)) . 'abrestan.php')['Version'] ?></span>
                <div>
                    <a class="uk-button uk-button-primary" target="_blank" href="https://kasb.abrestan.com">سایت
                        ابرستان</a>
                    <a class="uk-button uk-button-danger" target="_blank"
                       href="https://abrestan.com/help/%d8%b4%d8%b1%d9%88%d8%b9-%d8%a8%d9%87-%da%a9%d8%a7%d8%b1-%d8%a7%d8%a8%d8%b1%d8%b3%d8%aa%d8%a7%d9%86/">راهنما</a>
                </div>
            </div>
            <div uk-grid>
                <div class="uk-width-1-4 ">
                    <ul class="uk-nav uk-nav-default bg-head-settings" uk-sticky="offset: 90;"
                        uk-switcher="connect: #component-nav; animation: uk-animation-fade; active:<?php //echo $tab ?>;"
                        id="abrestan_nav_setting">
                        <li>
                            <a href="#">
                                <p><?php _e('connect', 'abrestan') ?></p>
                                <span><?php _e('Connect the site to the web service', 'abrestan') ?></span>
                                <span class="icon-m" uk-icon="link"></span>
                            </a>
                        </li>
                        <li class="abrestan_tab_setting"
                            style="<?php echo($login ? "display:flex" : 'display:none'); ?>"
                        >
                            <a href="#">
                                <p><?php _e('companies', 'abrestan') ?></p>
                                <span><?php _e('select company', 'abrestan') ?></span>
                                <span class="icon-m" uk-icon="album"></span>
                            </a>
                        </li>

                        <li class="abrestan_tab_setting"
                            style="<?php echo($login ? "display:flex" : 'display:none'); ?>"
                        >
                            <a href="#">
                                <p><?php _e('orders', 'abrestan') ?></p>
                                <span><?php _e('Sync orders', 'abrestan') ?></span>
                                <span class="icon-m" uk-icon="cloud-upload"></span>
                            </a>
                        </li>

                        <li class="abrestan_tab_setting"
                            style="<?php echo($login ? "display:flex" : 'display:none'); ?>">
                            <a href="#">
                                <p><?php _e('رخدادها', 'abrestan') ?></p>
                                <span><?php _e('نمایش رخدادها', 'abrestan') ?></span>
                                <span class="icon-m" uk-icon="file-text"></span>
                            </a>
                        </li>


                        <li class="abrestan_tab_setting"
                            style="<?php echo($login ? "display:flex" : 'display:none'); ?>">
                            <a href="#">
                                <p><?php _e('تنظیمات', 'abrestan') ?></p>
                                <span><?php _e('تنظیمات ابرستان', 'abrestan') ?></span>
                                <span class="icon-m" uk-icon="file-text"></span>
                            </a>
                        </li>

                    </ul>
                </div>
                <div style="padding-right:0px;background-color: #fff" class="uk-width-3-4">
                    <ul id="component-nav" class="uk-switcher content-setting">
                        <li>
                            <div class="uk-form-horizontal uk-margin-large">
                                <input type="hidden" name="action" value="login">
                                <div class="header-title-admin uk-margin">
                                    <div class="title-admin-AAM"><?php _e('Connect the site to the web service', 'abrestan') ?></div>
                                </div>
                                <div class="template-plugin-AAM">
                                    <div id="alert-success-login" class="uk-text-lighter" uk-alert style="display: none"
                                         dir="rtl">
                                        <p></p>
                                    </div>
                                    <div class="uk-alert-primary uk-text-lighter" uk-alert>
                                        <p>
                                            برای اتصال افزونه به ابرستان به عنوان نام کاربری،
                                            <span
                                                    class="uk-text-danger">ایمیل و یا شماره موبایل</span>
                                            خود را وارد نمایید.
                                        </p>
                                    </div>
                                    <div class="uk-alert-primary uk-text-lighter" uk-alert>
                                        <p>
                                            در هنگام وارد نمودن رمز عبور به
                                            <span class="uk-text-danger">حروف کوچک یا بزرگ</span>
                                            دقت نمایید.
                                        </p>
                                    </div>
                                    <div class="uk-margin">
                                        <label class="uk-form-label"
                                               for="form-horizontal-text"><?php _e('username', 'abrestan') ?></label>
                                        <div class="uk-form-controls ">
                                            <input class="uk-input uk-text-left " dir="ltr"
                                                   id="form-horizontal-text" type="text"
                                                   placeholder=""
                                                   name="username"
                                                   value=""
                                                <?php
                                                echo($login ? "disabled" : '');
                                                ?>
                                            >
                                        </div>
                                    </div>
                                    <div class="uk-margin">
                                        <label class="uk-form-label"
                                               for="form-horizontal-text1"><?php _e('password', 'abrestan') ?></label>
                                        <div class="uk-form-controls">
                                            <input class="uk-input uk-text-left" dir="ltr"
                                                   id="form-horizontal-text1"
                                                   type="password"
                                                   name="password"
                                                   value=""
                                                <?php
                                                echo($login ? "disabled" : '');
                                                ?>
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-flex uk-flex-around	">
                                        <button class="uk-button uk-button-primary uk-button-large btn_loader"
                                                style="<?php echo($login ? "display:none" : 'display:flex'); ?>"
                                                id="abrestan_login_btn"><?php _e('login', 'abrestan') ?>
                                            <div class="loader"></div>
                                        </button>
                                        <button class="uk-button uk-button-primary uk-button-large btn_loader"
                                                style="<?php echo($login ? "display:flex" : 'display:none'); ?>"
                                                id="abrestan_logout_btn"><?php _e('logout', 'abrestan') ?>
                                            <div class="loader"></div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="uk-form-horizontal uk-margin-large">
                                <input type="hidden" name="action" value="company">
                                <div class="header-title-admin uk-margin">
                                    <div class="title-admin-AAM"><?php _e('Connect the site to the web service', 'abrestan') ?></div>
                                </div>
                                <div class="template-plugin-AAM">
                                    <div id="alert-success-company" class="uk-text-lighter" uk-alert
                                         style="display: none" dir="rtl">
                                        <p></p>
                                    </div>
                                    <div class="uk-margin">
                                        <select class="uk-select" name="company_code">
                                            <option value="0"> - انتخاب کنید -</option>
                                            <?php
                                            $companies = get_option('abrestan_companies_list');

                                            $i = 0;
                                            if ($companies){
                                                foreach ($companies as $company) {
                                                    ?>
                                                    <option <?php echo (get_option('abrestan_company') && get_option('abrestan_company')['companyCode'] == esc_attr($company['company_id'])) ? 'selected' : ''; ?>
                                                            value="<?php echo esc_attr($company['company_id']) ?>"><?php echo esc_attr($company['company_name']) ?></option>
                                                    <?php
                                                    $i++;
                                                }
                                            }

                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-flex uk-flex-around	">
                                        <button class="uk-button uk-button-primary uk-button-large btn_loader"
                                                id="abrestan_company_btn">
                                            <?php _e('save setting', 'abrestan') ?>
                                            <div class="loader"></div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="uk-form-horizontal uk-margin-large">
                                <div class="header-title-admin uk-margin">
                                    <div class="title-admin-AAM"><?php _e('Sync orders', 'abrestan') ?></div>
                                </div>
                                <div id="alert-success-sync_orders" uk-alert style="display: none" dir="rtl">
                                    <p></p>
                                </div>
                                <div class="uk-alert-warning uk-text-lighter" uk-alert>
                                    <p>
                                        با زدن دکمه همگام سازی در بازه زمانی انتخاب شده تمامی سفارشاتی که در وضعیت تکمیل
                                        شده قرار گرفته اند با ابرستان همگام سازی میشوند.
                                    </p>
                                </div>
                                <div class="uk-alert-warning uk-text-lighter" uk-alert>
                                    <p>
                                        ممکن است برخی از سفارشات به دلیل عدم موجودی کالا در ابرستان همگام سازی نشود.
                                    </p>
                                </div>
                                <div class="uk-margin  uk-grid ">
                                    <label class="uk-form-label "
                                           for="from-date"><?php _e('from date', 'abrestan'); ?></label>
                                    <input dir="ltr" class="uk-input uk-form-width-small" id="from-date" type="text"
                                           name="from-date" placeholder="1400/01/01" value="1401/05/01">
                                </div>
                                <div class="uk-margin  uk-grid ">
                                    <label class="uk-form-label"
                                           for="to-date"><?php _e('to date', 'abrestan'); ?></label>
                                    <input dir="ltr" class="uk-input uk-form-width-small" id="to-date" type="text"
                                           name="to-date" placeholder="1400/12/29" value="1401/05/29">
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-flex uk-flex-around	">
                                        <button class="uk-button uk-button-primary uk-button-large btn_loader"
                                                id="abrestan_sync_orders_btn"><?php _e('Sync orders', 'abrestan') ?>
                                            <div class="loader"></div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="uk-form-horizontal uk-margin-large">
                                <div class="header-title-admin uk-margin">
                                    <div class="title-admin-AAM"><?php _e('نمایش رخدادها', 'abrestan') ?></div>
                                </div>
                                <div class="uk-overflow-auto">
                                    <table dir="ltr"
                                           class=" uk-text-lighter uk-table uk-table-small uk-table-divider log-table">
                                        <thead>
                                        <tr>
                                            <th class="uk-table-shrink">ردیف</th>
                                            <th class="uk-table-expand">زمان</th>
                                            <th class="uk-table-shrink">وضعیت</th>
                                            <th class="uk-table-small">اقدام</th>
                                            <th class="uk-table-expand">پیام</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?PHP
                                        $logs = get_log();
                                        foreach ($logs as $log) {
                                            ?>
                                            <?php echo ($log->status == "ERROR") ? "<tr class='uk-alert-danger'>" : "" ?>
                                            <?php echo ($log->status == "WARNING") ? "<tr class='uk-text-warning'>" : "" ?>
                                            <td><?php echo esc_attr($log->id) ?></td>
                                            <td><?php echo esc_attr($log->time) ?></td>
                                            <td><?php echo esc_attr($log->status) ?></td>
                                            <td><?php echo esc_attr($log->action) ?></td>
                                            <td class="uk-text-left"><?php echo esc_attr($log->message) ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </li>


                        <li>
                            <div id="alert_success_save_setting" class="uk-text-lighter" uk-alert
                                 style="display: none" dir="rtl">
                                <p></p>
                            </div>
                            <!--
                            <div class="uk-form-horizontal uk-margin-large">
                                <div class="header-title-admin uk-margin">
                                    <div class="title-admin-AAM"><?php _e('Set abrestan', 'abrestan') ?></div>
                                </div>
                                <div id="alert_success_save_setting" uk-alert style="display: none" dir="rtl">
                                    <p></p>
                                </div>

                                <div class="uk-margin">
                                    <div class="uk-alert-primary uk-text-lighter" uk-alert>
                                        <p>
                                            برای همگام سازی خودکار سفارش باید وضعیت سفارش را در یکی از حالات انتخابی زیر قرار داد.
                                        </p>
                                    </div>
                                    <label class="uk-form-label"
                                           for="form-horizontal-add-factor"><?php _e('Invoice Sync in Status:', 'abrestan') ?></label>
                                    <div class="uk-form-controls">
                                        <select class="uk-select uk-form-width-large"
                                                id="form-horizontal-add-factor" name="add_factor">
                                            <option value="0"> - انتخاب کنید -</option>
                                            <?php
                            foreach ($statuses as $statusCode => $statusname) {
                                ?>
                                                <option <?php echo (get_option('abrestan_setting')['add_factor'] == esc_attr($statusCode)) ? 'selected' : ''; ?>
                                                        value=<?php echo esc_attr($statusCode) ?> >
                                                    <?php echo esc_attr($statusname) ?>
                                                </option>
                                                <?php
                            }
                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-alert-primary uk-text-lighter" uk-alert>
                                        <p>
                                            برای حذف خودکار سفارش باید وضعیت سفارش را در یکی از حالات انتخابی زیر قرار داد.
                                        </p>
                                    </div>
                                    <label class="uk-form-label"
                                           for="form-horizontal-delete-factor"><?php _e('Delete invoice in status:', 'abrestan') ?></label>
                                    <div class="uk-form-controls ">
                                        <select class="uk-select uk-form-width-large"
                                                id="form-horizontal-delete-factor"  name="delete_factor">
                                            <option value="0"> - انتخاب کنید -</option>
                                            <?php
                            foreach ($statuses as $statusCode => $statusname) {
                                ?>
                                                <option <?php echo (get_option('abrestan_setting')['delete_factor'] == $statusCode) ? 'selected' : ''; ?>
                                                        value=<?php echo esc_attr($statusCode) ?>>
                                                    <?php echo esc_attr($statusname) ?>
                                                </option>
                                                <?php
                            }
                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-alert-primary uk-text-lighter" uk-alert>
                                        <p>
                                            نام دسته بندی برای محصولات همگام سازی شده با ابرستان را وارد نمایید.
                                        </p>
                                    </div>
                                    <label class="uk-form-label"
                                           for="form-horizontal-category-product"><?php _e('products categorization:', 'abrestan') ?></label>
                                    <div class="uk-form-controls">
                                        <input class="uk-input  uk-form-width-medium"
                                               id="form-horizontal-category-product" type="text" name="category_product"
                                               value="<?php echo (get_option('abrestan_setting')['category_product']) ? esc_attr(get_option('abrestan_setting')['category_product']) : 'محصولات فروشگاه'; ?>">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-alert-primary uk-text-lighter" uk-alert>
                                        <p>
                                            نام دسته بندی برای کاربران همگام سازی شده با ابرستان را وارد نمایید.
                                        </p>
                                    </div>
                                    <label class="uk-form-label"
                                           for="form-horizontal-category-users"><?php _e('users categorization:', 'abrestan') ?></label>
                                    <div class="uk-form-controls">
                                        <input class="uk-input  uk-form-width-medium"
                                               id="form-horizontal-category-users" type="text" name="category_user"
                                               value="<?php echo (get_option('abrestan_setting')['category_user']) ? esc_attr(get_option('abrestan_setting')['category_user']) : 'کاربران فروشگاه'; ?>">
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <div class="uk-alert-primary uk-text-lighter" uk-alert>
                                        <p>
                                            نام دسته بندی برای کاربران همگام سازی شده با ابرستان را وارد نمایید.
                                        </p>
                                    </div>
                                    <label class="uk-form-label"
                                           for="form-horizontal-category-users"><?php _e('users categorization:', 'abrestan') ?></label>
                                    <div class="uk-form-controls">
                                        <input class="uk-input  uk-form-width-medium"
                                               id="form-horizontal-category-users" type="text" name="category_user"
                                               value="<?php echo (get_option('abrestan_setting')['category_user']) ? esc_attr(get_option('abrestan_setting')['category_user']) : 'کاربران فروشگاه'; ?>">
                                    </div>
                                </div>
-->

                            <div class="uk-margin">
                                <div class="uk-alert-primary uk-text-lighter" uk-alert>
                                    <p>
                                        مقدار/ تعداد کالاهای صورتحساب را به موجودی اولیه کالا اضافه میکند.
                                    </p>
                                </div>

                                <label>
                                    <input class="uk-checkbox uk-form-width-medium" type="checkbox"
                                           name="AddFactorItemsToInitialBalance" <?php echo (get_option('abrestan_setting')['InitialBalance'] == "true") ? 'checked' : '' ?>> <?php _e('موجودی اولیه صورتحساب', 'abrestan') ?>
                                </label>
                            </div>
                            <div class="uk-margin">
                                <div class="uk-alert-primary uk-text-lighter" uk-alert>
                                    <p>
                                        با فعال نمودن این گزینه به صورت خودکار برای سفارش هایی که مشتری آن مهمان است
                                        کاربر ساخته میشود.
                                    </p>
                                </div>

                                <label>
                                    <input class="uk-checkbox uk-form-width-medium" type="checkbox"
                                           name="AddCustomerToOrder" <?php echo (get_option('abrestan_setting')['AddCustomerToOrder'] == "true") ? 'checked' : '' ?>> <?php _e('ساخت کاربر', 'abrestan') ?>
                                </label>
                            </div>
                            <div class="uk-margin">
                                <div class="uk-flex uk-flex-around	">
                                    <button class="uk-button uk-button-primary uk-button-large btn_loader"
                                            id="abrestan_setting_btn"><?php _e('save setting', 'abrestan') ?>
                                        <div class="loader"></div>
                                    </button>
                                </div>
                            </div>

                </div>
                </li>

                </ul>
            </div>
        </div>
    </div>
</div>
</div>



