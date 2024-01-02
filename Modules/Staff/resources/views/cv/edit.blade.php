@extends('staff::dashboards.layouts.dashboard')
@section('content')
<!-- Dashboard -->
<section class="user-dashboard">
    <div class="dashboard-outer">
        <div class="upper-title-box">
            <h3>Profile!</h3>
            <div class="text">Ready to jump back in?</div>
        </div>
        @include('staff::cv.includes.card')

        <div class="row">
            <div class="col-lg-12">
                @include('staff::cv.tabs.'.$tab)
                <!-- Ls widget -->
            </div>
        </div>
    </div>
</section>
<!-- End Dashboard -->
@endsection
@section('footer')
<script>
    jQuery(document).ready(function () {
        jQuery('.experience-update').click(function (e) {
            e.preventDefault(); 
            var formData = jQuery(this).closest('.experience-form').serialize(); 
            var url = jQuery(this).closest('.experience-form').attr('action');
            jQuery.ajax({
                url: url, 
                type: 'POST', 
                data: formData,
                success: function (data) {
                    if (data.success) {
                    let formData = data.data;
                    formUpdate.find('.input-numerical-update input').val(formData.numerical);
                }
                    console.log(data);
                },
                error: function (error) {
            
                    console.log(error);
                }
            });
        });
    });
</script>
@endsection