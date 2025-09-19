<?php

namespace App\Http\Controllers;

use App\Models\Widget;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    public function widgetControl()
    {
        $title = 'Dashboard Settings';
        $items = Widget::oldest()->get();

        return view("pages.settings.widget", compact('title', 'items'));
    }

    public function updateWidgetStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:widget_control,id',
            'status' => 'required|boolean',
        ]);

        $widget = Widget::find($request->id);
        $widget->status = $request->status;
        $widget->save();

        return response()->json(['message' => 'Widget status updated successfully.']);
    }
}
