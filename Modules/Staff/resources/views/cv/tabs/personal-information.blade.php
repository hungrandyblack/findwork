<div class="ls-widget">
    <div class="tabs-box">
        <div class="widget-title">
            <h4>Thông tin cá nhân</h4>
            <a href="{{ route('staff.cv.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>

        <div class="widget-content">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="form-group col-lg-12 col-md-12">
                <div class="uploading-outer">
                    <img src="{{ asset($staff->image_fm ?? '') }}" alt="Default Image"
                        style="max-width: 150px; max-height: 120px;">
                    <div class="file-input-wrapper">
                    </div>
                </div>
            </div>

            <form class="default-form" method="POST"
                action="{{ route('staff.cv.update', $cv_id, ['cv_id' => $cv_id, 'tab' => $tab]) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="tab" value="{{ request()->tab }}">
                <div class="row">
                    <div class="form-group col-lg-6 col-md-12">
                        <label>
                            Họ và tên
                            <span class="label-required"> *</span>
                        </label>
                        <input type="text" class="form-control" name="name"
                            value="{{ $item->name ?? $user->name }}">
                        @if ($errors->any())
                            <p style="color:red">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div class="form-group col-lg-6 col-md-12">
                        <label>
                            Email
                            <span class="label-required"> *</span>
                        </label>
                        <input type="text" class="form-control" name="email"
                            value="{{ $item->email ?? $user->email }}">
                        @if ($errors->any())
                            <p style="color:red">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <div class="form-group col-lg-6 col-md-12">
                        <label>
                            {{__('phone')}}
                            <span class="label-required"> *</span>
                        </label>
                        <input type="number" class="form-control" name="phone"
                            value="{{ $item->phone ?? ($staff->phone ?? '') }}">
                        @if ($errors->any())
                            <p style="color:red">{{ $errors->first('phone') }}</p>
                        @endif
                    </div>

                    <div class="form-group col-lg-6 col-md-12">
                        <label>
                            Năm Sinh
                            <span class="label-required"> *</span>
                        </label>
                        <input type="date" class="form-control" name="birthdate" class="form-control"
                            value="{{ $item->birthdate ?? ($staff->birthdate ?? '') }}">
                        @if ($errors->any())
                            <p style="color:red">{{ $errors->first('birthdate') }}</p>
                        @endif
                    </div>

                    <div class="form-group col-lg-6 col-md-12">
                        <label>
                            Số năm kinh nghiệm
                            <span class="label-required"> *</span>
                        </label>
                        <input type="number" class="form-control" name="experience_years"
                            value="{{ $item->experience_years ?? ($staff->experience_years ?? '') }}">
                        @if ($errors->any())
                            <p style="color:red">{{ $errors->first('experience_years') }}</p>
                        @endif
                    </div>

                    <div class="form-group col-lg-6 col-md-12">
                        <label>
                            Giới tính
                            <span class="label-required"> *</span>
                        </label>
                        @php
                            $item->gender = $item->gender ? $staff->gender : '';
                        @endphp
                        <select class="form-control" name="gender">
                            <option value="nam" @selected($item->gender == 'nam')>Nam</option>
                            <option value="nu" @selected($item->gender == 'nu')>Nữ</option>
                            <option value="khac" @selected($item->gender == 'khac')>Khác</option>
                        </select>
                    </div>


                    <div class="form-group col-lg-6 col-md-12">
                        <label>
                            Tỉnh\Thành phố
                            <span class="label-required"> *</span>
                        </label>
                        <input type="text" class="form-control" name="city"
                            value="{{ $item->city ?? ($staff->city ?? '') }}">
                        @if ($errors->any())
                            <p style="color:red">{{ $errors->first('city') }}</p>
                        @endif
                    </div>

                    <div class="form-group col-lg-6 col-md-12">
                        <label>
                            Địa chỉ
                            <span class="label-required"> *</span>
                        </label>
                        <input type="text" class="form-control" name="address"
                            value="{{ $item->address ?? ($staff->address ?? '') }}">
                        @if ($errors->any())
                            <p style="color:red">{{ $errors->first('address') }}</p>
                        @endif
                    </div>

                    <div class="form-group col-lg-12 col-md-12">
                        <label>
                            Thành tích nổi bật
                            <span class="label-required"> *</span>
                        </label>
                        <textarea class="form-control" name="outstanding_achievements">{{ $item->outstanding_achievements ?? ($staff->outstanding_achievements ?? '') }}</textarea>
                        @if ($errors->any())
                            <p style="color:red">{{ $errors->first('outstanding_achievements') }}</p>
                        @endif
                    </div>

                    <div class="form-group col-lg-6 col-md-12">
                        <button class="theme-btn btn-style-one">{{__('save')}} </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
