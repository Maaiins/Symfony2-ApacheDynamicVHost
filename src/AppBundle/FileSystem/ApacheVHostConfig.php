<?php
/**
 * ApacheVHostConfig FileSystem Class
 *
 * This file is part of the Symfony-ApacheDynamicVHost project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   FileSystem
 * @package    ApacheDynamicVHost
 * @author     Lauser, Nicolai <nicolai@lauser.info>
 * @copyright  2015 Lauser, Nicolai
 * @version    $ID$
 */

namespace AppBundle\FileSystem;

use AppBundle\Entity\VHost;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ApacheVHostConfig
 *
 * @category   FileSystem
 * @package    ApacheDynamicVHost
 * @author     Lauser, Nicolai <nicolai@lauser.info>
 * @copyright  2015 Lauser, Nicolai
 * @version    $ID$
 */
class ApacheVHostConfig extends Filesystem
{
    public function writeConfig(VHost $vHost, $content, $rootdir = '/var/www')
    {
        $filename = $rootdir . '/Resources/vHosts/' . $vHost->getFileName();

        $this->dumpFile(
            $filename,
            $content
        );
    }

    public function removeConfig(VHost $vHost, $rootdir = '/var/www')
    {
        $filename = $rootdir . '/Resources/vHosts/' . $vHost->getFileName();

        $this->remove(
            array(
                $filename
            )
        );
    }
}