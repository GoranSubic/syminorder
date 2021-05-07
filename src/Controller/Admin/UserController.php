<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; // @dth: to encode the pass
use Endroid\QrCode\Builder\BuilderInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * @Route("/admin/users")
 */
class UserController extends AbstractController
{
    private $passwordEncoder;
    private $qrCode;
    private $urlGenerator;
    private Environment $twig;
    private Pdf $pdf;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        BuilderInterface $customQrCodeBuilder,
        UrlGeneratorInterface $urlGenerator,
        Environment $twig
//        Pdf $pdf
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->qrCode = $customQrCodeBuilder;
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
        $realpath = realpath(\h4cc\WKHTMLToPDF\WKHTMLToPDF::PATH);
        $this->pdf = new Pdf($realpath);
    }

    /**
     * @Route("/show-qr", name="user_show_qr", methods={"GET"})
     */
    public function showQr(UserRepository $userRepository): Response
    {
        $tableUsers = $userRepository->findBy(['isTable' => true], ['username' => 'Asc'], $limit = 10);

        $qrTables = array();
        $i = 0;
        foreach ($tableUsers as $table) {
            $qrTables[$i]['firstName'] = $table->getFirstName();
            $qrTables[$i]['lastName'] = $table->getLastName();
            $qrTables[$i]['qrCode'] = $this->qrCode
                ->data(
                    $this->urlGenerator->generate(
                        'app_login_get',
                        ['tableUserName' => $table->getUsername()],
                        UrlGeneratorInterface::ABSOLUTE_URL)
                )
                ->size(200)
                ->margin(20)
                ->build()
                ->getDataUri()
            ;
            $i++;
        }

        return $this->render('user/qr_codes.html.twig', [
            'users' => count($tableUsers) ? $tableUsers : null,
            'qrTables' => $qrTables,
        ]);
    }

    /**
     * @Route("/print-action", name="user_print_qr_action", methods={"GET"})
     */
    public function printQrAction(UserRepository $userRepository)
    {
        $tableUsers = $userRepository->findBy(['isTable' => true], ['username' => 'Asc'], $limit = 10);

        $qrTables = array();
        $i = 0;
        foreach ($tableUsers as $table) {
            $qrTables[$i]['firstName'] = $table->getFirstName();
            $qrTables[$i]['lastName'] = $table->getLastName();
            $qrTables[$i]['qrCode'] = $this->qrCode
                ->data(
                    $this->urlGenerator->generate(
                        'app_login_get',
                        ['tableUserName' => $table->getUsername()],
                        UrlGeneratorInterface::ABSOLUTE_URL)
                )
                ->size(300)
                ->margin(10)
                ->build()
                ->getDataUri()
            ;
            $i++;
        }

        $html = $this->twig->render('user/qr_codes_print.html.twig', [
            'qrTables' => $qrTables,
        ]);

        return new Response(
            $this->pdf->getOutputFromHtml($html, array(
                'orientation' => 'portrait',
                'enable-javascript' => true,
                'javascript-delay' => 1000,
                'no-stop-slow-scripts' => true,
                'no-background' => false,
                'lowquality' => false,
                'encoding' => 'utf-8',
                'images' => true,
                'cookie' => array(),
                'dpi' => 300,
                'image-dpi' => 300,
                'enable-external-links' => true,
                'enable-internal-links' => true
            )),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="qrCodeTables.pdf"',
            ]
        );
    }

    /**
     * @Route("/", name="user_table_qrcode", methods={"GET"})
     */
    public function setQrPass(Request $request, UserRepository $userRepository)
    {
        //ToDo
    }

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $table = $userRepository->findBy(['isTable' => true], ['username' => 'Asc']);
        $error = $request->query->get('error');

        return $this->render('user/index.html.twig', [
            'users' => count($table) ? $table : null,
            'error' => isset($error) ? $error : null,
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $numTblUsers = $userRepository->findBy(['isTable' => true]);
        if(count($numTblUsers) >= 10) {
            $error = "Already has max defined tables!";
            return $this->redirectToRoute('user_index', [
                'error' => $error,
            ]);
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setIsTable(true);

            $plainpwd = $user->getPassword();
            $encoded = $this->passwordEncoder->encodePassword($user, $plainpwd);
            $user->setPassword($encoded);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $plainpwd = $user->getPassword();
            $encoded = $this->passwordEncoder->encodePassword($user, $plainpwd);
            $user->setPassword($encoded);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
