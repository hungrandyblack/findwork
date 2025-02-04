@extends('employee::layouts.master')
@section('content')
    <!-- Dashboard -->
    <section class="user-dashboard">
        <div class="dashboard-outer">
            <div class="upper-title-box">
                <h3>{{ __('hello') }}, {{ auth()->user()->name }}</h3>
                <div class="text">{{ __('have_a_good_day') }}!</div>
            </div>
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="row">
                <div class="ui-block col-xl-4 col-lg-6 col-md-6 col-sm-12">
                    <a href="{{ route('employee.job.index') }}">
                        <div class="ui-item">
                            <div class="left">
                                <i class="icon flaticon-briefcase"></i>
                            </div>
                            <div class="right">
                                <h4>{{ $count_jobs }}</h4>
                                <p>{{ __('job') }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="ui-block col-xl-4 col-lg-6 col-md-6 col-sm-12">
                    <a href="{{ route('employee.cv.index') }}">
                        <div class="ui-item ui-red">
                            <div class="left">
                                <i class="icon la la-file-invoice"></i>
                            </div>
                            <div class="right">
                                <h4>{{ $count_CVapply }}</h4>
                                <p>{{ __('profile') }}</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="ui-block col-xl-4 col-lg-6 col-md-6 col-sm-12"> 
                        <div class="ui-item ui-green">
                            <div class="left">
                                <i class="icon la la-bookmark-o"></i>
                            </div>
                            <div class="right">
                                <h4>0</h4>
                                <p>{{ __('favourite') }}</p>
                            </div>
                        </div> 
                </div>
            </div>

            <div class="row">








                {{-- <div class="col-lg-12">
                <!-- applicants Widget -->
                <div class="applicants-widget ls-widget">
                    <div class="widget-title">
                        <h4>Recent Applicants</h4>
                    </div>
                    <div class="widget-content">
                        <div class="row">
                            <!-- Candidate block three -->
                            <div class="candidate-block-three col-lg-6 col-md-12 col-sm-12">
                                <div class="inner-box">
                                    <div class="content">
                                        <figure class="image"><img src="images/resource/candidate-1.png" alt="">
                                        </figure>
                                        <h4 class="name"><a href="#">Darlene Robertson</a></h4>
                                        <ul class="candidate-info">
                                            <li class="designation">UI Designer</li>
                                            <li><span class="icon flaticon-map-locator"></span> London, UK</li>
                                            <li><span class="icon flaticon-money"></span> $99 / hour</li>
                                        </ul>
                                        <ul class="post-tags">
                                            <li><a href="#">App</a></li>
                                            <li><a href="#">Design</a></li>
                                            <li><a href="#">Digital</a></li>
                                        </ul>
                                    </div>
                                    <div class="option-box">
                                        <ul class="option-list">
                                            <li><button data-text="View Aplication"><span
                                                        class="la la-eye"></span></button></li>
                                            <li><button data-text="Approve Aplication"><span
                                                        class="la la-check"></span></button></li>
                                            <li><button data-text="Reject Aplication"><span
                                                        class="la la-times-circle"></span></button></li>
                                            <li><button data-text="Delete Aplication"><span
                                                        class="la la-trash"></span></button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Candidate block three -->
                            <div class="candidate-block-three col-lg-6 col-md-12 col-sm-12">
                                <div class="inner-box">
                                    <div class="content">
                                        <figure class="image"><img src="images/resource/candidate-2.png" alt="">
                                        </figure>
                                        <h4 class="name"><a href="#">Wade Warren</a></h4>
                                        <ul class="candidate-info">
                                            <li class="designation">UI Designer</li>
                                            <li><span class="icon flaticon-map-locator"></span> London, UK</li>
                                            <li><span class="icon flaticon-money"></span> $99 / hour</li>
                                        </ul>
                                        <ul class="post-tags">
                                            <li><a href="#">App</a></li>
                                            <li><a href="#">Design</a></li>
                                            <li><a href="#">Digital</a></li>
                                        </ul>
                                    </div>
                                    <div class="option-box">
                                        <ul class="option-list">
                                            <li><button data-text="View Aplication"><span
                                                        class="la la-eye"></span></button></li>
                                            <li><button data-text="Approve Aplication"><span
                                                        class="la la-check"></span></button></li>
                                            <li><button data-text="Reject Aplication"><span
                                                        class="la la-times-circle"></span></button></li>
                                            <li><button data-text="Delete Aplication"><span
                                                        class="la la-trash"></span></button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Candidate block three -->
                            <div class="candidate-block-three col-lg-6 col-md-12 col-sm-12">
                                <div class="inner-box">
                                    <div class="content">
                                        <figure class="image"><img src="images/resource/candidate-3.png" alt="">
                                        </figure>
                                        <h4 class="name"><a href="#">Leslie Alexander</a></h4>
                                        <ul class="candidate-info">
                                            <li class="designation">UI Designer</li>
                                            <li><span class="icon flaticon-map-locator"></span> London, UK</li>
                                            <li><span class="icon flaticon-money"></span> $99 / hour</li>
                                        </ul>
                                        <ul class="post-tags">
                                            <li><a href="#">App</a></li>
                                            <li><a href="#">Design</a></li>
                                            <li><a href="#">Digital</a></li>
                                        </ul>
                                    </div>
                                    <div class="option-box">
                                        <ul class="option-list">
                                            <li><button data-text="View Aplication"><span
                                                        class="la la-eye"></span></button></li>
                                            <li><button data-text="Approve Aplication"><span
                                                        class="la la-check"></span></button></li>
                                            <li><button data-text="Reject Aplication"><span
                                                        class="la la-times-circle"></span></button></li>
                                            <li><button data-text="Delete Aplication"><span
                                                        class="la la-trash"></span></button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Candidate block three -->
                            <div class="candidate-block-three col-lg-6 col-md-12 col-sm-12">
                                <div class="inner-box">
                                    <div class="content">
                                        <figure class="image"><img src="images/resource/candidate-1.png" alt="">
                                        </figure>
                                        <h4 class="name"><a href="#">Darlene Robertson</a></h4>
                                        <ul class="candidate-info">
                                            <li class="designation">UI Designer</li>
                                            <li><span class="icon flaticon-map-locator"></span> London, UK</li>
                                            <li><span class="icon flaticon-money"></span> $99 / hour</li>
                                        </ul>
                                        <ul class="post-tags">
                                            <li><a href="#">App</a></li>
                                            <li><a href="#">Design</a></li>
                                            <li><a href="#">Digital</a></li>
                                        </ul>
                                    </div>
                                    <div class="option-box">
                                        <ul class="option-list">
                                            <li><button data-text="View Aplication"><span
                                                        class="la la-eye"></span></button></li>
                                            <li><button data-text="Approve Aplication"><span
                                                        class="la la-check"></span></button></li>
                                            <li><button data-text="Reject Aplication"><span
                                                        class="la la-times-circle"></span></button></li>
                                            <li><button data-text="Delete Aplication"><span
                                                        class="la la-trash"></span></button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Candidate block three -->
                            <div class="candidate-block-three col-lg-6 col-md-12 col-sm-12">
                                <div class="inner-box">
                                    <div class="content">
                                        <figure class="image"><img src="images/resource/candidate-2.png" alt="">
                                        </figure>
                                        <h4 class="name"><a href="#">Wade Warren</a></h4>
                                        <ul class="candidate-info">
                                            <li class="designation">UI Designer</li>
                                            <li><span class="icon flaticon-map-locator"></span> London, UK</li>
                                            <li><span class="icon flaticon-money"></span> $99 / hour</li>
                                        </ul>
                                        <ul class="post-tags">
                                            <li><a href="#">App</a></li>
                                            <li><a href="#">Design</a></li>
                                            <li><a href="#">Digital</a></li>
                                        </ul>
                                    </div>
                                    <div class="option-box">
                                        <ul class="option-list">
                                            <li><button data-text="View Aplication"><span
                                                        class="la la-eye"></span></button></li>
                                            <li><button data-text="Approve Aplication"><span
                                                        class="la la-check"></span></button></li>
                                            <li><button data-text="Reject Aplication"><span
                                                        class="la la-times-circle"></span></button></li>
                                            <li><button data-text="Delete Aplication"><span
                                                        class="la la-trash"></span></button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Candidate block three -->
                            <div class="candidate-block-three col-lg-6 col-md-12 col-sm-12">
                                <div class="inner-box">
                                    <div class="content">
                                        <figure class="image"><img src="images/resource/candidate-3.png" alt="">
                                        </figure>
                                        <h4 class="name"><a href="#">Leslie Alexander</a></h4>
                                        <ul class="candidate-info">
                                            <li class="designation">UI Designer</li>
                                            <li><span class="icon flaticon-map-locator"></span> London, UK</li>
                                            <li><span class="icon flaticon-money"></span> $99 / hour</li>
                                        </ul>
                                        <ul class="post-tags">
                                            <li><a href="#">App</a></li>
                                            <li><a href="#">Design</a></li>
                                            <li><a href="#">Digital</a></li>
                                        </ul>
                                    </div>
                                    <div class="option-box">
                                        <ul class="option-list">
                                            <li><button data-text="View Aplication"><span
                                                        class="la la-eye"></span></button></li>
                                            <li><button data-text="Approve Aplication"><span
                                                        class="la la-check"></span></button></li>
                                            <li><button data-text="Reject Aplication"><span
                                                        class="la la-times-circle"></span></button></li>
                                            <li><button data-text="Delete Aplication"><span
                                                        class="la la-trash"></span></button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            </div>
        </div>
    </section>
    <!-- End Dashboard -->
@endsection
