<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class AppCategorySetSlugCommand
 * @package App\Command
 */
class AppCategorySetSlugCommand extends Command
{
    private $slugger;

    /**
     * @var string
     */
    protected static $defaultName = 'app:category:set-slug';

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * AppCategorySetSlugCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
    }

    protected function configure()
    {
        $this->setDescription('Set slug (if null) to categories');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $repo = $this->entityManager->getRepository(Category::class);


        $catNoSlug = $repo->findBy(['slug' => null]);
        foreach ($catNoSlug as $category) {
            $category->computeSlug($this->slugger, $this->entityManager);
            $this->entityManager->persist($category);

            $this->entityManager->flush();
        }


//        $io->success("Done");
        return Command::SUCCESS;
    }
}
