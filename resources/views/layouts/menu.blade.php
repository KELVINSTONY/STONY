<li class="nav-item {!! Request::is('home') ? 'active' : '' !!}"><a href="#" class="nav-link active"><span
            class="pcoded-micon">
            <i class="fas fa-tachometer-alt"></i></span><span class="pcoded-mtext">Dashboard</span></a>
</li>



    <li class="nav-item pcoded-hasmenu {!! Request::is('patients') || Request::is('vital-details') || Request::is('allocations') || Request::is('ipd-patients') || Request::is('ipd-patients/observation') || Request::is('ipd-patients/history') || Request::is('patient/sick-sheet') ? 'active pcoded-trigger' : '' !!}">
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-address-book"></i></span>
            <span class="pcoded-mtext">Patients</span>
        </a>
        <ul class="pcoded-submenu">
                <li class="active"><a href="{{ route('patients.index') }}"
                        class="">Patients</a>
                </li>

                <li a href="#">Vital
                        Details</a>
                </li>

                <li href="/allocation">Allocations</a></li>

                <li a href="#">IPD
                        Patients</a></li>

                <li a href="#">IPD Observation</a>
                </li>

                <li a href="#">IPD
                        History</a></li>

                <li a href="#" >Sick
                        Sheet</a></li>

                <li class="{!! Request::is('patient/merge') ? 'active' : '' !!}"><a href="#" class="">Patient
                        Merge</a></li>

        </ul>
    </li>
    <li class="nav-item pcoded-hasmenu {!! Request::is('consultation/consultation') || Request::is('consultation/history') ? 'active pcoded-trigger' : '' !!}">

        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-user-md"></i></span>
            <span class="pcoded-mtext">Consultation</span>
        </a>
        <ul class="pcoded-submenu">

                <li class="{!! Request::is('consultation/consultation') ? 'active' : '' !!}"><a href="#"
                        class="">Consultation</a>
                </li>


                <li class="{!! Request::is('consultation/history') ? 'active' : '' !!}"><a href="#"
                        class="">History</a>
                </li>


                <li class=""><a href="#" class="">Patients
                    Attended</a></li>

        </ul>

    </li>




    <li class="nav-item pcoded-hasmenu">
        {{-- &#xf5c9; --}}
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-tooth"></i></span>
            <span class="pcoded-mtext">Dental</span>
        </a>
        <ul class="pcoded-submenu">

                <li class=""><a href="#"
                        class="">Consultation</a>
                </li>


                <li class=""><a href="#"
                        class="">History</a>
                </li>


        </ul>

    </li>





    <li class="nav-item pcoded-hasmenu">
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-binoculars"></i></span>
            <span class="pcoded-mtext">Laboratory</span>
        </a>
        <ul class="pcoded-submenu">

                <li class=""><a href="#" class="">Test
                        Order
                        Booking</a></li>


                <li class=""><a href="#" class="">Order Book
                        Status</a></li>


                <li class=""><a href="#" class="">Auto Result
                        Posting</a></li>


                <li class=""><a href="#" class="">Test Result
                        Report</a></li>


                <li class=""><a href="#" class="">Reagent
                        Receiving</a></li>


                <li class=""><a href="#" class="">Reagent
                        Stock</a></li>


                <li class=""><a href="#" class="">Reagent Ledger</a>
                </li>


                <li class=""><a href="#" class="">Reagent Usage</a>
                </li>

        </ul>
    </li>


    <li class="nav-item pcoded-hasmenu">
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-bed"></i></span>
            <span class="pcoded-mtext">Procedures</span>
        </a>
        <ul class="pcoded-submenu">

                <li class=""><a href="#"
                        class="">Procedure Booking</a></li>



                <li class=""><a href="#" class="">Procedure
                        Status</a></li>



                <li class=""><a href="#" class="">Surgery
                    Schedule</a></li>


                <li class=""><a href="#" class="">Surgery
                    Report</a></li>

        </ul>
    </li>


    <li class="nav-item pcoded-hasmenu">
    <li class="nav-item pcoded-hasmenu">
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-x-ray"></i></span>
            <span class="pcoded-mtext">Radiology</span>
        </a>
        <ul class="pcoded-submenu">

                <li class=""><a href="#" class="">Imaging
                        Booking</a></li>


                <li class=""><a href="#" class="">Imaging
                        Status</a></li>


                <li class=""><a href="#" class="">Imaging
                        Report</a></li>

        </ul>
    </li>


    <li class="nav-item"><a href="#" class="nav-link"><span
                class="pcoded-micon">
                <i class="fas fa-first-aid"></i></span><span class="pcoded-mtext">Services</span></a>
    </li>


<li class="nav-item pcoded-hasmenu">
    {{-- <a href="#!" class="nav-link"><span class="pcoded-micon"><i --}}
    {{-- class="fas fa-life-ring"></i></span> --}}
    {{-- <span class="pcoded-mtext">Radiology</span> --}}
    {{-- </a> --}}
</li>

<li class="nav-item pcoded-hasmenu">
    {{-- <a href="#!" class="nav-link"><span class="pcoded-micon"><i --}}
    {{-- class="fas fa-procedures"></i></span> --}}
    {{-- <span class="pcoded-mtext">Procedures</span> --}}
    {{-- </a> --}}
</li>

    <li class="nav-item pcoded-hasmenu">
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-briefcase-medical"></i></span>
            <span class="pcoded-mtext">Pharmacy</span></a>
        <ul class="pcoded-submenu">

                <li class=""><a href="#!" class="">Sales</a>
                    <ul class="pcoded-submenu">

                            <li class=""><a href="{{route('sale')}}"
                                    class="">Point of Sale</a>
                            </li>


                            <li class=""><a href="#" class="">Quote
                                    List</a>
                            </li>


                            <li class=""><a href="#"
                                    class="">Sales History</a>
                            </li>


                            <li class=""><a href="#"
                                    class="">Prescription List</a></li>


                            <li class=""><a href="{{route('return')}}" class="">Sales
                                    Return</a></li>


                            <li class=""><a href="#"
                                    class="">Returns
                                    Approval</a>
                            </li>

                    </ul>
                </li>

        </ul>
    </li>


    <li class="nav-item pcoded-hasmenu">
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-store-alt"></i></span>
            <span class="pcoded-mtext">Stores</span></a>
        <ul class="pcoded-submenu">

                <li class=""><a href="#!" class="">Inventory</a>
                    <ul class="pcoded-submenu">

                            <li class=""><a href="{{route('stocking')}}"
                                    class="">Current Stock</a>
                            </li>


                            <li class=""><a href="#" class="">Price
                                    List</a>
                            </li>


                            <li class=""><a href="#"
                                    class="">Adjustment
                                    History</a>
                            </li>


                            <li class=""><a href="#"
                                    class="">Outgoing Stock</a>
                            </li>


                            <li class=""><a href="#"
                                    class="">Product Ledger</a>
                            </li>


                            <li class=""><a href="#"
                                    class="">Daily Stock Count</a>
                            </li>


                            {{-- <!--li class=""><a target="_blank" href="{{ route('inventory-count-sheet-pdf-gen') }}" class="">Inventory Count Sheet1111</a> </li--> --}}
                            <li class=""><a href="#"
                                    class="">Inventory Count Sheet</a>
                            </li>


                            <li class=""><a href="#"
                                    class="">Stock Transfer</a>
                            </li>


                            <li class=""><a href="#"
                                    class="">Transfer
                                    History</a></li>

                </li>
            </ul>
        </li>


        <li class=""><a href="#!" class="">Purchases</a>
            <ul class="pcoded-submenu">

                    <li class=""><a href="#"
                            class="">Requisitions</a></li>


                    <li class=""><a href="#"
                            class="">Issue</a></li>


                    <li class=""><a href="#" class="">Purchase
                            Order</a>
                    </li>


                    <li class=""><a href="#" class="">Purchase
                            Order
                            History</a>
                    </li>


                    <li class=""><a href="#" class="">Goods
                            Receiving</a>
                    </li>


                    <li class=""><a href="" class="">Material
                            Received</a>
                    </li>


                    <li class=""><a href="#" class="">Invoice
                            Management</a>
                    </li>

            </ul>
        </li>


    </ul>
    </li>


    <li class="nav-item pcoded-hasmenu">
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-calendar"></i></span>
            <span class="pcoded-mtext">Appointments</span>
        </a>
        <ul class="pcoded-submenu">
            {{--
            <li class=""><a href="{{ route('appointments.patients') }}" class="">Order
                    Appointment</a></li>
            {{-- --}}
            <li class=""><a href="#"
                    class="">Appointments</a></li>
        </ul>
    </li>


    <li class="nav-item pcoded-hasmenu">
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-money-bill-alt"></i></span>
            <span class="pcoded-mtext">Billing</span>
        </a>
        <ul class="pcoded-submenu">

                <li class=""><a href="#" class="">Bills List</a></li>


                <li class=""><a href="#" class="">IPD Bills
                        List</a>
                </li>


                <li class=""><a href="#" class="">Revoke
                        Payment</a></li>


                <li class=""><a href="#"
                        class="">Revoke
                        History</a></li>


                <li class=""><a href="#" class="">Billing
                        Auth No</a></li>

            <li class=""><a href="#" class="">Cashier Cash
                    Box</a></li>
        </ul>
    </li>


{{-- <li class="nav-item pcoded-hasmenu">
       <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-hand-holding-usd"></i></span>
           <span class="pcoded-mtext">HR & Payroll</span>
       </a>
    <ul class="pcoded-submenu">
        <li class=""><a href="{{route('employee-registration.index')}}" class="">PIM</a></li>
        <li class=""><a href="#!" class="">Roster Management</a>
            <ul class="pcoded-submenu">
                <li class=""><a href="{{route('job-description.index')}}" class="">Job Description</a></li>
                <li class=""><a href="#!" class="">Job Mapping</a></li>
            </ul>
        </li>
        <li class=""><a href="{{route('leave-roster.index')}}" class="">Leave Roster</a></li>
        <li class=""><a href="{{route('time-sheet.index')}}" class="">Time Sheet</a></li>
        <li class=""><a href="#!" class="">Attendance</a></li>
        <li class=""><a href="#!" class="">Staff Work Action Plan</a></li>
        <li class=""><a href="#!" class="">Appraisals & Review </a></li>
        <li class=""><a href="{{ route('salaries') }}" class="">Salaries</a></li>
        <li class=""><a href="{{ route('payrolls') }}" class="">Payroll</a></li>
        <li class=""><a href="#!" class="">Staff Regulations</a></li>
        <li class=""><a href="{{route('discipline-cases.index')}}" class="">Discipline Cases</a></li>
        <li class=""><a href="#!" class="">Hiring & Termination</a></li>
    </ul>
</li> --}}


    <li class="nav-item pcoded-hasmenu">
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-dollar-sign"></i></span>
            <span class="pcoded-mtext">Expenses</span>
        </a>
        <ul class="pcoded-submenu">
            <li class=""><a href="#" class="">Expenses</a></li>

                <li class=""><a href="#" class="">Vouchers</a></li>


                <li class=""><a href="#" class="">Voucher
                        Reports</a></li>

        </ul>
    </li>



    <li class="nav-item pcoded-hasmenu">
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-users"></i></span>
            <span class="pcoded-mtext">Users</span>
        </a>
        <ul class="pcoded-submenu">

                <li class=""><a href="#" class="">Roles</a></li>


                <li class=""><a href="#" class="">Users</a></li>


                <li class=""><a href="#" class="">User
                        Activities</a></li>

        </ul>
    </li>





    <li class="nav-item pcoded-hasmenu">
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-file-pdf"></i></span><span
                class="pcoded-mtext">Reports</span></a>

        <ul class="pcoded-submenu">

                <li class=""><a href="#!" class="">Pharmacy Reports</a>
                    <ul class="pcoded-submenu">

                            <li class=""><a href="#" class="">Sales
                                    Reports</a></li>


                            <li class=""><a href="#"
                                    class="">Inventory Reports</a>
                            </li>
                            {{-- <li class=""><a href="{{route('minimum_stock.index')}}" class="">Stock Below Minimum</a></li> --}}


                            <li class=""><a href="#"
                                    class="">Purchase Reports</a></li>


                            <li class=""><a href="#"
                                    class="">Accounting Reports</a>
                            </li>


                    </ul>
                </li>


                <li class=""><a href="#" class="">Patients
                        Reports</a></li>


                <li class=""><a href="#" class="">
                        Consultation Reports</a></li>


                <li class=""><a href="#"
                        class="">Laboratory Reports</a>


                <li class=""><a href="#" class="">Services
                        Reports</a>


                <li class=""><a href="#" class="">Billing
                        Reports</a></li>


                <li class=""><a href="#" class="">Reagents
                        Reports</a></li>


                <li class=""><a href="#!" class="">Other Reports</a>
                    <ul class="pcoded-submenu">
                        <li class=""><a href="#"
                                class="">Doctor Earnings
                                Report</a></li>
                        <li class=""><a href="#"
                                class="">Doctor Income Report</a></li>
                    </ul>
                </li>

        </ul>
    </li>


    <li class="nav-item pcoded-hasmenu">
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-file"></i></span>
            <span class="pcoded-mtext">MTUHA</span>
        </a>
        <ul class="pcoded-submenu">
            <li class=""><a href="{#" class="">All Reports</a></li>
        </ul>
    </li>


    <li class="nav-item pcoded-hasmenu">
        <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-stream"></i></span>
            <span class="pcoded-mtext">Masters</span>
        </a>
        <ul class="pcoded-submenu">
                <li class=""><a href="#!" class="">General</a>
                    <ul class="pcoded-submenu">
                            <li class=""><a href="#"
                                    class="">Doctors</a></li>

                            <li class=""><a href="#" class="">User
                                    Doctor</a></li>

                            <li class=""><a href="#"
                                    class="">Doctor Departments</a></li>

                            <li class=""><a href="#"
                                    class="">Doctor Services</a></li>

                            <li class=""><a href="#"
                                    class="">Doctor Earnings</a></li>

                            <li class=""><a href="#"
                                    class="">Price Categories</a>
                            </li>

                            <li class=""><a href="#"
                                    class="">Expense Categories</a>
                            </li>
                        <li class="">
                            <a href="#" class="">
                                Patient Import
                            </a>
                        </li>
                        <li class=""><a href="#" class="">Wards</a>
                        </li>
                        <li class=""><a href="#"
                                class="">Operation Theatre</a></li>
                    </ul>
                </li>
                <li class=""><a href="#!" class="nav-item pcoded-hasmenu">IPD Patients</a>
                    <ul class="pcoded-submenu">
                            <li><a href="#" class="">Patient Wards</a></li>

                    </ul>
                </li>
                <li class=""><a href="#!" class="nav-item pcoded-hasmenu">Services</a>
                    <ul class="pcoded-submenu">
                            <li><a href="#" class="">Manage Services</a></li>

                            <li class=""><a href="#"
                                    class="">Services
                                    Price List</a></li>

                    </ul>
                </li>
                <li class=""><a href="#!" class="nav-item pcoded-hasmenu">Laboratory</a>
                    <ul class="pcoded-submenu">
                            <li><a href="#" class="">Lab Tests</a></li>

                            <li class=""><a href="#" class="">Lab
                                    Tests Prices</a></li>

                            <li class=""><a href="#"
                                    class="">Lab Tests Price
                                    List</a></li>


                            <li><a href="#" class="">Reagents</a></li>


                            <li><a href="#" class="">Reagents Consumption</a>
                            </li>
                            <li><a href="#" class="">Consumables List</a>
                            </li>

                    </ul>
                </li>


                <li class=""><a href="#!" class="nav-item pcoded-hasmenu">Radiology</a>
                    <ul class="pcoded-submenu">

                            <li><a href="#" class="">Imaging Tests</a></li>


                            <li class=""><a href="#"
                                    class="">Imaging Tests Prices</a></li>


                            <li class=""><a href="#"
                                    class="">Imaging Tests Price
                                    List</a></li>

                    </ul>
                </li>



            {{-- <li class="">
            <a href="#!" class="nav-item pcoded-hasmenu">Radiology</a>
            <ul class="pcoded-submenu">
                <li class=""><a href="{{route('radiology.index')}}" class="">Radiology Procedures</a></li>
                <!-- <li class=""><a href="" class="">Radiologists</a></li>  -->
            </ul>
        </li> --}}

                <li class=""><a href="#!" class="">Store</a>
                    <ul class="pcoded-submenu">

                            <li class=""><a href="#"
                                    class="">Products</a></li>


                            <li class=""><a href="#"
                                    class="">Product
                                    Categories</a></li>


                            <li class=""><a href="#"
                                    class="">Product Subcategories</a>
                            </li>


                            <li class=""><a href="#"
                                    class="">Adjustment Reasons</a>
                            </li>


                            <li class=""><a href="#"
                                    class="">Suppliers</a></li>


                            <li class=""><a href="#" class="">Stores</a>
                            </li>


                            <li class=""><a href="#" class="">Product
                                    Import</a></li>


                            <li class=""><a href="#"
                                    class="">Stock Pricing</a></li>

                    </ul>
                </li>

        </ul>
    </li>


    <li class="nav-item pcoded-hasmenu">
        <a href="#!" class="">
            <span class="pcoded-micon">
                <i class="fas fa-h-square"></i></span><span class="pcoded-mtext">NHIF</a></span>
        <ul class="pcoded-submenu">

                <li class=""><a href="#"
                        class="">Authorizations</a></li>


                <li class="">
                    <a href="#" class="">Claims</a>
                    <ul class="pcoded-submenu">

                            <li class="">
                                <a href="#" class="">Generate Claims</a>
                            </li>


                            <li class="">
                                <a href="#" class="">Process Claims</a>
                            </li>

                    </ul>
                </li>


                <li class=""><a href="#"
                        class="">Settings</a>
                </li>

            <li class=""><a href="#!" class="">Services</a>
                <ul class="pcoded-submenu">

                        <li class=""><a href="#"
                                class="">NHIF
                                Services List</a></li>


                        <li class=""><a href="#"
                                class="">Tests Integration</a>
                        </li>


                        <li class=""><a href="#"
                                class="">Radiology Integration</a>
                        </li>


                        <li class=""><a href="#"
                                class="">Products Integration</a></li>


                        <li class=""><a href="#"
                                class="">Services Integration</a>
                        </li>


                </ul>
            </li>

                <li class=""><a href="#"
                        class="">Referrals</a></li>


                <li class=""><a href="#" class="">Reports</a>
                </li>

        </ul>
    </li>



    {{-- <li class="nav-item"><a href="{{route('configurations.index')}}" class="nav-link"><span class="pcoded-micon"><i
                class="feather icon-settings"></i></span><span class="pcoded-mtext">Settings</span></a>
</li> --}}
    <li class="nav-item pcoded-hasmenu">
        {{-- <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-users"></i></span>
        <span class="pcoded-mtext">Users</span>
    </a> --}}
        <a href="#!" class="nav-link"><span class="pcoded-micon">
                <i class="feather icon-settings"></i></span><span class="pcoded-mtext">Settings</span>
        </a>
        <ul class="pcoded-submenu">

                <li class=""><a href="#" class="">Master
                        Settings</a></li>


                <li class=""><a href="#" class="">Billing
                        Settings</a></li>



                <li class=""><a href="#"
                        class="">Notification Settings</a></li>


                <li class=""><a href="#" class="">ICD-10
                        Settings</a></li>


                <li class=""><a href="#" class="">MTUHA
                        Settings</a></li>

            <li class=""><a href="#" class="">TRA Tax
                    Settings</a></li>
        </ul>
    </li>


    <li class="nav-item {!! 'active' !!}">
        <a href="" class="nav-link">
            <span class="pcoded-micon"><i class="fas fa-database"></i></span><span class="pcoded-mtext">Backup</span>
        </a>
    </li>


    <li class="nav-item {!! 'active' !!}">
        <a href="" class="nav-link">
            <span class="pcoded-micon"><i class="fas fa-outdent"></i></span><span class="pcoded-mtext">Device
                Manager</span>
        </a>
    </li>


<li class="nav-item pcoded-menu-caption">
    <label>Support</label>
</li>
<li class="nav-item"><a href="" class="nav-link" target="_blank"><span class="pcoded-micon"><i
                class="fas fa-tablet"></i></span><span class="pcoded-mtext">User Guide</span></a></li>
<li class="nav-item"><a href="" class="nav-link" target="_blank"><span class="pcoded-micon"><i
                class="fas fa-question"></i></span><span class="pcoded-mtext">Help Desk</span></a>
</li>
<li class="nav-item pcoded-menu-caption">
    <label>Resources</label>
</li>
<li class="nav-item"><a href="" class="nav-link" target="_blank"><span class="pcoded-micon"><i
                class="fas fa-tablet"></i></span><span class="pcoded-mtext">User Guide</span></a></li>
<li class="nav-item"><a href="" class="nav-link" target="_blank"><span class="pcoded-micon"><i
                class="fas fa-question"></i></span><span class="pcoded-mtext">Help Desk</span></a>
</li>

