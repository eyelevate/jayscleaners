<?php

namespace App\Http\Controllers;
use Response;
use Illuminate\Http\Request;
use App\InvoiceItem;

class InvoiceItemsController extends Controller
{
    //
    public function deleteBulk(Request $request)
    {
        // delete all items within a list of ids
        InvoiceItem::whereIn('id', $request->get('ids'))->delete();

        return response()->json(true);
    }
}
