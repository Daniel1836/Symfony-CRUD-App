<?php

namespace App\Controller;
//namespace AppBundle\Controller;
//use AppBundle\Entity\Post;
use App\Entity\Post;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostController extends AbstractController

 {
/**
 * @Route("/post", name="view_post_route")
 */
    public function viewPostAction()
       {
          // $post = $this->getDoctrine()->getRepository("AppBundle:Post")->findAll();
           $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

           return $this->render("index.html.twig", ['posts' => $posts]);
       }
 
    public function createPostAction(Request $request)
    
       {
           $post = new Post;
           $form = $this->createFormBuilder($post)
           ->add('title', TextType::Class,array('attr' => array('class' => 'form-control')))
           ->add('description', TextareaType::Class,array('attr' => array('class' => 'form-control')))
           ->add('save', SubmitType::Class, array('label' => 'Create Post', 'attr' => array('class' => 'form-control')))
           ->getForm();
           $form->handleRequest($request);
           if($form->isSubmitted()&& $form->isValid()){
               $title = $form['title']->getData();
               $description = $form['description']->getData();

               $post->setTitle($title);
               $post->setDescription($description);

               $em = $this->getDoctrine()->getManager();
               $em->persist($post);
               $em->flush();
      
              return $this->redirectToRoute('view_post_route');
           }
            return $this->render("create.html.twig", [
                'form' => $form->createView()
            ]);
       }

       public function updatePostAction($id, Request $request)
    
       {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        $post->setTitle($post->getTitle());
        $post->setDescription($post->getDescription());

        $form = $this->createFormBuilder($post)
           ->add('title', TextType::Class,array('attr' => array('class' => 'form-control')))
           ->add('description', TextareaType::Class,array('attr' => array('class' => 'form-control')))
           ->add('save', SubmitType::Class, array('label' => 'Create Post', 'attr' => array('class' => 'form-control')))
           ->getForm();
           $form->handleRequest($request);
           if($form->isSubmitted()&& $form->isValid()){
               $title = $form['title']->getData();
               $description = $form['description']->getData();

               $em = $this->getDoctrine()->getManager();
               $post = $em->getRepository(Post::class)->find($id);

               $post->setTitle($title);
               $post->setDescription($description);
               $em->flush();
               return $this->redirectToRoute('view_post_route');
           }
            return $this->render("update.html.twig", [
                'form' => $form->createView()
            ]);
       }

       public function showPostAction($id, Request $request)
    
       {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
            return $this->render("view.html.twig", ['post' => $post]);
       }

       public function deletePostAction($id, Request $request)
    
       {
           $em = $this->getDoctrine()->getManager();
           $post = $em->getRepository(Post::class)->find($id);
           $em->remove($post);
           $em->flush();
           return $this->redirectToRoute('view_post_route');

            return $this->render("delete.html.twig");
       }
  }