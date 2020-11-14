<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class AppCategoryCreateCommand
 * @package App\Command
 */
class AppCategoryCreateCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:category:create';

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * AppCategoryCreateCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Create test categories');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $repository = $this->entityManager->getRepository(Category::class);

        // level 0: Home
        $home = new Category();
        $home->setName('Home');

        // level 1: Drinks, Food
        $drinks = new Category();
        $drinks->setName('Drinks');
        $drinks->setParent($home);

        $food = new Category();
        $food->setName('Food');
        $food->setParent($home);

        $this->entityManager->persist($home);
        $this->entityManager->persist($drinks);
        $this->entityManager->persist($food);

        $this->entityManager->flush();

        // demonstrate using repository functions

        $water = new Category();
        $water->setName('Water');
        $repository->persistAsLastChildOf($water, $drinks);

        $springWater = new Category();
        $springWater->setName('Spring Water');
        $repository->persistAsLastChildOf($springWater, $water);

        $regularWater = new Category();
        $regularWater->setName('Regular Water');
        $repository->persistAsNextSiblingOf($regularWater, $springWater);

        $coffee = new Category();
        $coffee->setName('Coffee');
        $repository->persistAsNextSiblingOf($coffee, $water);

        // children of Wheels & Tyres

        $breakfast = new Category();
        $breakfast->setName('Breakfast');
        $repository->persistAsLastChildOf($breakfast, $food);

        $mainMeal = new Category();
        $mainMeal->setName('Main meal');
        $repository->persistAsNextSiblingOf($mainMeal, $breakfast);

        $this->entityManager->flush();

//        $io->success("Done");
        return Command::SUCCESS;
    }
}
