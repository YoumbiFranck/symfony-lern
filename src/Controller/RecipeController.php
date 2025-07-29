<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecipeController extends AbstractController
{

    #[Route('/recipe', name: 'recipe.index')]
    public function index(Request $request, RecipeRepository $repository): Response
    {
        //$recipes = $repository->findAll();
        $recipes = $repository->findWithLowerDurationThan(50); // récuperer les recettes dont le temps de cuisson est inférieur à 50 minutes
        //dd($recipes);
        return $this->render("recipe/index.html.twig", [
            'recipes' => $recipes
        ]);
    }

    #[Route('/recipe/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id, RecipeRepository $repository): Response
    {
        $recipe = $repository->find($id);
        //$recipe = $repository->findOneBy(['slug' => $slug]);
        //dd($recipe);
        // Petite vérification pour correctement rediriger l'utilisateur
        if($recipe->getSlug() ==! $slug){
            return $this->redirectToRoute('recipe.show', ['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
        }
        return $this->render('recipe/show.html.twig',
            [
                "recipe" => $recipe
            ]
        );
        # return new Response("Nouvelle recette ! " . "nom de la rette : " . $slug . " id de la recette: " . $id );
    }
}
