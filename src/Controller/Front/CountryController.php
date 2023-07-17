<?php

namespace App\Controller\Front;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use App\Service\Entity\CountryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;


#[Route('/pays', name: 'app_country_')]
class CountryController extends AbstractController
{

    public function __construct(
        private CountryService $countryService,
        private TranslatorInterface $translator,
        private CountryRepository $countryRepository
    ) { }

    #[Route('/nouveau', name: 'new')]
    public function new(Request $request): Response {
        $form = $this->createForm(CountryType::class, new Country());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->countryService->initEmptyFields($form->getData());
            $this->addFlash(
            'success',
                $this->translator->trans('pages.country.success_create')
            );
            return $this->redirectToRoute('app_home');
        }

        return $this->render('front/pages/country/new.html.twig', [
           'form' => $form->createView(),
            'title' => $this->translator->trans('pages.country.title.new'),
        ]);
    }

    #[Route('/modifier/{slug}', name: 'edit')]
    public function edit(Request $request, string $slug): Response {
        $country = $this->countryRepository->findOneBy(['slug' => $slug]);

        if ($country === null) {
            $this->addFlash(
                'danger',
                $this->translator->trans('pages.error')
            );
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->countryService->initEmptyFields($form->getData());
            $this->addFlash(
                'success',
                $this->translator->trans('pages.country.success_edit')
            );
            return $this->redirectToRoute('app_country_edit', [
                'slug' => $country->getSlug()
            ]);
        }

        return $this->render('front/pages/country/new.html.twig', [
            'form' => $form->createView(),
            'title' => $this->translator->trans('pages.country.title.edit'),
        ]);
    }

}
