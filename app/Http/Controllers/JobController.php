<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Career;
use App\Models\Country;
use App\Models\Job;
use App\Models\Wage;
use App\Models\Rank;
use App\Models\Province;
use App\Models\UserEmployee;
use App\Models\User;
use App\Models\JobPackage;
use App\Models\Level;
use App\Models\FormWork;
use App\Models\JobJobTag;
use App\Models\JobTag;
use Carbon\Carbon;
use App\Models\Banner;

use Illuminate\Support\Str;
class JobController extends Controller
{
    // Trong nước
    public function vnjobs(Request $request, $job_type = ''){

        $sidebarBanners = Banner::where('group_banner', 'Sidebar Banner')->orderBy('position')->get();
        $degrees = Level::where('status',Level::ACTIVE)->orderBy('position')->get();
        $formworks = FormWork::where('status',FormWork::ACTIVE)->orderBy('position')->get();
        $job_categories = Career::where('status', 1)->orderBy('position')->get()->chunk(9);
        $careers = Career::where('status', 1)->orderBy('position')->get();
        $wages = Wage::where('status', 1)->orderBy('position')->get();
        $countries = Country::get();
        $newWages = [];
        foreach($wages as $wage){
            $newWages[$wage->salaryMin. '-'. $wage->salaryMax] = $wage->name;
        }

        $ranks = Rank::where('status', 1)->orderBy('position')->get();
        $job_packages = JobPackage::whereIn('slug', ['tin-gap', 'tin-hot'])->get();
        $model = new Job;
        
        $normal_provinces = Province::whereNotIn('id',[1,50,32])->orderBy('name')->get();
        $provinces = Province::whereIn('id',[1,50,32])->orderByRaw("FIELD(id,1,50,32)")->get()->merge($normal_provinces);
        // Việc làm mới nhất trong nước
        // $imageUserEmployyee = UserEmployee::class;
        $query = Job::select('jobs.*')->where('jobs.status',1);
        $query->where('country', 'VN');
        // dd($request->name);
        if($request->name){
            $query->where('jobs.name', 'LIKE', '%'.$request->name.'%');
        }
        if ($request->jobpackage_id) {
            $query->whereHas('job_package', function ($query) use ($request) {
                $query->where('jobpackage_id', $request->jobpackage_id);
            });
        }
        
        if( $request->career_id ){
            $query->whereHas('careers', function ($query) use($request) {
                $query->where('career_id', $request->career_id);
            });
        }
        if( $request->wage_id ){
            $wage_id = $request->wage_id;//'10-15'
            $wage = explode('-', $wage_id);
            if($wage[0] == 0){
                $query->where('salaryMin','<=', $wage[1]);
            }
            elseif($wage[1] == 0){
                $query->where('salaryMin','>=', $wage[0]);
            }
            else{
                $query->whereBetween('salaryMin',[ $wage[0], $wage[1] ]);
            }
        }
     
        if( $request->rank_id ){
            $query->where('rank_id', $request->rank_id);
        }
        if( $request->degree_id ){
            $query->where('degree_id', $request->degree_id);
        }
        if( $request->formwork_id ){
            $query->where('formwork_id', $request->formwork_id);
        }
        if( $request->province_id ){
            if( $request->province_id == 'quoc_te' ){
                return redirect()->route('jobs.nnjobs',$request->all());
            }
            $query->where('province_id', $request->province_id);
        }
        switch ($job_type) {
            case 'hap-dan':
                $title = 'Việc làm trong nước hấp dẫn';
                //Việc làm Mới nhất	Toàn bộ các tin đăng	
                //Hot.VIP -> Gấp.VIP -> VIP -> Gấp -> Hot -> Tin thường
                $query->join('job_packages', 'jobs.jobpackage_id', '=', 'job_packages.id');

                $query->where(function ($query) {
                    $query->where('jobs.salaryMax','>=',10000000)
                    ->orWhere('jobs.salaryMax','');
                });
                
                $query->orderByRaw("CASE
                    WHEN job_packages.slug = 'tin-hot-vip' THEN 1
                    WHEN job_packages.slug = 'tin-gap-vip' THEN 2
                    WHEN job_packages.slug = 'tin-vip' THEN 3
                    WHEN job_packages.slug = 'tin-gap' THEN 4
                    WHEN job_packages.slug = 'tin-hot' THEN 5
                    WHEN job_packages.slug = 'tin-thuong' THEN 6
                    ELSE 7
                END");
                break;
                case 'moi-nhat':
                    $title = 'Việc làm trong nước mới nhất';
                    //Việc làm Mới nhất	Toàn bộ các tin đăng	
                    //Gấp.VIP -> Hot.VIP -> VIP -> Gấp -> Hot -> Tin thường
                    $query->join('job_packages', 'jobs.jobpackage_id', '=', 'job_packages.id')
                    ->orderByRaw("CASE
                            WHEN job_packages.slug = 'tin-gap-vip' THEN 1
                            WHEN job_packages.slug = 'tin-hot-vip' THEN 2
                            WHEN job_packages.slug = 'tin-vip' THEN 3
                            WHEN job_packages.slug = 'tin-gap' THEN 4
                            WHEN job_packages.slug = 'tin-hot' THEN 5
                            WHEN job_packages.slug = 'tin-thuong' THEN 6
                            ELSE 7
                        END");
                    break;
            case 'hot':
                // Việc làm Hot nhất	Toàn bộ các tin đăng	
                //Hot.VIP -> Hot -> Gấp.VIP -> VIP -> Gấp -> Tin thường
                $query->join('job_packages', 'jobs.jobpackage_id', '=', 'job_packages.id')
                ->orderByRaw("CASE
                        WHEN job_packages.slug = 'tin-hot-vip' THEN 1
                        WHEN job_packages.slug = 'tin-hot' THEN 2
                        WHEN job_packages.slug = 'tin-gap-vip' THEN 3
                        WHEN job_packages.slug = 'tin-vip' THEN 4
                        WHEN job_packages.slug = 'tin-gap' THEN 5
                        WHEN job_packages.slug = 'tin-thuong' THEN 6
                        ELSE 7
                    END");
                $title = 'Việc làm trong nước hot nhất';
                break;
            case 'today':
                //Các tin đăng trong vòng 48h tính từ lúc user access của phiên đó	
                //Gấp.VIP -> Hot.VIP -> VIP -> Gấp -> Hot -> Tin thường
                $startDate = Carbon::now()->subHours(72);
                $endDate = Carbon::now();
                // $query->whereBetween('jobs.created_at', [$startDate, $endDate]);
                $query->join('job_packages', 'jobs.jobpackage_id', '=', 'job_packages.id')
                ->orderByRaw("CASE
                        WHEN job_packages.slug = 'tin-gap-vip' THEN 1
                        WHEN job_packages.slug = 'tin-hot-vip' THEN 2
                        WHEN job_packages.slug = 'tin-vip' THEN 3
                        WHEN job_packages.slug = 'tin-gap' THEN 4
                        WHEN job_packages.slug = 'tin-hot' THEN 5
                        WHEN job_packages.slug = 'tin-thuong' THEN 6
                        ELSE 7
                    END")
                ->orderBy('jobs.created_at', 'desc');
                $title = 'Việc làm trong nước hôm nay';
                break;
            case 'urgent':
                // Tuyển gấp	Toàn bộ các tin đăng	
                // Gấp.VIP -> Gấp -> Hop.VIP -> VIP -> Hot -> Tin thường
                // $query->where('jobpackage_id',JobPackage::GAP);
                $query->join('job_packages', 'jobs.jobpackage_id', '=', 'job_packages.id')
                ->orderByRaw("CASE
                        WHEN job_packages.slug = 'tin-gap-vip' THEN 1
                        WHEN job_packages.slug = 'tin-gap' THEN 2
                        WHEN job_packages.slug = 'tin-hot-vip' THEN 3
                        WHEN job_packages.slug = 'tin-vip' THEN 4
                        WHEN job_packages.slug = 'tin-hot' THEN 5
                        WHEN job_packages.slug = 'tin-thuong' THEN 6
                        ELSE 7
                    END")
                ->orderBy('jobs.created_at', 'desc');
                $title = 'Việc làm trong nước tuyển gấp';
                break;
            default:
                $title = 'Việc làm trong nước hôm nay';
                $query->join('job_packages', 'jobs.jobpackage_id', '=', 'job_packages.id')
                ->orderByRaw("CASE
                        WHEN job_packages.slug = 'tin-gap-vip' THEN 1
                        WHEN job_packages.slug = 'tin-hot-vip' THEN 2
                        WHEN job_packages.slug = 'tin-vip' THEN 3
                        WHEN job_packages.slug = 'tin-gap' THEN 4
                        WHEN job_packages.slug = 'tin-hot' THEN 5
                        WHEN job_packages.slug = 'tin-thuong' THEN 6
                        ELSE 7
                    END")
                ->orderBy('jobs.created_at', 'desc');
                $jobs = $query->limit(20)->get()->chunk(12);
                break;
        }

        $sort = $request->sort;
        switch ($sort) {
            case 'salary-desc':
                $query->orderBy('wage_id','DESC');
                break;
            case 'date-desc':
                $query->orderBy('jobs.created_at','DESC');
                break;
            case 'date-asc':
                $query->orderBy('jobs.created_at','ASC');
                break;
            default:
                break;
        }

        $view_path = 'website.jobs.index';
        if($job_type){
            $view_path = 'website.jobs.sub-index';
            $jobs = $query->paginate(25);
        }

        // Việc làm hấp dẫn trong nước
        $hot_jobs = Job::where('status',1)->where('country', 'VN');
        $hot_jobs->where(function ($hot_jobs) {
            $hot_jobs->where('jobs.salaryMax','>=',10000000)
            ->orWhere('jobs.salaryMax','');
        });
        $hot_jobs->where('jobs.country','VN');
        if($request->province_id){
            $hot_jobs->where('province_id', $request->province_id);
        }
        if($request->name){
            $hot_jobs->where('jobs.name', 'LIKE', '%'.$request->name.'%');
        }
        if( $request->rank_id ){
            $hot_jobs->where('rank_id', $request->rank_id);
        }
        if( $request->degree_id ){
            $hot_jobs->where('degree_id', $request->degree_id);
        }
        if( $request->formwork_id ){
            $hot_jobs->where('formwork_id', $request->formwork_id);
        }
        if( $request->wage_id ){
            
            switch ($request->wage_id) {
                case 'duoi_10tr':
                        $hot_jobs->where('salaryMax','<=', 10000000);
                    break;
                case '10-15':
                    $hot_jobs->whereBetween('salaryMax',[10000000,15000000]);
                    break;
                case '15-20':
                    $hot_jobs->whereBetween('salaryMax',[15000000,20000000]);
                    break;
                case '20-25':
                    $hot_jobs->whereBetween('salaryMax',[20000000,25000000]);

                    break;
                case '25-30':
                    $hot_jobs->whereBetween('salaryMax',[25000000,30000000]);
                    break;
                case '30-50':
                    $hot_jobs->whereBetween('salaryMax',[30000000,50000000,]);
                    break;
                case 'tren_50':
                    $hot_jobs->where('salaryMax','>=',[50000000,]);
                    break;
                case 'thoa_thuan':
                default:
                    $salaryMax = 0;
                    break;
            }
        }
        $hot_jobs->orderBy('id','DESC')->limit(20);
        $hot_jobs=$hot_jobs ->get()->chunk(10);

        $job_job_tags = count($jobs) ? JobJobTag::whereIn('job_id',$jobs->pluck('id')->toArray())->pluck('id')->toArray() : null;
        $job_tags = $job_job_tags ? JobTag::whereIn('id',$job_job_tags)->get() : [];
        $employees = UserEmployee::get();
        $top_employees = UserEmployee::orderBy('position')->limit(8)->get();

        $currentRoute = route::current()->getName();
// dd($currentRoute);
        $params = [
            'route' => $currentRoute,
            'careers' => $careers,
            'ranks' => $ranks,
            'jobs' => $jobs,
            'hot_jobs' => $hot_jobs,
            'wages' => $newWages,
            'provinces' => $provinces,
            'employees' => $employees,
            'top_employees' => $top_employees,
            'title' => $title,
            'degrees' => $degrees,
            'formworks' => $formworks,
            'job_type' => $job_type,
            'job_tags' => $job_tags,
            'job_packages'=> $job_packages,
            'countries'=>$countries,
            'special_employee_jobs'=>$this->_special_employee_jobs(),
            'sidebarBanners' => $sidebarBanners,

        ];
        return view($view_path,$params);
    }
    // Ngoài nước
    public function nnjobs (Request $request, $job_type = ''){
        $model = new Job;
        $careers = Career::where('status', 1)->get();
        $wages = [
            'duoi_10tr'=> 'Dưới 10 triệu',
            '10-15'=>'10 - 15 triệu',
            '15-20'=>'15 - 20 triệu',
            '20-25'=>'20 - 25 triệu',
            '25-30'=>'25 - 30 triệu',
            '30-50'=>'30 - 50 triệu',
            'tren_50'=>'Trên 50 triệu',
            'thoa_thuan'=>'Thỏa thuận'
        ];
        $sidebarBanners = Banner::where('group_banner', 'Sidebar Banner')->orderBy('position')->get();
        $ranks = Rank::where('status', 1)->orderBy('position')->get();
        $degrees = Level::where('status',Level::ACTIVE)->orderBy('position')->get();
        $formworks = FormWork::where('status',FormWork::ACTIVE)->orderBy('position')->get();
        $job_packages = JobPackage::whereIn('slug',['tin-gap','tin-hot'])->get();
        $provinces = Province::all();
        $countries = Country::all();
        // Việc làm mới nhất ngoài nước
        $query = Job::select('jobs.*')->where('jobs.status',1);
        $query->where('country','!=', 'VN');
        if( $request->career_id ){
            $query->whereHas('careers', function ($query) use($request) {
                $query->where('career_id', $request->career_id);
            });
        }
        if ($request->jobpackage_id) {
            $query->whereHas('job_package', function ($query) use ($request) {
                $query->where('jobpackage_id', $request->jobpackage_id);
            });
        }
        if($request->name){
            $query->where('jobs.name','LIKE','%' . $request->name. '%');
        }
        if( $request->wage_id ){
            
            switch ($request->wage_id) {
                case 'duoi_10tr':
                        $query->where('salaryMax','<=', 10000000);
                    break;
                case '10-15':
                    $query->whereBetween('salaryMax',[10000000,15000000]);
                    break;
                case '15-20':
                    $query->whereBetween('salaryMax',[15000000,20000000]);
                    break;
                case '20-25':
                    $query->whereBetween('salaryMax',[20000000,25000000]);

                    break;
                case '25-30':
                    $query->whereBetween('salaryMax',[25000000,30000000]);
                    break;
                case '30-50':
                    $query->whereBetween('salaryMax',[30000000,50000000,]);
                    break;
                case 'tren_50':
                    $query->where('salaryMax','>=',[50000000,]);
                    break;
                case 'thoa_thuan':
                default:
                    $salaryMax = 0;
                    break;
            }
        }
        if( $request->rank_id ){
            $query->where('rank_id', $request->rank_id);
        }
        if( $request->province_id ){
            $query->where('province_id', $request->province_id);
        }
        if( $request->degree_id ){
            $query->where('degree_id', $request->degree_id);
        }
        if( $request->formwork_id ){
            $query->where('formwork_id', $request->formwork_id);
        }
        switch ($job_type) {
            case 'moi-nhat':
                $title = 'Việc làm ngoài nước mới nhất';
                //Việc làm Mới nhất	Toàn bộ các tin đăng	
                //Gấp.VIP -> Hot.VIP -> VIP -> Gấp -> Hot -> Tin thường
                $query->join('job_packages', 'jobs.jobpackage_id', '=', 'job_packages.id')
                ->orderByRaw("CASE
                        WHEN job_packages.slug = 'tin-gap-vip' THEN 1
                        WHEN job_packages.slug = 'tin-hot-vip' THEN 2
                        WHEN job_packages.slug = 'tin-vip' THEN 3
                        WHEN job_packages.slug = 'tin-gap' THEN 4
                        WHEN job_packages.slug = 'tin-hot' THEN 5
                        WHEN job_packages.slug = 'tin-thuong' THEN 6
                        ELSE 7
                    END")
                    ->orderBy('jobs.created_at', 'desc');
                break;   case 'hap-dan':
                    $title = 'Việc làm ngoài nước hấp dẫn';
                    //Việc làm Mới nhất	Toàn bộ các tin đăng	
                    //Hot.VIP -> Gấp.VIP -> VIP -> Gấp -> Hot -> Tin thường
                    $query->join('job_packages', 'jobs.jobpackage_id', '=', 'job_packages.id')
                ->where('jobs.salaryMax','>=',10000000)
                ->orWhere('jobs.salaryMax','')
                ->where('jobs.country','NN')
                    ->orderByRaw("CASE
                            WHEN job_packages.slug = 'tin-hot-vip' THEN 1
                            WHEN job_packages.slug = 'tin-gap-vip' THEN 2
                            WHEN job_packages.slug = 'tin-vip' THEN 3
                            WHEN job_packages.slug = 'tin-gap' THEN 4
                            WHEN job_packages.slug = 'tin-hot' THEN 5
                            WHEN job_packages.slug = 'tin-thuong' THEN 6
                            ELSE 7
                        END")
                        ->orderBy('jobs.created_at', 'desc');
                    break;
            case 'hot':
                // Việc làm Hot nhất	Toàn bộ các tin đăng	
                //Hot.VIP -> Hot -> Gấp.VIP -> VIP -> Gấp -> Tin thường
                $query->join('job_packages', 'jobs.jobpackage_id', '=', 'job_packages.id')
                ->orderByRaw("CASE
                        WHEN job_packages.slug = 'tin-hot-vip' THEN 1
                        WHEN job_packages.slug = 'tin-hot' THEN 2
                        WHEN job_packages.slug = 'tin-gap-vip' THEN 3
                        WHEN job_packages.slug = 'tin-vip' THEN 4
                        WHEN job_packages.slug = 'tin-gap' THEN 5
                        WHEN job_packages.slug = 'tin-thuong' THEN 6
                        ELSE 7
                    END")
                    ->orderBy('jobs.created_at', 'desc');
                $title = 'Việc làm ngoài nước hot nhất';
                break;
            case 'today':
                //Các tin đăng trong vòng 48h tính từ lúc user access của phiên đó	
                //Gấp.VIP -> Hot.VIP -> VIP -> Gấp -> Hot -> Tin thường
                $startDate = Carbon::now()->subHours(72);
                $endDate = Carbon::now();
                // $query->whereBetween('jobs.created_at', [$startDate, $endDate]);
                $query->join('job_packages', 'jobs.jobpackage_id', '=', 'job_packages.id')
                ->orderByRaw("CASE
                        WHEN job_packages.slug = 'tin-gap-vip' THEN 1
                        WHEN job_packages.slug = 'tin-hot-vip' THEN 2
                        WHEN job_packages.slug = 'tin-vip' THEN 3
                        WHEN job_packages.slug = 'tin-gap' THEN 4
                        WHEN job_packages.slug = 'tin-hot' THEN 5
                        WHEN job_packages.slug = 'tin-thuong' THEN 6
                        ELSE 7
                    END")
                    ->orderBy('jobs.created_at', 'desc');
                
                $title = 'Việc làm ngoài nước hôm nay';
                break;
            case 'urgent':
                // Tuyển gấp	Toàn bộ các tin đăng	
                // Gấp.VIP -> Gấp -> Hop.VIP -> VIP -> Hot -> Tin thường
                // $query->where('jobpackage_id',JobPackage::GAP);
                $query->join('job_packages', 'jobs.jobpackage_id', '=', 'job_packages.id')
                ->orderByRaw("CASE
                        WHEN job_packages.slug = 'tin-gap-vip' THEN 1
                        WHEN job_packages.slug = 'tin-gap' THEN 2
                        WHEN job_packages.slug = 'tin-hot-vip' THEN 3
                        WHEN job_packages.slug = 'tin-vip' THEN 4
                        WHEN job_packages.slug = 'tin-hot' THEN 5
                        WHEN job_packages.slug = 'tin-thuong' THEN 6
                        ELSE 7
                    END")
                    ->orderBy('jobs.created_at', 'desc');
                $title = 'Việc làm ngoài nước tuyển gấp';
                break;
            default:
                $title = 'Việc làm ngoài nước hôm nay';
                $query->join('job_packages', 'jobs.jobpackage_id', '=', 'job_packages.id')
                ->orderByRaw("CASE
                        WHEN job_packages.slug = 'tin-gap-vip' THEN 1
                        WHEN job_packages.slug = 'tin-hot-vip' THEN 2
                        WHEN job_packages.slug = 'tin-vip' THEN 3
                        WHEN job_packages.slug = 'tin-gap' THEN 4
                        WHEN job_packages.slug = 'tin-hot' THEN 5
                        WHEN job_packages.slug = 'tin-thuong' THEN 6
                        ELSE 7
                    END")
                    ->orderBy('jobs.created_at', 'desc');
                $jobs = $query->limit(20)->get()->chunk(12);
                break;
        }
        $sort = $request->sort;
        switch ($sort) {
            case 'salary-desc':
                $query->orderBy('wage_id','DESC');
                break;
            case 'date-desc':
                $query->orderBy('jobs.created_at','DESC');
                break;
            case 'date-asc':
                $query->orderBy('jobs.created_at','ASC');
                break;
            default:
                $query->orderBy('jobs.created_at','DESC');
                break;
        }
        $view_path = 'website.jobs.index';
        if($job_type){
            $view_path = 'website.jobs.sub-index';
            $jobs = $query->paginate(10);
        }

        $job_job_tags = count($jobs) ? JobJobTag::whereIn('job_id',$jobs->pluck('id')->toArray())->pluck('id')->toArray() : null;
        $job_tags = $job_job_tags ? JobTag::whereIn('id',$job_job_tags)->get() : [];

        // Việc làm hấp dẫn ngoài nước
        $hot_jobs = Job::where('status',1)->where('jobs.country', 'NN');

        $hot_jobs->where(function ($hot_jobs) {
            $hot_jobs->where('jobs.salaryMax','>=',10000000)
            ->orWhere('jobs.salaryMax','');
        });
        if($request->province_id){
            $hot_jobs->where('province_id', $request->province_id);
        }
        if($request->name){
            $hot_jobs->where('jobs.name', 'LIKE', '%'.$request->name.'%');
        }
        if( $request->rank_id ){
            $hot_jobs->where('rank_id', $request->rank_id);
        }
        if( $request->degree_id ){
            $hot_jobs->where('degree_id', $request->degree_id);
        }
        if( $request->formwork_id ){
            $hot_jobs->where('formwork_id', $request->formwork_id);
        }
        if( $request->wage_id ){
            
            switch ($request->wage_id) {
                case 'duoi_10tr':
                        $hot_jobs->where('salaryMax','<=', 10000000);
                    break;
                case '10-15':
                    $hot_jobs->whereBetween('salaryMax',[10000000,15000000]);
                    break;
                case '15-20':
                    $hot_jobs->whereBetween('salaryMax',[15000000,20000000]);
                    break;
                case '20-25':
                    $hot_jobs->whereBetween('salaryMax',[20000000,25000000]);

                    break;
                case '25-30':
                    $hot_jobs->whereBetween('salaryMax',[25000000,30000000]);
                    break;
                case '30-50':
                    $hot_jobs->whereBetween('salaryMax',[30000000,50000000,]);
                    break;
                case 'tren_50':
                    $hot_jobs->where('salaryMax','>=',[50000000,]);
                    break;
                case 'thoa_thuan':
                default:
                    $salaryMax = 0;
                    break;
            }
        }
        
        $hot_jobs->orderBy('id','DESC')->limit(20);
        $hot_jobs = $hot_jobs->get()->chunk(10);
        $employees = UserEmployee::get();
        $top_employees = UserEmployee::orderBy('position')->limit(8)->get();

        $params = [
            'careers' => $careers,
            'route' => 'jobs.nnjobs',
            'ranks' => $ranks,
            'jobs' => $jobs,
            'hot_jobs' => $hot_jobs,
            'wages' => $wages,
            'provinces' => $provinces,
            'employees' => $employees,
            'top_employees' => $top_employees,
            'title' => $title,
            'degrees' => $degrees,
            'formworks' => $formworks,
            'job_type' => $job_type,
            'job_tags' => $job_tags,
            'job_packages' => $job_packages,
            'countries'=>$countries,
            'special_employee_jobs'=>$this->_special_employee_jobs(),
            'sidebarBanners' => $sidebarBanners,

        ];
        return view($view_path,$params);
    }

    private function _special_employee_jobs(){
        $employee_id = 373180;
        $employee = UserEmployee::where('user_id',$employee_id)->first();
        $jobs = Job::where('user_id',$employee_id)
        ->where('status',1)
        ->limit(10)->get();
        return [
            'employee' => $employee,
            'jobs' => $jobs,
        ];
    }
   
}