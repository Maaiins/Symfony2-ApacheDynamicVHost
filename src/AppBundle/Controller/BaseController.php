<?php
/**
 * BaseController Controller Class
 *
 * This file is part of the Symfony-ApacheDynamicVHost project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @category   Controller
 * @package    ApacheDynamicVHost
 * @author     Lauser, Nicolai <nicolai@lauser.info>
 * @copyright  2015 Lauser, Nicolai
 * @version    $ID$
 */

namespace AppBundle\Controller;

use AppBundle\Console\ApacheCommands;
use AppBundle\Entity\VHost;
use AppBundle\FileSystem\ApacheVHostConfig;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseController
 *
 * @category   Controller
 * @package    ApacheDynamicVHost
 * @author     Lauser, Nicolai <nicolai@lauser.info>
 * @copyright  2015 Lauser, Nicolai
 * @version    $ID$
 */
class BaseController extends Controller
{
    /**
     * @Route(path="/", name="vhost_home")
     * @Template(":default:index.html.twig")
     *
     * @param Request $request
     * @return array
     */
    public function showVHostAction(Request $request)
    {
        $vHost = new VHost();

        $form = $this->createFormBuilder($vHost)
            ->add('prefix', 'text', array('required' => false))
            ->add('project', 'text', array('required' => true))
            ->add('domain', 'text', array('required' => false))
            ->add('symlinks', 'checkbox', array(
                    'label' => 'Use Symlinks?',
                    'required' => false
                )
            )
            ->add('development', 'checkbox', array(
                    'label' => 'Development Environment?',
                    'required' => false
                )
            )
            ->add('save', 'submit', array('label' => 'Create VHost'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $vHost = $form->getData();

            $results = $this->getVHost(array(
                'prefix' => $vHost->getPrefix(),
                'project' => $vHost->getProject(),
                'domain' => $vHost->getDomain()
            ));

            if (0 == count($results)) {
                $this->newConfig($vHost);

                ApacheCommands::restart();

                $em = $this->getDoctrine()->getManager();
                $em->persist($vHost);
                $em->flush();
            }
        }

        return array(
            'form' => $form->createView(),
            'vHosts' => $this->getVHosts()
        );
    }

    /**
     * @Route(path="/remove/{id}", requirements={"id": "\d+"}, name="vhost_remove")
     * @ParamConverter("vHost", class="AppBundle:VHost", options={"id" = "id"})
     *
     * @param $vHost
     * @return Response
     */
    public function removeVHostAction(VHost $vHost)
    {
        $rootdir = $this->get('kernel')->getRootDir();

        $configHandler = new ApacheVHostConfig();
        $configHandler->removeConfig($vHost, $rootdir);

        ApacheCommands::restart();

        $em = $this->getDoctrine()->getManager();
        $em->remove($vHost);
        $em->flush();

        return $this->redirect($this->generateUrl('vhost_home'));
    }

    /**
     * @Route(path="/create/{id}", requirements={"id": "\d+"}, name="vhost_create")
     * @ParamConverter("vHost", class="AppBundle:VHost", options={"id" = "id"})
     *
     * @param $vHost
     * @return Response
     */
    public function createVHostAction(VHost $vHost)
    {
        $this->newConfig($vHost);

        ApacheCommands::restart();

        return $this->redirect($this->generateUrl('vhost_home'));
    }

    /**
     * @Route(path="/show/{id}", requirements={"id": "\d+"}, name="vhost_show")
     * @ParamConverter("vHost", class="AppBundle:VHost", options={"id" = "id"})
     *
     * @param VHost $vHost
     * @return Response
     */
    public function showVHostConfigAction(VHost $vHost)
    {
        $content = $this->renderView(
            ':config:vhost.conf.twig',
            array(
                'vHost' => $vHost
            )
        );

        $response = new Response();

        $response->setContent($content);
        $response->setStatusCode(Response::HTTP_OK);
        $response->headers->set('Content-Type', 'text');

        return $response;
    }

    /**
     * @return \AppBundle\Entity\VHost[]|array
     */
    private function getVHosts()
    {
        $em = $this->getDoctrine()->getManager();
        $vHosts = $em->getRepository('AppBundle:VHost')->findAll();

        return $vHosts;
    }

    /**
     * @param $data
     * @return \AppBundle\Entity\VHost[]|array
     */
    private function getVHost($data)
    {
        $em = $this->getDoctrine()->getManager();
        $vHost = $em->getRepository('AppBundle:VHost')->findBy($data);

        return $vHost;
    }

    /**
     * @param $vHost
     */
    private function newConfig($vHost)
    {
        $content = $this->renderView(
            ':config:vhost.conf.twig',
            array(
                'vHost' => $vHost
            )
        );

        $rootdir = $this->get('kernel')->getRootDir();

        $configHandler = new ApacheVHostConfig();
        $configHandler->writeConfig($vHost, $content, $rootdir);
    }
}