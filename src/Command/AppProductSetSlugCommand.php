<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * Class AppProductSetSlugCommand
 * @package App\Command
 */
class AppProductSetSlugCommand extends Command
{
    private $slugger;

    /**
     * @var string
     */
    protected static $defaultName = 'app:product:set-slug';

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * AppProductSetSlugCommand constructor.
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
        $this->setDescription('Set slug (if null) to products');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $repo = $this->entityManager->getRepository(Product::class);


        $prodNoSlug = $repo->findBy(['slug' => null]);
        foreach ($prodNoSlug as $product) {
            $product->computeSlug($this->slugger, $this->entityManager);
            $this->entityManager->persist($product);

            $this->entityManager->flush();
        }


//        $io->success("Done");
        return Command::SUCCESS;
    }
}
