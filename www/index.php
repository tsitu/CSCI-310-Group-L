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
$am = AccountManager::getInstance();
$tm = TransactionManager::getInstance();


//get accounts and map [id, account]
$awb = $am->getAccountsWithBalance($user_id);
$accounts = [];
foreach ($awb as $a)
{
    $a->institution = rtrim($a->institution);
    $a->type = rtrim($a->type);
    $a->name = $a->institution . ' - ' . $a->type;
    $accounts[$a->id] = $a;
}

//default to 3 months
$now = new DateTime();
$mon = clone $now;
$mon->modify('-3 month');


//get list of transactions for each account over 3 months
//map [account id, ta. list] and also create all transaction list
$initMap = [];
$initList = [];
foreach ($accounts as $aid => $a)
{
    $list = $tm->getListForAccountBetween($aid, $mon, $now);
    
    if (count($list) > 0)
    {
        $initMap[$aid] = $list;
        $initList = array_merge($initList, $list);
    }
}

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
    <link rel='stylesheet' href='css/dialog.css'>
    <link rel='stylesheet' href='css/dash-layout (new).css'>
    <link rel='stylesheet' href='css/dash-style (new).css'>
</head>
<body>
    
    <script>
        var initMap = <?= json_encode($initMap) ?>;
        var initList = <?= json_encode($initList) ?>;
        var listActive = new Set(<?= json_encode(array_keys($initMap)) ?>);
        
        console.log(listActive);
        console.log(initMap);
        console.log(initList);
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
    <button id='show-side' class='show-side toggle-side fa fa-bars'></button>
    <div class='panel side-panel'>
        
        <div class='side-header'>
            <button class='toggle-side side-option'>
                <span class='fa fa-times'></span>
                <p class='label'>Close</p>
            </button>
            <button id='side-logout' class='logout side-option'>
                <span class='fa fa-sign-out'></span>
                <p class='label'>Logout</p>
            </button>
        </div>
        
        <div id='account-module' class='account-module flex-glue'>
            
            <ul id='account-list' class='flex-glue'>
            <?php
            //account list
    
            $first = true;
            foreach($accounts as $aid => $a)
            {
            ?>

                <li id='account-<?= $a->id ?>' class='account-item' data-account-id='<?= $a->id ?>'>
                    <p class='account-name'><?= $a->name ?></p>
                    <p class='account-amount'><?= number_format($a->balance, 2) ?></p>

                    <div class='account-menu'>
                        <button class='account-option toggle-graph fa fa-line-chart'></button>
                        <button class='account-option toggle-list fa fa-list-ul active'></button>
                        <button class='account-option toggle-edit fa fa-cog'></button>
                    </div>
                    <div class='account-edit'>
                        <form class='edit-form'>
                            <input name='new-institution' placeholder='<?= $a->institution ?>'
                                   class='edit-option edit-field inst-field'>
                            <input name='new-type' placeholder='<?= $a->type ?>'
                                   class='edit-option edit-field type-field'>
                            <button class='edit-option rename-button'>Rename</button>
                            <button class='edit-option delete-button'>Delete Account</button>
                        </form>
                    </div>
                </li>  
            <?php
            }
            ?>
            </ul>
            
            <div id='upload-module' class='upload-module mini-module'>
                <div id='upload-header' class='upload-header mini-module-header'>
                    <button id='toggle-upload' class='toggle-upload fa fa-plus'></button>
                </div>
                <form id='upload-form' class='upload-form'>
                    <p id='csv-msg' class='csv-msg'>No CSV</p>
                    
                    <input type='file' id='csv-file' name='csv-file'>
                    <label for='csv-file' id='csv-choose' class='csv-choose upload-option file-label'>
                        <span class='option-icon fa fa-upload'></span>
                        <span id='csv-label'>Choose CSV</span>
                    </label>
                    <button id='csv-upload' class='csv-upload upload-option' disabled='disabled'>
                        Upload
                    </button>
                </form>
            </div>
            
        </div>
    </div>
    
    <!-- Main Content -->
    <div class='content'>
        
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
                <button id='list-beg' class='date-select'>4/8/2016</button>
                ~
                <button id='list-end' class='date-select'>4/8/2016</button>
            </div>
            
            <ul id='transaction-list' class='table-list list'>
                
            <?php 
            foreach ($initList as $t)
            {
                $a = $accounts[$t->account_id];
                $name = $a->institution . ' - ' . $a->type;
            ?>    
                <li class='transaction-item'>
                    <p class="account-id hidden"><?= $a->id ?></p>
                    <p class="transaction-account"><?= $name; ?></p>
                    <p class="transaction-date"   ><?= date_format(new Datetime($t->t), "Y. n. j"); ?></p>
                    <p class="transaction-amount" ><?= number_format($t->amount, 2); ?></p>
                    <p class="transaction-category"><?= $t->category; ?></p>
                    <p class="transaction-merchant"><?= $t->merchant; ?></p>
                </li>
            <?php 
            }
            ?>
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
    <script src='js/dash-graph.js'></script>
    <script src='js/dash-list.js'></script>
    <script src='js/dash-ui.js'></script>
</body>
</html>