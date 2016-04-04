<?php

session_start();

//redirect if not logged in
if ( !isset($_POST['user_id']) )
{
    header('Location: /login');
    exit();
}


//data

?>

<!DOCType html>
<html>
<head>
    <meta charset="utf-8">
    <title>minance</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel='stylesheet' href='css/global.css'>
    <link rel='stylesheet' href='css/dialog.css'>
    <link rel='stylesheet' href='css/dash-layout.css'>
    <link rel='stylesheet' href='css/dash-style.css'>
</head>
<body>
    
    <div class='top-bar wide'>
        <h1 class='title app-title'>minance</h1>

        <div class='bar-content'>
            <h2 class='title section-title'>Dashboard</h2>

            <div class='user-menu'>
                <span class='label user-label'>username</span>
                <button class="fa fa-sign-out logout"></button>
            </div>
        </div>
    </div>
    
    <div class='content wide'>
        
        <div id='account-module' class='panel side-panel'>
            <ol id='account-list'>
                <li class='account-item'>
                    <p class='account-name'>Bank of America</p>
                    <p class='account-amount'>$310.00</p>
                    
                    <div class='account-options'>
                        <button class='account-menu fa fa-line-chart'></button>
                        <button class='account-menu fa fa-list-ul'></button>
                        <button class='account-menu fa fa-cog'></button>
                    </div>
                </li>
                <li class='account-item'>
                    <p class='account-name'>Chase - Checking</p>
                    <p class='account-amount'>$450.00</p>
                    
                    <div class='account-options'>
                        <button class='account-menu fa fa-line-chart'></button>
                        <button class='account-menu fa fa-list-ul'></button>
                        <button class='account-menu fa fa-cog'></button>
                    </div>
                </li>
            </ol>
            
            <button id='add-account'>Add Account</button>
        </div>
        
        
        <div class='panel main-panel'>
            <!-- Overview -->
            <div id='overview-module'>

            </div>

            <!-- Graph -->
            <div id='graph-module'>

            </div>

            <!-- Transaction -->
            <div id='transaction-module' class='module'>
                <div class='module-header'>
                    <h3 class='module-title'>Transactions</h3>
                </div>

                <table id='transaction-table' class=''>
                    <tr class='transaction-fields'>
                        <td class='col-1 transaction-col'>Date <i class='sorter fa fa-chevron-down'></i></td>
                        <td class='col-2 transaction-col'>Amount <i class='sorter'></i></td>
                        <td class='col-3 transaction-col'>Category <i class='sorter'></i></td>
                        <td class='col-4 transaction-col'>Merchant <i class='sorter'></i></td>
                    </tr>

                    <tr class='transaction-data'>
                        <td class='col-1 transaction-date'>Apr 1, 2016</td>
                        <td class='col-2 transaction-amount'>$7.30</td>
                        <td class='col-3 transaction-category'>Food</td>
                        <td class='col-4 transaction-merchant'>Chipotle</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Popup dialogs -->
    <div id='dialog-background'></div>
    
    <div id='new-account-dialog' class='dialog'>
        <div class='dialog-header'>
            <h3 class='dialog-label'>New Account</h3>
            <button class='dialog-cancel fa fa-close'></button>
        </div>
        <form class='new-account-form'>
            <input type='text' name='new-account-name' class='new-account-name' placeholder='Account Name'>
            <input type='file' name='new-account-upload' class='new-account-upload'>
        </form>
        
        <button class='new-account-button'>Add</button>
    </div>
    
    
    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script src='js/libraries/papaparse.min.js'></script>
    
    <script src='js/dashboard.js'></script>
    <script src='js/uploadCSV.js'></script>
</body>
</html>