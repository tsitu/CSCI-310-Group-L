<?php

require_once __DIR__ . '/src/inc/queries.php';

session_start();

//redirect if not logged in
if ( !isset($_SESSION['user_id']) )
{
    header('Location: /login');
    exit();
}


//data
$uid = $_SESSION['user_id'];
$username = $_SESSION['username'];




?>

<!DOCType html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

    
    <meta charset="utf-8">
    <title>minance</title>
    
    <link rel='stylesheet' href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel='stylesheet' href='css/libraries/pikaday.css'>
    
    <link rel='stylesheet' href='css/global.css'>
    <link rel='stylesheet' href='css/dash-layout (new).css'>
    <link rel='stylesheet' href='css/dash-style (new).css'>
</head>
<body>
    
    <!-- Top -->
    <div class='top-bar hor-flex'>
        <h1 class='title app-title'>mi<span class='shrink'>nance</span></h1>

        <div class='bar-content hor-flex'>
            <h2 class='title section-title'>Dashboard</h2>

            <div class='user-menu hor-flex'>
                <span class='label user-label'> <?= $username ?> </span>
                <button class="logout fa fa-sign-out"></button>
            </div>
        </div>
    </div>
    
    <!-- Side -->
    <button class='show-side toggle-side fa fa-bars'></button>
    <div class='panel side-panel'>
        <div class='side-header'>
            <button class='toggle-side side-option'>
                <span class='fa fa-times'></span>
                <p class='label'>Close</p>
            </button>
            <button class='logout side-option'>
                <span class='fa fa-sign-out'></span>
                <p class='label'>Logout</p>
            </button>
        </div>
        <ul id='account-list'>

            <?php
            $accountIDs = getAccountIds($uid);
            foreach ($accountIDs as $aid)
            {
                $account = getAccount($aid);
                $transactions = getTransactions($uid, $aid);

                $balance = number_format(Transaction::tabulateAmount($transactions), 2);
            ?>

            <li id='account-<?= $aid ?>' class='account-item'>
                <p class='account-name'><?= $account->institution . ' - ' . $account->type ?></p>
                <p class='account-amount'>$<?= $balance ?></p>

                <div class='account-menu'>
                    <button class='account-option fa fa-line-chart'></button>
                    <button class='account-option fa fa-list-ul'></button>
                    <button class='account-option fa fa-cog'></button>
                </div>
            </li>

            <?php
            }
            ?>
        </ul>

        <button id='show-add' class='show-add'>Add Account</button>
    </div>
    
    <!-- Main Content -->
    <div class='content'>
        
        <div id='graph-module' class='module graph-module'>
            <div class='module-header'>
                <h3 class='label module-label'>Graph</h3>
                <div class='flex-glue'></div>
                
                <button id='beg-date' class='date-select'>4/8/2016</button>
                <pre> ~ </pre>
                <button id='end-date' class='date-select'>4/8/2016</button>
            </div>
            <div id='graph'></div>
        </div>
        
        <div id='transaction-module' class='module transactions-module'>
            <div class='module-header'>
                <h3 class='label module-label'>Transactions</h3>
                
            </div>
            
            <ul id='transaction-list' class='table-list'>
                <li class='transaction-item'>
                    <p class='transaction-account'>Bank of America - Checking</p>
                    <p class='transaction-date'   >2016. 04. 12</p>
                    <p class='transaction-amount' >-120.30</p>
                    <p class='transaction-merchant'>Chipotle</p>
                    <p class='transaction-category'>Food</p>
                </li>
            </ul>
        </div>
    </div>
    
    
    <!-- Popup -->
    <div id='curtain'></div>
    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.2.0/list.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src='js/libraries/papaparse.min.js'></script>
    <script src='js/libraries/moment.min.js'></script>
    <script src='js/libraries/pikaday.js'></script>
    
    <script src='js/dashboard.js'></script>
    <script src='js/uploadCSV.js'></script>
</body>
</html>