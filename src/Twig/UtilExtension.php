<?php
/**
 * Created by PhpStorm.
 * Name:  Ubel Angel Fonseca Cedeño
 * Email: ubelangelfonseca@gmail.com
 * Date:  23/09/20
 * Time:  12:50
 */

namespace App\Twig;


use App\Entity\User;
use App\Repository\CompanyRepository;
use App\Util\FileIcons;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class UtilExtension
 * @package App\Twig
 */
class UtilExtension extends AbstractExtension
{

    /** @var CompanyRepository */
    private $companyRepository;

    /** @var TokenStorageInterface */
    private $token;

    /**
     * UtilExtension constructor.
     * @param CompanyRepository $companyRepository
     * @param TokenStorage $tokenStorage
     */
    public function __construct(CompanyRepository $companyRepository, TokenStorageInterface $tokenStorage)
    {
        $this->companyRepository = $companyRepository;
        $this->token = $tokenStorage;
    }


    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getIcon', [$this, 'getIcon']),
            new TwigFunction('youtube_embed', [$this, 'youtube_embed']),
            new TwigFunction('getCompany', [$this, 'getCompany']),
            new TwigFunction('colors', [$this, 'getColors']),
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
    public function getIcon($mime_type): string
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

    /**
     * @return \App\Entity\Company|null
     */
    public function getCompany()
    {
        if ($this->getCurrentCompany())
            return $this->getCurrentCompany();

        $all = $this->companyRepository->findAll();
        return count($all) > 0 ? $all[0] : null;
    }

    public function getColors()
    {
        $defalt = [
            'primary' => '#3b7ddd',
            'secondary' => '#6c757d',
            'success' => '#28a745',
            'warning' => '#ffc107',
            'info' => '#17a2b8',
        ];
        /** @var \App\Entity\Company $company */
        if (null != $company = $this->getCurrentCompany() ) {
            if (null != $identity = $company->getIdentity()){
                try{
                    return [
                        'primary' => $identity->getColorPrimary() ? $identity->getColorPrimary() : $defalt['primary'],
                        'secondary' => $identity->getColorSecondary() ? $identity->getColorSecondary() : $defalt['secondary'],
                        'success' => $identity->getColorSuccess() ? $identity->getColorSuccess() : $defalt['success'],
                        'warning' => $identity->getColorWarning() ? $identity->getColorWarning() : $defalt['warning'],
                        'info' => $identity->getColorInfo() ? $identity->getColorInfo() : $defalt['info'],
                    ];
                }catch (\Exception $exception){
                    return $defalt;
                }
            }
        }

        return $defalt;
    }


    public function getCurrentCompany()
    {
        try{
            if ($this->token->getToken()->getUser() instanceof User)
                return $this->token->getToken()->getUser()->getCompany();
            return null;
        }catch (\Exception $exception){
            return null;
        }
    }
}