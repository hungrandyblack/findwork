<div class="attractive-banner">
    <div class="hero-banner">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
            @foreach($sidebarBanners as $banner)
                <div class="swiper-slide">
                    <img src="{{ asset($banner->image) }}" alt="{{ $banner->name }}">
                </div>
            @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</div>