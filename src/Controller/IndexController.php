<?php


namespace App\Controller;


use App\Form\ExtendedFilterType;
use App\Form\FilterType;
use App\Service\ApiWrapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends AbstractController
{

    private $em;
    private $apiWrapper;

    /**
     * IndexController constructor.
     * @param EntityManagerInterface $em
     * @param ApiWrapper $apiWrapper
     */
    public function __construct(EntityManagerInterface $em, ApiWrapper $apiWrapper)
    {
        $this->em = $em;
        $this->apiWrapper = $apiWrapper;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('wrapper/index.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getBooksAction(Request $request)
    {
        $form = $this->createForm(FilterType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $limit = $request->query->get('limit') !='' ? $request->query->get('limit') : 0;
            $offset = $request->query->get('offset') !='' ? $request->query->get('offset') : 0;

            $books = $this->apiWrapper->getBooks($limit, $offset);
        }else {
            $books = $this->apiWrapper->getBooks();
        }

        return $this->render('wrapper/books.html.twig', [
            'books' => $books,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAuthorsAction(Request $request)
    {
        $form = $this->createForm(FilterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $limit = $request->query->get('limit') !='' ? $request->query->get('limit') : 0;
            $offset = $request->query->get('offset') !='' ? $request->query->get('offset') : 0;
            $authors = $this->apiWrapper->getAuthors($limit, $offset);
        }else {
            $authors = $this->apiWrapper->getAuthors();
        }

        return $this->render('wrapper/authors.html.twig', [
            'authors' => $authors,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAuthorsBooksAction(Request $request)
    {
        $form = $this->createForm(ExtendedFilterType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $limit = $request->query->get('limit') !='' ? $request->query->get('limit') : 0;
            $offset = $request->query->get('offset') !='' ? $request->query->get('offset') : 0;
            $authorId = $request->query->get('authorId') !='' ? $request->query->get('authorId') : 1;

            $objects = $this->apiWrapper->getBooksByAuthors($authorId, $limit, $offset);
        }else {
            $objects = $this->apiWrapper->getBooksByAuthors(1);
        }

        return $this->render('wrapper/books.html.twig', [
            'books' => $objects,
            'form' => $form->createView(),
            'setId' => true
        ]);
    }

}