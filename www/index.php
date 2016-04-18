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


//data
$am = AccountManager::getInstance();
$tm = TransactionManager::getInstance();

$now = new DateTime();
$mon = clone $now;
$mon->modify('-3 month');

$accounts = $am->getAccountsWithBalance($user_id);

$initMap = [];
$initList = [];
foreach ($accounts as $a)
{
    $list = $tm->getListForAccountBetween($aid, $mon, $now);;
    $initMap[$a->id] = $list
    $iniList = array_merge($initList, $list);
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
        var initList = <?= json_encode($initList) ?>;
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
        
        <div id='account-module' class='flex-glue'>
            
            <ul id='account-list' class='flex-glue'>
            <?php
            //account list
            foreach($accounts as $account)
            {
                $name = $account->institution . ' - ' . $account->type;
                $account_map[$account->id] = $name;
                
                $listed = ($aid === $account->id) ? 'active' : '';
            ?>

                <li id='account-<?= $account->id ?>' class='account-item'>
                    <p class='account-name'><?= $name ?></p>
                    <p class='account-amount'><?= number_format($account->balance, 2) ?></p>

                    <div class='account-menu'>
                        <button class='account-option fa fa-line-chart'></button>
                        <button class='account-option fa fa-list-ul <?= $listed ?>'></button>
                        <button class='account-option option-edit fa fa-cog'></button>
                    </div>
                    <div class='account-edit'>
                        <form class='edit-form'>
                            <input name='new-institution' placeholder='<?= $account->institution ?>'
                                   class='edit-option edit-field inst-field'>
                            <input name='new-type' placeholder='<?= $account->type ?>'
                                   class='edit-option edit-field type-field'>
                            <button class='edit-option confirm-edit'>Confirm</button>
                            <button class='edit-option delete-button'>Delete Account</button>
                        </form>
                    </div>
                </li>  
            <?php
            }
            ?>
            </ul>
            
            <div id='add-module' class='add-module mini-module'>
                <div id='add-header' class='add-header mini-module-header'>
                    <button id='add-toggle' class='add-toggle fa fa-plus'></button>
                </div>
                <form id='add-form' class='add-form' method='post' action='src/scripts/upload.php'>
                    <p id='csv-msg' class='csv-msg'>No CSV</p>
                    
                    <input type='file' id='csv-file' name='csv-file'>
                    <label for='csv-file' id='csv-choose' class='csv-choose add-option file-label'>
                        <span class='option-icon fa fa-upload'></span>
                        <span id='csv-label'>Choose CSV</span>
                    </label>
                    <button id='csv-upload' class='csv-upload add-option' disabled='disabled'>
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
                <button id='beg-graph' class='date-select'>4/8/2016</button>
                ~
                <button id='end-graph' class='date-select'>4/8/2016</button>
            </div>
            <div id='graph'></div>
        </div>
        
        <div id='transaction-module' class='module transactions-module'>
            <div class='module-header'>
                <h3 class='label module-label'>Transactions</h3>
            </div>
            <div class='module-subheader'>
                <button id='beg-transaction' class='date-select'>4/8/2016</button>
                ~
                <button id='end-transaction' class='date-select'>4/8/2016</button>
            </div>
            
        <?php 
        foreach ($initList as $aid => $a)
        {
        ?>    
            <ul id='transaction-list' class='table-list'>
                <li class='transaction-item'>
                    <p class='transaction-account'><?= $account_map[$t->account_id] ?></p>
                    <p class='transaction-date'   ><?= date_format(new Datetime($t->t), "Y. n. j") ?></p>
                    <p class='transaction-amount' ><?= number_format($t->amount, 2) ?></p>
                    <p class='transaction-category'><?= $t->category ?></p>
                    <p class='transaction-merchant'><?= $t->merchant ?></p>
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
    <script src='js/utils.js'></script>
</body>
</html>