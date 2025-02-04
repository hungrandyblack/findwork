<!--navigation-->
<ul class="metismenu" id="menu">
    <li>
        <a href="{{ route('admin.home') }}">
            <div class="parent-icon"><span class="material-symbols-outlined">home</span>
            </div>
            <div class="menu-title">{{ __('dashboard') }}</div>
        </a>
    </li>
    <li class="menu-label">{{ __('account') }}</li>
    <li>
        <a class="has-arrow" aria-expanded="false" href="javascript:;">
            <div class="parent-icon">
                <span class="material-symbols-outlined">account_circle</span>
            </div>
            <div class="menu-title">{{ __('candidate') }}</div>
        </a>
        <ul class="mm-collapse">
            <li>
                <a href="{{ route('adminuser.index',['type'=>'staff']) }}">
                    <span class="material-symbols-outlined">arrow_right</span>{{ __('list') }}
                </a>
            </li>
            <li>
                <a href="{{ route('adminpost.index',['type'=>'UserCV']) }}">
                    <span class="material-symbols-outlined">arrow_right</span>Hồ Sơ</a>
            </li>
        </ul>
    </li>
    <li>
        <a class="has-arrow" aria-expanded="false" href="javascript:;">
            <div class="parent-icon">
                <span class="material-symbols-outlined">account_circle</span>
            </div>
            <div class="menu-title">{{ __('employee') }}</div>
        </a>
        <ul class="mm-collapse">
            <li>
                <a href="{{ route('adminuser.index',['type'=>'employee']) }}">
                    <span class="material-symbols-outlined">arrow_right</span>{{ __('list') }}</a>
            </li>
            <li>
                <a href="{{ route('adminpost.index',['type'=>'Job']) }}">
                    <span class="material-symbols-outlined">arrow_right</span>{{ __('post') }}</a>
            </li>
        </ul>
    </li>
    <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon">
                <span class="material-symbols-outlined">account_circle</span>
            </div>
            <div class="menu-title">{{ __('job') }}</div>
        </a>
        <ul class="mm-collapse">
            <li>
                <a href="{{ route('admintaxonomy.index',['type'=>'Career']) }}">
                    <span class="material-symbols-outlined">arrow_right</span>{{ __('career') }}
                </a>
            </li>
            <li>
                <a href="{{ route('admintaxonomy.index',['type'=>'Level']) }}">
                    <span class="material-symbols-outlined">arrow_right</span>{{ __('level') }}
                </a>
            </li>
            <li>
                <a href="{{ route('admintaxonomy.index',['type'=>'Rank']) }}">
                    <span class="material-symbols-outlined">arrow_right</span>{{ __('rank') }}
                </a>
            </li>
            <li>
                <a href="{{ route('admintaxonomy.index',['type'=>'Wage']) }}">
                    <span class="material-symbols-outlined">arrow_right</span>{{ __('salary') }}
                </a>
            </li>
            <li>
                <a href="{{ route('admintaxonomy.index',['type'=>'FormWork']) }}">
                    <span class="material-symbols-outlined">arrow_right</span>{{ __('work_pattern') }}
                </a>
            </li>
            <li>
                <a href="{{ route('admintaxonomy.index',['type'=>'JobPackage']) }}">
                    <span class="material-symbols-outlined">arrow_right</span>{{__('jobpackage')}}
                </a>
            </li>
            <li>
                <a href="{{ route('admintaxonomy.index',['type'=>'JobTag']) }}">
                    <span class="material-symbols-outlined">arrow_right</span>{{__('card_job')}}
                </a>
            </li>
            <li>
                <a href="{{ route('countries.index') }}">
                    <span class="material-symbols-outlined">arrow_right</span>{{__('Quốc Gia')}}
                </a>
            </li>
        </ul>
    </li>
    <li class="menu-label">{{ __('system') }}</li>
    <li>
        <a class="has-arrow" aria-expanded="false" href="javascript:;">
            <div class="parent-icon">
                <span class="material-symbols-outlined">account_circle</span>
            </div>
            <div class="menu-title"> {{__('system')}} </div>
        </a>
        <ul class="mm-collapse">
            <li>
                <a href="{{ route('banners.index') }}">
                    <span class="material-symbols-outlined">arrow_right</span> {{__('Banner')}} </a>
            </li>
            <li>
                <a href="{{ route('adminpost.index',['type'=>'Post']) }}">
                    <span class="material-symbols-outlined">arrow_right</span> {{__('post')}} </a>
            </li>
            <li>
                <a href="{{ route('adminpost.index',['type'=>'Page']) }}">
                    <span class="material-symbols-outlined">arrow_right</span>{{__('page')}}</a>
            </li>
            <li>
                <a href="{{ route('admin.cvs.index') }}">
                    <span class="material-symbols-outlined">arrow_right</span> {{__('cv_template')}} </a>
            </li>
            <li>
                <a href="{{ route('admin.transactions.index') }}">
                    <span class="material-symbols-outlined">arrow_right</span> {{__('transaction')}} </a>
            </li>
            <li>
                <a href="{{ route('admintaxonomy.index',['type'=>'Account']) }}">
                    <span class="material-symbols-outlined">arrow_right</span>{{__('account_jobpackage')}}</a>
            </li>
            <li>
                <a href="{{ route('adminuser.index') }}">
                    <span class="material-symbols-outlined">arrow_right</span>{{ __('admin') }}</a>
            </li>
            <li>
                <a href="{{ route('adminuser.index') }}">
                    <span class="material-symbols-outlined">arrow_right</span>{{ __('group') }}</a>
            </li>
        </ul>
    </li>
</ul>
<!--end navigation-->