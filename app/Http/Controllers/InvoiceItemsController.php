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
        // delete only if there are ids length > 0
        if (count($request->get('ids')) > 0) {
            InvoiceItem::whereIn('id', $request->get('ids'))->delete();
        }

        return response()->json(true);
    }
}
