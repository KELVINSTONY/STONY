<?php
namespace App\Http\Controllers;
use App\Models\Patient;
use App\Models\Sale;
use App\Stock;
use App\Models\Medicine;
use App\Models\Medicine as ModelsMedicine;
use App\SalesDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('login');
    }

    //login form
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function updating()
    {
        return view('profile.partials.update-password-form');
    }

    public function allocation()
    {
        return view('profile.partials.update-password-form');
    }


    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    // public function index(){
    //    $medicines = Db::table('medicines')->paginate(10);

    //    return view('dashboard',compact('medicines'));
    // }
     
    public function index(Request $request)
    {
        $medicines= Medicine::orderBy('id','DESC')->paginate(5);
        $patients = Patient::orderBy('id','DESC')->paginate(5);
        $user = User::first();
        $user->assignRole('Admin');

        return view('dashboard',compact('medicines','patients'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    private function patientDashboard()
    {
        $data = array();

        /*average patient registration*/
        $totalRegistration = Patient::count();
        $days_of_registration = Patient::select(DB::raw('date(created_at)'))
            ->distinct()
            ->get();
        if ($days_of_registration->count() == 0) {
            $avgDailyRegistration = 0;
        } else {
            $avgDailyRegistration = $totalRegistration / $days_of_registration->count();
        }
        /*end average patient registration*/

        /*average patient visits*/
        $totalVisit = Visit::count();
        $days_of_visit = Visit::select(DB::raw('date(created_at)'))
            ->distinct()
            ->get();
        if ($days_of_visit->count() == 0) {
            $avgDailyVisit = 0;
        } else {
            $avgDailyVisit = $totalVisit / $days_of_visit->count();
        }
        /*end average patient visits*/


        /*average patient registration by gender*/

        $totalRegistrationByGender = Patient::select(DB::raw("sex as category,count(id) amount"))
            ->groupBy('sex')
            ->get();
        $days_of_registration_by_gender = Patient::select(DB::raw('date(created_at)'))
            ->distinct()
            ->get();

        $avgDailyRegistrationByGender = array();
        if ($days_of_registration_by_gender->count() == 0) {
            $avgDailyRegistrationByGender = 0;
        } else {
            foreach ($totalRegistrationByGender as $item) {
                if ($item->category == "Female") {
                    $x = $item->amount / $days_of_registration_by_gender->count();
                    array_push($avgDailyRegistrationByGender, array(
                        "category" => "Female",
                        "amount" => $x
                    ));
                } else {
                    $x = $item->amount / $days_of_registration_by_gender->count();
                    array_push($avgDailyRegistrationByGender, array(
                        "category" => "Male",
                        "amount" => $x
                    ));
                }
            }
        }
        /*end average patient registration by gender*/

        /*today registration*/
        $todayRegistration = Patient::whereRaw('date(created_at) = date(now())')
            ->count();
        /*end today registration*/

        /*total daily registration*/
        $totalDailyRegistration = Patient::select(DB::raw('date(created_at) date, count(id) value'))
            ->groupBy(DB::raw('date(created_at)'))
            ->limit('60')
            ->get();
        /*end total daily registration*/

        /*total reg by category*/
        $registrationByCategory = Patient::select(DB::raw("name as category,count(patients.id) amount"))
            ->join('price_categories', 'price_categories.id', '=', 'patients.price_category_id')
            ->groupBy('price_category_id')
            ->get();
        /*end total reg by category*/

        /*total reg by gender*/
        $registrationByGender = Patient::select(DB::raw("sex as category,count(id) amount"))
            ->whereRaw('month(created_at) = month(now())')
            ->groupBy('sex')
            ->get();
        /*end total reg by gender*/

        /*total monthly registration*/
        $totalMonthlyRegistration = Patient::select(DB::raw("DATE_FORMAT(created_at, '%b %y') month,count(id) amount"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y%m')"))
            ->get();
        /*end total monthly registration*/

        /*today visits*/
        $todayVisit = Visit::whereRaw('date(created_at) = date(now())')
            ->count();
        /*end today visits*/

        /*average visit by gender*/
        $totalVisitByGender = Visit::select(DB::raw("sex as category,count(visits.id) amount"))
            ->join('patients', 'patients.id', '=', 'visits.patient_id')
            ->groupBy('sex')
            ->get();

        $days_of_visit_by_gender = Visit::select(DB::raw('date(created_at)'))
            ->distinct()
            ->get();

        $avgDailyVisitByGender = array();
        if ($days_of_visit_by_gender->count() == 0) {
            $avgDailyVisitByGender = 0;
        } else {
            foreach ($totalVisitByGender as $item) {
                if ($item->category == "Female") {
                    $x = $item->amount / $days_of_visit_by_gender->count();
                    array_push($avgDailyVisitByGender, array(
                        "category" => "Female",
                        "amount" => $x
                    ));
                } else {
                    $x = $item->amount / $days_of_visit_by_gender->count();
                    array_push($avgDailyVisitByGender, array(
                        "category" => "Male",
                        "amount" => $x
                    ));
                }
            }
        }
        /*end average visit by gender*/

        /*total visit by gender*/
        $visitByGender = Visit::select(DB::raw("sex as category,count(visits.id) amount"))
            ->join('patients', 'patients.id', '=', 'visits.patient_id')
            ->whereRaw('month(visits.created_at) = month(now())')
            ->groupBy('sex')
            ->get();
        /*end visit reg by gender*/

        /*total monthly visit*/
        $totalMonthlyVisit = Visit::select(DB::raw("DATE_FORMAT(created_at, '%b %y') month,count(id) amount"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y%m')"))
            ->get();
        /*end total monthly visit*/

        /*total visit by category*/
        $visitByCategory = Visit::select(DB::raw("name as category,count(visits.id) amount"))
            ->join('patients', 'patients.id', '=', 'visits.patient_id')
            ->join('price_categories', 'price_categories.id', '=', 'visits.price_category_id')
            ->groupBy('visits.price_category_id')
            ->get();
        /*end total visit by category*/

        /*total visit by category By Monthly*/
        $visitByCategoryMonthly = Visit::select(DB::raw("name as category,count(visits.id) amount"))
            ->join('patients', 'patients.id', '=', 'visits.patient_id')
            ->join('price_categories', 'price_categories.id', '=', 'visits.price_category_id')
            ->groupBy('visits.price_category_id')
            ->whereYear('visits.created_at', Carbon::now()->year)
            ->whereMonth('visits.created_at', Carbon::now()->month)
            ->get();
        /*end total visit by category By Monthly*/

        /*total daily visit*/
        $totalDailyVisit = Visit::select(DB::raw('date(created_at) date, count(id) value'))
            ->groupBy(DB::raw('date(created_at)'))
            // ->limit('60')
            ->get();
        /*end total daily visit*/

        $data['avg_daily_visits'] = $avgDailyVisit;
        $data['today_visits'] = $todayVisit;
        $data['total_monthly_visit'] = $totalMonthlyVisit;
        $data['visit_by_gender'] = $visitByGender;
        $data['avg_daily_visit_by_gender'] = $avgDailyVisitByGender;
        $data['visit_by_category'] = $visitByCategory;
        $data['visit_by_category_monthly'] = $visitByCategoryMonthly;
        $data['total_daily_visit'] = $totalDailyVisit;

        $data['avg_daily_reg'] = $avgDailyRegistration;
        $data['avg_daily_reg_by_gender'] = $avgDailyRegistrationByGender;
        $data['today_reg'] = $todayRegistration;
        $data['total_daily_reg'] = $totalDailyRegistration;
        $data['reg_by_category'] = $registrationByCategory;
        $data['reg_by_gender'] = $registrationByGender;
        $data['total_monthly'] = $totalMonthlyRegistration;

        return $data;
    }

    private function consultationDashboard()
    {
        $data = array();

        /*average visit*/
        $totalVisit = Visit::count();
        $days_of_visit = Visit::select(DB::raw('date(created_at)'))
            ->distinct()
            ->get();
        if ($days_of_visit->count() == 0) {
            $avgDailyVisit = 0;
        } else {
            $avgDailyVisit = $totalVisit / $days_of_visit->count();
        }
        /*end average visit*/

        /*average visit by gender*/
        $totalVisitByGender = Visit::select(DB::raw("sex as category,count(visits.id) amount"))
            ->join('patients', 'patients.id', '=', 'visits.patient_id')
            ->groupBy('sex')
            ->get();

        $days_of_visit_by_gender = Visit::select(DB::raw('date(created_at)'))
            ->distinct()
            ->get();

        $avgDailyVisitByGender = array();
        if ($days_of_visit_by_gender->count() == 0) {
            $avgDailyVisitByGender = 0;
        } else {
            foreach ($totalVisitByGender as $item) {
                if ($item->category == "Female") {
                    $x = $item->amount / $days_of_visit_by_gender->count();
                    array_push($avgDailyVisitByGender, array(
                        "category" => "Female",
                        "amount" => $x
                    ));
                } else {
                    $x = $item->amount / $days_of_visit_by_gender->count();
                    array_push($avgDailyVisitByGender, array(
                        "category" => "Male",
                        "amount" => $x
                    ));
                }
            }
        }
        /*end average visit by gender*/

        /*today registration*/
        $setting = Setting::find(125);
        if ($setting->value == 'On Date Change') {
            $todayVisits = Visit::whereRaw('date(created_at) = date(now())')
                ->count();
        } else {
            $todayVisits = Visit::where('created_at', '>=', Carbon::parse('-24 hours'))
                ->count();
        }

        /*end today registration*/

        /*total daily registration*/
        $totalDailyVisit = Visit::select(DB::raw('date(created_at) date, count(id) value'))
            ->groupBy(DB::raw('date(created_at)'))
            ->limit('60')
            ->get();
        /*end total daily registration*/

        /*total reg by category*/
        $visitByCategory = Visit::select(DB::raw("name as category,count(visits.id) amount"))
            ->join('patients', 'patients.id', '=', 'visits.patient_id')
            ->join('price_categories', 'price_categories.id', '=', 'patients.price_category_id')
            ->groupBy('visits.price_category_id')
            ->get();
        /*end total reg by category*/

        /*total reg by gender*/
        $visitByGender = Visit::select(DB::raw("sex as category,count(visits.id) amount"))
            ->join('patients', 'patients.id', '=', 'visits.patient_id')
            ->whereRaw('month(visits.created_at) = month(now())')
            ->groupBy('sex')
            ->get();
        /*end total reg by gender*/

        /*total monthly registration*/
        $totalMonthlyVisit = Visit::select(DB::raw("DATE_FORMAT(created_at, '%b %y') month,count(id) amount"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y%m')"))
            ->get();
        /*end total monthly registration*/

        $data['avg_daily_reg'] = $avgDailyVisit;
        $data['avg_daily_reg_by_gender'] = $avgDailyVisitByGender;
        $data['today_reg'] = $todayVisits;
        $data['total_daily_reg'] = $totalDailyVisit;
        $data['reg_by_category'] = $visitByCategory;
        $data['reg_by_gender'] = $visitByGender;
        $data['total_monthly'] = $totalMonthlyVisit;

        return $data;
    }

    private function pharmacyDashboard()
    {
        $data = array();

        $outOfStock = CurrentStock::where('quantity', 0)->count();

        $outOfStockList = CurrentStock::where('quantity', 0)->get();

        $expired = CurrentStock::whereRaw('expiry_date <  date(now())')->count();

        $totalSales = SalesDetail::whereNotNUll('type')->sum('amount');
        // $totalSalesDiscount = SalesDetail::sum('discount');
        // $totalSalesVat = SalesDetail::sum('vat');

        $days = DB::table('sale_details')
            ->select(DB::raw('date(sold_at)'))
            ->distinct()
            ->get();

        if ($days->count() == 0) {
            $avgDailySales = 0;
        } else {
            $avgDailySales = $totalSales / $days->count();
        }

        $todaySales = DB::table('sale_details')
            ->whereRaw('date(sold_at) = date(now())')
            ->where('status', '=', 1)
            ->orwhere('status', '=', 5)
            ->sum('amount');

        $totalDailySales = DB::table('sale_details')
            ->select(DB::raw('date(sold_at) date, sum(amount) value'))
            ->where('status', '=', 1)
            ->orwhere('status', '=', 5)
            ->groupBy(DB::raw('date(sold_at)'))
            ->limit('60')
            ->get();

        $totalMonthlySales = DB::table('sale_details')
            ->select(DB::raw("DATE_FORMAT(sold_at, '%b %y') month,sum(amount) amount"))
            ->where('status', '=', 1)
            ->orwhere('status', '=', 5)
            ->groupBy(DB::raw("DATE_FORMAT(sold_at, '%Y%m')"))
            ->get();

        $salesByCategory = DB::table('sale_details')
            ->select(DB::raw("price_category as category,sum(amount) amount"))
            ->where('status', '=', 1)
            ->orwhere('status', '=', 5)
            ->groupBy('price_category')
            ->get();

        $data['avg_daily_reg'] = $avgDailySales;
        $data['today_reg'] = $todaySales;
        $data['total_daily_reg'] = $totalDailySales;
        $data['reg_by_category'] = $salesByCategory;
        $data['total_monthly'] = $totalMonthlySales;
        $data['out_of_stock'] = $outOfStock;
        $data['out_of_stock_list'] = $outOfStockList;
        $data['expired'] = $expired;


        return $data;
    }

    private function laboratoryDashboard()
    {
        $data = array();

        /*average patient registration by gender*/
        $totalRegistrationByGender = Patient::select(DB::raw("sex as category,count(id) amount"))
            ->groupBy('sex')
            ->get();
        $days_of_registration_by_gender = Patient::select(DB::raw('date(created_at)'))
            ->distinct()
            ->get();

        $avgDailyRegistrationByGender = array();
        if ($days_of_registration_by_gender->count() == 0) {
            $avgDailyRegistrationByGender = 0;
        } else {
            foreach ($totalRegistrationByGender as $item) {
                if ($item->category == "Female") {
                    $correct_data = $item->amount / $days_of_registration_by_gender->count();
                    array_push($avgDailyRegistrationByGender, array(
                        "category" => "Female",
                        "amount" => $correct_data
                    ));
                } else {
                    $correct_data = $item->amount / $days_of_registration_by_gender->count();
                    array_push($avgDailyRegistrationByGender, array(
                        "category" => "Male",
                        "amount" => $correct_data
                    ));
                }
            }
        }
        /*end average patient registration by gender*/

        /*today registration*/
        $todayTests = LabTestOrder::select(DB::raw("DATE_FORMAT(lab_tests_orders.created_at, '%b %y') month"), 'lab_tests_id', DB::raw('date(lab_tests_orders.created_at) as date'))
            ->join('visits', 'visits.id', '=', 'lab_tests_orders.visit_id')
            ->join('patients', 'patients.id', '=', 'visits.patient_id')
            ->join('price_categories', 'visits.price_category_id', '=', 'price_categories.id')
            ->where('lab_tests_orders.result_status', '!=', 'null')
            ->whereRaw('date(lab_tests_orders.created_at) = date(now())')
            ->groupby('lab_tests_orders.id')
            ->get();

        $todayTests = json_decode($todayTests, true);
        $today_results = $this->labCount($todayTests);
        if ($today_results[0] == []) {
            $todayTests = 0;
        } else {
            $todayTests = $today_results[0][0]['amount'];
        }
        /*end today registration*/

        /*total reg by gender*/
        $registrationByGender = Patient::select(DB::raw("sex as category,count(id) amount"))
            ->whereRaw('month(created_at) = month(now())')
            ->groupBy('sex')
            ->get();
        /*end total reg by gender*/

        /*total monthly registration*/
        $totalMonthlyTest = LabTestOrder::select(
            DB::raw("DATE_FORMAT(lab_tests_orders.created_at, '%b %y') month"),
            'lab_tests_id',
            DB::raw('date(lab_tests_orders.created_at) as date')
        )
            ->join('visits', 'visits.id', '=', 'lab_tests_orders.visit_id')
            ->join('patients', 'patients.id', '=', 'visits.patient_id')
            ->join('price_categories', 'visits.price_category_id', '=', 'price_categories.id')
            ->where('lab_tests_orders.result_status', '!=', 'null')
            ->groupby('lab_tests_orders.id')
            ->get();
        $totalMonthlyTest = json_decode($totalMonthlyTest, true);
        $results = $this->labCount($totalMonthlyTest);
        $totalMonthlyTest = $results[0];
        /*end total monthly registration*/

        /*total daily registration*/
        $totalDailyTest = $results[4];
        /*end total daily registration*/

        /*average tests*/
        $totalTests = array_sum($results[2]);
        $days_of_tests = LabTestOrder::select(DB::raw('date(created_at)'))
            ->distinct()
            ->get();
        if ($days_of_tests->count() == 0) {
            $avgDailyTest = 0;
        } else {
            $avgDailyTest = $totalTests / $days_of_tests->count();
        }
        /*end average test*/

        /*total test by names*/
        $testByName = $results[3];
        /* There are many test and pie chart doesnt look good, so we take only to 10 tests */
        $testByName = collect($testByName);
        $testByName = $testByName->sortByDesc(fn ($data) => $data['amount']);
        $testByName = $testByName->take(10)->reduce(function ($carry, $value) {
            array_push($carry, $value);
            return $carry;
        }, []);
        /*end total test by names*/

        /*labtest by category*/
        $labTestByCategory = LabTestOrder::select(DB::raw("price_categories.name as category,count(lab_tests_orders.id) amount"))
            ->join('price_categories', 'price_categories.id', '=', 'lab_tests_orders.price_category_id')
            ->whereNotNull('lab_tests_orders.payment_status')
            ->groupBy('lab_tests_orders.price_category_id')
            ->get();
        /*labtest by category*/

        $data['avg_daily_test'] = $avgDailyTest;
        $data['avg_daily_reg_by_gender'] = $avgDailyRegistrationByGender;
        $data['today_test'] = $todayTests;
        $data['total_daily_test'] = $totalDailyTest;
        $data['reg_by_category'] = $testByName;
        $data['reg_by_gender'] = $registrationByGender;
        $data['total_monthly'] = $totalMonthlyTest;
        $data['lab_test_by_category'] = $labTestByCategory;

        return $data;
    }

    private function procedureDashboard()
    {
        $data = array();

        /*today procedures*/
        $todayProcedures = ServiceOrder::select(DB::raw("DATE_FORMAT(service_orders.created_at, '%b %y') month"), 'service_id', DB::raw('date(service_orders.created_at) as date'))
            ->join('services', 'service_orders.service_id', '=', 'services.id')
            //->join('visits', 'visits.id', '=', 'service_orders.visit_id')
            //->join('patients', 'patients.id', '=', 'visits.patient_id')
            //->join('price_categories', 'patients.price_category_id', '=', 'price_categories.id')
            ->where('services.type', '=', 'Procedure')
            ->where('service_orders.status', '!=', 'null')
            ->whereRaw('date(service_orders.created_at) = date(now())')
            //->groupby('service_orders.id', 'lab_tests_id', 'price_categories.name')
            ->get();

        $countTodayProcedures = $todayProcedures->count();

        $monthlyProcedures = ServiceOrder::select(DB::raw("DATE_FORMAT(service_orders.created_at, '%b %y') month"), 'service_id', DB::raw('date(service_orders.created_at) as date'))
            ->join('services', 'service_orders.service_id', '=', 'services.id')
            //->join('visits', 'visits.id', '=', 'service_orders.visit_id')
            //->join('patients', 'patients.id', '=', 'visits.patient_id')
            //->join('price_categories', 'patients.price_category_id', '=', 'price_categories.id')
            ->where('services.type', '=', 'Procedure')
            ->where('service_orders.status', '!=', 'null')
            //->whereRaw('month(service_orders.created_at) = month(now())')
            //->groupby('service_orders.id', 'lab_tests_id', 'price_categories.name')
            ->get();

        $totalProcedures = $monthlyProcedures->count();

        $days_of_procedures = ServiceOrder::select(DB::raw('date(created_at)'))
            ->join('services', 'service_orders.service_id', '=', 'services.id')
            ->where('services.type', '=', 'Procedure')
            ->where('service_orders.status', '!=', 'null')
            ->distinct()
            ->get();

        /*procedure by category*/
        $procedureByCategory = ServiceOrder::select(DB::raw("price_categories.name as category,count(service_orders.id) amount"))
            ->join('price_categories', 'price_categories.id', '=', 'service_orders.price_category_id')
            ->join('services', 'service_orders.service_id', '=', 'services.id')
            ->where('services.type', 'Procedure')
            ->whereNotNull('service_orders.status')
            ->groupBy('service_orders.price_category_id')
            ->get();
        /*procedure by category*/

        if ($days_of_procedures->count() == 0) {
            $avgDailyProcedure = 0;
        } else {
            $avgDailyProcedure = $totalProcedures / $days_of_procedures->count();
        }

        $monthlyProcedures = json_decode($monthlyProcedures, true);


        $results = $this->procedureCount($monthlyProcedures);
        $monthlyProcedures = $results[0];

        $procedureByName = $results[3];

        $totalDailyProcedure = $results[4];


        $data['avg_daily_procedure'] = $avgDailyProcedure;
        $data['total_daily_procedure'] = $totalDailyProcedure;
        $data['reg_by_category'] = $procedureByName;
        $data['total_monthly_procedures'] = $monthlyProcedures;
        $data['today_procedures'] = $countTodayProcedures;
        $data['total_procedures_by_category'] = $procedureByCategory;

        return $data;
    }

    private function procedureCount($monthlyProcedures)
    {
        $grouped_procedure = array();
        $grouped_procedure_by_date = array();
        $total_grouped_procedure = array();
        $counts = array();
        $counts_by_date = array();

        foreach ($monthlyProcedures as $val) {
            if (array_key_exists('month', $val)) {
                $grouped_procedure[$val['month']][] = $val;
                $total_grouped_procedure[] = $val;
            }

            if (array_key_exists('date', $val)) {
                $grouped_procedure_by_date[$val['date']][] = $val;
            }
        }

        /*grouping by date*/
        $correct_data_by_date = [];
        foreach ($grouped_procedure_by_date as $key => $item) {
            $counts_by_date[$key] = array_count_values(array_column($item, 'service_id'));
        }

        foreach ($grouped_procedure_by_date as $key => $item) {
            array_push($correct_data_by_date, array(
                'date' => $key,
                'value' => array_sum($counts_by_date[$key])
            ));
        }
        /*end grouping by date*/


        /*grouping by months*/
        $correct_data_by_month = [];

        foreach ($grouped_procedure as $key => $item) {
            $counts[$key] = array_count_values(array_column($item, 'service_id'));
        }

        $total_count = array_count_values(array_column($total_grouped_procedure, 'service_id'));
        $total_count_name = array();
        foreach ($total_count as $key => $item) {
            $service_name = Service::find($key);

            array_push($total_count_name, array(
                'category' => $service_name->name,
                'amount' => $item
            ));
        }

        foreach ($grouped_procedure as $key => $item) {
            array_push($correct_data_by_month, array(
                'month' => $key,
                'amount' => array_sum($counts[$key])
            ));
        }
        /*end grouping by months*/


        return [$correct_data_by_month, $counts, $total_count, $total_count_name, $correct_data_by_date];
    }

    private function labCount($totalMonthlyTest)
    {
        $grouped_result = array();
        $grouped_result_by_date = array();
        $total_grouped_result = array();
        $counts = array();
        $counts_by_date = array();

        foreach ($totalMonthlyTest as $val) {
            if (array_key_exists('month', $val)) {
                $grouped_result[$val['month']][] = $val;
                $total_grouped_result[] = $val;
            }

            if (array_key_exists('date', $val)) {
                $grouped_result_by_date[$val['date']][] = $val;
            }
        }

        /*grouping by date*/
        $correct_data_by_date = [];
        foreach ($grouped_result_by_date as $key => $item) {
            $counts_by_date[$key] = array_count_values(array_column($item, 'lab_tests_id'));
        }

        foreach ($grouped_result_by_date as $key => $item) {
            array_push($correct_data_by_date, array(
                'date' => $key,
                'value' => array_sum($counts_by_date[$key])
            ));
        }
        /*end grouping by date*/


        /*grouping by months*/
        $correct_data_by_month = [];

        foreach ($grouped_result as $key => $item) {
            $counts[$key] = array_count_values(array_column($item, 'lab_tests_id'));
        }

        $total_count = array_count_values(array_column($total_grouped_result, 'lab_tests_id'));
        $total_count_name = array();
        foreach ($total_count as $key => $item) {
            $tests_name = LabTest::find($key);

            array_push($total_count_name, array(
                'category' => $tests_name->name,
                'amount' => $item
            ));
        }

        foreach ($grouped_result as $key => $item) {
            array_push($correct_data_by_month, array(
                'month' => $key,
                'amount' => array_sum($counts[$key])
            ));
        }
        /*end grouping by months*/


        return [$correct_data_by_month, $counts, $total_count, $total_count_name, $correct_data_by_date];
    }

    private function billDashboard()
    {
        $data = array();

        /*total bill service*/

        /*total sales before VAT and DISCOUNT*/
        $total_sale = SalesDetail::where('type', '!=', null)->sum('amount');
        // total discount for sales
        $total_sale_discount = SalesDetail::where('type', '!=', null)->sum('discount');
        // total lab income
        $total_lab = LabTestOrder::where('payment_status', '!=', null)->sum('price');
        //total lab discount
        $total_lab_discount = LabTestOrder::where('payment_status', '!=', null)->sum('discount');

        // total service income
        $total_service = ServiceOrder::where('service_id', '>', 0)
            ->where('status', '!=', null)->sum('price');
        // total service discount
        $total_service_discount = ServiceOrder::where('service_id', '>', 0)
            ->where('status', '!=', null)->sum('discount');


        // Total sales
        $total = $total_sale + $total_lab + $total_service;

        //Total discount
        $total_discount = $total_sale_discount + $total_lab_discount + $total_service_discount;
        $total = $total - $total_discount;


        $days_of_billing = ServiceOrder::select(DB::raw('date(created_at)'))
            ->distinct()
            ->get();
        if ($days_of_billing->count() == 0) {
            $avgDailyBill = 0;
        } else {
            $avgDailyBill = $total / $days_of_billing->count();
        }
        /*end total bill service*/

        /*today registration*/
        // total sales
        $total_sale_today = SalesDetail::join('sales', 'sales.id', '=', 'sales_details.sale_id')
            ->whereRaw('date(date) = date(now())')
            ->where('type', '!=', null)
            ->sum('amount');
        // total sales discount
        $total_sale_discount_today = SalesDetail::join('sales', 'sales.id', '=', 'sales_details.sale_id')
            ->whereRaw('date(date) = date(now())')
            ->where('type', '!=', null)
            ->sum('discount');
        // Total lab sales
        $total_lab_today = LabTestOrder::whereRaw('date(created_at) = date(now())')
            ->where('payment_status', '!=', null)
            ->sum('price');
        // Total lab discount
        $total_lab_discount_today = LabTestOrder::whereRaw('date(created_at) = date(now())')
            ->where('payment_status', '!=', null)
            ->sum('discount');

        // Total services sales
        $total_service_today = ServiceOrder::where('service_id', '>', 0)
            ->whereRaw('date(created_at) = date(now())')
            ->where('status', '!=', null)
            ->sum('price');
        // Total services discount
        $total_service_discount_today = ServiceOrder::where('service_id', '>', 0)
            ->whereRaw('date(created_at) = date(now())')
            ->where('status', '!=', null)
            ->sum('discount');
        $todayBill = $total_sale_today + $total_lab_today + $total_service_today;
        $todayDiscount = $total_sale_discount_today + $total_lab_discount_today + $total_service_discount_today;
        $todayBill -= $todayDiscount;

        /*end today registration*/

        /*total monthly bill*/
        $total_pharmacy_monthly = SalesDetail::select(DB::raw("DATE_FORMAT(date, '%b %y') month,sum(amount-discount) amount"))
            ->join('sales', 'sales.id', '=', 'sales_details.sale_id')
            ->where('type', '!=', null)
            ->groupBy(DB::raw("DATE_FORMAT(date, '%Y%m')"))
            ->get();
        $total_pharmacy_monthly = json_decode($total_pharmacy_monthly, true);
        $total_lab_monthly = LabTestOrder::select(DB::raw("DATE_FORMAT(created_at, '%b %y') month,sum(price-discount) amount"))
            ->where('payment_status', '!=', null)
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y%m')"))
            ->get();
        $total_lab_monthly = json_decode($total_lab_monthly, true);
        $total_service_monthly = ServiceOrder::select(DB::raw("DATE_FORMAT(created_at, '%b %y') month,sum(price-discount) amount"))
            ->where('service_id', '>', 0)
            ->where('status', '!=', null)
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y%m')"))
            ->get();
        $total_service_monthly = json_decode($total_service_monthly, true);
        $total_bills_by_month = [$total_pharmacy_monthly, $total_lab_monthly, $total_service_monthly];

        $commonFunction = new CommonFunctions();
        $sum_by_month = array();
        foreach ($total_bills_by_month as $item) {
            foreach ($item as $value) {
                $index = $commonFunction->sumByKey($value['month'], $sum_by_month, 'month');
                if ($index < 0) {
                    $sum_by_month[] = $value;
                } else {
                    $sum_by_month[$index]['amount'] += $value['amount'];
                }
            }
        }
        /*end total monthly bill*/

        /*total bill by category*/
        $billByService = [
            [
                'category' => 'Pharmacy',
                'amount' => $total_sale
            ],
            [
                'category' => 'Laboratory',
                'amount' => $total_lab
            ],
            [
                'category' => 'Others (Procedure, Registration,...)',
                'amount' => $total_service
            ]
        ];
        /*end total bill by category*/

        /*procedure by category*/

        $sales_bills_by_category = Sale::select(DB::raw("price_categories.name as category,sum(sales_details.amount) amount"))
            ->join('sales_details', 'sales.id', '=', 'sales_details.sale_id')
            ->join('price_categories', 'price_categories.id', '=', 'sales.price_category_id')
            ->where('sales_details.type', '!=', null)
            ->groupBy('sales.price_category_id')
            ->get();
        $lab_bills_by_category = LabTestOrder::select(DB::raw("price_categories.name as category,sum(lab_tests_orders.price) amount"))
            ->join('price_categories', 'price_categories.id', '=', 'lab_tests_orders.price_category_id')
            ->where('payment_status', '!=', null)
            ->groupBy('lab_tests_orders.price_category_id')
            ->get();
        $service_bills_by_category = ServiceOrder::select(DB::raw("price_categories.name as category,sum(service_orders.price) amount"))
            ->join('price_categories', 'price_categories.id', '=', 'service_orders.price_category_id')
            ->where('service_id', '>', 0)
            ->where('status', '!=', null)
            ->groupBy('service_orders.price_category_id')
            ->get();
        /*procedure by category*/

        /*total daily registration*/
        $total_pharmacy_daily = SalesDetail::select(DB::raw("date(date) date,sum(amount) value"))
            ->join('sales', 'sales.id', '=', 'sales_details.sale_id')
            ->where('type', '!=', null)
            ->groupBy(DB::raw("date(date)"))
            ->get();
        $total_pharmacy_daily = json_decode($total_pharmacy_daily, true);
        $total_lab_daily = LabTestOrder::select(DB::raw("date(created_at) date,sum(price) value"))
            ->where('payment_status', '!=', null)
            ->groupBy(DB::raw("date(created_at)"))
            ->get();
        $total_lab_daily = json_decode($total_lab_daily, true);
        $total_service_daily = ServiceOrder::select(DB::raw("date(created_at) date,sum(price) value"))
            ->where('service_id', '>', 0)
            ->where('status', '!=', null)
            ->groupBy(DB::raw("date(created_at)"))
            ->get();
        $total_service_daily = json_decode($total_service_daily, true);
        $total_bills_by_daily = [$total_pharmacy_daily, $total_lab_daily, $total_service_daily];

        $commonFunction = new CommonFunctions();
        $sum_by_date = array();
        foreach ($total_bills_by_daily as $item) {
            foreach ($item as $value) {
                $index = $commonFunction->sumByKey($value['date'], $sum_by_date, 'date');
                if ($index < 0) {
                    $sum_by_date[] = $value;
                } else {
                    $sum_by_date[$index]['value'] += $value['value'];
                }
            }
        }
        $array_column = array_column($sum_by_date, 'date');
        array_multisort($array_column, SORT_ASC, $sum_by_date);
        /*end total daily registration*/


        $data['avg_daily_bill'] = $avgDailyBill;
        $data['today_bill'] = $todayBill;
        $data['total_daily_bill'] = $sum_by_date;
        $data['bill_by_service'] = $billByService;
        $data['sales_bills_by_category'] = $sales_bills_by_category;
        $data['lab_bills_by_category'] = $lab_bills_by_category;
        $data['service_bills_by_category'] = $service_bills_by_category;
        $data['total_monthly'] = $sum_by_month;
        return $data;
    }

    public function showChangePasswordForm()
    {
        $password_policy = Setting::where('id', 139)->value('value');
        return view('auth.passwords.change_password', compact('password_policy'));
    }

    public function changePassword(Request $request)
    {
        $password_policy = Setting::where('id', 139)->value('value');
        if ($password_policy == 'YES') {
            if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
                // The passwords matches
                return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
            }
            if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
                //Current password and new password are same
                return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
            }

            $validatedData = $request->validate([
                'current-password' => 'required',
                'new-password' => [
                    'required',
                    'string',
                    'min:8',             // must be at least 10 characters in length
                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/',      // must contain at least one digit
                    'regex:/[@$!%*#?&]/', // must contain a special character
                ],
            ]);

            //Change Password
            $user = Auth::user();
            $user->password = bcrypt($request->get('new-password'));
            $user->password_changed_on = now();
            $user->save();
            Session::flash("alert-success", "Password changed successfully!");
            return redirect()->route('showProfile');
        } else {
            if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
                // The passwords matches
                return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
            }
            if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
                //Current password and new password are same
                return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
            }
            $validatedData = $request->validate([
                'current-password' => 'required',
                'new-password' => 'required|string|min:6|confirmed',
            ]);

            //Change Password
            $user = Auth::user();
            $user->password = bcrypt($request->get('new-password'));
            $user->save();
            Session::flash("alert-success", "Password changed successfully!");
            return redirect()->route('home');
        }
    }
}
