<?php

/**
 * Demonstration of the Razorgator PHP Client Library
 *
 * LICENSE
 *
 * This source file is subject to the BSD 3-Clause License that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://github.com/TeamOneTickets/razorgator-php-demo/blob/master/LICENSE.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@teamonetickets.com so we can send you a copy immediately.
 *
 * @author      J Cobb <j@teamonetickets.com>
 * @copyright   Copyright (c) 2013 Team One Tickets & Sports Tours, Inc. (http://www.teamonetickets.com)
 * @license     https://github.com/TeamOneTickets/razorgator-php-demo/blob/master/LICENSE.txt     BSD 3-Clause License
 */


/**
 * Get the configuration
 * Be sure to copy config.sample.php to config.php and enter your own information.
 */
@(include_once '../application/config.php')
    OR die ('You need to copy /application/config.sample.php to /application/config.php and enter your own API credentials');


/**
 * Use Composer’s autoloader.
 */
require_once '../vendor/autoload.php';


session_start();


$environments = array(
//     'sandbox',
    'production',
);

$options = array(
    'apiToken',
);

foreach ($environments as $environment) {
    foreach ($options as $option) {
        if (!empty(${$environment}[$option]) && empty($_SESSION[$environment][$option])) {
            $_SESSION[$environment][$option] = ${$environment}[$option];
        }
    }
}



/**
 * If the form has been submitted filter & validate the input for safety.
 * This is just good practice.
 *
 * Because of the HUGE number of possibilities of specific method parameters
 * we are only filtering a few standard ones as an example.
 */
