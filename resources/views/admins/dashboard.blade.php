@extends('layouts.admin')
@section('content')

<div class="lh-main-content">
    <div class="container-fluid">
        <!-- Page title & breadcrumb -->
        <div class="lh-page-title">
            <div class="lh-breadcrumb">
                <h5>Dashboard</h5>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li>Dashboard</li>
                </ul>
            </div>
            <div class="lh-tools">
                <a href="javascript:void(0)" title="Refresh" class="refresh"><i class="ri-refresh-line"></i></a>
                <div id="pagedate">
                    <div class="lh-date-range" title="Date">
                        <span></span>
                    </div>
                </div>
                <div class="filter">
                    <div class="dropdown" title="Filter">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ri-sound-module-line"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="#">Booking</a></li>
                            <li><a class="dropdown-item" href="#">Revenue</a></li>
                            <li><a class="dropdown-item" href="#">Expence</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="lh-card lh-card-1">
                    <div class="lh-card-content label-card">
                        <div class="title">
                            <div class="growth-numbers">
                                <h4>Visitor</h4>
                                <h5>698k</h5>
                            </div>
                            <span class="icon"><i class="ri-shield-user-line"></i></span>
                        </div>
                        <p class="card-groth up">
                            <i class="ri-arrow-up-line"></i>
                            25%
                            <span>Last Month</span>
                        </p>
                        <div class="mini-chart">
                            <div id="userNumbers"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="lh-card lh-card-2">
                    <div class="lh-card-content label-card">
                        <div class="title">
                            <div class="growth-numbers">
                                <h4>Bookings</h4>
                                <h5>10.63k</h5>
                            </div>
                            <span class="icon"><i class="ri-shopping-bag-3-line"></i>
                            </span>
                        </div>
                        <p class="card-groth down">
                            <i class="ri-arrow-down-line"></i>
                            .5%
                            <span>Last Month</span>
                        </p>
                        <div class="mini-chart">
                            <div id="bookingNumbers"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="lh-card lh-card-3">
                    <div class="lh-card-content label-card">
                        <div class="title">
                            <div class="growth-numbers">
                                <h4>Revenue</h4>
                                <h5>$85420</h5>
                            </div>
                            <span class="icon"><i class="ri-money-dollar-circle-line"></i></span>
                        </div>
                        <p class="card-groth down">
                            <i class="ri-arrow-down-line"></i>
                            2.1%
                            <span>Last Month</span>
                        </p>
                        <div class="mini-chart">
                            <div id="revenueNumbers"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="lh-card lh-card-4">
                    <div class="lh-card-content label-card">
                        <div class="title">
                            <div class="growth-numbers">
                                <h4>Rooms</h4>
                                <h5><span data-bs-toggle="tooltip" aria-label="Available"
                                        data-bs-original-title="Available">45</span>/365</h5>
                            </div>
                            <span class="icon"><i class="ri-exchange-dollar-line"></i></span>
                        </div>
                        <p class="card-groth up">
                            <i class="ri-arrow-up-line"></i>
                            9%
                            <span>Last Month</span>
                        </p>
                        <div class="mini-chart">
                            <div id="expensesNumbers"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-8 col-md-12">
                <div class="lh-card revenue-overview">
                    <div class="lh-card-header header-575">
                        <h4 class="lh-card-title">Revenue Overview</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="m-r-10 lh-full-card">
                                <i class="ri-fullscreen-line" title="Full Screen"></i></a>
                            <div class="lh-date-range date" title="Date">
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="lh-card-content">
                        <div class="lh-chart-header">
                            <div class="block">
                                <h6>Bookings</h6>
                                <h5>825
                                    <span class="up"><i class="ri-arrow-up-line"></i>24%</span>
                                </h5>
                            </div>
                            <div class="block">
                                <h6>Revenue</h6>
                                <h5>$89k
                                    <span class="up"><i class="ri-arrow-up-line"></i>24%</span>
                                </h5>
                            </div>
                            <div class="block">
                                <h6>Expence</h6>
                                <h5>$68k
                                    <span class="down"><i class="ri-arrow-down-line"></i>24%</span>
                                </h5>
                            </div>
                            <div class="block">
                                <h6>Profit</h6>
                                <h5>$21k
                                    <span class="up"><i class="ri-arrow-up-line"></i>24%</span>
                                </h5>
                            </div>
                        </div>
                        <div class="lh-chart-content">
                            <div id="overviewChart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-12">
                <div class="lh-card" id="lhmap">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">Top Country</h4>
                        <div class="header-tools">
                            <div class="lh-date-range dots">
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="lh-card-content">
                        <div class="lh-map-view">
                            <div id="world-map"></div>
                        </div>
                        <div class="lh-map-detail">
                            <div class="lh-map-detail">
                                <div class="title">
                                    <h5>Revenue</h5>
                                    <a href="#" class="visit" title="View all data">view <i
                                            class="ri-arrow-right-line"></i></a>
                                </div>
                                <div class="lh-detail-list">
                                    <div class="lh-label">
                                        <div>
                                            <label>India</label>
                                            <span class="down"><i class="ri-arrow-down-line"></i>2.6%</span>
                                        </div>
                                        <p>$958.5k</p>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                            style="width: 95%" aria-valuenow="95" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="lh-detail-list">
                                    <div class="lh-label">
                                        <div>
                                            <label>Morocco</label>
                                            <span class="up"><i class="ri-arrow-up-line"></i>5.6%</span>
                                        </div>
                                        <p>$788.7k</p>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-secondary" role="progressbar"
                                            style="width: 84%" aria-valuenow="84" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="lh-detail-list">
                                    <div class="lh-label">
                                        <div>
                                            <label>Brazil</label>
                                            <span class="up"><i class="ri-arrow-up-line"></i>3.7%</span>
                                        </div>
                                        <p>$592.2k</p>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-secondary" role="progressbar"
                                            style="width: 76%" aria-valuenow="76" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="lh-card" id="bookingtbl">
                    <div class="lh-card-header">
                        <h4 class="lh-card-title">Bookings</h4>
                        <div class="header-tools">
                            <a href="javascript:void(0)" class="m-r-10 lh-full-card"><i
                                    class="ri-fullscreen-line" title="Full Screen"></i></a>
                            <div class="lh-date-range dots">
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="lh-card-content card-default">
                        <div class="booking-table">
                            <div class="table-responsive">
                                <table id="booking_table" class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>CheckIn</th>
                                            <th>CheckOut</th>
                                            <th>Proof</th>
                                            <th>Payment</th>
                                            <th>Amount</th>
                                            <th>RoomNo</th>
                                            <th>Rooms</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="token">2650</td>
                                            <td><img class="cat-thumb" src="assets/img/user/1.jpg"
                                                    alt="clients Image"><span class="name">Zara nails</span>
                                            </td>
                                            <td>15/01/2024</td>
                                            <td>20/01/2024</td>
                                            <td>Passport</td>
                                            <td class="active">Cash</td>
                                            <td>$550</td>
                                            <td class="type"><span>VIP : </span>101, 102</td>
                                            <td class="rooms">
                                                <span class="mem">6 Member</span> /
                                                <span class="room">2 Room</span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-outline-success"><i
                                                            class="ri-information-line"></i></button>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">2650</td>
                                            <td><img class="cat-thumb" src="assets/img/user/2.jpg"
                                                    alt="clients Image"><span class="name">Zara nails
                                                    Pvt.</span></td>
                                            <td>19/04/2024</td>
                                            <td>29/04/2024</td>
                                            <td>Pan Card</td>
                                            <td class="close">Cheque</td>
                                            <td>$200</td>
                                            <td class="type"><span>Junior : </span>105</td>
                                            <td class="rooms">
                                                <span class="mem">4 Member</span> /
                                                <span class="room">1 Room</span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-outline-success"><i
                                                            class="ri-information-line"></i></button>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">2365</td>
                                            <td><img class="cat-thumb" src="assets/img/user/3.jpg"
                                                    alt="clients Image"><span class="name">Olive Yew</span></td>
                                            <td>01/07/2024</td>
                                            <td>02/07/2024</td>
                                            <td>Pan Card</td>
                                            <td class="pending">Pending</td>
                                            <td>$400</td>
                                            <td class="type"><span>VVIP : </span>107</td>
                                            <td class="rooms">
                                                <span class="mem">2 Member</span> /
                                                <span class="room">1 Room</span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-outline-success"><i
                                                            class="ri-information-line"></i></button>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">2205</td>
                                            <td><img class="cat-thumb" src="assets/img/user/4.jpg"
                                                    alt="clients Image"><span class="name">Allie Grater</span>
                                            </td>
                                            <td>01/07/2024</td>
                                            <td>02/07/2024</td>
                                            <td>Adhar Card</td>
                                            <td class="success">Gpay</td>
                                            <td>$1200</td>
                                            <td class="type"><span>Premium :</span> 103, 104, <span>Delux :
                                                </span>106</td>
                                            <td class="rooms">
                                                <span class="mem">12 Member</span> /
                                                <span class="room">3 Room</span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-outline-success"><i
                                                            class="ri-information-line"></i></button>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">2187</td>
                                            <td><img class="cat-thumb" src="assets/img/user/5.jpg"
                                                    alt="clients Image"><span class="name">Stanley Knife</span>
                                            </td>
                                            <td>22/03/2024</td>
                                            <td>05/04/2024</td>
                                            <td>Passport</td>
                                            <td class="active">Cash</td>
                                            <td>$1200</td>
                                            <td class="type"><span>Delux : </span>108</td>
                                            <td class="rooms">
                                                <span class="mem">1 Member</span> /
                                                <span class="room">1 Room</span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-outline-success"><i
                                                            class="ri-information-line"></i></button>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">2050</td>
                                            <td><img class="cat-thumb" src="assets/img/user/6.jpg"
                                                    alt="clients Image"><span class="name">Zara nails</span>
                                            </td>
                                            <td>09/09/2022</td>
                                            <td>15/09/2022</td>
                                            <td>Adhar Card</td>
                                            <td class="close">Cheque</td>
                                            <td>$1560</td>
                                            <td class="type"><span>VIP : </span>203</td>
                                            <td class="rooms">
                                                <span class="mem">2 Member</span> /
                                                <span class="room">1 Room</span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-outline-success"><i
                                                            class="ri-information-line"></i></button>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">1995</td>
                                            <td><img class="cat-thumb" src="assets/img/user/7.jpg"
                                                    alt="clients Image"><span class="name">Ivan Itchinos</span>
                                            </td>
                                            <td>16/08/2024</td>
                                            <td>20/08/2024</td>
                                            <td>Pan Card</td>
                                            <td class="success">Gpay</td>
                                            <td>$1560</td>
                                            <td class="type"><span>VIP : </span>204, <span>Junior : </span>401,
                                                402</td>
                                            <td class="rooms">
                                                <span class="mem">6 Member</span> /
                                                <span class="room">3 Room</span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-outline-success"><i
                                                            class="ri-information-line"></i></button>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">1985</td>
                                            <td><img class="cat-thumb" src="assets/img/user/8.jpg"
                                                    alt="clients Image"><span class="name">Moris Waites</span>
                                            </td>
                                            <td>19/12/2021</td>
                                            <td>25/12/2021</td>
                                            <td>Pan Card</td>
                                            <td class="success">Gpay</td>
                                            <td>$1560</td>
                                            <td class="type"><span>Deluxe : </span>104, <span>Junior :
                                                </span>401, 402</td>
                                            <td class="rooms">
                                                <span class="mem">10 Member</span> /
                                                <span class="room">4 Room</span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-outline-success"><i
                                                            class="ri-information-line"></i></button>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">1945</td>
                                            <td><img class="cat-thumb" src="assets/img/user/9.jpg"
                                                    alt="clients Image"><span class="name">Sarah Moanees</span>
                                            </td>
                                            <td>25/02/2024</td>
                                            <td>25/02/2024</td>
                                            <td>Pan Card</td>
                                            <td class="pending">pending</td>
                                            <td>$400</td>
                                            <td class="type"><span>VIP : </span>104</td>
                                            <td class="rooms">
                                                <span class="mem">1 Member</span> /
                                                <span class="room">1 Room</span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-outline-success"><i
                                                            class="ri-information-line"></i></button>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="token">1865</td>
                                            <td><img class="cat-thumb" src="assets/img/user/10.jpg"
                                                    alt="clients Image"><span class="name">Anne Ortha</span>
                                            </td>
                                            <td>28/02/2024</td>
                                            <td>05/03/2024</td>
                                            <td>Passport</td>
                                            <td class="active">Cash</td>
                                            <td>$800</td>
                                            <td class="type"><span>Deluxe : </span>304, 305</td>
                                            <td class="rooms">
                                                <span class="mem">7 Member</span> /
                                                <span class="room">2 Room</span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-outline-success"><i
                                                            class="ri-information-line"></i></button>
                                                    <button type="button"
                                                        class="btn btn-outline-success dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-display="static">
                                                        <span class="sr-only"><i
                                                                class="ri-settings-3-line"></i></span>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
