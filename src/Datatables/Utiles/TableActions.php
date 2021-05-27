<?php
	/**
	 * Created by PhpStorm.
	 * User: Ubel
	 * Date: 17/02/2021
	 * Time: 6:45 PM
	 */
	
	namespace App\Datatables\Utiles;

    /**
     * Class TableActions
     * @package App\Datatables\Utiles
     */
	class TableActions
	{

        /**
         * @param array $columns
         * @return array
         */
        public static function buttonPRINT(array $columns = []): array
        {
            return [
                'extend' => 'print',
                'text' => 'Imprimir',
                'class_name' => 'btn-primary fa fa-print mr-5 my-4 btn',
                'button_options' => [
                    'exportOptions' => [
                        'columns' => $columns,
                    ],
                ],
            ];
        }
		/**
		 * @param $route
		 * @param $icon
		 * @param $class
		 * @param $title
		 * @param array $routeParams
		 * @param string $color
		 * @return array
		 */
		public static function default($route, $icon, $class, $title, $routeParams = [], $color = "")
		{
			return [
				'route' => $route,
				'route_parameters' => array_merge(array(
					'uuid' => 'uuid'
				), $routeParams),
				'icon' => 'fa ' . $icon . ' cortex-table-action-icon',
				'attributes' => [
					'class' => $class,
					'style' => "color: " . $color . ";",
					'data-tippy-content' => $title
				]
			];
		}
		
		/**
		 * @param $route
		 * @return array
		 */
		public static function show($route): array
		{
			return self::default($route, 'fa-eye text-success', 'action-show', 'Mostrar');
		}
		
		/**
		 * @param $route
		 * @return array
		 */
		public static function add($route): array
		{
			return self::default($route, 'fa-plus text-success', 'action-add', 'Adicionar');
		}
		
		/**
		 * @param $route
		 * @return array
		 */
		public static function edit($route): array
		{
			return self::default($route, 'fa-edit text-info', 'action-edit', 'Editar');
		}
		
		/**
		 * @param $route
		 * @return array
		 */
		public static function delete($route): array
		{
			return self::default($route, 'fa-trash text-danger', 'confirm action-delete', 'Eliminar');
		}
		
		/**
		 * @param $route
		 * @return array
		 */
		public static function export($route)
		{
			return self::default($route, 'fa-print text-warning', 'action-export', 'Exportar');
		}

		/**
		 * @param $route
		 * @return array
		 */
		public static function mailSend($route)
		{
			return self::default($route, 'fa-paper-plane text-info', 'sendMail', 'Enviar mensaje');
		}
	}