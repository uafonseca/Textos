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

/**
 * Class UtilExtension
 * @package App\Twig
 */
class UtilExtension extends AbstractExtension
{

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getIcon', [$this, 'getIcon']),
        ];
    }

    /**
     * @return array|TwigFilter[]
     */
    public function getFilters(): array
    {
        return array(
            new TwigFilter('external_link', array($this, 'externalLinkFilter')),
        );
    }


    /**
     * @param $mime_type
     * @return string
     */
    public function getIcon ($mime_type): string
    {
        return FileIcons::getIcon($mime_type);
    }

    public function externalLinkFilter($url): string
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }

        return $url;
    }
}