<?php

/**
 * Created by PhpStorm.
 * User: Ubel
 * Date: 17/02/2021
 * Time: 6:47 PM
 */

namespace App\Datatables\Tables;

use App\Datatables\Utiles\TableActions;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Style;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;

/**
 * Class UserDatatable
 * @package App\Datatables\Tables
 */
class NoRecivedDatatable extends AbstractDatatable
{
	public function __construct(AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $securityToken, $translator, RouterInterface $router, EntityManagerInterface $em, Environment $twig)
    {
        parent::__construct($authorizationChecker, $securityToken, $translator, $router, $em, $twig);
        $this->uniqueId = uniqid();
    }
    /**
     * @param array $options
     * @throws \Exception
     */
	public function buildDatatable(array $options = [])
	{
		$this->ajax->set([
			'url' => $options['url'],
			'method' => 'POST',
		]);

		$this->options->set([
			'classes' => Style::BOOTSTRAP_4_STYLE,
			'individual_filtering' => false,
			'order_cells_top' => true,
		]);
		$this->extensions->set(array(
			'responsive' => true,
		));
		$this->features->set([
			'processing' => true,
		]);
		$this->columnBuilder
			->add('uuid', Column::class, [
				'title' => 'uuid',
				'visible' => false,
			])
			// ->add('id', Column::class, [
			// 	'title' => 'id',
			// 	'visible' => false,
			// ])
			// ->add('tarea', Column::class, [
			// 	'title' => 'Tarea',
			// 	'dql' => '(SELECT {m}.subject FROM App:User {u} JOIN App:Mail USING({}) {m} WHERE  {id} NOT IN (SELECT {r}.User.id FROM App:MailResponse {r} WHERE {r}.mail = {m} ))',
			// 	'searchable' => true,
			// 	'orderable' => true,
			// ])
			->add('name', Column::class, [
				'title' => 'Nombre',
			])
			->add('firstName', Column::class, [
				'title' => 'Apellidos',
			])
			->add('username', Column::class, [
				'title' => 'Usuario',
			])
			->add('email', Column::class, [
				'title' => 'Correo',
			])
			;
	}

    /**
     * @return string
     */
	public function getEntity()
	{
		return User::class;
	}

    /**
     * @return string
     */
	public function getName()
	{
		return 'user-datatable';
	}
}