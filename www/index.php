<?php

require_once __DIR__ . '/src/model/DBManager.php';

session_start();

//redirect if not logged in
if ( !isset($_SESSION['user_id']) )
{
    header('Location: /login');
    exit();
}


//session vars
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];


//data
$manager = new DBManager();
$accounts = $manager->getAccountsWithBalance($user_id);
$recent_transactions = $manager->getTransactionsForUser($user_id);

$account_map = [];

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
    
    <script>
        var accounts = <?= json_encode($accounts) ?>;
        var recent_transactions = <?= json_encode($recent_transactions) ?>;
        
        console.log(accounts);
        console.log(recent_transactions);
    </script>
    
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
        //account list
        foreach($accounts as $account)
        {
            $name = $account->institution . ' - ' . $account->type;
            $account_map[$account->id] = $name;
        ?>
            
            <li id='account-<?= $account->id ?>' class='account-item'>
                <p class='account-name'><?= $name ?></p>
                <p class='account-amount'>$<?= number_format($account->balance, 2) ?></p>

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
            
        <?php 
        foreach ($recent_transactions as $t)
        {
        ?>    
            <ul id='transaction-list' class='table-list'>
                <li class='transaction-item'>
                    <p class='transaction-account'><?= $account_map[$t->account_id] ?></p>
                    <p class='transaction-date'   ><?= date_format($t->time, "Y. n. j.") ?></p>
                    <p class='transaction-amount' ><?= number_format($t->amount, 2) ?></p>
                    <p class='transaction-merchant'><?= $t->descriptor ?></p>
                    <p class='transaction-category'><?= $t->category ?></p>
                </li>
            </ul>
        <?php 
        }
        ?>
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