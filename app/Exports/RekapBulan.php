<?php

namespace App\Exports;

use App\FreeDay;
use App\Presence;
use App\Ptk;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class RekapBulan implements FromCollection, WithEvents
{
    private $bulan;

    private $heading = array();
    private $ptks;

    private $end;

    private const JAM_SEHARI = 8.5;

    private const ROWHEAD               = 3;
    private const CONTENT               = self::ROWHEAD + 1;

    private const HEADER_NO             = "B";
    private const HEADER_NAMA           = "C";
    private const HEADER_TGL_S          = "D";
    private const HEADER_TGL_E          = "AI";
    private const HEADER_HK             = "AJ";
    private const HEADER_X              = "AK";
    private const HEADER_Y              = "AL";
    private const HEADER_T1             = "AN";
    private const HEADER_T2             = "AO";
    private const HEADER_T3             = "AP";
    private const HEADER_T4             = "AQ";
    private const HEADER_TAD            = "AV";
    private const HEADER_TAP            = "AW";
    private const HEADER_ET             = "AX";
    private const HEADER_EPC            = "AY";
    private const HEADER_S              = "AZ";
    private const HEADER_DL             = "BG";
    private const HEADER_TR             = "BL";
    private const HEADER_TOTAL_APLHA        = "BO";
    private const HEADER_TOTAL_TERLAMBAT    = "BP";
    private const HEADER_TOTAL_AKUMULASI    = "BQ";
    private const HEADER_TOTAL_PLNGCEPAT    = "BR";
    private const HEADER_TOTAL_PROSENTASE   = "BS";
    private const HEADER_TOTAL_KONVERSI     = "BT";




    public function __construct($bulan)
    {
        $this->bulan    = Carbon::parse($bulan);
        $this->ptks     = Ptk::orderByRaw('CASE WHEN id = 1 THEN 0 ELSE 1 END')->orderBy('name')->get();

        //Init Tanggal
        $start  = $this->bulan->copy();
        $end    = $this->bulan->endOfMonth();
        $this->end = $end;
        $period = CarbonPeriod::create($start, $end);

    }

    public function collection()
    {
        $dadi = $this->ptks->map(function(Ptk $ptk, $i){

            $type = [
                    "X" => 0,
                    "Y" => 0,
                    "V" => 0,
                    "T1" => 0,
                    "T2" => 0,
                    "T3" => 0,
                    "T4" => 0,
                    "PC1" => 0,
                    "PC2" => 0,
                    "PC3" => 0,
                    "PC4" => 0,
                    "TAD" => 0,
                    "TAP" => 0,
                    "ET" => 0,
                    "EPC" => 0,
                    "S" => 0,
                    "CT" => 0,
                    "CBS" => 0,
                    "CBSR" => 0,
                    "CS" => 0,
                    "CM" => 0,
                    "CAP" => 0,
                    "CH" => 0,
                    "CN" => 0,
                    "MJ" => 0,
                    "CLN" => 0,
                    "DL" => 0,
                    "R" => 0,
                    "I" => 0,
                    "TB" => 0,
                    "IB" => 0,
                    "TR" => 0,
                    "P5" => 0,
            ];

            $TOTAL_ALPHA = 0;
            $TOTAL_TERLAMBAT = 0;
            $TOTAL_PCEPAT = 0;
            $TOTAL_KONVERSI = 0;

            $temp = array();
            $temp[] = null;

            //No
            $temp[] = ($i + 1);

            //Nama
            $temp[] = $ptk->name;

            $temp[] = null;

            //Presensi
            for($i = 1; $i <= 31; $i++){
                $waktu = $this->bulan->copy();

                if($i > $this->end->day){
                    $temp[] = '-';
                }else{
                    $waktu->setDay($i);
                    if(!FreeDay::isFree($waktu)){
                        $presence = Presence::where('ptk_id', $ptk->id)->where('date', $waktu->format('Y-m-d'));
                        if($presence->count()){
                            $semua = $presence->get()->pluck('type', 'id');

                            $temp[] = implode(', ', $semua->toArray());

                            if($semua->search('TAD', true) || $semua->search('TAP', true) )
                            {
                                $TOTAL_ALPHA += self::JAM_SEHARI * 60;
                            }

                            foreach($semua as $key => $cilik){
                                $type[$cilik]++;
                                // if(in_array($cilik, ['TAD', 'TAP'])){
                                //     $TOTAL_ALPHA += self::JAM_SEHARI * 60;
                                // }
                                if(in_array($cilik, ['T1', 'T2', 'T3', 'T4'])){
                                    $TOTAL_TERLAMBAT += Presence::find($key)->value;
                                }
                                elseif(in_array($cilik, ['PC1', 'PC2', 'PC3', 'PC4'])){
                                    $TOTAL_PCEPAT += Presence::find($key)->value;
                                }
                            }

                            if(
                                !($semua->search('TAD', true) && $semua->search('TAP', true)) &&
                                !$semua->search('S', true) && !$semua->search('I', true) &&
                                !$semua->search('DL', true)
                            ){
                                $type['Y']++;
                            }

                        }else{
                            $type['Y']++;
                            $temp[] = 'Y';
                        }
                    }else{
                        $temp[] = null;
                    }
                }
            }

            //HK
            $temp[] = FreeDay::jumlah_hk($this->bulan->format('Y-m'));

            //X
            $temp[] = $type['X'];

            //Y
            $temp[] = $type['Y'];

            $temp[] = null;

            //T1
            $temp[] = $type['T1'];

            //T2
            $temp[] = $type['T2'];

            //T2
            $temp[] = $type['T3'];

            //T2
            $temp[] = $type['T4'];

            $temp[] = null;$temp[] = null;$temp[] = null;$temp[] = null;

            //TAD
            $temp[] = $type['TAD'];

            //TAP
            $temp[] = $type['TAP'];

            //ET
            $temp[] = $type['ET'];

            //EPC
            $temp[] = $type['EPC'];

            //S
            $temp[] = $type['S'];

            $temp[] = null;$temp[] = null;$temp[] = null;$temp[] = null;$temp[] = null;$temp[] = null;

            //DL
            $temp[] = $type['DL'];

            $temp[] = null;$temp[] = null;$temp[] = null;$temp[] = null;

            //TR
            $temp[] = $type['TR'];

            $temp[] = null;$temp[] = null;

            //ALPHA
            $temp[] = gmdate('H:i:s', $TOTAL_ALPHA * 60);

            //TERLAMBAT
            $temp[] = gmdate('H:i:s', $TOTAL_TERLAMBAT * 60);

            //PCEPAT
            $temp[] = gmdate('H:i:s', $TOTAL_PCEPAT * 60);

            //AKUMULASI
            $temp[] = ($TOTAL_ALPHA + $TOTAL_TERLAMBAT + $TOTAL_PCEPAT) . " Menit";

            //PERSENTASE
            $total_menit = FreeDay::jumlah_hk($this->bulan->format('Y-m')) * self::JAM_SEHARI * 60;
            $temp[] = round(100 - (($total_menit - ($TOTAL_ALPHA + $TOTAL_TERLAMBAT + $TOTAL_PCEPAT)) * 100 / $total_menit));

            //TOTAL KONVERSI
            $temp[] = gmdate('H:i:s', ($TOTAL_ALPHA + $TOTAL_TERLAMBAT + $TOTAL_PCEPAT) * 60);
            return $temp;


        })->sortByDesc(function($data){
            if($data[2] == "Dr. SUYITNO, M.Pd") return 1000;
            return $data[70];
        })->map(function($data){
            $data[70] .= "%";
            return $data;
        })->values();

        return $dadi;
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {
                $templateFile = new LocalTemporaryFile(resource_path('template_rekap.xls'));
                $event->writer->reopen($templateFile, Excel::XLS);
                $event->writer->getSheetByIndex(0);

                $this->calledByEvent = true; // set the flag
                $event->writer->getSheetByIndex(0)->export($event->getConcernable()); // call the export on the first sheet

                return $event->getWriter()->getSheetByIndex(0);
            },
        ];
    }
}
