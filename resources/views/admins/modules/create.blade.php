@php
$pageTitle = 'Create Module';
$isEdit = false;
$moduleName = 'modules';
@endphp

@extends('admins.layout')

@section('title', $pageTitle)

@section('breadcrumb')
    <div class="kt-subheader__breadcrumbs">
        <a href="{{ route('admins.home') }}" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>
        <span class="kt-subheader__breadcrumbs-separator"></span>
        <a href="{{ route($moduleName . '.list') }}" class="kt-subheader__breadcrumbs-link">
            Modules
        </a>
        <span class="kt-subheader__breadcrumbs-separator"></span>
        <a href="javascript:;" class="kt-subheader__breadcrumbs-link">
            {{ $pageTitle }}
        </a>
    </div>
@endsection


@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile form-wrapper">
                <div class="kt-portlet__head kt-portlet__head--lg" style="">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">{{ $pageTitle }}</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="javascript:history.back();" class="btn btn-clean kt-margin-r-10">
                            <i class="la la-arrow-left"></i>
                            <span class="kt-hidden-mobile">Back</span>
                        </a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-brand btn-submit" onclick="submitForm(0)">
                                <i class="la la-check"></i>
                                <span class="kt-hidden-mobile">Save</span>
                            </button>
                            <button type="button" class="btn btn-brand dropdown-toggle dropdown-toggle-split"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end"
                                style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(123px, 38px, 0px);">
                                <ul class="kt-nav">
                                    <li class="kt-nav__item">
                                        <a href="javascript:submitForm(1)" class="kt-nav__link">
                                            <span class="kt-nav__link-text">Save & Edit</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="javascript:submitForm(2)" class="kt-nav__link">
                                            <span class="kt-nav__link-text">Save & Continue</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-xl-2"></div>
                        <div class="col-xl-8">
                            @include('admins.' . $moduleName . '.form')
                        </div>
                        <div class="col-xl-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
