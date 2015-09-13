<?php
/**
 * ApacheCommands ConsoleClass
 *
 * This file is part of the Symfony-ApacheDynamicVHost project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   Console
 * @package    ApacheDynamicVHost
 * @author     Lauser, Nicolai <nicolai@lauser.info>
 * @copyright  2015 Lauser, Nicolai
 * @version    $ID$
 */

namespace AppBundle\Console;

/**
 * Class ApacheCommands
 *
 * @category   Console
 * @package    ApacheDynamicVHost
 * @author     Lauser, Nicolai <nicolai@lauser.info>
 * @copyright  2015 Lauser, Nicolai
 * @version    $ID$
 */
class ApacheCommands
{
    public static function restart()
    {
        exec('/sbin/service apache2 restart');
    }
}