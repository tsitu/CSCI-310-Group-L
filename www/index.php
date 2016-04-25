<?php

require_once "src/model/AccountManager.php";
require_once "src/model/TransactionManager.php";

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

//managers
$beg = new DateTime('-3 months');
$end = new DateTime();

$am = AccountManager::getInstance();
$tm = TransactionManager::getInstance();

$accounts = [];
$transactions = [];
$activeList = [];

$awb = $am->getAccountsWithBalance($user_id);
foreach ($awb as $a)
{
    $aid = $a->id;
    $activeList[] = $aid;
    
    $accounts[] = [$aid, $a];
    $transactions[] = [$aid, $tm->getListForAccountBetween($aid, $beg, $end)];
}

?>

<!DOCType html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    
    <meta charset="utf-8">
    <title>minance</title>
    
    <link rel='stylesheet' href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel='stylesheet' href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel='stylesheet' href='css/libraries/pikaday.css'>
    
    <link rel='stylesheet' href='css/global.css'>
    <link rel='stylesheet' href='css/dialog.css'>
    <link rel='stylesheet' href='css/dash-layout (new).css'>
    <link rel='stylesheet' href='css/dash-style (new).css'>
</head>
<body>
    
    <aside id='side' class='side'>
        
    </aside>
    
    <main>
        <div class='top-bar'>
            <button class='toggle-side fa fa-bars'></button>
            
            <div class='flex-glue'></div>
            <div class='dropdown dd-user'>
                <button class='toggle-drop profile fa fa-user'></button>
                <ul class='droplist'>
                    <li class='dropitem logout'>Logout</li>
                </ul>
            </div>
        </div>
        
        <div id='content' class='content'>
            
            <div id='graph-module' class='module graph-module'>
                <div class='module-header'>
                    <h3 class='label module-label'>Graph</h3>
                </div>
                <div class='module-subheader'>
                    <button id='graph-beg' class='date-select'>4/8/2016</button>
                    ~
                    <button id='graph-end' class='date-select'>4/8/2016</button>
                </div>
                <div id='graph'></div>
            </div>

            <div id='transaction-module' class='module transactions-module'>
                <div class='module-header'>
                    <h3 class='label module-label'>Transactions</h3>
                </div>
                <div class='module-subheader'>
                    <div class='dropdown dd-sort'>
                        <button class='toggle-drop'>
                            <span class='sort-label'>Date</span>
                            <span class='fa fa-chevron-down sort-icon'></span>
                        </button>
                        <ul class='droplist'>
                            <li class='dropitem' data-sort='transaction-date'>Date</li>
                            <li class='dropitem' data-sort='transaction-amount'>Amount</li>
                            <li class='dropitem' data-sort='transaction-account'>Account</li>
                            <li class='dropitem' data-sort='transaction-category'>Category</li>
                            <li class='dropitem' data-sort='transaction-merchant'>Merchant</li>
                        </ul>
                    </div>

                    <div class='flex-glue'></div>

                    <button id='list-beg' class='date-select'>4/8/2016</button>
                    ~
                    <button id='list-end' class='date-select'>4/8/2016</button>
                </div>

                <ul id='transaction-list' class='table-list list'>

                <?php 
                foreach ($transactions as $pair)
                {
                    $aid = $pair[0];
                    $list = $pair[1];

                    foreach($list as $t)
                    {
                        for ($i = 0; $i < 10; $i++)
                        {
                ?>    
                    <li class='transaction-item' 
                            data-id='<?= $t->id ?>' 
                            data-account-id='<?= $aid ?>'
                            data-unixtime='<?= $t->unixtime * 1000 ?>'
                            data-amount='<?= $t->amount ?>'
                        >
                        <p class="transaction-account"><?= $a->name ?></p>
                        <p class="transaction-date"   ><?= date_format($t->time, "Y. n. j") ?></p>
                        <p class="transaction-amount" ><?= number_format($t->amount, 2) ?></p>
                        <p class="transaction-category"><?= $t->category ?></p>
                        <p class="transaction-merchant"><?= $t->merchant ?></p>
                    </li>
                <?php 
                        }
                    }
                }
                ?>
                </ul>
            </div>
        </div>
    </main>
    
    <div id='curtain'></div>
    
    
    <!-- JS -->
    <script>
        var accounts = new Map(<?= json_encode($accounts) ?>);
        var transactions = new Map(<?= json_encode($transactions) ?>);
        var activeList = new Set(<?= json_encode($activeList) ?>);
        
        console.log(accounts);
        console.log(transactions);
        console.log(activeList);
    </script>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.2.0/list.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    
    <script src='js/libraries/papaparse.min.js'></script>
    <script src='js/libraries/moment.min.js'></script>
    <script src='js/libraries/pikaday.js'></script>
    
    <script src='js/dashboard.js'></script>
    <script src='js/dash-user.js'></script>
    <script src='js/dash-list.js'></script>
    <script src='js/dash-graph.js'></script>
</body>
</html>