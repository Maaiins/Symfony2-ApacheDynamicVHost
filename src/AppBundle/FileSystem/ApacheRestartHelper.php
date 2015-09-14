<?php
/**
 * ApacheRestartHelper FileSystem Class
 *
 * This file is part of the ApacheDynamicVHost project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   FileSystem
 * @package    ProductStructure
 * @author     Lauser Nicolai <n.lauser@polytec.de>
 * @copyright  2015 Polytec GmbH
 * @version    $ID$
 */

namespace AppBundle\FileSystem;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ApacheRestartHelper
 *
 * @category   FileSystem
 * @package    ProductStructure
 * @author     Lauser Nicolai <n.lauser@polytec.de>
 * @copyright  2015 Polytec GmbH
 * @version    $ID$
 */
class ApacheRestartHelper extends Filesystem
{
    public function writeConfig($content = true , $rootdir = '/var/www/ApacheDynamicVHost/app/Resources/cron')
    {
        $filename = $rootdir . '/restart';

        $this->dumpFile(
            $filename,
            $content
        );
    }
}