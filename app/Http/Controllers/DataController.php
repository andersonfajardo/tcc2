<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;
use App\Models\KPI;
use Illuminate\Support\Facades\Storage;

class DataController extends Controller
{
    /**
     * Inserir dados manualmente para um indicador (KPI).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_indicator' => 'required|exists:indicator,id',
            'datavalue' => 'required|string|max:255',
            'data_type_id' => 'required|exists:datatype,id',
        ]);

        $data = Data::create($validated);

        return response()->json([
            'message' => 'Dados inseridos com sucesso',
            'data' => $data,
        ], 201);
    }

    /**
     * Fazer upload de planilhas com dados para indicadores.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048', // Apenas formatos de planilha
        ]);

        // Armazenar o arquivo temporariamente
        $filePath = $request->file('file')->store('uploads');

        // Processar o arquivo (exemplo para CSV)
        $data = $this->processFile(storage_path('app/' . $filePath));

        // Salvar os dados no banco
        foreach ($data as $row) {
            $validated = $this->validateRow($row);

            if ($validated) {
                Data::create($validated);
            }
        }

        // Deletar o arquivo após o processamento
        Storage::delete($filePath);

        return response()->json([
            'message' => 'Dados carregados com sucesso',
        ]);
    }

    /**
     * Processar o arquivo enviado.
     */
    private function processFile($filePath)
    {
        $rows = [];
        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = null;

            while (($line = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $line; // A primeira linha é o cabeçalho
                } else {
                    $rows[] = array_combine($header, $line);
                }
            }

            fclose($handle);
        }

        return $rows;
    }

    /**
     * Validar e preparar os dados de uma linha do arquivo.
     */
    private function validateRow($row)
    {
        if (isset($row['id_indicator'], $row['datavalue'], $row['data_type_id'])) {
            return [
                'id_indicator' => $row['id_indicator'],
                'datavalue' => $row['datavalue'],
                'data_type_id' => $row['data_type_id'],
            ];
        }

        return null;
    }
}
