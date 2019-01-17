<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class TemplateController extends Controller
{
    /**
     * @Route("/{template}")
     */
    public function indexAction(Request $request, Packages $packages, $template)
    {
        try {
            $htmlName = $template.'.html';
            $path = $this->container->getParameter('twig.default_path');
            if (file_exists($path.'/'.$htmlName)) {
                $content = file_get_contents($path.'/'.$htmlName);
            } else {
                $content = $this->renderView($htmlName.'.twig');
            }
            $responsive = !$request->query->has('noresponsive');

            if ($request->query->has('inline')) {
                $inliner = new CssToInlineStyles();

                if (!filter_var($uri = $packages->getUrl('build/css/'.$template.'.css'), FILTER_VALIDATE_URL)) {
                    $uri = $request->getUriForPath($uri);
                }

                $style = file_get_contents($uri);
                $content = $inliner->convert($content, $style);

                if ($responsive) {
                    if (!filter_var($uri = $packages->getUrl('build/css/'.$template.'.r.css'), FILTER_VALIDATE_URL)) {
                        $uri = $request->getUriForPath($uri);
                    }

                    if ($style = trim(@file_get_contents($uri))) {
                        if (false === $pos = strripos($content, '<body')) {
                            $pos = strlen($content);
                        } else {
                            $pos = strpos($content, '>', $pos)+1;
                        }

                        $styleTag = sprintf("\n<style>\n%s\n</style>", $style);
                        $content = substr($content, 0, $pos).$styleTag.substr($content, $pos);
                    }
                }
            } else {
                if (false === $pos = strripos($content, '</head>')) {
                    $pos = 0;
                }

                $styleTag = sprintf('<link rel="stylesheet" href="%s"/>', $packages->getUrl('build/css/'.$template.'.css'))."\n";
                if ($responsive) {
                    $styleTag .= sprintf('<link rel="stylesheet" href="%s"/>', $packages->getUrl('build/css/'.$template.'.r.css'))."\n";
                }
                $scriptTag = sprintf('<script src="%s"></script>', $packages->getUrl('build/js/dev.js'))."\n";
                $content = substr($content, 0, $pos).$styleTag.$scriptTag.substr($content, $pos);
            }

        } catch (\Exception $e) {
            throw $e;
            throw new NotFoundHttpException(sprintf('Template "%s.html" not found', $template));
        }

        return new Response($content);
    }
}