<?php
namespace App\Exports;

use App\regAgendaModel;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;


class BladeExport implements FromView
{

    //private $data;
    //public function __construct($data)
    //{
    //    $this->data = $data;
    //}

    //public function view(): View
    //{
    //    return view('sicinar.agenda.export', [
    //        'data' => $this->data
    //    ]);
    //}

    use Exportable;
    protected $regprogdil;

    //private $data;
    public function __construct($regprogdil = null)
    {
        $this->regprogdil = $regprogdil;
    }

    public function view(): View
    {
        $regprogdil = $this->regprogdil;
        return view('sicinar.agenda.export', compact('regprogdil'));
        //return view('sicinar.agenda.export', [
        //    'data' => $this->data
        //]);
    }    
}

