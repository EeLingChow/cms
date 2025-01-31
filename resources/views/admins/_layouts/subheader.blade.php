<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title" data-section="title">
                @yield('title')
            </h3>
            <span class="kt-subheader__separator kt-hidden"></span>
            <div class="kt-subheader__breadcrumbs" data-section="breadcrumb">
                @yield('breadcrumb')
            </div>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper" data-section="subheader-tools">
                @yield('subheader-tools')
                <!-- <a href="#" class="btn kt-subheader__btn-primary">Actions</a> -->
            </div>
        </div>
    </div>
    <div class="progress" id="page-progress">
        <div class="progress-bar kt-bg-warning" role="progressbar" style="width: 0;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

</div>