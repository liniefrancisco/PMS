<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LEASING DOCS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        body {
            padding-top: 50px;
        }
        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 50px; /* Adjust this if you have a fixed navbar */
            left: 0;
            background-color: #f8f8f8;
            border-right: 1px solid #ddd;
            padding-top: 20px;
        }
        .sidenav a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #333;
            display: block;
        }
        .sidenav a:hover {
            background-color: #ddd;
        }
        .main {
            margin-left: 270px; /* Same width as the sidebar + some margin */
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-home-tab" data-toggle="pill" data-target="#master_files" type="button" role="tab" aria-controls="master_files" aria-selected="true">Masterfile</button>
                <button class="nav-link" id="v-pills-home-tab" data-toggle="pill" data-target="#tenant_management" type="button" role="tab" aria-controls="tenant_management" aria-selected="true">Tenant Management</button>
                <button class="nav-link" id="v-pills-profile-tab" data-toggle="pill" data-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile</button>
                <button class="nav-link" id="v-pills-messages-tab" data-toggle="pill" data-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</button>
                <button class="nav-link" id="v-pills-settings-tab" data-toggle="pill" data-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</button>
            </div>
        </div>
        <div class="col-9 card">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active card-body" id="master_files" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <ol>
                        <li>
                            <strong>Stores/Property</strong>
                            <ul>
                                <li>Here you can setup stores that has available lease area.</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Floor Plan Model Setup</strong>
                            <ul>
                                <li>Upload a floor plan model on every store. (CURRENTLY NOT IN USE OR NOT FUNCTIONAL)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Cash to Bank Setup</strong>
                            <ul>
                                <li>Assign the bank account that was setup on Bank Setup to payment module.</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Lessee Category</strong>
                            <ul>
                                <li>Setup lessee category for use in contract creation. Eg. (Category 1, 2, and 3)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Lessee Type</strong>
                            <ul>
                                <li>Setup lessee type for use in contract creation. (Eg. RESTAURANT, BOTIQUE, APLLIANCE)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Area Classification</strong>
                            <ul>
                                <li>Setup lessee type for use in contract creation. (Eg. LOBBY, ROOM, ACTIVITY CENTER)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Area Type</strong>
                            <ul>
                                <li>Area Type Setup (Eg. PREMIUM, STANDARD, DELUXE)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Rent Period Setup</strong>
                            <ul>
                                <li>Setup rent period range for lessee</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Location Slot Setup</strong>
                            <ul>
                                <li>Setup location for lease</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Charges</strong>
                            <ul>
                                <li>Setup location for rent charges</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Discount Management</strong>
                            <ul>
                                <li>Setup discounts for rent</li>
                            </ul>
                        </li>
                        <li>
                            <strong>VAT & Rental Increment</strong>
                            <ul>
                                <li>VAT & Annual Rental Incrementation Setup</li>
                            </ul>
                        </li>
                        <li>
                            <strong>G/L Accounts Setup</strong>
                            <ul>
                                <li>This setups GL Accounts for the leasing ledgers, please refer to IAD for the available GL Accounts</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Bank Setup</strong>
                            <ul>
                                <li>Sets up bank that is accredited for every store.</li>
                            </ul>
                        </li>
                        <li>
                            <strong>User Setup</strong>
                            <ul>
                                <li>Setup users for leasing, cfs and modules</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Tenant User Setup</strong>
                            <ul>
                                <li>NOT USED - NOT FUNCTIONAL</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Migrate Data</strong>
                            <ul>
                                <li>NOT USED</li>
                            </ul>
                        </li>
                    </ol>
                </div>
                <div class="tab-pane fade show" id="tenant_management" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <h5>Contract Management</h5>
                    <ul>
                        <li>Masterfile For Contract Signing, Pending Contracts, Active Tenants, and Terminated Contracts</li>
                    </ul>
                </div>
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">...</div>
                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">...</div>
                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...</div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>
</html>