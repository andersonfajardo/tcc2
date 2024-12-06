<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OKR;
use App\Models\KPI;
use App\Models\Data;

class ReportController extends Controller
{
    /**
     * Gerar relatório de OKRs.
     */
    public function okrReport(Request $request)
    {
        $okrReport = OKR::with(['user', 'kpis'])
            ->when($request->user_id, function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                $query->whereBetween('createdate', [$request->start_date, $request->end_date]);
            })
            ->get();

        return response()->json([
            'message' => 'Relatório de OKRs gerado com sucesso',
            'data' => $okrReport,
        ]);
    }

    /**
     * Gerar relatório de KPIs.
     */
    public function kpiReport(Request $request)
    {
        $kpiReport = KPI::with(['user', 'okr', 'data'])
            ->when($request->user_id, function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                $query->whereBetween('createdate', [$request->start_date, $request->end_date]);
            })
            ->get();

        return response()->json([
            'message' => 'Relatório de KPIs gerado com sucesso',
            'data' => $kpiReport,
        ]);
    }
}
