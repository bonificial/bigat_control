
<div class="app-header header-shadow  ">
    <div class="app-header__logo">
        <div class="logo-src"><img src="assets/images/logo_small.png" class="img-100"/> </div>

        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                        data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
                <span>
                    <button type="button"
                            class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
    </div>
    <div class="app-header__content">
        <div class="app-header-left">
            <div class="search-wrapper">
                <div class="input-holder">
                    <input type="text" class="search-input" placeholder="Type to search">
                    <button class="search-icon"><span></span></button>
                </div>
                <button class="close"></button>
            </div>
            <ul class="header-menu nav">
                <li class="nav-item">
                    <a href="./" class="nav-link">
                        <i class="nav-link-icon fa fa-home"> </i>
                        Home
                    </a>
                </li>


            </ul>
        </div>
        <div class="app-header-right">
            <div class="header-btn-lg pr-0">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="btn-group">
                                <a aria-expanded="false" aria-haspopup="true" class="p-0 btn"
                                   data-toggle="dropdown">
                                    <img alt="" class="rounded-circle" src="assets/images/avatars/1.jpg" width="42">
                                    <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                </a>
                                <div aria-hidden="true" class="dropdown-menu dropdown-menu-right" role="menu"
                                     tabindex="-1">

                                    <div class="dropdown-divider" tabindex="-1"></div>
                                    <a href="./logout" class="dropdown-item" tabindex="0" >  <i class="metismenu-icon fa fa-sign-out-alt"></i> Logout</a>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content-left  ml-3 header-user-info">
                            <div class="widget-heading">
                                <?php  echo ucfirst($user['user']['firstname'] . " ". $user['user']['lastname'])?>>
                            </div>
                            <div class="widget-subheading">
                                BIGAT System User/Admin
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>