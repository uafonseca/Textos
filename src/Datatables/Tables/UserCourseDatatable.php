<?php

/**
 * Created by PhpStorm.
 * User: Ubel
 * Date: 17/02/2021
 * Time: 6:47 PM
 */

namespace App\Datatables\Tables;

use App\Datatables\Utiles\TableActions;
use App\Entity\Book;
use App\Entity\Code;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Style;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Environment;

/**
 * Class UserCourseDatatable
 * @package App\Datatables\Tables
 */
class UserCourseDatatable extends AbstractDatatable
{
	private User $user;

	public function __construct(AuthorizationCheckerInterface $authorizationChecker, TokenStorageInterface $securityToken, $translator, RouterInterface $router, EntityManagerInterface $em, Environment $twig)
	{
		parent::__construct($authorizationChecker, $securityToken, $translator, $router, $em, $twig);
		$this->uniqueId = uniqid();
	}

	public function getLineFormatter()
	{
		return function ($row) {
			$obj = $this->getEntityManager()->getRepository('App:Code')->find($row['id']);
			$row['status'] = $obj->getStatus();
			$row['user'] = $this->user->getId();
			$row['course'] = $obj->getBook()->getId();
			return $row;
		};
	}

	/**
	 * @param array $options
	 * @throws \Exception
	 */
	public function buildDatatable(array $options = [])
	{
		$this->user = $options['user'];
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
			->add('id', Column::class, [
				'title' => 'id',
				'visible' => false,
			])
			->add('course', VirtualColumn::class, [
				'title' => 'book',
				'visible' => false,
			])
			->add('user', Column::class, [
				'title' => 'user',
				'visible' => false,
			])
			->add('book.title', Column::class, [
				'title' => 'Curso/Capacitación',
			])
			->add('starDate', DateTimeColumn::class, [
				'title' => 'Fecha de inicio',
				'date_format' => 'D-MM-yy' 
			])
			->add('endDate', DateTimeColumn::class, [
				'title' => 'Fecha fin',
				'date_format' => 'D-MM-yy',
				'default_content' => 'NA'
			])
			->add('status', VirtualColumn::class, [
				'title' => 'Estado',
			])
			->add(null, ActionColumn::class, [
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => [
                    TableActions::default('visit_index','fa-history text-warning','action-details', 'Registro',[
						'id' => 'course',
						'user' => 'user'
					]),
                ],
            ])	
			;
	}

	/**
	 * @return string
	 */
	public function getEntity()
	{
		return Code::class;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'user-course-datatable';
	}
}
