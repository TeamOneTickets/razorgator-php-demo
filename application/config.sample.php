<?php

/**
 * Demonstration of the Razorgator PHP Client Library *
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
 * Set your Razorgator Fulfillment API information.
 *
 * To get your API security token, follow these steps:
 * 1. Log in to Pearl @ https://pearl.razorgator.com/
 * 2. Change the address to: https://pearl.razorgator.com/AboutApi.aspx
 * 3. Copy your token into a safe place, then hit the back button to go back to Pearl’s home page.
 *
 * NOTE: These are exclusive to your company and should NEVER be shared with
 *       anyone else. These should be protected just like your bank password.
 *
 */
$sandbox['apiToken']        = (string) 'YOUR_API_TOKEN_HERE';
$sandbox['usePersistentConnections'] = true;

$staging['apiToken']        = (string) 'YOUR_API_TOKEN_HERE';
$staging['usePersistentConnections'] = true;

$production['apiToken']     = (string) 'YOUR_API_TOKEN_HERE';
$production['usePersistentConnections'] = true;