if (isset($_REQUEST['libraryMethod'])) {
    $filters = array(
        '*' => array(
            'StringTrim',
            'StripTags',
            'StripNewlines',
        )
    );
    $validators = array(
        'libraryMethod' => array(
            'Alpha',
            'presence'          => 'required',
            'allowEmpty'        => false,
            'allowWhiteSpace'   => false,
        ),
        'environment' => array(
            'presence'          => 'required',
            'allowEmpty'        => false,
            'allowWhiteSpace'   => false,
        ),
        'apiToken' => array(
            'presence'          => 'required',
            'allowEmpty'        => false,
            'allowWhiteSpace'   => false,
        ),
        'apiVersion' => array(
            'Digits',
            'presence'          => 'required',
            'allowEmpty'        => false,
            'allowWhiteSpace'   => false,
        ),
    );

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $input = new \Zend_Filter_Input($filters, $validators, $_POST);
    } else {
        $input = new \Zend_Filter_Input($filters, $validators, $_GET);
    }
    //var_dump($_GET);

    $cfg['params']['apiToken'] = $input->apiToken;
    $cfg['params']['secretKey'] = $input->secretKey;
    $cfg['params']['buyerId'] = $input->buyerId;

    switch ($input->environment) {
        case 'production':
        default:
            $cfg['params']['baseUri'] = 'https://pearl.razorgator.com';
            break;

        case 'sandbox':
            $cfg['params']['baseUri'] = 'https://pearl.sandbox.razorgator.com';
    }
    $cfg['params']['apiVersion'] = $input->apiVersion;

    foreach ($options as $option) {
        $_SESSION[$input->environment][$option] = $cfg['params'][$option];
    }

    /**
     * You can initialize the Razorgator class with either a Zend_Config object
     * or with the above $cfg array.
     *
     * Zend_Config method
     * $config = new \Zend_Config($cfg);
     * $client = new \Razorgator\Client($cfg->params);
     *
     * Array method
     * $client = new \Razorgator\Client($cfg['params']);
     */

    // We'll use the Zend_Config method here
    $config = new \Zend_Config($cfg);

    $client = new \Razorgator\Client($config->params);
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Demonstration of the Razorgator PHP Client Library</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Demonstration of the Razorgator PHP Client Library">
        <meta name="author" content="J Cobb <j+razorgator@teamonetickets.com>">

        <!-- Stylesheets -->
        <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">

        <link rel="shortcut icon" href="/favicon.ico">
    </head>
    <body>
        <div>
            <img class="banner sandbox" src="images/sandbox-banner.png" alt="sandbox" />
            <img class="banner staging" src="images/staging-banner.png" alt="staging" />
            <img class="banner production" src="images/production-banner.png" alt="production" />
        </div>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="brand" href="/">Razorgator PHP Client Library Demo</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Demo <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="https://github.com/TeamOneTickets/razorgator-php-demo">GitHub <i class="icon-white icon-github"></i></a></li>
                                    <li><a href="https://github.com/TeamOneTickets/razorgator-php-demo/issues" target="_blank">Issues</a></li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">PHP Client Library <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="https://github.com/TeamOneTickets/razorgator-php">GitHub</a></li>
                                    <li><a href="https://github.com/TeamOneTickets/razorgator-php/issues" target="_blank">Issues</a></li>
                                    <li><a href="https://github.com/TeamOneTickets/razorgator-php/wiki" target="_blank">Wiki</a></li>
                                </ul>
                            </li>
                            <li><a href="http://www.teamonetickets.com/" target="_blank">Team One Tickets <i class="icon-white icon-share-alt"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container main">
            <div class="page-header">
                <h1>Demonstration of the Razorgator PHP Client Library</h1>
            </div>
		    <p>This is a quick demo of the Razorgator PHP Client Library which is used to access the <a href="https://pearl.razorgator.com/">Razorgator Fullfilment API</a>.</p>
		    <p>Some of the <code>list*()</code> methods will return a <code>Razorgator\Client\ResultSet</code> object with can be easily iterated using simple loops. If you prefer PHP’s <a href="http://www.php.net/manual/en/spl.iterators.php">built-in SPL iterators</a> you will be happy to know that <code>Razorgator\Client\ResultSet</code> implements <code><a href="http://www.php.net/manual/en/class.seekableiterator.php">SeekableIterator</a></code>.</p>

		    <?php
		        if (isset($input)) {
                    echo '<h2>Current configuration code</h2>' . PHP_EOL
                       . '<pre>' . PHP_EOL
                       . '/**' . PHP_EOL
                       . ' * Setup configuration' . PHP_EOL
                       . ' */' . PHP_EOL
                       . '$cfg[\'params\'][\'apiToken\'] = (string) \'' . $cfg['params']['apiToken'] . '\';' . PHP_EOL
                       . '$cfg[\'params\'][\'apiVersion\'] = (string) \'' . $cfg['params']['apiVersion'] . '\';' . PHP_EOL
                       . '$cfg[\'params\'][\'baseUri\'] = (string) \'' . $cfg['params']['baseUri'] . '\';' . PHP_EOL
                       . PHP_EOL
                       . '/**' . PHP_EOL
                       . ' * Create a Zend_Config object to pass to Razorgator\Client' . PHP_EOL
                       . ' */' . PHP_EOL
                       . '$config = new Zend_Config($cfg);' . PHP_EOL
                       . PHP_EOL
                       . '</pre>' . PHP_EOL
                    ;


		            // The form has been submitted. Demo the selected method.
		            $libraryMethod = (string) $input->libraryMethod;

		            /**
		             * This section documents the actual code used for the call.
		             * The final bit of code is added below because it is specific
		             * to each call.
		             */
		            echo '<h2>Code used for ' . $libraryMethod . '() method</h2>' . PHP_EOL
		               . '<pre>' . PHP_EOL
		               . '/**' . PHP_EOL
		               . ' * Finished setting up configuration.' . PHP_EOL
		               . ' * Initialize a Razorgator\Client object.' . PHP_EOL
		               . ' */' . PHP_EOL
		               . '$client = new Razorgator\Client($config->params);' . PHP_EOL
		               . PHP_EOL
		               . PHP_EOL
		               . '/**' . PHP_EOL
		               . ' * Below here is where all the method-specific stuff is.' . PHP_EOL
		               . ' */' . PHP_EOL
		            ;

                    /**
                     * Setup any necessary vars and execute the call
                     */
                    $options = _getOptions($input);
                    //var_dump($options);

                    switch ($libraryMethod) {
                        default:
                        case 'listOrders':
                        case 'listOrdersCompleted':
                        case 'getAirbill':
                        case 'downloadAirbill':
                            _outputListCode($libraryMethod, $options);
                            $results = _doList($client, $libraryMethod, $options);
                            break;

                        case 'showOrder' :
                            $showId = $options['orderId'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doShow($client, $libraryMethod, $showId);
                            break;

                        case 'rejectOrder':
                            _outputListCode($libraryMethod, $options);
                            $results = _doOther($client, $libraryMethod, $options['orderId'], $options['orderToken']);
                            break;

                        case 'getPurchaseOrder':
                        case 'downloadPurchaseOrder':
                            $showId = $options['orderId'];
                            _outputShowCode($libraryMethod, $showId);
                            $results = _doOther($client, $libraryMethod, $options['purchaseOrderId'], $options['orderId'], $options['orderToken']);
                            break;

                        case 'acceptOrder' :
                        case 'shipOrder' :
                            $displayOptions = array(
                                'orderId'               => (int)    $options['orderId'],
                                'orderToken'            => (string) $options['orderToken'],
                            );

                            if (!empty($options['shipNow'])) {
                                $displayOptions['shipNow'] = (string) $options['shipNow'];

                                if ($options['shipNow'] == false) {
                                    $estimatedShipDate = new DateTime();
                                    $displayOptions['estimatedShipDate'] = $estimatedShipDate->format('Y-m-d');
                                }
                            }

                            if (!empty($options['seatNumbers'])) {
                                $displayOptions['seatNumbers'] = (string) $options['seatNumbers'];
                            }

                            if (!empty($options['estimatedShipDate'])) {
                                $estimatedShipDate = new DateTime($options['estimatedShipDate']);
                                $displayOptions['estimatedShipDate'] = $estimatedShipDate->format('Y-m-d');
                                unset($estimatedShipDate);
                            }


                            // var_dump($_FILES);
                            if (!empty($_FILES['ticketFile']['name'])) {
                                $ticketFiles = array();
                                foreach ($_FILES as $ticketFile) {
                                    // var_dump($ticketFile);
                                    if ($ticketFile['error'] == 0) {
                                        $ticketFiles[] = base64_encode(file_get_contents($ticketFile['tmp_name']));
                                    }
                                }
                                $options['ticketFile'] = implode(';', $ticketFiles);
                                $displayOptions['ticketFile'] = substr($options['ticketFile'],0, 100) . '…';
                                unset($ticketFile, $ticketFiles);
                            }

                            _outputListCode($libraryMethod, $displayOptions);
                            $results = _doCreateById($client, $libraryMethod, $options['orderId'], $options);
                            break;

                    }


                    echo '</pre>' . PHP_EOL; // Close up the echoing of the code used

                    // Display the results
                    if ($results) {
                        echo _getRequest($client, $libraryMethod, false);
                        echo _getResponse($client, $libraryMethod, false);

                        echo '<h2>Results of ' . $libraryMethod . '() method</h2>' . PHP_EOL;
                        if ($results instanceof Countable) {
                            echo '<p>There are ' . count($results) . ' results available.</p>' . PHP_EOL;
                            foreach ($results as $result) {
                                echo '<pre>' . print_r($result, true) . '</pre><br />' . PHP_EOL;
                            }
                        } else {
                            echo '<pre>' . print_r($results, true) . '</pre><br />' . PHP_EOL;
                        }

                        echo '<h2>print_r() of ' . get_class($results) . ' result object</h2>' . PHP_EOL
                           . '<p>This shows all the public and protected properties of the full '
                           . '<strong>' . get_class($results) . '</strong> object that is returned from the '
                           . '<strong>' . $libraryMethod . '()</strong> call. Each method will return different '
                           . 'types of objects depending on what the data returned is.</p>'

                           . '<pre>' . print_r($results, true) . '</pre>' . PHP_EOL;
                    } else {
                        echo '</pre>' . PHP_EOL
                           . '<h1>Exception thrown trying to perform API request</h1>' . PHP_EOL
                           . _getRequest($client, $libraryMethod, true)
                           . _getResponse($client, $libraryMethod, true);
                    }
                }
		    ?>
		    <form action="index.php" id="api-demo-form" method="get" target="_top" class="form-horizontal" onsubmit="checkForm();" enctype="multipart/form-data">
		        <fieldset id="environmentAndCredentials">
                    <legend>Environment and Credentials</legend>

                    <div class="control-group">
                        <label class="control-label" for="environment">API Environment</label>
                        <div class="controls">
                            <select name="environment" id="environment" onchange="changeEnvironment();">
                                <option value="production"<?php if (@$input->environment == 'production') { echo ' selected="selected"';} ?>>Production</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="apiVersion">API Version</label>
                        <div class="controls">
                            <input class="input-mini" name="apiVersion" id="apiVersion" type="text" value="1" size="2" readonly="readonly" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Your API Token</label>
                        <div class="controls">
                            <input class="apiToken input-xxlarge sandbox" name="apiToken" type="text" value="<?php if (!empty($_SESSION['sandbox']['apiToken'])) {echo $_SESSION['sandbox']['apiToken'];} ?>" />
                            <input class="apiToken input-xxlarge production" name="apiToken" type="text" value="<?php if (!empty($_SESSION['production']['apiToken'])) {echo $_SESSION['production']['apiToken'];} ?>" />
                        </div>
                    </div>

                </fieldset>

		        <fieldset>
                    <legend>PHP Client Library Method</legend>

                    <div class="control-group">
                        <label class="control-label" for="libraryMethod">Method</label>
                        <div class="controls">
                            <select id="libraryMethod" name="libraryMethod" size="1" onchange="toggleOptions();">
                                <option label="Select a method…" value="">Select a method…</option>

                                <optgroup label="Orders Methods">
                                    <option label="listOrders()" value="listOrders">listOrders()</option>
                                    <option label="listOrdersCompleted()" value="listOrdersCompleted">listOrdersCompleted()</option>
                                    <option label="showOrder()" value="showOrder">showOrder()</option>
                                    <option label="acceptOrder()" value="acceptOrder">acceptOrder()</option>
                                    <option label="shipOrder()" value="shipOrder">shipOrder()</option>
                                    <option label="rejectOrder()" value="rejectOrder">rejectOrder()</option>
                                </optgroup>

                                <optgroup label="Airbill Methods">
                                    <option label="getAirbill()" value="getAirbill">getAirbill()</option>
                                    <option label="downloadAirbill()" value="downloadAirbill">downloadAirbill()</option>
                                </optgroup>

                                <optgroup label="Purchase Order Methods">
                                    <option label="getPurchaseOrder()" value="getPurchaseOrder">getPurchaseOrder()</option>
                                    <option label="downloadPurchaseOrder()" value="downloadPurchaseOrder">downloadPurchaseOrder()</option>
                                </optgroup>

                            </select>
                        </div>
                    </div>
		        </fieldset>

		        <fieldset id="methodInput" class="options">
                    <legend>Method Specific Parameters</legend>

                    <div class="control-group listOrders">
                        <label class="control-label" for="status"><code>status</code></label>
                        <div class="controls">
                            <select name="status" id="status">
                                <option value="UNCONFIRMED">UNCONFIRMED</option>
                                <option value="PENDING_SHIPMENT">PENDING_SHIPMENT</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group listOrdersCompleted">
                        <label class="control-label" for="startDate"><code>startDate</code></label>
                        <div class="controls">
                            <input name="startDate" id="startDate" class="" type="text" value="" placeholder="<?php echo date('Y-m-d');?>" />
                        </div>
                    </div>

                    <div class="control-group listOrdersCompleted">
                        <label class="control-label" for="endDate"><code>endDate</code></label>
                        <div class="controls">
                            <input name="endDate" id="endDate" class="" type="text" value="" placeholder="<?php echo date('Y-m-d');?>" />
                        </div>
                    </div>

                    <div class="control-group showOrder acceptOrder shipOrder rejectOrder getAirbill downloadAirbill getPurchaseOrder downloadPurchaseOrder">
                        <label class="control-label" for="orderId"><code>orderId</code></label>
                        <div class="controls">
                            <input name="orderId" id="orderId" class="" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group acceptOrder shipOrder rejectOrder getAirbill downloadAirbill getPurchaseOrder downloadPurchaseOrder">
                        <label class="control-label" for="orderToken"><code>orderToken</code></label>
                        <div class="controls">
                            <input name="orderToken" id="orderToken" class="input-xxlarge" type="text" value="" />
                        </div>
                    </div>

                    <div class="control-group acceptOrder">
                        <label class="control-label" for="seatNumbers"><code>seatNumbers</code></label>
                        <div class="controls">
                            <input name="seatNumbers" id="seatNumbers" class="" type="text" value="" placeholder="1-4" />
                        </div>
                    </div>

                    <div class="control-group acceptOrder">
                        <label class="control-label" for="shipNow"><code>shipNow</code></label>
                        <div class="controls">
                            <select name="shipNow" id="shipNow">
                                <option value="true">true</option>
                                <option value="false">false</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group acceptOrder">
                        <label class="control-label" for="estimatedShipDate"><code>estimatedShipDate</code></label>
                        <div class="controls">
                            <input name="estimatedShipDate" id="estimatedShipDate" class="" type="date" value="" placeholder="<?php echo date('Y-m-d');?>" />
                        </div>
                    </div>

                    <div class="control-group acceptOrder shipOrder">
                        <label class="control-label" for="ticketFile"><code>ticketFile</code>(s)</label>
                        <div class="controls">
                            <input name="ticketFile" id="ticketFile" type="file" multiple="multiple" />
                        </div>
                    </div>

                    <div class="control-group shipOrder getAirbill downloadAirbill getPurchaseOrder downloadPurchaseOrder">
                        <label class="control-label" for="purchaseOrderId"><code>purchaseOrderId</code></label>
                        <div class="controls">
                            <input name="purchaseOrderId" id="purchaseOrderId" class="" type="text" value="" />
                        </div>
                    </div>

		        </fieldset>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-large disabled">Submit</button>
                </div>

		    </form>
		</div>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>
        <script src="/js/demo.js"></script>

    </body>
</html>

<?php

/**
 * Utility function for putting the submitted options into an array
 *
 * @param Zend_Filter_Input $input
 * @return array
 */
function _getOptions($input)
{
    $options = array();

    $dateFields = array(
        'updated_at',
        'occurs_at',
        'deleted_at',
        'created_at',
    );

    /**
     * We get the unknown $input variables because we
     * don't want to have to specify each one in the
     * $validators for this demo.
     */
    $unknown = $input->getUnknown();
    foreach ($unknown as $key => $value) {
        if ($value !== '' && stristr($key, '_operator') === false) {
//        if (stristr($key, '_operator') === false) {
            if (in_array($key, $dateFields)) {
                $operatorKey = $key . '_operator';
                $options[$key . '.' . $unknown[$operatorKey]] = $value;
            } else {
                $options[$key] = $value;
            }
        }
    }

    return $options;
}


/**
 * Utility function for outputting PHP code for demo purposes
 *
 * @param string $libraryMethod
 * @param int $showId
 */
function _outputShowCode($libraryMethod, $showId)
{
    echo '$results = $client->' . $libraryMethod . '(' . $showId . ');' . PHP_EOL;
}


/**
 * Utility function for outputting PHP code for demo purposes
 *
 * @param string $libraryMethod
 * @param int $itemId
 * @param int $showId
 */
function _outputShowByIdCode($libraryMethod, $itemId, $showId)
{
    echo '$results = $client->' . $libraryMethod . '(' . $itemId . ', ' . $showId . ');' . PHP_EOL;
}


/**
 * Utility function for outputting PHP code for demo purposes
 *
 * @param array $options
 */
function _outputOptionsCode($options)
{
    echo '$options = array(' . PHP_EOL;
    foreach( $options as $key => $val) {
        if (!is_array($val) && !is_object($val) && is_numeric($val)) {
            echo '    \'' . $key . '\' => ' . $val . ',' . PHP_EOL;
        } elseif (!is_array($val) && !is_object($val) && !is_numeric($val)) {
            echo '    \'' . $key . '\' => \'' . $val . '\',' . PHP_EOL;
//         } elseif (is_array($val)) {
//             echo '    \'' . $key . '\' => \'' . implode(',', $val) . '\',' . PHP_EOL;
        }
    }
    echo ');' . PHP_EOL . PHP_EOL
    ;
}


/**
 * Utility function for outputting PHP code for demo purposes
 *
 * @param string $libraryMethod
 * @param array $options
 */
function _outputListCode($libraryMethod, $options)
{
    _outputOptionsCode($options);

    echo '$results = $client->' . $libraryMethod . '($options);' . PHP_EOL;
}


/**
 * Utility function for outputting PHP code for demo purposes
 *
 * @param string $libraryMethod
 * @param int $listId
 * @param array $options
 */
function _outputListByIdCode($libraryMethod, $listId, $options)
{
    echo '$options = array(' . PHP_EOL;
    foreach( $options as $key => $val) {
        echo '    \'' . $key . '\' => ' . $val . ',' . PHP_EOL;
    }
    echo ');' . PHP_EOL
       . PHP_EOL
       . '$results = $client->' . $libraryMethod . '(' . $listId . ', $options);' . PHP_EOL
    ;
}


/**
 * Utility function for outputting PHP code for demo purposes
 *
 * @param string $libraryMethod
 * @param string $queryString
 * @param array $options
 */
function _outputSearchCode($libraryMethod, $queryString, $options)
{
    _outputOptionsCode($options);

    echo '$results = $client->' . $libraryMethod . '(\'' . $queryString . '\', $options);' . PHP_EOL;
}


/**
 * Utility function for performing show*() calls
 *
 * @param \Razorgator\Client $client
 * @param string $libraryMethod
 * @param int $showId
 * @return stdClass
 */
function _doShow(\Razorgator\Client $client, $libraryMethod, $showId)
{
    // Execute the call
    try {
        $results = $client->$libraryMethod($showId);
    } catch (Exception $e) {
        echo '</pre>' . PHP_EOL
           . '<h1>Exception thrown trying to perform API request</h1>' . PHP_EOL
           . _getRequest($client, $libraryMethod, true)
           . _getResponse($client, $libraryMethod, true);
        exit (1);
    }

    return $results;
}


/**
 * Utility function for performing show*() calls
 *
 * @param \Razorgator\Client $client
 * @param string $libraryMethod
 * @param int $itemId
 * @param int $showId
 * @return stdClass
 */
function _doShowById(\Razorgator\Client $client, $libraryMethod, $itemId, $showId)
{
    // Execute the call
    try {
        $results = $client->$libraryMethod($itemId, $showId);
    } catch (Exception $e) {
        echo '</pre>' . PHP_EOL
           . '<h1>Exception thrown trying to perform API request</h1>' . PHP_EOL
           . _getRequest($client, $libraryMethod, true)
           . _getResponse($client, $libraryMethod, true);
        exit (1);
    }

    return $results;
}


/**
 * Utility function for performing list*() calls
 *
 * @param \Razorgator\Client $client
 * @param string $libraryMethod
 * @param array $options
 * @return stdClass
 */
function _doList(\Razorgator\Client $client, $libraryMethod, array $options)
{
    // Execute the call
    try {
        $results = $client->$libraryMethod($options);
        return $results;
    } catch (Exception $e) {
        return false;
    }
}


/**
 * Utility function for performing list*() calls
 *
 * @param \Razorgator\Client $client
 * @param string $libraryMethod
 * @param int $listId
 * @param array $options
 * @return stdClass
 */
function _doListById(\Razorgator\Client $client, $libraryMethod, $listId, array $options)
{
    // Execute the call
    try {
        $results = $client->$libraryMethod($listId, $options);
        return $results;
    } catch (Exception $e) {
        return false;
    }
}


/**
 * Utility function for performing search*() calls
 *
 * @param \Razorgator\Client $client
 * @param string $libraryMethod
 * @param string $queryString
 * @param array $options
 * @return stdClass
 */
function _doSearch(\Razorgator\Client $client, $libraryMethod, $queryString, $options)
{
    // Execute the call
    try {
        $results = $client->$libraryMethod($queryString, $options);
    } catch (Exception $e) {
        return false;
    }

    return $results;
}


/**
 * Utility function for performing create*() calls
 *
 * @param \Razorgator\Client $client
 * @param string $libraryMethod
 * @param stdClass $properties
 * @return stdClass
 */
function _doCreate(\Razorgator\Client $client, $libraryMethod, $properties)
{
    // Execute the call
    try {
        $results = $client->$libraryMethod($properties);
    } catch (Exception $e) {
        return false;
    }

    return $results;
}


/**
 * Utility function for performing create*() calls
 *
 * @param \Razorgator\Client $client
 * @param string $libraryMethod
 * @param int $itemId
 * @param array $properties
 * @return stdClass
 */
function _doCreateById(\Razorgator\Client $client, $libraryMethod, $itemId, array $properties)
{
    // Execute the call
    try {
        $results = $client->$libraryMethod($itemId, $properties);
    } catch (Exception $e) {
        return false;
    }

    return $results;
}


/**
 * Utility function for performing update*() calls
 *
 * @param \Razorgator\Client $client
 * @param string $libraryMethod
 * @param int $updateId
 * @param stdClass $properties
 * @return stdClass
 */
function _doUpdate(\Razorgator\Client $client, $libraryMethod, $updateId, $properties)
{
    // Execute the call
    try {
        $results = $client->$libraryMethod($updateId, $properties);
    } catch (Exception $e) {
        return false;
    }

    return $results;
}


/**
 * Utility function for performing update*() calls
 *
 * @param \Razorgator\Client $client
 * @param string $libraryMethod
 * @param int $itemId
 * @param int $updateId
 * @param array $properties
 * @return stdClass
 */
function _doUpdateById(\Razorgator\Client $client, $libraryMethod, $itemId, $updateId, $properties)
{
    // Execute the call
    try {
        $results = $client->$libraryMethod($itemId, $updateId, $properties);
    } catch (Exception $e) {
        return false;
    }

    return $results;
}


/**
 * Utility function for performing update*() calls
 *
 * @param \Razorgator\Client $client
 * @param string $libraryMethod
 * @param mixed $param1
 * @param mixed $param2
 * @param mixed $param3
 * @return stdClass
 */
function _doOther(\Razorgator\Client $client, $libraryMethod, $param1, $param2=null, $param3=null)
{
    // Execute the call
    try {
        if (!is_null($param3)) {
            $results = $client->$libraryMethod($param1, $param2, $param3);
        } elseif (!is_null($param2)) {
            $results = $client->$libraryMethod($param1, $param2);
        } else {
            $results = $client->$libraryMethod($param1);
        }
    } catch (Exception $e) {
        return false;
    }

    return $results;
}


/**
 * Utility function for returning formatted API request info
 *
 * @param \Razorgator\Client $client
 * @param string $libraryMethod
 * @param bool $isException
 * @return string
 */
function _getRequest($client, $libraryMethod, $isException=true)
{
    $class = ($isException) ? 'class="exception" ' : '';
    $html = '<h2>Actual request for ' . $libraryMethod . '() method</h2>' . PHP_EOL
          . '<pre ' . $class . '>' . PHP_EOL
          . print_r ($client->getRestClient()->getHttpClient()->getLastRequest(), true)
          . '</pre><br />' . PHP_EOL;

    return $html;
}


/**
 * Utility function for returning formatted API response info
 *
 * @param \Razorgator\Client $client
 * @param string $libraryMethod
 * @param bool $isException
 * @return string
 */
function _getResponse($client, $libraryMethod, $isException=true)
{
    $class = ($isException) ? 'class="exception" ' : '';
    $html = '<h2>Actual response for ' . $libraryMethod . '() method</h2>' . PHP_EOL
          . '<pre ' . $class . '>' . PHP_EOL
          . print_r ($client->getRestClient()->getHttpClient()->getLastResponse(), true)
          . '</pre><br />' . PHP_EOL;

    return $html;
}
