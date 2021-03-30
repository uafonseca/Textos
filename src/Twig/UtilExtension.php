<?php
/**
 * Created by PhpStorm.
 * Name:  Ubel Angel Fonseca Cedeño
 * Email: ubelangelfonseca@gmail.com
 * Date:  23/09/20
 * Time:  12:50
 */

namespace App\Twig;


use App\Util\FileIcons;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class UtilExtension extends AbstractExtension
{

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('getIcon', [$this, 'getIcon']),
        ];
    }

    /**
     * @return array|TwigFilter[]
     */
    public function getFilters()
    {
        return array(
            new TwigFilter('external_link', array($this, 'externalLinkFilter')),
        );
    }


    /**
     * @param $mime_type
     * @return string
     */
    public function getIcon ($mime_type) {
        return FileIcons::getIcon($mime_type);
    }

    public function externalLinkFilter($url)
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }

        return $url;
    }
}