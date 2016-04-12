<?php

require_once __DIR__ . '/src/model/DBManager.php';

session_start();

//redirect if not logged in
if ( !isset($_SESSION['user_id']) )
{
    header('Location: /CSCI-310-Group-L/www/');
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

<<<<<<< HEAD
            <div class='user-menu hor-flex'>
                <span class='label user-label'> <?= $username ?> </span>
                <button class="logout fa fa-sign-out"></button>
=======
            <div id='user-menu' class='user-menu'>
                <span class='label user-label'> <?= $username ?> </span>
                <button id='logout' class="fa fa-sign-out logout"></button>
>>>>>>> tran
            </div>
        </div>
    </div>
    
<<<<<<< HEAD
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
=======
    <div class='content wide'>
        
        <div id='account-module' class='panel side-panel'>
            <ol id='account-list'>
                <script type="text/javascript">
                var accountInstitutions = [];
                var accountTypes = [];
                </script>
                
                <?php
                $accountIDs = getAccountIds($uid);
                foreach ($accountIDs as $aid)
                {
                    $account = getAccount($aid);
                    $transactions = getTransactions($uid, $aid);
                    
                    $balance = number_format(Transaction::tabulateAmount($transactions), 2);
                ?>

                <script type="text/javascript">
                var accountInstitution = "<?php echo $account->institution ?>";
                var accountType = "<?php echo $account->type ?>";

                accountInstitutions.push(accountInstitution);
                accountTypes.push(accountType);
                </script>
                
                <li id='account-<?= $aid ?>' class='account-item'>
                    <p class='account-name'><?= $account->institution . ' - ' . $account->type ?></p>
                    <p class='account-amount'>$<?= $balance ?></p>
                    
                    <div class='account-options'>
                        <button class='account-menu account-chart fa fa-line-chart'></button>
                        <button class='account-menu account-list fa fa-list-ul'></button>
                        <button class='account-menu account-settings fa fa-cog'></button>
                        <button class='account-menu account-remove fa fa-trash'></button>
                    </div>
                </li>

                <script type="text/javascript">
                var buttons = document.getElementsByClassName('account-remove');
                var buttonIndex = 1;
                for (var i=0; i<buttons.length; i++) {
                    buttons[i].id = buttonIndex;
                    buttonIndex++;
                }
                </script>
                
                <?php
                }
                ?>
            </ol>
            
            <button id='add-account'>Add Account</button>
>>>>>>> tran
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
                    <button class='account-option fa fa-list-ul active'></button>
                    <button class='account-option fa fa-cog'></button>
                </div>
            </li>
            
        <?php
        }
        ?>
        </ul>

<<<<<<< HEAD
        <button id='show-add' class='show-add'>Add Account</button>
=======
                <table id='transaction-table' class=''>
                    <tr class='transaction-fields'>
                        <td class='col-5 transaction-col'>Name <i class='sorter'></i></td>
                        <td class='col-1 transaction-col'>Date <i class='sorter fa fa-chevron-down'></i></td>
                        <td class='col-2 transaction-col'>Amount <i class='sorter'></i></td>
                        <td class='col-3 transaction-col'>Category <i class='sorter'></i></td>
                        <td class='col-4 transaction-col'>Merchant <i class='sorter'></i></td>
                    </tr>

                    <?php
                    $accountIDs = getAccountIds($uid);
                    
                    $transactions = array();
                    foreach ($accountIDs as $aid)
                    {
                        $account = getAccount($aid);
                        $transactions = array_merge($transactions, getTransactions($uid, $aid));
                    }
                    
                    foreach ($transactions as $t)
                    {
                        $sign = '';
                        if ( $t->amount > 0 ) $sign = 'pos';
                        if ( $t->amount < 0 ) $sign = 'neg';
                        
                        $amount = number_format(abs($t->amount), 2);

                        $account = getAccount($t->accountId);
                        $name = $account->institution . " - " . $account->type;
                    ?>

                    <tr class='transaction-data'>
                        <td class='col-5 transaction-name'><?= $name ?></td>
                        <td class='col-1 transaction-date'><?= date('M j, Y, g:i a', $t->timestamp) ?></td>
                        <td class='col-2 transaction-amount <?= $sign ?>'>$<?= $amount ?></td>
                        <td class='col-3 transaction-category'><?= $t->category ?></td>
                        <td class='col-4 transaction-merchant'><?= $t->descriptor ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </div>
>>>>>>> tran
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
                
                <div class='module-subheader'></div>
            </div>
            
        <?php 
        foreach ($recent_transactions as $t)
        {
        ?>    
            <ul id='transaction-list' class='table-list'>
                <li class='transaction-item'>
                    <p class='transaction-account'><?= $account_map[$t->account_id] ?></p>
                    <p class='transaction-date'   ><?= date_format($t->time, "Y. n. j") ?></p>
                    <p class='transaction-amount' ><?= number_format($t->amount, 2) ?></p>
                    <p class='transaction-category'><?= $t->category ?></p>
                    <p class='transaction-descriptor'><?= $t->descriptor ?></p>
                </li>
            </ul>
        <?php 
        }
        ?>
        </div>
    </div>

    <div id='remove-account-dialog' class='dialog'>
        <div class='dialog-header'>
            <h3 class='dialog-label'>Remove Account</h3>
            <button class='dialog-cancel fa fa-close'></button>
        </div>
        <p style="text-align: center">Are you sure?</p>
        <button class='remove-account-confirm'>Confirm</button>
    </div>
    
    
    <!-- Popup -->
    <div id='curtain'></div>
    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<<<<<<< HEAD
    <script src="//cdnjs.cloudflare.com/ajax/libs/list.js/1.2.0/list.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src='js/libraries/papaparse.min.js'></script>
    <script src='js/libraries/moment.min.js'></script>
    <script src='js/libraries/pikaday.js'></script>
=======
    <script src='js/lib/papaparse.min.js'></script>
>>>>>>> tran
    
    <script src='js/dashboard.js'></script>
    <script src='js/utils.js'></script>
</body>
</html>