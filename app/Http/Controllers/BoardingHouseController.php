<?php

namespace App\Http\Controllers;

use App\Interfaces\BoardingHouseRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use App\Interfaces\CityRepositoryInterface;

class BoardingHouseController extends Controller
{
    private CityRepositoryInterface $cityRepository;
    private CategoryRepositoryInterface $categoryRepository;
    private BoardingHouseRepositoryInterface $boardingHouseRepository;

    public function __construct(
        CityRepositoryInterface $cityRepository,
        CategoryRepositoryInterface $categoryRepository,
        BoardingHouseRepositoryInterface $boardingHouseRepository
    ) {
        $this->cityRepository = $cityRepository;
        $this->categoryRepository = $categoryRepository;
        $this->boardingHouseRepository = $boardingHouseRepository;
    }
    public function find()
    {
        $cities = $this->cityRepository->getAllCities();
        $categories = $this->categoryRepository->getAllCategories();

        return view('pages.boarding-house.find', compact('cities', 'categories'));
    }

    public function findResults(Request $request)
    {
        $boardingHouses = $this->boardingHouseRepository->getAllBoardingHouses($request->search, $request->city, $request->category);

        return view('pages.boarding-house.index', compact('boardingHouses'));
    }

    public function details($slug)
    {
        $boardingHouse = $this->boardingHouseRepository->getBoardingHouseBySlug($slug);
        return view('pages.boarding-house.details', compact('boardingHouse'));
    }

    public function rooms($slug)
    {
        $boardingHouse = $this->boardingHouseRepository->getBoardingHouseBySlug($slug);
        $rooms = $boardingHouse->rooms;
        return view('pages.boarding-house.rooms', compact('boardingHouse', 'rooms'));
    }
}
