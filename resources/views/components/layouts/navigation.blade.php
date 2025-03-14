<!--begin::Header MENU-->
<div id="kt_header" class="header  header-fixed ">
    <!--begin::Container-->
    <div class=" container  d-flex align-items-stretch justify-content-between">
        <!--begin::Left-->
        <div class="d-flex align-items-stretch mr-3">            

            <!--begin::Header Menu Wrapper-->
            <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                <!--begin::Header Menu-->
                <div id="kt_header_menu"
                    class="header-menu header-menu-left header-menu-mobile  header-menu-layout-default ">
                    <!--begin::Header Navigation-->
                    <ul class="menu-nav ">
                        <li class="menu-item  menu-item-open menu-item-rel" aria-haspopup="true">
                            <a href="/" class="menu-link"><span class=" menu-text">Dashboard</span><i
                                    class="menu-arrow"></i></a>
                        </li>                    
                        @foreach ($navigations as $navigation)
                       
                            @can($navigation->permission_name)
                                <li class="menu-item  menu-item-submenu menu-item-rel" data-menu-toggle="click"
                                    aria-haspopup="true"><a href="javascript:;" class="menu-link menu-toggle"><span
                                            class="menu-text">{{ $navigation->name }}</span><span class="menu-desc"></span><i
                                            class="menu-arrow"></i></a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">
                                            @foreach ($navigation->children->sortBy('urut') as $child)
                                                @can($child->permission_name)
                                                    <li class="menu-item " aria-haspopup="true"><a href="{{ url($child->url) }}"
                                                        class=" menu-link ">
                                                        <span class=" svg-icon menu-icon">
                                                            <i class="{{ $child->icon }}"></i>
                                                        </span>
                                                        <span class="menu-text">{{ $child->name }}</span><span class="menu-label">
                                                        </span></a>
                                                   </li>
                                                @endcan
                                          
                                            @endforeach
                                        </ul>
                                    </div>
                                </li>
                            @endcan
                        @endforeach


                    </ul>
                    <!--end::Header Navigation-->
                </div>
                <!--end::Header Menu-->
            </div>
            <!--end::Header Menu Wrapper-->
        </div>
        <!--end::Left-->

        <!--begin::Topbar-->
        <div class="topbar">

            <!--begin::Quick panel-->
            <div class="topbar-item">
                <div class="btn btn-icon btn-hover-transparent-white btn-lg mr-1" id="kt_quick_panel_toggle">
                    <span class="svg-icon svg-icon-xl">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg--><svg
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5" />
                                <path
                                    d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z"
                                    fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                        <!--end::Svg Icon--></span> </div>
            </div>
            <!--end::Quick panel-->

            <!--begin::User-->
            <div class="dropdown">
                <!--begin::Toggle-->
                <div class="topbar-item" data-toggle="dropdown" data-offset="0px,0px">
                    <div
                        class="btn btn-icon btn-hover-transparent-white d-flex align-items-center btn-lg px-md-2 w-md-auto">
                        <span
                            class="text-white opacity-70 font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
                        <span
                            class="text-white opacity-90 font-weight-bolder font-size-base d-none d-md-inline mr-4">{{ Auth::user()->name }}</span>
                        <span class="symbol symbol-35">
                            <span class="symbol-label text-white font-size-h5 font-weight-bold bg-white-o-30">S</span>
                        </span>
                    </div>
                </div>
                <!--end::Toggle-->

                <!--begin::Dropdown-->
                <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg p-0">
                    <!--begin::Header-->
                    <div class="d-flex align-items-center p-8 rounded-top">
                        <!--begin::Symbol-->
                        <div class="symbol symbol-md bg-light-primary mr-3 flex-shrink-0">
                            @if(Auth::user()->avatar == "")
                            <img src="{{ asset('assets/media/users/blank.png') }}" alt="" />
                            @else
                            <img src="{{ asset('storage/'.Auth::user()->avatar) }}" alt="" />
                            @endif
                        </div>
                        <!--end::Symbol-->

                        <!--begin::Text-->
                        <div class="text-dark m-0 flex-grow-1 mr-3 font-size-h5">{{ Auth::user()->name }}</div>

                        <!--end::Text-->
                    </div>
                    <div class="separator separator-solid"></div>
                    <!--end::Header-->

                    <!--begin::Nav-->
                    <div class="navi navi-spacer-x-0 pt-5">
                        <!--begin::Item-->
                        <a href="/profile/edit" class="navi-item px-8">
                            <div class="navi-link">
                                <div class="navi-icon mr-2">
                                    <i class="flaticon2-calendar-3 text-success"></i>
                                </div>
                                <div class="navi-text">
                                    <div class="font-weight-bold">
                                        My Profile
                                    </div>
                                    <div class="text-muted">
                                        Account settings and more
                                        <span
                                            class="label label-light-danger label-inline font-weight-bold">update</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <!--end::Item-->

                        <!--begin::Footer-->
                        <div class="navi-separator mt-3"></div>
                        <div class="navi-footer  px-8 py-5">
                            @guest
                            <a href="/register" class="btn btn-clean font-weight-bold">Register</a>
                            <a href="/login" class="btn btn-light-primary font-weight-bold">Log In</a>
                            @else
                            <a href="/logout"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit()"
                                class="btn btn-light-danger font-weight-bold">Log Out<output></output></a>
                            <form action="/logout" method="post" id="logout-form">@csrf</form>
                            @endguest



                        </div>
                        <!--end::Footer-->
                    </div>
                    <!--end::Nav-->
                </div>
                <!--end::Dropdown-->
            </div>
            <!--end::User-->
        </div>
        <!--end::Topbar-->
    </div>
    <!--end::Container-->
</div>
<!--end::Header-->