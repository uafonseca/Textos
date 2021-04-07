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
            new TwigFunction('youtube_embed', [$this, 'youtube_embed']),
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
     * @param $string
     * @param int $width
     * @param float $height
     * @return string|string[]|null
     */
    public function youtube_embed($string, $width = 400, $height = 152.5)
    {
        return preg_replace(
            "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
            '<iframe class="responsive-iframe" src="//www.youtube.com/embed/$2" width="100%" height="100%"  allowfullscreen></iframe>',
            $string
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