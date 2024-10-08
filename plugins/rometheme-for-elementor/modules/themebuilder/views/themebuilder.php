<div class="spacer-2"></div>
<?php
require RomeTheme::plugin_dir() . 'view/header.php';

?>
<div class="d-flex flex-column gap-3 me-3  mb-3 rtm-container rounded-2 rtm-bg-gradient-1 rtm-text-font" style="margin-top: -8rem;">
    <div class="px-5 rounded-3 pb-5">
        <div class="spacer"></div>
        <div class="row row-cols-lg-2 row-cols-1 p-4">
            <div class="col col-lg-7">
                <div class="d-flex flex-column gap-3">
                    <span class="accent-color">Build the Future</span>
                    <div class="d-flex flex-row gap-3 align-items-center ">
                        <h1 class="text-white text-nowrap m-0">
                            Themebuilder
                        </h1>
                        <div class="rtm-divider rounded-pill"></div>
                    </div>
                    <p class="text">
                        Make the best experience when using RomethemeKit by learning and seeing how to use it,
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="rtm-bg-gradient-3 rounded-3 me-3">
    <ul class="nav sub-nav py-3 px-5 justify-content-between rtm-border-bottom" id="pills-tab" role="tablist">
        <ul class="d-flex flex-row align-items-center ">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-all-tab" data-bs-toggle="pill" data-bs-target="#tab-all" type="button" role="tab" aria-controls="tab-all" aria-selected="true">All</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-header-tab" data-bs-toggle="pill" data-bs-target="#tab-header" type="button" role="tab" aria-controls="tab-header" aria-selected="true">Header</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-footer-tab" data-bs-toggle="pill" data-bs-target="#tab-footer" type="button" role="tab" aria-controls="tab-footer" aria-selected="false">Footer</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-singlepost-tab" data-bs-toggle="pill" data-bs-target="#tab-singlepost" type="button" role="tab" aria-controls="tab-singlepost" aria-selected="false">Single Post</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-errorpage-tab" data-bs-toggle="pill" data-bs-target="#tab-errorpage" type="button" role="tab" aria-controls="tab-errorpage" aria-selected="false">404 Page</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-archivepage-tab" data-bs-toggle="pill" data-bs-target="#tab-archivepage" type="button" role="tab" aria-controls="tab-archivepage" aria-selected="false">Archive</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-formpage-tab" data-bs-toggle="pill" data-bs-target="#tab-formpage" type="button" role="tab" aria-controls="tab-formpage" aria-selected="false">Form</button>
            </li>
        </ul>
        <li class="nav-item" role="presentation">
            <button class="nav-link invalid-color fw-semibold" id="tab-trash-tab" data-bs-toggle="pill" data-bs-target="#tab-trash" type="button" role="tab" aria-controls="tab-trash" aria-selected="false"><i class="fas fa-trash-can me-1"></i>Trash</button>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="tab-all" role="tabpanel" aria-labelledby="tab-all-tab" tabindex="0">
            <div class="p-5">
                <?php
                require RomeTheme::module_dir() . 'themebuilder/views/all.php';
                ?>
            </div>
        </div>
        <div class="tab-pane fade" id="tab-header" role="tabpanel" aria-labelledby="tab-header-tab" tabindex="0">
            <div class="p-5">
                <?php
                require RomeTheme::module_dir() . 'HeaderFooter/views/header.php';
                ?>
            </div>
        </div>
        <div class="tab-pane fade" id="tab-footer" role="tabpanel" aria-labelledby="tab-footer-tab" tabindex="0">
            <div class="p-5">
                <?php
                require RomeTheme::module_dir() . 'HeaderFooter/views/footer.php';
                ?>
            </div>

        </div>
        <div class="tab-pane fade" id="tab-singlepost" role="tabpanel" aria-labelledby="tab-singlepost-tab" tabindex="0">
            <div class="p-5">
                <?php
                if (class_exists('RomethemePro')) {
                    require RomethemePro::module_dir() . '/single/view/single-view.php';
                } else {
                    require RomeTheme::module_dir() . 'themebuilder/views/getproversion.php';
                }
                ?>
            </div>
        </div>
        <div class="tab-pane fade" id="tab-errorpage" role="tabpanel" aria-labelledby="tab-errorpage-tab" tabindex="0">
            <div class="p-5">
                <?php
                if (class_exists('RomethemePro')) {
                    require RomethemePro::module_dir() . '/404/views/404-view.php';
                } else {
                    require RomeTheme::module_dir() . 'themebuilder/views/getproversion.php';
                }
                ?>
            </div>
        </div>
        <div class="tab-pane fade" id="tab-archivepage" role="tabpanel" aria-labelledby="tab-archivepage-tab" tabindex="0">
            <div class="p-5">
                <?php
                if (class_exists('RomethemePro')) {
                    require RomethemePro::module_dir() . '/archive/view/archive-view.php';
                } else {
                    require RomeTheme::module_dir() . 'themebuilder/views/getproversion.php';
                }
                ?>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-formpage" role="tabpanel" aria-labelledby="tab-formpage-tab" tabindex="0">
            <div class="p-5">
                <?php if (class_exists('RomeThemeForm')) {
                    require RomethemeForm::module_dir() . 'form/views/form-view.php';
                } else {
                    require Rometheme::module_dir() . 'Form/form-view.php';
                }
                ?>
            </div>
        </div>

        <div class="tab-pane fade" id="tab-trash" role="tabpanel" aria-labelledby="tab-trash-tab" tabindex="0">
            <div class="p-5">
                <?php
                require RomeTheme::module_dir() . 'themebuilder/views/trash.php';
                ?>
            </div>
        </div>
    </div>
</div>

<?php require_once \RomeTheme::module_dir() . 'themebuilder/views/modal.php'; ?>