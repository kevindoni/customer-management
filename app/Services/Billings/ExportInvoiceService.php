<?php

namespace App\Services\Billings;

use App\Models\Websystem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;



class ExportInvoiceService
{

    public function create_invoice_file($invoice, $fileName)
    {
        $websystem = Websystem::first();
        $directory = 'invoices';

        Storage::makeDirectory($directory);

        $fileName = $fileName . '.pdf';
        $path = config('filesystems.disks.local.root');
        $pdf = Pdf::loadView('exports.billing.invoice-export', [
            'invoice' => $invoice,
            'system' => $websystem
        ]);
        $pdf->save($path . '/' . $directory . '/' . $fileName);
        return $directory . '/' . $fileName;
    }

    public function create_invoices_file($users, $fileName)
    {
        $websystem = Websystem::first();
        $directory = 'invoices';

        // $fileName = 'invoices.pdf';
        $fileName = $fileName.'.pdf';
        $path = config('filesystems.disks.local.root');
        $pdf = Pdf::loadView('exports.billing.invoices-export', [
            'users' => $users,
            'system' => $websystem
        ]);

        $pdf->save($path . '/' . $directory . '/' . $fileName);
        return $directory . '/' . $fileName;
    }

    public function download($file)
    {

        $cekFile = $this->ceckFile($file);
        if ($cekFile) {
            return Storage::download($file);
        } else {
            return false;
        }
    }

    public function ceckFile($file): bool
    {
        if (Storage::disk('local')->exists($file)) {
            $fileExist = true;
        } else {
            $fileExist = false;
        }

        return  $fileExist;
    }
}
