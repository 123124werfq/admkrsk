<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-2-third">
                <ol class="breadcrumbs">
                    <li class="breadcrumbs_item"><a href="/">Главная</a></li>
                    <li class="breadcrumbs_item"><span>Пресс-центр</span></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-2-third order-xs-1">
                <h1>Поиск по сайту</h1>
            </div>
        </div>
        <hr class="hr hr__large hr__mt" style="margin: 0px 0px 10px;">

        <div class="search-section" style="padding-bottom: 0px;">
            <div class="ya-site-form ya-site-form_bg_transparent ya-site-form_inited_yes" id="ya-site-form0">
                <div class="ya-site-form__form">
                    <table class="ya-site-form__wrap" cellspacing="0" cellpadding="0" style="width: 80%">
                        <tbody>
                        <tr>
                            <td class="ya-site-form__search-wrap">
                                <table class="ya-site-form__search" cellspacing="0" cellpadding="0">
                                    <tbody>
                                    <tr>
                                        <td class="ya-site-form__search-input">
                                            <form method="GET">
                                            <table class="ya-site-form__search-input-layout">
                                                <tbody>
                                                <tr>
                                                    <td class="ya-site-form__search-input-layout-l">
                                                        <div class="ya-site-form__input">
                                                            <input name="q" type="search" value="<?=$request?>" class="ya-site-form__input-text" placeholder="" autocomplete="off">
                                                            <div class="ya-site-suggest">
                                                                <div class="ya-site-suggest-popup" style="display: none;">
                                                                    <i class="ya-site-suggest__opera-gap"></i>
                                                                    <div class="ya-site-suggest-list">
                                                                        <div class="ya-site-suggest__iframe"></div>
                                                                        <ul class="ya-site-suggest-items"></ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="ya-site-form__search-input-layout-r">
                                                        <input class="ya-site-form__submit" type="submit" value="Найти">
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ya-site-form__gap">
                                            <div class="ya-site-form__gap-i"></div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="search-results content">
        <?php
            foreach ($result as $row)
            {
                if(is_numeric($row['content_date']))
                    echo "<p><strong><a href='{$row['url']}'>{$row['header']}</a></strong> / <small>".date("d.m.Y", $row['content_date'])."</small><br>...{$row['headline']}...</p>";
                else
                    echo "<p><strong><a href='{$row['url']}'>{$row['header']}</a></strong><br>...{$row['headline']}...</p>";
            }

        ?>
        </div>
    </div>
</div>