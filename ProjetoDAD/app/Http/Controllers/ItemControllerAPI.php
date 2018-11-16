<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\Item as ItemResource;
use App\Item;

class ItemControllerAPI extends Controller
{
    public function index()
    {
        return ItemResource::collection(Item::all());
    }
}
