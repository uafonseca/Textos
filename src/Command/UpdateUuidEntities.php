<?php
	/**
	 * Created by PhpStorm.
	 * User: Ubel
	 * Date: 15/02/2021
	 * Time: 6:57 PM
	 */
	
	namespace App\Command;
	
	
	use App\Entity\User;
	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Bridge\Doctrine\IdGenerator\UuidV1Generator;
	use Symfony\Component\Console\Command\Command;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Input\InputOption;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Console\Style\SymfonyStyle;
	use Symfony\Component\Finder\Finder;
	
	class UpdateUuidEntities extends Command
	{
		/**
		 * @var EntityManagerInterface
		 */
		private $entityManager;
		
		private $finder;
		
		private $uuid;
		
		/**
		 * UpdateCedulasCommand constructor.
		 *
		 * @param EntityManagerInterface $entityManager
		 * @param UuidGenerator $uuid
		 */
		public function __construct(EntityManagerInterface $entityManager, UuidV1Generator $uuid)
		{
			$this->finder = new Finder();
			$this->entityManager = $entityManager;
			$this->uuid = $uuid;
			
			parent::__construct();
		}
		
		protected function configure()
		{
			$this
				->setName('update:uuid')
				->addOption('target', 't', InputOption::VALUE_REQUIRED, 'Declare la entidad a actualizar')
				->setDescription('Comando para generar UUID para la entidad seleccionada');
		}
		
		protected function execute(InputInterface $input, OutputInterface $output)
		{
			$io = new SymfonyStyle($input, $output);
			
			$clases = $this->entityManager->getRepository(User::class)->findAll();
			if (null === $input->getOption('target')) {
				$forAll = $io->confirm('No ha establecido una entidad, desea generar uuid para todas las entidades, use el parametro target');
				if ($forAll) {
					$this->generateUuidForEntities($io, $input, true);
				}
			} else {
				$this->generateUuidForEntities($io, $input);
			}
			
			return 0;
		}
		
		private function generateUuidForEntities(SymfonyStyle $io, InputInterface $input, $all = false)
		{
			$class = str_replace('/', '\\', $input->getOption('target'));
			$allEntities = $all ?
				$this->entityManager->getConfiguration()->getMetadataDriverImpl()->getAllClassNames() :
				[$class];
			
			$io->createProgressBar();
			$io->progressStart(count($allEntities));
			
			foreach ($allEntities as $entity) {
				$algo = explode('\\', $entity)[0];
				if ('App' === $algo) {
					$all = $this->entityManager->getRepository($entity)->findAll();
					/** @var Entity $item */
					foreach ($all as $item) {
						if (method_exists($entity, 'getUuid') && empty($item->getUuid())) {
							$uuid = $this->uuid->generate($this->entityManager, $item);
							$item->setUuid($uuid);
							$this->entityManager->persist($item);
						}
					}
				}
				$io->progressAdvance();
			}
			$this->entityManager->flush();
			$io->progressFinish();
			$io->success('El proceso se ha ejecutado correctamente');
		}
	}