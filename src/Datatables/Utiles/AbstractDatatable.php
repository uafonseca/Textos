<?php
	/**
	 * Created by PhpStorm.
	 * User: Ubel
	 * Date: 17/02/2021
	 * Time: 6:44 PM
	 */
	
	namespace App\Datatables\Utiles;
	
	use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
	use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
	use Symfony\Component\Routing\RouterInterface;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\Translation\TranslatorInterface;
	use Twig_Environment;
	use Exception;
	
	abstract class AbstractDatatable extends \Sg\DatatablesBundle\Datatable\AbstractDatatable
	{
		/**
		 * AbstractDatatable constructor.
		 *
		 * @param AuthorizationCheckerInterface $authorizationChecker
		 * @param TokenStorageInterface $securityToken
		 * @param TranslatorInterface $translator
		 * @param RouterInterface $router
		 * @param EntityManagerInterface $em
		 * @param Twig_Environment $twig
		 *
		 * @throws Exception
		 * @throws Exception
		 */
		public function __construct(
			AuthorizationCheckerInterface $authorizationChecker,
			TokenStorageInterface $securityToken,
			TranslatorInterface $translator,
			RouterInterface $router,
			EntityManagerInterface $em,
			Twig_Environment $twig
		)
		{
			parent::__construct($authorizationChecker, $securityToken, $translator, $router, $em, $twig);
			$this->uniqueId = uniqid();
		}
	}