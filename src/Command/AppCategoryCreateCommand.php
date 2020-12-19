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
        $home->setDescription('Home Description commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.');

        // level 1: Drinks, Food
        $food = new Category();
        $food->setName('Food');
        $food->setDescription('Food Description commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.');
        $food->setParent($home);

        $drinks = new Category();
        $drinks->setName('Drinks');
        $drinks->setDescription('Drinks Description commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.');
        $drinks->setParent($home);

        $this->entityManager->persist($home);
        $this->entityManager->persist($food);
        $this->entityManager->persist($drinks);

        $this->entityManager->flush();

        // demonstrate using repository functions

        $water = new Category();
        $water->setName('Water');
        $water->setDescription('Water Description commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.');
        $repository->persistAsLastChildOf($water, $drinks);

        $springWater = new Category();
        $springWater->setName('Spring Water');
        $springWater->setDescription('Spring Water Description commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.');
        $repository->persistAsLastChildOf($springWater, $water);

        $regularWater = new Category();
        $regularWater->setName('Regular Water');
        $regularWater->setDescription('Regular Water Description commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.');
        $repository->persistAsNextSiblingOf($regularWater, $springWater);

        $coffee = new Category();
        $coffee->setName('Coffee');
        $coffee->setDescription('Coffee Description commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.');
        $repository->persistAsNextSiblingOf($coffee, $water);

        // children of Wheels & Tyres

        $breakfast = new Category();
        $breakfast->setName('Breakfast');
        $breakfast->setDescription('Breakfast Description commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.');
        $repository->persistAsLastChildOf($breakfast, $food);

        $mainMeal = new Category();
        $mainMeal->setName('Main meal');
        $mainMeal->setDescription('Main meal Description commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.');
        $repository->persistAsNextSiblingOf($mainMeal, $breakfast);

        $this->entityManager->flush();

//        $io->success("Done");
        return Command::SUCCESS;
    }
}
